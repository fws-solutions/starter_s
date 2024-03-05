<?php
declare(strict_types=1);

namespace FWS\Cron;

use FWS\Singleton;


/**
 * Abstract class for all CRON classes.
 */
abstract class AbstractCron extends Singleton
{

    // time interval for executing cron jobs, in seconds
    protected $interval = 3600;

    // time delay for first cron job execution, in seconds, zero to run immediately
    protected $delay = 0;

    // debug mode
    protected $debug = false;

    // internal properties
    protected $class;

    protected $cronHookName;

    protected $cronIntervalName;

    protected $instanceId;

    protected $startedAt;


    /**
     * Class constructor.
     */
    protected function __construct()
    {
        // resolve dynamic class name
        $this->class = get_class($this);

        // prepare unique names
        $this->cronHookName = $this->class . '-CronHook';
        $this->cronIntervalName = $this->class . '-Interval';

        // capture timestamp
        $this->startedAt = time();

        // prepare unique string
        $this->instanceId = substr(str_replace(['+', '/'], '', base64_encode(md5(rand() . "", true))), 0, 6);

        // register our cron interval
        $this->setInterval();

        // register our cron handler
        $this->scheduleCronJob();

        // custom setup
        $this->setup();
    }


    /**
     * Register our cron interval.
     */
    protected function setInterval(): void
    {
        // ensure integer type
        $this->interval = intval($this->interval);

        // register filter
        add_filter('cron_schedules', [$this, 'onCronSchedules']);
    }


    /**
     * Register our cron job handler.
     */
    protected function scheduleCronJob(): void
    {
        // in debug mode this cron will be executed on EACH page opening
        if ($this->debug) {
            $timestamp = wp_next_scheduled($this->cronHookName);
            if ($timestamp) {
                wp_unschedule_event($timestamp, $this->cronHookName);
            }
        }

        // don't schedule if already scheduled
        if (!wp_next_scheduled($this->cronHookName)) {
            wp_schedule_event(time() + $this->delay, $this->cronIntervalName, $this->cronHookName);
        }

        // register CRON hook action
        add_action($this->cronHookName, [$this, 'onCron']);
    }


    /**
     * Listener of "cron_schedules" filter.
     *
     * @param array $schedules
     * @return array
     */
    public function onCronSchedules(array $schedules): array
    {
        $schedules[$this->cronIntervalName] = [
            'interval' => $this->interval,
            'display'  => $this->interval . ' seconds',
        ];
        return $schedules;
    }


    /**
     * Place your custom initialization here.
     */
    protected function setup(): void
    {
        //..
    }


    /**
     * Cron job handler.
     * Place your recurring code here.
     */
    abstract public function onCron(): void;


    /**
     * Calculate is it time to terminate executing.
     * Use this in loops to quickly determine is it safe to continue.
     *
     * @param int $breakAfter  duration in seconds
     * @return bool
     */
    protected function isTimeToBreak(int $breakAfter): bool
    {
        return time() - $this->startedAt > $breakAfter;
    }

}
