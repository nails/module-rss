<?php

/**
 * The Compile Cron task
 *
 * @package  Nails\Rss
 * @category Task
 */

namespace Nails\Rss\Cron\Task;

use Nails\Cron\Task\Base;

/**
 * Class Compile
 *
 * @package Nails\Rss\Cron\Task
 */
class Compile extends Base
{
    /**
     * The cron expression of when to run
     *
     * @var string
     */
    const CRON_EXPRESSION = '@hourly';

    /**
     * The console command to execute
     *
     * @var string
     */
    const CONSOLE_COMMAND = 'rss:compile';
}
