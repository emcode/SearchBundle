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
            // we will ask some questions...
            $answers = $this->askQuestionsWith($input, $output);
            $indexItem = $repository->buildSearchIndexItem($answers[0], $answers[1], $answers[2]);
        }

        $entityManager = $repository->getEm();
        $entityManager->persist($indexItem);
        $entityManager->flush();
        $output->writeln('New entity has been successfully added to search index');
    }


    protected function askQuestionsWith(InputInterface $input, OutputInterface $output)
    {

        /* @var $questionHelper \Symfony\Component\Console\Helper\QuestionHelper */
        $questionHelper = $this->getHelper('question');
        $phrases = [
            "Please type in searchable content: ",
            "Please type in numeric id of connected entity: ",
            "Please type in string identifier of entity:  "
        ];
        $answers = [];

        foreach ($phrases as $text)
        {
            $answers[] = $questionHelper->ask($input, $output, new Question($text));
        }

        return $answers;
    }
}
