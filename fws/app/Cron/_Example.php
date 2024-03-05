<?php
declare(strict_types=1);

namespace FWS\Cron;

// phpcs:disable Inpsyde.CodeQuality.Psr4.WrongFilename -- this is demo class

/**
 * Example for CRON classes.
 *
 * File name is intentionally prefixed with "_" to prevent loading by autoloader.
 *
 * @package FWS\Cron
 */
class Example extends AbstractCron
{

    // time interval for executing cron jobs, in seconds
    protected $interval = 60;        // run on every minute


    /**
     * Cron job handler.
     */
    public function onCron(): void
    {
        // just send email
        wp_mail('nobody@forwardslashny.com', 'Testing cron', 'We just testing cron operation');
    }

}
