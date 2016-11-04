<?php

namespace Acme\SearchBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\SearchBundle\Repository\SearchIndexItemRepository;
use Acme\SearchBundle\Entity\SearchIndexItem;
use Symfony\Component\Console\Question\Question;

class AddItemToIndexCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('search:index:add')
            ->setDescription('Add item to search index')
            ->addArgument('content', InputArgument::OPTIONAL, 'Content that will be searchable')
            ->addArgument('entityId', InputArgument::OPTIONAL, 'Numeric id of connected entity')
            ->addArgument('entityType', InputArgument::OPTIONAL, 'String identifier of entity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = $input->getArgument('content');
        $entityId = $input->getArgument('entityId');
        $entityType = $input->getArgument('entityType');

        $serviceContainer = $this->getContainer();
        $repository = $serviceContainer->get('search-index-item-repository');

        if ($content && $entityId && $entityType)
        {
            $indexItem = $repository->buildSearchIndexItem($content, $entityId, $entityType);

        } else
        {
            // @todo Refactor this to ask all needed questions (you can to this using array stuff if you want)
            // we will ask some questions...
            $question = new Question("Please type in searchable content: ");
            /* @var $questionHelper \Symfony\Component\Console\Helper\QuestionHelper */
            $questionHelper = $this->getHelper('question');
            $content = $questionHelper->ask($input, $output, $question);
        }

        $entityManager = $repository->getEm();
        $entityManager->persist($indexItem);
        $entityManager->flush();
    }
}
