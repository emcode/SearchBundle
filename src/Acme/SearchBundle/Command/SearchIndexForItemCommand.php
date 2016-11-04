<?php

namespace Acme\SearchBundle\Command;

use Acme\SearchBundle\Entity\SearchIndexItem;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
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
            ->addArgument('searchPhrase', InputArgument::REQUIRED, 'Some search phrase');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rawSearchPhrase = $input->getArgument('searchPhrase');
        $normalizedLikePhrase = '%' . $rawSearchPhrase . '%';
        $queryBuilder = $this->itemRepository->createQueryBuilder('sit');
        $expressionBuilder = $queryBuilder->expr();
        $queryBuilder->where(
            $expressionBuilder->like('sit.content', ':someLikePhrase')
        );
        $queryBuilder->setParameter(':someLikePhrase', $normalizedLikePhrase);
        $query = $queryBuilder->getQuery();
        $output->writeln(sprintf("Searching for: %s", $normalizedLikePhrase));

        /* @var $matchingEntities SearchIndexItem[] */
        $matchingEntities = $query->getResult();

        if (!empty($matchingEntities))
        {
            $output->writeln("Matching items:");

            foreach($matchingEntities as $entity)
            {
                $output->writeln(sprintf(
                    "id: %s, entity id: %s, content: %s",
                    $entity->getId(), $entity->getEntityId(), $entity->getContent()
                ));
            }

        } else
        {
            $output->writeln("There are no matching entities in DB");
        }

        $output->writeln("Command complete!");
    }
}
