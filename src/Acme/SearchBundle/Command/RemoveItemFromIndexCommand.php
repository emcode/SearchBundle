<?php

namespace Acme\SearchBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\SearchBundle\Repository\SearchIndexItemRepository;
use Symfony\Component\Console\Question\Question;


class RemoveItemFromIndexCommand extends Command
{
    /**
     * @var SearchIndexItemRepository
     */
    protected $itemRepository;

    public function __construct(SearchIndexItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('search:index:remove')
            ->setDescription('Remove item from search index')
            ->addArgument('entityType', InputArgument::OPTIONAL, 'String identifier of entity')
            ->addArgument('entityId', InputArgument::OPTIONAL, 'Numeric id of connected entity')
            ->addArgument('content', InputArgument::OPTIONAL, 'Content that will be searchable');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityType = $input->getArgument('entityType');
        $entityId = $input->getArgument('entityId');
        $content = $input->getArgument('content');

        //content search part
        $normalizedContent = '%' . $content . '%';
        $normalizedType = '%' . $entityType . '%';
        $queryBuilder = $this->itemRepository->createQueryBuilder('sit');
        $expBuilder = $queryBuilder->expr();
        //todo build query
        $queryBuilder->where(
            $expBuilder->andX(
                $expBuilder->eq('sit.entityType', ':searchableType')
            ),
            $expBuilder->andX(
                $expBuilder->eq('sit.entityId', $entityId)
            ),
            $expBuilder->like('sit.content', ':searchableContent')
        );
        $queryBuilder
            ->setParameters([
                ':searchableType' => $normalizedType,
                ':searchableContent' => $normalizedContent
            ]);
        $query = $queryBuilder->getQuery();
        $items = $query->getResult();


        print_r($query->getSQL());
        print_r($items);
        $output->writeln($entityType);
        $output->writeln($entityId);
        $output->writeln($content);
        //check which args we have and search appropriately

    }
}
