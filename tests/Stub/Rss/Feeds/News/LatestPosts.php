<?php

namespace Tests\Rss\Stub\Rss\Feeds\News;

use Nails\Rss\Factory\ItemCollection;
use Nails\Rss\Interfaces;
use Nails\Rss\Traits;

class LatestPosts implements Interfaces\Feed
{
    use Traits\Feed {
        createElement as public;
    }

    public function getItems(): ItemCollection
    {
        return new ItemCollection();
    }
}
