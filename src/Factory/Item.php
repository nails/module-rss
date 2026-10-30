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
}
