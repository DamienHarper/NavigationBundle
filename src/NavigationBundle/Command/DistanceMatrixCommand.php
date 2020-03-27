<?php

namespace DH\NavigationBundle\Command;

use DH\NavigationBundle\Model\DistanceMatrix\Element;
use DH\NavigationBundle\NavigationManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DistanceMatrixCommand extends Command
{
    protected static $defaultName = 'navigation:distance-matrix';

    /**
     * @var NavigationManager
     */
    private $manager;

    public function __construct(NavigationManager $manager)
    {
        $this->manager = $manager;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('navigation:distance-matrix')
            ->setDescription('Computes a distance matrix')
            ->addOption('provider', null, InputOption::VALUE_REQUIRED)
            ->addOption('from', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Origin')
            ->addOption('to', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Destination')
            ->setHelp(
                <<<'EOF'
The <info>navigation:distance-matrix</info> command will compute a distance matrix from the given addresses.

You can choose a provider with the "provider" option.

<info>php bin/console navigation:distance-matrix --from="45.834278,1.260816" --to="44.830109,-0.603649" --provider=here</info>
EOF
            )
        ;
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
                if (Element::STATUS_OK === $element->getStatus()) {
                    $r[] = $element->getDistance().', '.$element->getDuration();
                } else {
                    $r[] = 'unavailable';
                }
            }
            $data[] = $r;
        }

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($data)
        ;
        $table->render();

        return 0;
    }
}
