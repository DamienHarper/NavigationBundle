<?php

namespace DH\NavigationBundle\Command;

use DH\NavigationBundle\NavigationManager;
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
    protected static $defaultName = 'navigation:distance-matrix';

    /**
     * @var null|ContainerInterface
     */
    private $container;

    /**
     * @var NavigationManager
     */
    private $manager;

    /**
     * @param NavigationManager $manager
     */
    public function __construct(NavigationManager $manager)
    {
        $this->manager = $manager;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('navigation:distance-matrix')
            ->setDescription('Computes a distance matrix')
            ->addOption('provider', null, InputOption::VALUE_REQUIRED)
            ->addOption('from', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Origin')
            ->addOption('to', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Destination')
            ->setHelp(
                <<<'HELP'
The <info>navigation:distance-matrix</info> command will compute a distance matrix from the given addresses.

You can choose a provider with the "provider" option.

<info>php bin/console navigation:distance-matrix --from="45.834278,1.260816" --to="44.830109,-0.603649" --provider=here</info>
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
            $this->manager->using($input->getOption('provider'));
        }

        $query = $this->manager->createDistanceMatrixQuery();

        foreach ($input->getOption('from') as $from) {
            $query->addOrigin($from);
        }
        foreach ($input->getOption('to') as $to) {
            $query->addDestination($to);
        }
        $response = $query->execute();

        $origins = $query->getOrigins();
        $destinations = $query->getDestinations();

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
