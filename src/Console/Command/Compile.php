<?php

/**
 * The rss:compile console command
 *
 * @package  Nails\Rss
 * @category Console
 */

namespace Nails\Rss\Console\Command;

use Nails\Common\Exception\FactoryException;
use Nails\Common\Exception\ModelException;
use Nails\Console\Command\Base;
use Nails\Console\Exception\ConsoleException;
use Nails\Factory;
use Nails\Rss\Constants;
use Nails\Rss\Factory\FeedCollection;
use Nails\Rss\Service;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Compile extends Base
{
    protected Service\Rss $rss;

    public function __construct()
    {
        parent::__construct();
        $this->rss = Factory::service('Rss', Constants::MODULE_SLUG);
    }

    /**
     * Configure the rss:compile command
     */
    protected function configure()
    {
        $this
            ->setName('rss:compile')
            ->setDescription('Compiles RSS feed(s)')
            ->addOption('feed', 'f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Feed to compile');
    }

    // --------------------------------------------------------------------------

    /**
     * Execute the command
     *
     * @param InputInterface  $input  The Input Interface provided by Symfony
     * @param OutputInterface $output The Output Interface provided by Symfony
     *
     * @return int
     * @throws FactoryException
     * @throws ModelException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->banner('Compile RSS Feeds');

        $feeds = $this->getFeedsToCompile($input);

        foreach ($feeds as $feed) {
            $output->write('Compiling feed <info>' . $feed::class . '</info>... ');
            $fileCacheItem = $feed->compile();
            $output->writeln('<info>done!</info>');
            $output->writeln(' -> <comment>' . $fileCacheItem->getPath() . '</comment>');
        }

        $output->writeln('');

        return static::EXIT_CODE_SUCCESS;
    }

    private function getFeedsToCompile(InputInterface $input): FeedCollection
    {
        $filters = $input->getOption('feed');

        if (!empty($filters)) {
            $feeds = Factory::factory('FeedCollection', Constants::MODULE_SLUG);
            foreach ($filters as $filter) {
                $feed = $this->rss->getFeed($filter);
                if (empty($feed)) {
                    throw new ConsoleException(sprintf('"%s" is not a valid feed', $filter));
                }
                $feeds->add($feed);
            }
        } else {
            $feeds = $this->rss->getFeeds();
        }

        return $feeds;
    }
}
