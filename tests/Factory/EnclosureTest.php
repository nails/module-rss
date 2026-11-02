<?php

namespace Tests\Rss\Factory;

use Nails\Rss\Factory\Enclosure;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nails\Rss\Factory\Enclosure
 */
class EnclosureTest extends TestCase
{
    public function test_defaults_to_an_mp3_of_unknown_length(): void
    {
        $enclosure = new Enclosure('https://cdn.example.com/episode.mp3');

        self::assertSame('https://cdn.example.com/episode.mp3', $enclosure->url);
        self::assertSame(0, $enclosure->length);
        self::assertSame('audio/mpeg', $enclosure->type);
    }
}
