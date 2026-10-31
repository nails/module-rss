<?php

use Nails\Common\Controller\Base;
use Nails\Common\Service\FileCache;
use Nails\Common\Service\Output;
use Nails\Common\Service\Uri;
use Nails\Factory;
use Nails\Rss;

class Feed extends Base
{
    public function index()
    {
        /** @var Uri $uri */
        $uri = Factory::service('Uri');
        /** @var Rss\Service\Rss $rss */
        $rss = Factory::service('Rss', Rss\Constants::MODULE_SLUG);
        /** @var FileCache $fileCache */
        $fileCache = Factory::service('FileCache');
        /** @var Output $output */
        $output = Factory::service('Output');

        $slug = $uri->rsegment(3);
        $feed = $rss->getFeed($slug ?? '');
        if (!$feed) {
            show404();
        }

        $xmlPath = $feed->getXmlPath();
        if (!$fileCache->exists($xmlPath)) {
            $feed->compile();
        }

        $xmlPath = $fileCache->read($xmlPath)?->getPath();
        if (!$xmlPath || !is_file($xmlPath)) {
            show404();
        }

        $output
            ->setContentType('application/rss+xml')
            ->setOutput(file_get_contents($xmlPath));
    }
}
