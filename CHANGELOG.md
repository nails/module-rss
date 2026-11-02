# Changelog

## Unreleased

- Initial release.
- Podcast feeds: the `Nails\Rss\Traits\Feed\Podcast` trait adds the iTunes and Atom namespaces, channel tags (`itunes:author`, `itunes:image`, `itunes:category`, `itunes:type`, `itunes:explicit`, `itunes:complete`, `itunes:summary`, `atom:link`) and per-item tags (`itunes:duration`, `itunes:season`, `itunes:episode`, `itunes:summary`, `itunes:explicit`).
- `Item::setEnclosure()` accepts a URL, a CDN object (or its ID), or the new `Nails\Rss\Factory\Enclosure` value object, and takes precedence over the image when building `<enclosure>`. A CDN object no longer has to be an image to produce an enclosure.
- Feed variants: a feed can return several URLs from `getRouteVariants()`. Each is routed, compiled and cached separately, and `withVariant()` returns a copy of the feed bound to one variant. `getRouteVariants()`, `withVariant()` and `getVariantId()` are new on `Nails\Rss\Interfaces\Feed`; feeds that use the `Feed` trait get them automatically.
