<?php
declare(strict_types=1);
namespace FWS\Services;

/**
 * Class Logger
 */
class Logger
{

    // name of log
    protected $name;

    // full path to log file
    protected $logPath = '';

    // resolved configuration
    protected $config;

    // default configuration
    protected $defaultConfig = [
        'enabled' => true,
        'logPath' => '/logs/%s.log',      // %s will be replaced with logname
        'sizeLimit' => 2 << 22,           // 4 Mb
        'timeZone' => 'Europe/Belgrade',  // 'UTC', 'America/New_York', 'Etc/GMT+7', ...
        'timeFormat' => 'Y-m-d H:i:s',
    ];

    // flag that log file is prepared
    protected $initialized = false;


    /**
     * Constructor.
     *
     * @param string $name
     * @param array $config
     */
    public function __construct(string $name, array $config)
    {
        // store configuration, allow filters to alter it
        $this->config = apply_filters('fws_logger_config', $config + $this->defaultConfig, $name);

        // store other properties
        $this->name = $name;
        $this->logPath = wp_upload_dir()['basedir'] . sprintf($this->config['logPath'], $name);
    }


    /**
     * Store log message.
     *
     * @param string $message
     * @param bool   $showCallStack
     */
    public function log(string $message, bool $showCallStack = false): void
    {
        // dispatch custom action, even if logger is disabled
        do_action('fws_logger_log', $this->name, $this->config['enabled'], $message);

        // skip if not enabled
        if (!$this->config['enabled']) {
            return;
        }

        // lazy prepare log file
        $this->initLogFile();

        // prepend header to message
        $datetime = new \DateTime;
        $datetime->setTimezone(new \DateTimeZone($this->config['timeZone']));
        $header = "\n\n" . $datetime->format($this->config['timeFormat']);
        $message = "$header  $message";

        // prepare call stack info
        if ($showCallStack) {
            $callStack = array_slice(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 4); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
            $list = [];
            foreach ($callStack as $step) {
                $list[] = (isset($step['file']) ? basename($step['file']) : "unknown file")
                         . (isset($step['line']) ? "[$step[line]]" : "[?]");
            }
            $message .= "\n  Call stack: " . implode(' > ', array_reverse($list));
        }

        // append log content
        file_put_contents($this->logPath, $message, FILE_APPEND);
    }


    /**
     * Initialize log storage.
     */
    protected function initLogFile(): void
    {
        // skip if already initialized or not enabled
        if (!$this->initialized || $this->config['enabled']) {
            return;
        }
        $this->initialized = true;

        // create directory if missing, protect it from direct access
        $dir = dirname($this->logPath);
        if (!is_dir($dir)) {
            mkdir($dir);
            file_put_contents("$dir/index.html", '');
            file_put_contents("$dir/.htaccess", 'deny from all');
        }

        // rotate log file if larger then configured
        if (@filesize($this->logPath) > $this->config['sizeLimit']) { // phpcs:ignore Generic.PHP.NoSilencedErrors
            $header = '<log-rotate>     .   .   .  . . . .......';
            $offset = intval(-round($this->config['sizeLimit'] * 0.9));
            $newDump = file_get_contents($this->logPath, false, null, $offset);
            file_put_contents($this->logPath, $header . $newDump);
        }
    }


    /**
     * Expose path to logger file.
     *
     * @return string
     */
    public function getLogFilePath(): string
    {
        return $this->logPath;
    }

}
