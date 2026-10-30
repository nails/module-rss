<?php

namespace Nails\Rss\Traits;

use Nails\Cdn;
use Nails\Cdn\Resource\CdnObject;
use Nails\Common\Resource\FileCache;
use Nails\Common\Service\Locale;
use Nails\Factory;
use Nails\Rss\Factory\Item;
use Nails\Rss\Service\Rss;

trait Feed
{
    /**
     * Returns the feed's slug, infers from the class name
     */
    public function getSlug(): string
    {
        $class    = static::class;
        $class    = preg_replace('/^(.+)\\\\' . preg_quote(Rss::FEED_CLASS_PREFIX, '/') . '\\\/', '$1', $class);
        $class    = preg_replace('/([a-z])([A-Z0-9])/', '$1\\\$2', $class);
        $segments = explode('\\', $class);
        $segments = array_map('strtolower', $segments);
        $slug     = implode('-', $segments);

        return $slug;
    }

    /**
     * Returns the feed's URL, defaults to the module's controller
     */
    public function getUrl(): string
    {
        return 'rss/' . $this->getSlug();
    }

    /**
     * Returns the feed's title, infers from the class name
     */
    public function getTitle(): string
    {
        $class    = static::class;
        $class    = preg_replace('/^(.+)\\\\' . preg_quote(Rss::FEED_CLASS_PREFIX, '/') . '\\\/', '', $class);
        $class    = preg_replace('/([a-z])([A-Z0-9])/', '$1\\\$2', $class);
        $segments = explode('\\', $class);
        $title    = implode(' ', $segments);

        return $title;
    }

    /**
     * Returns the feed's description
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * Returns the feed's language
     *
     * @throws \Nails\Common\Exception\FactoryException
     */
    public function getLanguage(): string
    {
        /** @var Locale $oLocale */
        $oLocale = Factory::service('Locale');
        return $oLocale
            ->getDefautLocale()
            ->getLanguage()
            ->getLabel();
    }

    /**
     * Returns the feed's image
     */
    public function getImage(): null|string|CdnObject
    {
        return null;
    }

    /**
     * Returns the feed's category
     */
    public function getCategory(): string
    {
        return '';
    }

    /**
     * Returns the feed's copyright byline
     */
    public function getCopyright(): string
    {
        return sprintf('Copyright %s, %s', Factory::factory('DateTime')->format('Y'), APP_NAME);
    }

    /**
     * Returns the cache key/path for where the XML for this feed should be saved
     */
    public function getXmlPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            'rss',
            'feeds',
            $this->getSlug() . '.xml',
        ]);
    }

    /**
     * Returns the XML for the feed as a DOMDocument
     *
     * @throws \DOMException
     */
    public function getXml(): \DOMDocument
    {
        // Create a DOM Document
        $doc               = $this->createDoc();
        $doc->formatOutput = true;

        // Create the root RSS element
        $doc->appendChild($this->createRssNode($doc));

        // Create channel element
        $channel = $this->createChannelNode($doc);
        $doc->appendChild($channel);

        /** @var Item $item */
        foreach ($this->getItems() as $item) {
            $channel->appendChild($this->createItemNode($doc, $item));
        }

        return $doc;
    }

    /**
     * Creates a new DOMDocument
     */
    protected function createDoc(): \DOMDocument
    {
        return new \DOMDocument('1.0', 'UTF-8');
    }

    /**
     * Creates a new `<rss>` node with appropriate attributes
     *
     * @throws \DOMException
     */
    protected function createRssNode(\DOMDocument $doc): \DOMElement
    {
        $node = $doc->createElement('rss');

        foreach ($this->getRssNodeAttributes() as $attrName => $attrValue) {
            if ($attrValue) {
                $node->setAttribute($attrName, $attrValue);
            }
        }

        return $node;
    }

    /**
     * Returns the attributes to set on the `<rss>` node
     *
     * @return string[]
     */
    protected function getRssNodeAttributes(): array
    {
        return [
            'version' => '2.0',
        ];
    }

    /**
     * Creates a new `<channel>` node with appropriate child nodes
     *
     * @throws \DOMException
     */
    protected function createChannelNode(\DOMDocument $doc): \DOMElement
    {
        $node = $doc->createElement('channel');

        foreach (array_filter($this->getChannelNodeChildren($doc)) as $childNode) {
            $node->appendChild($childNode);
        }

        return $node;
    }

    /**
     * Returns the child nodes for the `<channel>` node
     *
     * @throws \DOMException
     */
    protected function getChannelNodeChildren(\DOMDocument $doc): array
    {
        return [
            $this->getTitle()
                ? $this->createElement($doc, 'title', $this->getTitle())
                : null,
            $this->getUrl()
                ? $this->createElement($doc, 'link', siteUrl($this->getUrl()))
                : null,
            $this->getDescription()
                ? $this->createElement($doc, 'description', $this->getDescription())
                : null,
            $this->getCategory()
                ? $this->createElement($doc, 'category', $this->getCategory())
                : null,
            $this->getImage()
                ? $this->createElementImage($doc, $this->getImage())
                : null,
            $this->getLanguage()
                ? $this->createElement($doc, 'language', $this->getLanguage())
                : null,
            $this->getCopyright()
                ? $this->createElement($doc, 'copyright', $this->getCopyright())
                : null,

            $this->createElement($doc, 'lastBuildDate', Factory::factory('DateTime')->format(\DateTime::RFC2822)),
            $this->createElement($doc, 'ttl', '60'),
        ];
    }

    /**
     * Creates a new `<item>` node with appropriate child nodes
     *
     * @throws \DOMException
     */
    protected function createItemNode(\DOMDocument $doc, Item $item): \DOMElement
    {
        $node = $doc->createElement('item');

        foreach (array_filter($this->getItemNodeChildren($doc, $item)) as $childNode) {
            $node->appendChild($childNode);
        }

        return $node;
    }

    /**
     * Returns the child nodes for each `<item>` node
     *
     * @throws \DOMException
     */
    protected function getItemNodeChildren(\DOMDocument $doc, Item $item): array
    {
        return [
            $item->getTitle()
                ? $this->createElement($doc, 'title', $item->getTitle())
                : null,
            $item->getLink()
                ? $this->createElement($doc, 'link', siteUrl($item->getLink()))
                : null,
            $item->getPubDate()
                ? $this->createElement($doc, 'pubDate', $item->getPubDate()->format(\DateTime::RFC2822))
                : null,
            $item->getLink()
                ? $this->createElement($doc, 'guid', siteUrl($item->getLink()))
                : null,
            $item->getDescription()
                ? $this->createElement($doc, 'description', $item->getDescription())
                : null,
            $item->getAuthor()
                ? $this->createElement($doc, 'author', $item->getAuthor())
                : null,
            $item->getCategory()
                ? $this->createElement($doc, 'category', $item->getCategory())
                : null,
            $item->getImage()
                ? $this->createElementEnclosure($doc, $item->getImage())
                : null,
        ];
    }

    /**
     * Compiles the XML and saves to the cache directory
     *
     * @throws \Nails\Common\Exception\FactoryException
     */
    public function compile(): FileCache\Item
    {
        $xml = $this->getXml();

        /** @var \Nails\Common\Service\FileCache $fileCache */
        $fileCache = Factory::service('FileCache');
        return $fileCache
            ->write(
                $xml->saveXML(),
                $this->getXmlPath()
            );
    }

    /**
     * Whether the feed should be linked to within the header, or not
     * Provides an opportunity/hook for the feed to analyse the environment
     * and prevent loading (e.g. in multi-language situation).
     */
    public function shouldLink(): bool
    {
        return true;
    }

    /**
     * Creates a new node, automatically wrapping in CDATA tags if nessecary
     *
     * @throws \DOMException
     */
    protected function createElement(\DOMDocument $doc, string $key, string $value, array $attributes = []): \DOMElement
    {
        if (preg_match('/[<>&"\']/', $value) || strpos($value, ']]>') !== false) {
            $node = $this->createElementWithCDATA($doc, $key, $value, $attributes);
        } else {
            $node = $doc->createElement($key, $value);
            foreach ($attributes as $attrName => $attrValue) {
                $node->setAttribute($attrName, $attrValue);
            }
        }

        return $node;
    }

    /**
     * Creates a node with the contents wrapped in CDATA tags
     *
     * @throws \DOMException
     */
    protected function createElementWithCDATA(\DOMDocument $doc, string $key, string $value, array $attributes = []): \DOMElement
    {
        $node = $doc->createElement($key);
        $node->appendChild(
            $doc->createCDATASection(
                $value
            )
        );
        foreach ($attributes as $attrName => $attrValue) {
            $node->setAttribute($attrName, $attrValue);
        }
        return $node;
    }

    /**
     * Creates an image node with image-orientated child elements
     *
     * @throws \DOMException
     */
    protected function createElementImage(\DOMDocument $doc, string|int|CdnObject $value, ?string $key = null): \DOMElement
    {
        $node     = $doc->createElement($key ?? 'image');
        $children = [];

        if (is_string($value)) {
            $children[] = $doc->createElement('url', siteUrl($value));
        } else {
            $objectModel = Factory::model('Object', Cdn\Constants::MODULE_SLUG);
            /** @var CdnObject|null $object */
            $object = is_int($value) ? $objectModel->getById($value) : $value;
            if ($object && $object->is_img) {
                $children[] = $doc->createElement('url', $object->url->src);
                $children[] = $doc->createElement('title', $object->file->name->human);
                $children[] = $doc->createElement('width', (string) $object->img->width);
                $children[] = $doc->createElement('height', (string) $object->img->height);
            }
        }

        foreach ($children as $child) {
            $node->appendChild($child);
        }

        return $node;
    }

    /**
     * Creates an enclosure node with image-orientated child elements
     *
     * @throws \DOMException
     */
    protected function createElementEnclosure(\DOMDocument $doc, string|int|CdnObject $value, ?string $key = null): \DOMElement
    {
        $node       = $doc->createElement($key ?? 'enclosure');
        $attributes = [
            'length' => 0,
        ];

        if (is_string($value)) {
            $attributes['url'] = siteUrl($value);
        } else {
            $objectModel = Factory::model('Object', Cdn\Constants::MODULE_SLUG);
            /** @var CdnObject|null $object */
            $object = is_int($value) ? $objectModel->getById($value) : $value;
            if ($object && $object->is_img) {
                $attributes['url']    = $object->url->src;
                $attributes['length'] = $object->file->size->bytes;
                $attributes['type']   = $object->file->mime;
            }
        }

        foreach ($attributes as $attrName => $attrValue) {
            $node->setAttribute($attrName, $attrValue);
        }

        return $node;
    }
}
