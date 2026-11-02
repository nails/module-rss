<?php

namespace Tests\Rss\Traits;

use PHPUnit\Framework\TestCase;
use Tests\Rss\Stub\Rss\Feeds\News\LatestPosts;

/**
 * @covers \Nails\Rss\Traits\Feed
 */
class FeedTest extends TestCase
{
    public function test_slug_is_inferred_from_the_class_name(): void
    {
        self::assertSame('tests-rss-stub-news-latest-posts', (new LatestPosts())->getSlug());
    }

    public function test_url_defaults_to_the_module_controller(): void
    {
        self::assertSame('rss/tests-rss-stub-news-latest-posts', (new LatestPosts())->getUrl());
    }

    public function test_title_is_inferred_from_the_class_name(): void
    {
        self::assertSame('News Latest Posts', (new LatestPosts())->getTitle());
    }

    public function test_xml_path_uses_the_slug(): void
    {
        self::assertSame(
            implode(DIRECTORY_SEPARATOR, ['rss', 'feeds', 'tests-rss-stub-news-latest-posts.xml']),
            (new LatestPosts())->getXmlPath()
        );
    }

    public function test_optional_fields_default_to_empty(): void
    {
        $feed = new LatestPosts();

        self::assertSame('', $feed->getDescription());
        self::assertSame('', $feed->getCategory());
        self::assertNull($feed->getImage());
        self::assertTrue($feed->shouldLink());
    }

    public function test_plain_values_are_not_wrapped_in_cdata(): void
    {
        $doc  = new \DOMDocument('1.0', 'UTF-8');
        $node = (new LatestPosts())->createElement($doc, 'title', 'Hello world', ['lang' => 'en']);

        self::assertSame('<title lang="en">Hello world</title>', $doc->saveXML($node));
    }

    public function test_values_with_markup_are_wrapped_in_cdata(): void
    {
        $doc  = new \DOMDocument('1.0', 'UTF-8');
        $node = (new LatestPosts())->createElement($doc, 'description', '<p>Fish & chips</p>');

        self::assertSame('<description><![CDATA[<p>Fish & chips</p>]]></description>', $doc->saveXML($node));
    }

    public function test_feed_has_no_variants_by_default(): void
    {
        $feed = new LatestPosts();

        self::assertSame([], $feed->getRouteVariants());
        self::assertNull($feed->getVariantId());
    }

    public function test_with_variant_returns_a_bound_copy(): void
    {
        $feed    = new LatestPosts();
        $variant = $feed->withVariant('12');

        self::assertNotSame($feed, $variant);
        self::assertNull($feed->getVariantId());
        self::assertSame('12', $variant->getVariantId());
    }

    public function test_variant_writes_a_distinct_xml_path(): void
    {
        self::assertSame(
            implode(DIRECTORY_SEPARATOR, ['rss', 'feeds', 'tests-rss-stub-news-latest-posts-12.xml']),
            (new LatestPosts())->withVariant('12')->getXmlPath()
        );
    }

    public function test_variant_is_sanitised_in_the_xml_path(): void
    {
        self::assertSame(
            implode(DIRECTORY_SEPARATOR, ['rss', 'feeds', 'tests-rss-stub-news-latest-posts-a-b-c.xml']),
            (new LatestPosts())->withVariant('a/b c')->getXmlPath()
        );
    }
}
