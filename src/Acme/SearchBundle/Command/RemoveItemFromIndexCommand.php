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
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;


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
            ->addArgument('entityId', InputArgument::OPTIONAL, 'Numeric id of connected entity')
            ->addArgument('entityType', InputArgument::OPTIONAL, 'String identifier of entity')
            ->addArgument('content', InputArgument::OPTIONAL, 'Content that will be searchable');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $predicates = array_filter(array_slice($input->getArguments(), 1));

        if (count($predicates) == 0)
        {
            return $output->writeln('Please specify arguments for search query');
        }

        $items = $this->itemRepository->findItemsDynamically($predicates);
        $entityManager = $this->itemRepository->getEm();

        if (count($items) == 0)
        {
            return $output->writeln('No results to remove. Please try again.');
        }

        $table = new Table($output);
        $table->setHeaders(array('id', 'entity id', 'entity type', 'content'))
              ->setRows(array_map([$this, 'formatTableRow'], $items));
        $table->render();

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Would you like to delete matching items? (Y/n)', false);

        if ($helper->ask($input, $output, $question))
        {
            foreach ($items as $item)
            {
                $entityManager->remove($item);
            }
            $entityManager->flush();
            $output->writeln('All items have been removed.');
        }
        else
        {
            $output->writeln('No items were removed.');
        }

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
