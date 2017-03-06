<?php

/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 3/2/17
 * Time: 9:00 PM
 */

namespace Ibd\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this->setName('ibd:test')
            ->setDescription('Totally just a test command')
            ->setHelp('This is the intro for the help command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('I love you guys');
    }
}

