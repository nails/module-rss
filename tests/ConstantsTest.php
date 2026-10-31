<?php

namespace Tests\Rss;

use Nails\Rss\Constants;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Nails\Rss\Constants
 */
class ConstantsTest extends TestCase
{
    public function test_module_slug_is_correct(): void
    {
        self::assertSame('nails/module-rss', Constants::MODULE_SLUG);
    }
}
