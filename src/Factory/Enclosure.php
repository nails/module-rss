<?php

namespace Nails\Rss\Factory;

class Enclosure
{
    public function __construct(
        public string $url,
        public int $length = 0,
        public string $type = 'audio/mpeg',
    ) {
    }
}
