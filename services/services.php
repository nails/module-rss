<?php

use Nails\Rss\Factory;
use Nails\Rss\Service;

return [
    'services'  => [
        'Rss' => function (): Service\Rss {
            if (class_exists('\App\Rss\Service\Rss')) {
                return new \App\Rss\Service\Rss();
            } else {
                return new Service\Rss();
            }
        },
    ],
    'factories' => [
        'FeedCollection' => function (array $feeds = []): Factory\FeedCollection {
            if (class_exists('\App\Rss\Factory\FeedCollection')) {
                return new \App\Rss\Factory\FeedCollection($feeds);
            } else {
                return new Factory\FeedCollection($feeds);
            }
        },
        'Item'           => function (): Factory\Item {
            if (class_exists('\App\Rss\Factory\Item')) {
                return new \App\Rss\Factory\Item();
            } else {
                return new Factory\Item();
            }
        },
        'ItemCollection' => function (array $items = []): Factory\ItemCollection {
            if (class_exists('\App\Rss\Factory\ItemCollection')) {
                return new \App\Rss\Factory\ItemCollection($items);
            } else {
                return new Factory\ItemCollection($items);
            }
        },
        'Enclosure'      => function (string $url = '', int $length = 0, string $type = 'audio/mpeg'): Factory\Enclosure {
            if (class_exists('\App\Rss\Factory\Enclosure')) {
                return new \App\Rss\Factory\Enclosure($url, $length, $type);
            } else {
                return new Factory\Enclosure($url, $length, $type);
            }
        },
    ],
];
