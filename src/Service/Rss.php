<?php

namespace Nails\Rss\Service;

use Nails\Components;
use Nails\Factory;
use Nails\Rss\Constants;
use Nails\Rss\Factory\FeedCollection;
use Nails\Rss\Interfaces;

class Rss
{
    const FEED_CLASS_PREFIX = 'Rss\\Feeds';

    protected FeedCollection $feeds;

    public function __construct()
    {
        $this->feeds = $this->discoverFeeds();
    }

    public function discoverFeeds(): FeedCollection
    {
        /** @var FeedCollection $feeds */
        $feeds = Factory::factory('FeedCollection', Constants::MODULE_SLUG);

        foreach (Components::available() as $component) {
            $classes = $component
                ->findClasses(static::FEED_CLASS_PREFIX)
                ->whichImplement(Interfaces\Feed::class)
                ->whichCanBeInstantiated();

            foreach ($classes as $class) {
                $feeds->add(new $class());
            }
        }

        return $feeds;
    }

    public function getFeeds(): FeedCollection
    {
        return $this->feeds;
    }

    public function getFeed(Interfaces\Feed|string $feedClass): ?Interfaces\Feed
    {
        $filtered = $this
            ->getFeeds()
            ->filter(fn(Interfaces\Feed $feed) => $feed instanceof $feedClass);

        $filtered
            ->rewind();

        return $filtered->count() ? $filtered->current() : null;
    }
}
