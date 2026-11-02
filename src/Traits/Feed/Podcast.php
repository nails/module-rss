<?php

namespace Nails\Rss\Traits\Feed;

use Nails\Cdn;
use Nails\Cdn\Resource\CdnObject;
use Nails\Factory;
use Nails\Rss\Factory\Item;
use Nails\Rss\Traits\Feed;

trait Podcast
{
    use Feed {
        getRssNodeAttributes as feedGetRssNodeAttributes;
        getChannelNodeChildren as feedGetChannelNodeChildren;
        getItemNodeChildren as feedGetItemNodeChildren;
    }

    public const ITUNES_NAMESPACE = 'http://www.itunes.com/dtds/podcast-1.0.dtd';
    public const ATOM_NAMESPACE   = 'http://www.w3.org/2005/Atom';

    protected function getRssNodeAttributes(): array
    {
        return array_merge(
            $this->feedGetRssNodeAttributes(),
            [
                'xmlns:itunes' => static::ITUNES_NAMESPACE,
                'xmlns:atom'   => static::ATOM_NAMESPACE,
            ]
        );
    }

    protected function getChannelNodeChildren(\DOMDocument $doc): array
    {
        $children = $this->feedGetChannelNodeChildren($doc);

        $children[] = $this->createElement($doc, 'atom:link', '', [
            'href' => siteUrl($this->getUrl()),
            'rel'  => 'self',
            'type' => 'application/rss+xml',
        ]);

        if ($this->getItunesAuthor()) {
            $children[] = $this->createElement($doc, 'itunes:author', $this->getItunesAuthor());
        }

        $image = $this->getItunesImage() ?? $this->getImage();
        if (is_int($image)) {
            /** @var CdnObject|null $image */
            $image = Factory::model('Object', Cdn\Constants::MODULE_SLUG)->getById($image);
        }

        $href = match (true) {
            $image instanceof CdnObject => $image->url->src,
            is_string($image) && $image !== '' => siteUrl($image),
            default => '',
        };
        if ($href) {
            $children[] = $this->createElement($doc, 'itunes:image', '', ['href' => $href]);
        }

        $children[] = $this->createElement(
            $doc,
            'itunes:explicit',
            $this->isItunesExplicit() ? 'true' : 'false'
        );

        if ($this->getItunesCategory()) {
            $children[] = $this->createElement($doc, 'itunes:category', '', [
                'text' => $this->getItunesCategory(),
            ]);
        }

        $children[] = $this->createElement($doc, 'itunes:type', $this->getItunesType());

        if ($this->isItunesComplete()) {
            $children[] = $this->createElement($doc, 'itunes:complete', 'Yes');
        }

        if ($this->getItunesSummary()) {
            $children[] = $this->createElement($doc, 'itunes:summary', $this->getItunesSummary());
        }

        return $children;
    }

    protected function getItemNodeChildren(\DOMDocument $doc, Item $item): array
    {
        $children = $this->feedGetItemNodeChildren($doc, $item);

        if ($item->getItunesDuration()) {
            $children[] = $this->createElement($doc, 'itunes:duration', $item->getItunesDuration());
        }
        if ($item->getItunesSeason()) {
            $children[] = $this->createElement($doc, 'itunes:season', (string) $item->getItunesSeason());
        }
        if ($item->getItunesEpisode()) {
            $children[] = $this->createElement($doc, 'itunes:episode', (string) $item->getItunesEpisode());
        }
        if ($item->getItunesSummary()) {
            $children[] = $this->createElement($doc, 'itunes:summary', $item->getItunesSummary());
        }
        $children[] = $this->createElement(
            $doc,
            'itunes:explicit',
            $item->getItunesExplicit() ? 'true' : 'false'
        );

        return $children;
    }

    public function getItunesAuthor(): string
    {
        return '';
    }

    public function getItunesImage(): null|string|int|CdnObject
    {
        return $this->getImage();
    }

    public function isItunesExplicit(): bool
    {
        return false;
    }

    public function getItunesCategory(): string
    {
        return $this->getCategory();
    }

    public function getItunesType(): string
    {
        return 'serial';
    }

    public function isItunesComplete(): bool
    {
        return false;
    }

    public function getItunesSummary(): string
    {
        return $this->getDescription();
    }
}
