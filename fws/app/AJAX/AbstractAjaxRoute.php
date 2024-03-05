<?php
declare(strict_types=1);

namespace FWS\AJAX;


/**
 * Class AbstractAjaxRoute is base of all AJAX routes.
 */
abstract class AbstractAjaxRoute
{

    // whether only recognized user can consume this route
    protected $userMustBeLoggedIn = true;

    // specify required capability of current user, example: "manage_options"
    protected $currentUserCan = null;

    // whether to skip nonce check
    protected $skipNonceValidation = false;


    /**
     * AJAX handler entry point.
     */
    public static function handle(): void
    {
        // dispatch event
        do_action('fws_ajax_request', static::class);

        // instantiate handler
        $handler = new static();

        // validate request params
        $handler->validate();

        // execute some logic and prepare response
        $handler->execute();

        // fallback if "execute" fail to terminate request
        wp_send_json([
            'success' => false,
            'data' => [],
            'error' => 'Unresolved fate of request.',
        ], 405);
    }


    /**
     * Constructor.
     */
    final public function __construct() // declaring final to satisfy phpstan for "new static()" in "handle" method
    {
    }


    /**
     * Perform validation of request.
     * Call "$this->fail(message)" to prematurely terminate current request.
     * Descendant classes should override this method to additionally validate input values.
     */
    public function validate(): void
    {
        // firewall: user must be recognized
        if ($this->userMustBeLoggedIn && !wp_get_current_user()->exists()) {
            $this->fail('Access denied.', [], 401);
        }

        // firewall: user must have capability
        if ($this->currentUserCan && !current_user_can($this->currentUserCan)) {
            $this->fail('Access denied.', [], 403);
        }

        // firewall: nonce check (nonce must be sent as "_ajax_nonce" or "_wpnonce" param)
        if (!$this->skipNonceValidation && !check_ajax_referer(fws()->render()->localizedNonceAction(), false, false)) {
            $this->fail('Nonce check failed.', [], 403);
        }
    }


    /**
     * Execute request and send response.
     * Descendant classes should override this method to actually handle request.
     */
    public function execute(): void
    {
        $data = [
            'abstract' => true,
        ];
        $this->success($data);
    }


    /**
     * Terminate current request with success.
     *
     * @param array $data
     */
    protected function success(array $data): void
    {
        // send example data
        $response = [
            'success' => true,
            'data' => $data,
        ];
        do_action('fws_ajax_response', $response, 200);
        wp_send_json($response, 200);
    }


    /**
     * Terminate current request with failure.
     *
     * @param string $message
     * @param array $data
     * @param int $status
     */
    protected function fail(string $message, array $data = [], int $status = 200): void
    {
        $response = [
            'success' => false,
            'data' => $data,
            'error' => $message,
        ];
        do_action('fws_ajax_response', $response, $status);
        wp_send_json($response, $status);
    }

}
