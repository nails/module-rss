<?php

namespace Nails\Rss\Event\Listener\System;

use Nails\Common\Events;
use Nails\Common\Events\Subscription;
use Nails\Common\Service\Meta;
use Nails\Factory;
use Nails\Rss\Constants;
use Nails\Rss\Interfaces\Feed;
use Nails\Rss\Service\Rss;

class Ready extends Subscription
{
    public function __construct()
    {
        $this
            ->setEvent(Events::SYSTEM_READY)
            ->setNamespace(Events::getEventNamespace())
            ->setCallback([$this, 'execute']);
    }

    // --------------------------------------------------------------------------

    public function execute(): void
    {
        /** @var Rss $rss */
        $rss = Factory::service('Rss', Constants::MODULE_SLUG);
        /** @var Meta $meta */
        $meta = Factory::service('Meta');

        $feeds = $rss->getFeeds();
        /** @var Feed $feed */
        foreach ($feeds as $feed) {
            if ($feed->shouldLink()) {
                $meta->addRaw([
                    'tag'   => 'link',
                    'rel'   => 'alternate',
                    'type'  => 'application/rss+xml',
                    'title' => $feed->getTitle(),
                    'href'  => siteUrl($feed->getUrl()),
                ]);
            }
        }
    }
}
