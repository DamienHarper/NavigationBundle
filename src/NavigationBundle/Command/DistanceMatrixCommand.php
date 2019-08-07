<?php

namespace DH\NavigationBundle\Command;

use DH\NavigationBundle\Provider\Here\DistanceMatrix\DistanceMatrixQuery;
use DH\NavigationBundle\Provider\ProviderAggregator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DistanceMatrixCommand extends Command implements ContainerAwareInterface
{
    protected static $defaultName = 'distance-matrix:compute';

    /**
     * @var null|ContainerInterface
     */
    private $container;

    /**
     * @var ProviderAggregator
     */
    private $providerAggregator;

    /**
     * @param ProviderAggregator $providerAggregator
     */
    public function __construct(ProviderAggregator $providerAggregator)
    {
        $this->providerAggregator = $providerAggregator;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('distance-matrix:compute')
            ->setDescription('Computes distance matrix')
            ->addOption('provider', null, InputOption::VALUE_REQUIRED)
            ->addOption('from', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Origin')
            ->addOption('to', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Destination')
            ->setHelp(
                <<<'HELP'
The <info>distance-matrix:compute</info> command will compute a distance matrix from the given addresses.

You can force a provider with the "provider" option.

<info>php bin/console distance-matrix:compute "Eiffel Tower" --provider=google_maps</info>
HELP
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (empty($input->getOption('from'))) {
            throw new InvalidArgumentException('Mission required "from" option.');
        }

        if (empty($input->getOption('to'))) {
            throw new InvalidArgumentException('Mission required "to" option.');
        }

        if ($input->getOption('provider')) {
            $this->providerAggregator->using($input->getOption('provider'));
        }

        $distanceMatrix = new DistanceMatrixQuery($this->providerAggregator->getProvider());

//        $now = new \DateTime('now', new \DateTimeZone('GMT+2'));
//        $provider->setDepartureTime($now->add(new \DateInterval('P1D')));

        foreach ($input->getOption('from') as $from) {
            $distanceMatrix->addOrigin($from);
        }
        foreach ($input->getOption('to') as $to) {
            $distanceMatrix->addDestination($to);
        }
        $response = $distanceMatrix->execute();

        $origins = $distanceMatrix->getOrigins();
        $destinations = $distanceMatrix->getDestinations();

        $headers = array_merge([''], $destinations);
        $data = [];
        foreach ($response->getRows() as $index => $row) {
            $r = [$origins[$index]];
            foreach ($row->getElements() as $element) {
                $r[] = $element->getDistance().', '.$element->getDuration();
            }
            $data[] = $r;
        }

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($data)
        ;
        $table->render();
    }

    /**
     * @throws \LogicException
     *
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        if (null === $this->container) {
            $application = $this->getApplication();
            if (null === $application) {
                throw new \LogicException('The container cannot be retrieved as the application instance is not yet set.');
            }

            $this->container = $application->getKernel()->getContainer();
        }

        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }
}
