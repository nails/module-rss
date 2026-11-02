<?php

namespace Tests\Rss\Factory;

use Nails\Rss\Factory\Enclosure;
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
        self::assertNull($item->getEnclosure());
        self::assertNull($item->getItunesDuration());
        self::assertNull($item->getItunesSeason());
        self::assertNull($item->getItunesEpisode());
        self::assertNull($item->getItunesSummary());
        self::assertFalse($item->getItunesExplicit());
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

    public function test_podcast_setters_are_chainable_and_stored(): void
    {
        $enclosure = new Enclosure('https://cdn.example.com/episode.mp3', 12345);
        $item      = (new Item())
            ->setEnclosure($enclosure)
            ->setItunesDuration('0:12:03')
            ->setItunesSeason(1)
            ->setItunesEpisode(2)
            ->setItunesSummary('Summary')
            ->setItunesExplicit(true);

        self::assertSame($enclosure, $item->getEnclosure());
        self::assertSame('0:12:03', $item->getItunesDuration());
        self::assertSame(1, $item->getItunesSeason());
        self::assertSame(2, $item->getItunesEpisode());
        self::assertSame('Summary', $item->getItunesSummary());
        self::assertTrue($item->getItunesExplicit());
    }
}
