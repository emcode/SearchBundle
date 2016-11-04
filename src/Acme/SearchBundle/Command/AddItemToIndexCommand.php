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

class AddItemToIndexCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('search:index:add')
            ->setDescription('Add item to search index')
            ->addArgument('command_name', InputArgument::OPTIONAL, 'The command name', 'empty arg');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $entity = $repository->buildSearchIndexItem("hmm", 23, "cool-type");


//        $name = $input->getArgument('command_name');
//        $output->writeln($name);
        $item = new SearchIndexItem();

        //find table by name ( if not found return notice )
        //add items from found table to search index ( try / catch ? )
        // return ok if everything done
    }
}
