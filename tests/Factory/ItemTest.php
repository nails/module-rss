<?php

namespace Tests\Rss\Factory;

use Nails\Rss\Factory\Item;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nails\Rss\Factory\Item
 */
class ItemTest extends TestCase
{
    public function test_fields_are_empty_by_default(): void
    {
        $item = new Item();

        self::assertNull($item->getTitle());
        self::assertNull($item->getLink());
        self::assertNull($item->getPubDate());
        self::assertNull($item->getDescription());
        self::assertNull($item->getAuthor());
        self::assertNull($item->getCategory());
        self::assertNull($item->getImage());
    }

    public function test_setters_are_chainable_and_stored(): void
    {
        $item = (new Item())
            ->setTitle('Title')
            ->setLink('blog/post')
            ->setDescription('Description')
            ->setAuthor('author@example.com')
            ->setCategory('News')
            ->setImage(123);

        self::assertSame('Title', $item->getTitle());
        self::assertSame('blog/post', $item->getLink());
        self::assertSame('Description', $item->getDescription());
        self::assertSame('author@example.com', $item->getAuthor());
        self::assertSame('News', $item->getCategory());
        self::assertSame(123, $item->getImage());
    }
}
