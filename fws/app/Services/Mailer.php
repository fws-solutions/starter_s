<?php

declare(strict_types=1);
namespace FWS\Services;


/**
 * Class Mailer is used fo dispatching emails using templates.
 * This is lazy service, it will be instantiated only when needed.
 */
class Mailer
{

    // default header lines
    protected $defaultHeaders = [
        'MIME-Version' => '1.0',
        'Content-type' => 'text/html;charset=UTF-8',
    ];

    // content of last caught error
    protected $lastError = null;


    /**
     * Singleton getter.
     *
     * @return static
     */
    public static function getInstance(): self
    {
        static $instance;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }


    /**
     * Constructor.
     */
    public function __construct()
    {
        // place hook to capture error message
        add_action('wp_mail_failed', [$this, 'onWpMailFailed'], 10, 1);
    }


    /**
     * Send email using local template and supplied query variables.
     *
     * @param string $to recipient of mail
     * @param string $subject subject of mail
     * @param string $tplPath path to template file, specify with 'emails/' prefix
     * @param array  $queryVars list of query-vars as key=>value
     * @param array  $headers list of additional headers as key=>value
     * @param array  $atts list of attachments
     * @return bool  success
     */
    public function send(string $to, string $subject, string $tplPath, array $queryVars = [], array $headers = [], array $atts = []): bool
    {
        // skip if missing values
        if (!$to) {
            return false;
        }

        // prepare content of mail
        $body = $this->prepareBody($queryVars, $tplPath);

        // prepare headers, add "From" field if missing and append default headers
        $headers = $this->prepareHeaders($headers);

        // log debug event
        fws()->logger('mailer')->log(sprintf("Send: To='%s', Subject='%s', Tpl='%s'", $to, $subject, $tplPath));
        //fws()->logger('mailer')->log(sprintf("Content: Vars=%s, Headers=%s", json_encode($queryVars), json_encode($headers)));

        // dispatch email
        $this->lastError = null;
        $timer = microtime(true);
        $success = wp_mail($to, $subject, $body, $headers, $atts);

        // log failure
        if (!$success) {
            $lastError = function_exists('mg_api_last_error') && mg_api_last_error() !== null
                ? 'Mailgun error: ' . mg_api_last_error()
                : 'WpError-message: ' . $this->lastError;
            $message = "Mailer ERROR: failed to send email to '$to'. $lastError";
            fws()->logger('mailer')->log($message);
            fws()->logger('system')->log($message);
        } else {
            $time = microtime(true) - $timer;
            fws()->logger('mailer')->log(sprintf('Time required to send email: %01.4f seconds.', $time));
        }

        // return bool
        return $success;
    }


    /**
     * Build content of mail.
     *
     * @param array  $queryVars
     * @param string $tplPath
     * @return string
     */
    protected function prepareBody(array $queryVars, string $tplPath): string
    {
        // setup query variables
        foreach ($queryVars as $key => $value) {
            set_query_var($key, $value);
        }

        // render content of mail
        ob_start();
        get_template_part('template-views/emails/' . trim($tplPath, '/'));
        return ob_get_clean() ?: '';
    }


    /**
     * Build mail headers.
     *
     * @param array $headers
     * @return array
     */
    protected function prepareHeaders(array $headers): array
    {
        // append "From" if missing
        $headers += [
            'From' => '<noreply@' . sanitize_text_field($_SERVER['HTTP_HOST'] ?? 'localhost') . '>',
        ];

        // append default headers
        $headers += $this->defaultHeaders;

        // pack
        array_walk($headers, static function (?string &$val, string $key): void {
            $val = "$key: $val";
        });
        return array_values($headers);
    }


    /**
     * Capture wp_mail error message.
     *
     * @param \WP_Error $wpError
     */
    public function onWpMailFailed(\WP_Error $wpError): void
    {
        $this->lastError = $wpError->get_error_message();
        fws()->logger('mailer')->log('OnWpMailFailed: ' . $this->lastError);
    }

}
