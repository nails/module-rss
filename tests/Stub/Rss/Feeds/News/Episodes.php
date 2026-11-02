<?php

namespace Tests\Rss\Stub\Rss\Feeds\News;

use Nails\Rss\Factory\Item;
use Nails\Rss\Factory\ItemCollection;
use Nails\Rss\Interfaces;
use Nails\Rss\Traits\Feed\Podcast;

class Episodes implements Interfaces\Feed
{
    use Podcast {
        getRssNodeAttributes as public;
        getItemNodeChildren as public;
    }

    public function getItems(): ItemCollection
    {
        return new ItemCollection();
    }
}
