<?php

namespace Acme\SearchBundle\Command;

use Acme\SearchBundle\Entity\SearchIndexItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\SearchBundle\Repository\SearchIndexItemRepository;
use Symfony\Component\DependencyInjection\Dump\Container;

class SearchIndexForItemCommand extends Command
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
            ->setName('search:index:find')
            ->setDescription('Search index for item')
            ->addArgument('phrase', InputArgument::OPTIONAL, 'Search phrase - find items by content')
            ->addOption('entity-type', 't', InputOption::VALUE_REQUIRED, 'Optional name of content type')
            ->addOption('entity-id', 'i', InputOption::VALUE_REQUIRED, 'Optional id of index item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $predicates = [];

        $phrase = $input->getArgument('phrase');
        $entityType = $input->getOption('entity-type');
        $entityId = $input->getOption('entity-id');

        if (!empty($phrase))
        {
            $predicates['content'] = $phrase;
        }

        if (!empty($entityType))
        {
            $predicates['entityType'] = $entityType;
        }

        if (!empty($entityId))
        {
            $predicates['entityId'] = $entityId;
        }

        if (empty($predicates))
        {
            $output->writeln('Please provide search phrase or search options');
            return;
        }

        $items = $this->itemRepository->findItemsDynamically($predicates);

        $table = new Table($output);
        $table->setHeaders(array('id', 'entity id', 'entity type', 'content'))
            ->setRows(array_map([$this, 'formatTableRow'], $items));
        $table->render();
    }

    public function formatTableRow(SearchIndexItem $item)
    {
        return [
            $item->getId(),
            $item->getEntityId(),
            $item->getEntityType(),
            $item->getContent()
        ];
    }
}
