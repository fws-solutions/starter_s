<?php
declare(strict_types=1);

namespace FWS\Admin\AdminTools\Tools;


class LogViewer extends AbstractTool
{

    // unique slug of this tool
    protected static $slug = 'LogViewer';

    // title for menu
    protected static $title = 'Log viewer';


    /**
     * Show form.
     */
    protected function renderForm(): void
    {
        $availableLogs = $this->CollectLogs();
        ?>
        <h1><?= esc_html(self::getTitle()) ?></h1>
        <br>
        <form action="admin.php?page=fws-tools&tool=LogViewer" method="post">
            <?php $this->renderLogsMenu(basename(wp_get_upload_dir()['basedir']), $availableLogs, 0); ?>
        </form>
        <?php
    }


    /**
     * Handle from submission.
     */
    protected function handleForm(): bool
    {
        // get para
        $logParam = sanitize_text_field($_POST['log'] ?? '');
        $actParam = sanitize_text_field($_POST['act'] ?? '');

        // validate
        $filePath = dirname(wp_get_upload_dir()['basedir']) . DIRECTORY_SEPARATOR . trim($logParam, DIRECTORY_SEPARATOR);
        if (!$this->isValidLogFile($logParam)) {
            echo 'Invalid log param.';
            return true;
        }
        if ($actParam === 'download' && !is_file($filePath)) {
            echo 'File not exists.';
            return true;
        }

        // display log file
        $this->showLogFile($logParam);

        // successfully handled
        return true;
    }


    /**
     * Validate specified log file path.
     *
     * @param string $path
     * @return bool
     */
    protected function isValidLogFile(string $path): bool
    {
        if (strpos($path, '..' . DIRECTORY_SEPARATOR) !== false) {
            return false;  // prevent going back
        }
        if (strpos($path, '://' . DIRECTORY_SEPARATOR) !== false) {
            return false;  // local files only
        }
        if (explode('/', $path)[0] !== basename(wp_get_upload_dir()['basedir'])) {
            return false;  // must be within "uploads"
        }
        if (substr(strtolower($path), -4) !== '.log') {
            return false;  // must have "log" file extension
        }
        return true;
    }


    /**
     * Find all logs.
     *
     * @return array
     */
    protected function collectLogs(): array
    {
        $baseLogDir = wp_get_upload_dir()['basedir'];
        return $this->collectLogsSub(dirname($baseLogDir), basename($baseLogDir));
    }


    /**
     * Recursive method for collecting list of log files.
     *
     * @param string $baseDir
     * @param string $subDir
     * @return array
     */
    protected function collectLogsSub(string $baseDir, string $subDir): array
    {
        $path = $subDir ? $baseDir . '/' . trim($subDir, '/') : $baseDir;
        $entries = glob("$path/*");
        $outFiles = [];
        $outDirs = [];
        foreach ($entries as $Entry) {
            $baseName = basename($Entry);
            if (is_dir("$path/$baseName")) {
                $outDirs["$subDir/$baseName"] = $this->CollectLogsSub($baseDir, "$subDir/$baseName");
            } else {
                if (strtolower(substr($baseName, -4)) === '.log') {
                    $outFiles["$subDir/$baseName"] = "$subDir/$baseName";
                }
            }
        }
        ksort($outDirs);
        ksort($outFiles);
        return array_filter($outFiles + $outDirs);
    }


    /**
     * Render menu.
     *
     * @param string $title
     * @param array $array
     * @param int $indent
     */
    protected function renderLogsMenu(string $title, array $array, int $indent): void
    {
        ?>
        <div style="margin-left:2em; padding:10px 0 10px 4px; border-left:1px dashed #aaa;">
            <h4 style="display: inline"><?=esc_html($title)?>/</h4>
            <div style="display: inline">
                <?php
                foreach ($array as $key => $item) {
                    if (is_array($item)) {
                        $this->renderLogsMenu($key, $item, $indent + 1);
                    } else {
                        ?>
                        <span>
                            <button name="log" value="<?=esc_attr($item)?>">
                                <?=esc_html(basename($key))?>
                            </button>
                        </span>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }


    /**
     * Display content of specified log file.
     *
     * @param string $logFile
     * @return void
     */
    protected function showLogFile(string $logFile): void
    {
        $logPath = dirname(wp_get_upload_dir()['basedir']) . DIRECTORY_SEPARATOR . trim($logFile, DIRECTORY_SEPARATOR);
        ?>
        <style>
            #log-text p {
                padding: 1em;
                margin: 0
            }
            #log-text p:nth-of-type(odd) {
                background-color: #f8f8f8;
            }
        </style>
        <h3>Log: <?= esc_html($logPath) ?></h3>
        <?php
        // dump content
        $fileSize = filesize($logPath);
        $shownSize = 1024 * 1024;   // 1Mb
        if (is_file($logPath)) {
            $content = file_get_contents($logPath, false, null, -min($fileSize, $shownSize));
            $content = str_replace("\n\n", '</p><p>', esc_html($content));
            $content = str_replace(["\n", '  '], ['<br>', ' &nbsp; &nbsp; '], $content);
            $prefix = $fileSize > $shownSize ? '. . . ' : '';
            ?>
            <div id="log-text" style="height:600px; overflow:auto; border:1px solid silver; margin-right:1em; resize:block;">
                <?php // phpcs:ignore WordPress.Security.EscapeOutput -- escaped ?>
                <p><?php echo $prefix . $content ?></p>
            </div>
            <div style="text-align:right; margin:1em 1em  0 0;">
                <form action="admin.php?page=fws-tools&tool=LogViewer" method="post">
                    <?php wp_nonce_field('fws-dl-' . $logFile);?>
                    <button name="act" value="download">Download</button>
                    <input type="hidden" name="log" value="<?=esc_attr($logFile)?>" />
                </form>
            </div>
            <?php
            if ($fileSize > $shownSize) {
                $msg = 'Only latest entries from log file are shown, to get whole log file download it';
                echo '<div style="color:gray; margin-top:-2em">' . esc_html($msg) . '</div>';
            }
        } else {
            echo 'File not found!';
        }
    }


    /**
     * Perform some actions before sending HTML content to manipulate with HTTP headers.
     * Useful for redirections or downloads.
     */
    public function executeEarly(): void
    {
        // get para
        $logParam = sanitize_text_field($_POST['log'] ?? '');
        $actParam = sanitize_text_field($_POST['act'] ?? '');

        // download
        if ($actParam === 'download' && wp_verify_nonce(sanitize_text_field($_POST['_wpnonce'] ?? ''), 'fws-dl-' . $logParam)) {
            $this->downloadLogFile($logParam);
        }
    }


    /**
     * Stream specified log file to browser (download) and terminate request.
     *
     * @param string $logFile
     */
    protected function downloadLogFile(string $logFile): void
    {
        $logPath = dirname(wp_get_upload_dir()['basedir']) . DIRECTORY_SEPARATOR . trim($logFile, DIRECTORY_SEPARATOR);
        if (!$this->isValidLogFile($logFile)) {
            wp_die('Invalid log file: ' . esc_html($logPath));
        }

        // open file for streaming
        $handle = fopen($logPath, 'rb');
        if (!$handle) {
            wp_die('Error opening file for streaming: ' . esc_html($logPath));
        }

        // send headers
        $size = filesize($logPath);
        $baseName = basename($logFile);
        header('Content-Length: ' . $size);
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="' . $baseName . '"');
        header('Last-Modified: ' . date('D, d M Y H:i:s \G\M\T', filemtime($logPath)));
        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        // send out in 256kb chunks
        set_time_limit(86400); // 24 hours
        while (!feof($handle) && connection_status() === 0) {
            echo fread($handle, 1024 * 256);  // phpcs:ignore WordPress.Security.EscapeOutput -- binary content
            flush();
        }

        // close file handler
        fclose($handle);

        // terminate request
        die;
    }

}
