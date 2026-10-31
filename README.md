# Nails RSS

Define RSS feeds in code and serve them from Nails applications.

Apps and modules define feeds by implementing `Nails\Rss\Interfaces\Feed` (normally by using the `Nails\Rss\Traits\Feed` trait) under `src/Rss/Feeds/`. Feeds are discovered automatically, compiled hourly by the `rss:compile` cron task, served at `/rss/{slug}`, and linked from the page `<head>`.

See [the documentation](https://docs.nailsapp.co.uk/modules/other/rss) for the feed API, console command, and overrides.
