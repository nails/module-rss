<?php

namespace Tests\Rss\Traits\Feed;

use Nails\Rss\Factory\Enclosure;
use Nails\Rss\Factory\Item;
use PHPUnit\Framework\TestCase;
use Tests\Rss\Stub\Rss\Feeds\News\Episodes;

/**
 * @covers \Nails\Rss\Traits\Feed\Podcast
 */
class PodcastTest extends TestCase
{
    public function test_rss_node_declares_the_itunes_and_atom_namespaces(): void
    {
        self::assertSame(
            [
                'version'      => '2.0',
                'xmlns:itunes' => 'http://www.itunes.com/dtds/podcast-1.0.dtd',
                'xmlns:atom'   => 'http://www.w3.org/2005/Atom',
            ],
            (new Episodes())->getRssNodeAttributes()
        );
    }

    public function test_item_includes_itunes_fields_and_the_audio_enclosure(): void
    {
        $item = (new Item())
            ->setTitle('Episode two')
            ->setEnclosure(new Enclosure('https://cdn.example.com/episode.mp3', 12345))
            ->setItunesDuration('0:12:03')
            ->setItunesSeason(1)
            ->setItunesEpisode(2)
            ->setItunesSummary('Notes');

        $xml = $this->renderItem($item);

        self::assertStringContainsString('<title>Episode two</title>', $xml);
        self::assertStringContainsString('<enclosure length="12345" url="https://cdn.example.com/episode.mp3" type="audio/mpeg"/>', $xml);
        self::assertStringContainsString('<itunes:duration>0:12:03</itunes:duration>', $xml);
        self::assertStringContainsString('<itunes:season>1</itunes:season>', $xml);
        self::assertStringContainsString('<itunes:episode>2</itunes:episode>', $xml);
        self::assertStringContainsString('<itunes:summary>Notes</itunes:summary>', $xml);
        self::assertStringContainsString('<itunes:explicit>false</itunes:explicit>', $xml);
    }

    public function test_item_omits_empty_itunes_fields(): void
    {
        $xml = $this->renderItem((new Item())->setTitle('Episode one')->setItunesExplicit(true));

        self::assertStringNotContainsString('itunes:duration', $xml);
        self::assertStringNotContainsString('itunes:season', $xml);
        self::assertStringNotContainsString('itunes:episode', $xml);
        self::assertStringNotContainsString('enclosure', $xml);
        self::assertStringContainsString('<itunes:explicit>true</itunes:explicit>', $xml);
    }

    private function renderItem(Item $item): string
    {
        $doc  = new \DOMDocument('1.0', 'UTF-8');
        $node = $doc->createElement('item');
        foreach (array_filter((new Episodes())->getItemNodeChildren($doc, $item)) as $child) {
            $node->appendChild($child);
        }

        return (string) $doc->saveXML($node);
    }
}
