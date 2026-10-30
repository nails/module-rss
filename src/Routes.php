<?php

namespace Nails\Rss;

use Nails\Common\Interfaces\RouteGenerator;
use Nails\Factory;
use Nails\Rss\Interfaces\Feed;
use Nails\Rss\Service\Rss;

class Routes implements RouteGenerator
{
    public static function generate(): array
    {
        /** @var Rss $service */
        $service = Factory::service('Rss', Constants::MODULE_SLUG);
        $routes  = [];
        $feeds   = $service->getFeeds();

        /** @var Feed $feed */
        foreach ($feeds as $feed) {
            $routes[$feed->getUrl()] = 'rss/feed/index/' . $feed::class;
        }

        return $routes;
    }
}
