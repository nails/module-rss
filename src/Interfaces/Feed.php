<?php

namespace Nails\Rss\Interfaces;

use Nails\Cdn\Resource\CdnObject;
use Nails\Common\Resource\FileCache;
use Nails\Rss\Factory\ItemCollection;

interface Feed
{
    /**
     * Returns the feed's slug, this should be unique or url-resolution conflicts could occur
     */
    public function getSlug(): string;

    /**
     * Returns the feed's URL, recommended to default to the module's controller, `/rss/{slug}`
     */
    public function getUrl(): string;

    /**
     * Returns the feed's title
     */
    public function getTitle(): string;

    /**
     * Returns the feed's description
     */
    public function getDescription(): string;

    /**
     * Returns the feed's language (e.g., `en`, `es`, `de`)
     */
    public function getLanguage(): string;

    /**
     * Returns the feed's image, this may be a string (url), or a CDN Object [ID]
     */
    public function getImage(): null|string|int|CdnObject;

    /**
     * Returns the feed's category
     */
    public function getCategory(): string;

    /**
     * Returns the feed's copyright byline
     */
    public function getCopyright(): string;

    /**
     * Returns the feed's items. This must be an ItemCollection contianing Item objects.
     */
    public function getItems(): ItemCollection;

    /**
     * Returns the cache key/path for where the XML for this feed should be saved
     */
    public function getXmlPath(): string;

    /**
     * Returns the XML for the feed as a DOMDocument
     */
    public function getXml(): \DOMDocument;

    /**
     * Compiles the XML and saves to the cache directory
     */
    public function compile(): FileCache\Item;

    /**
     * Whether the feed should be linked to within the header, or not
     * Provides an opportunity/hook for the feed to analyse the environment
     * and prevent loading (e.g. in multi-language situation).
     */
    public function shouldLink(): bool;

    /**
     * Additional public URLs for this feed, keyed by URL path, valued by variant id.
     * An empty array means the default getUrl() is registered once.
     *
     * @return array<string, string>
     */
    public function getRouteVariants(): array;

    /**
     * Returns a copy of the feed bound to a variant (e.g. a series id).
     */
    public function withVariant(?string $variantId): self;

    /**
     * The variant currently bound to this instance, if any.
     */
    public function getVariantId(): ?string;
}
