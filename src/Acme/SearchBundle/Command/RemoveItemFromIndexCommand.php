<?php

namespace Acme\SearchBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\SearchBundle\Repository\SearchIndexItemRepository;


class RemoveItemFromIndexCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('search:index:remove')
            ->setDescription('Remove item from search index')
            ->addArgument('command_name', InputArgument::OPTIONAL, 'Remove item from search index', 'empty arg');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('command_name');
        $output->writeln($name);

    }
}
