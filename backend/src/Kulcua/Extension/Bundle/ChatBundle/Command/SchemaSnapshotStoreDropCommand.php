<?php

declare(strict_types=1);

namespace Kulcua\Extension\Bundle\ChatBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use OpenLoyalty\Bundle\CoreBundle\Repository\DBALSnapshotRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SchemaSnapshotStoreDropCommand.
 */
class SchemaSnapshotStoreDropCommand extends DoctrineCommand
{
    /**
     * @var DBALSnapshotRepository
     */
    private $snapshotRepository;

    /**
     * {@inheritdoc}
     *
     * @param DBALSnapshotRepository $snapshotRepository
     */
    public function __construct(DBALSnapshotRepository $snapshotRepository, $name = null)
    {
        parent::__construct($name);
        $this->snapshotRepository = $snapshotRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('broadway:snapshoting:schema:drop')
             ->setDescription('Drops snapshoting store');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        if (!$this->snapshotRepository->dropSchema()) {
            $output->writeln('Broadway snapshoting store does not exist');

            return;
        }

        $output->writeln('Broadway snapshoting store has been dropped');
    }
}
