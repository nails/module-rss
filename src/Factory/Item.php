<?php

namespace Nails\Rss\Factory;

use Nails\Cdn\Resource\CdnObject;
use Nails\Common\Resource\DateTime;
use Nails\Factory;

class Item
{
    public function __construct(
        protected ?string $title = null,
        protected ?string $link = null,
        protected ?DateTime $pubDate = null,
        protected ?string $description = null,
        protected ?string $author = null,
        protected ?string $category = null,
        protected null|string|int|CdnObject $image = null,
        protected null|string|int|CdnObject|Enclosure $enclosure = null,
        protected ?string $itunesDuration = null,
        protected ?int $itunesSeason = null,
        protected ?int $itunesEpisode = null,
        protected ?string $itunesSummary = null,
        protected bool $itunesExplicit = false,
    ) {
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setPubDate(string|DateTime $pubDate): self
    {
        if (is_string($pubDate)) {
            /** @var DateTime $pubDate */
            $pubDate = Factory::resource('DateTime', null, ['raw' => $pubDate]);
        }

        $this->pubDate = $pubDate;
        return $this;
    }

    public function getPubDate(): ?DateTime
    {
        return $this->pubDate;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setImage(null|string|int|CdnObject $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getImage(): null|string|int|CdnObject
    {
        return $this->image;
    }

    public function setEnclosure(null|string|int|CdnObject|Enclosure $enclosure): self
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    public function getEnclosure(): null|string|int|CdnObject|Enclosure
    {
        return $this->enclosure;
    }

    public function setItunesDuration(?string $duration): self
    {
        $this->itunesDuration = $duration;
        return $this;
    }

    public function getItunesDuration(): ?string
    {
        return $this->itunesDuration;
    }

    public function setItunesSeason(?int $season): self
    {
        $this->itunesSeason = $season;
        return $this;
    }

    public function getItunesSeason(): ?int
    {
        return $this->itunesSeason;
    }

    public function setItunesEpisode(?int $episode): self
    {
        $this->itunesEpisode = $episode;
        return $this;
    }

    public function getItunesEpisode(): ?int
    {
        return $this->itunesEpisode;
    }

    public function setItunesSummary(?string $summary): self
    {
        $this->itunesSummary = $summary;
        return $this;
    }

    public function getItunesSummary(): ?string
    {
        return $this->itunesSummary;
    }

    public function setItunesExplicit(bool $explicit): self
    {
        $this->itunesExplicit = $explicit;
        return $this;
    }

    public function getItunesExplicit(): bool
    {
        return $this->itunesExplicit;
    }
}
