<?php
/**
 * Created by PhpStorm.
 * User: jgrubb
 * Date: 3/3/17
 * Time: 8:49 AM
 */

namespace Ibd\Command\Post;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostListCommand extends Command
{
    protected function configure()
    {
        $this->setName('post:list')
            ->setDescription('Lists all posts');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}