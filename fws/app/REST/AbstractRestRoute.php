<?php
declare(strict_types=1);

namespace FWS\REST;

use WP_REST_Request;
use WP_REST_Response;


/**
 * Class AbstractRestRoute is base of all REST routes.
 */
abstract class AbstractRestRoute
{

    // whether only recognized user can consume this route
    protected $userMustBeLoggedIn = true;

    // specify required capability of current user, example: "manage_options"
    protected $currentUserCan = null;

    // whether to skip nonce check
    protected $skipNonceValidation = false;

    // current request
    protected $request;


    /**
     * REST handler entry point.
     */
    public static function handle(WP_REST_Request $request): ?WP_REST_Response
    {
        // dispatch event
        do_action('fws_rest_request', static::class, $request);

        // instantiate handler
        $handler = new static($request);

        // validate request params
        $response = $handler->validate();
        if ($response) {
            return $response;
        }

        // execute some logic and return response
        return $handler->execute();
    }


    /**
     * AbstractRestRoute constructor.
     */
    final protected function __construct(WP_REST_Request $request)
    {
        // store current request object
        $this->request = $request;
    }


    /**
     * Perform validation of request.
     * Call "$this->fail(message)" to prematurely terminate current request.
     * Descendant classes should override this method to additionally validate input values.
     */
    public function validate(): ?WP_REST_Response
    {
        // firewall: user must be recognized
        if ($this->userMustBeLoggedIn && !wp_get_current_user()->exists()) {
            return $this->fail('Access denied.', [], 401);
        }

        // firewall: user must have capability
        if ($this->currentUserCan && !current_user_can($this->currentUserCan)) {
            return $this->fail('Access denied.', [], 403);
        }

        // firewall: nonce check (nonce must be sent as "nonce" param - in $_GET, $_POST or JSON body)
        if (!$this->skipNonceValidation) {
            $nonce = isset($_REQUEST['nonce'])
                ? sanitize_text_field($_REQUEST['nonce'] ?? '')
                : sanitize_text_field($this->request->get_json_params()['nonce'] ?? '');
            $action = fws()->render()->localizedNonceAction();
            if (!$nonce) {
                return $this->fail('Nonce missing!', [], 403);
            }
            if (!wp_verify_nonce($nonce, $action)) {
                return $this->fail('Nonce check failed. Refresh your session.', [], 403);
            }
        }

        // pass
        return null;
    }


    /**
     * Execute request and return response.
     * Descendant classes should override this method to actually handle request.
     *
     * @return WP_REST_Response
     */
    public function execute(): WP_REST_Response
    {
        // return example data
        $data = [
            'abstract' => true,
        ];
        return $this->success($data);
    }


    /**
     * Terminate current request with success.
     *
     * @param array $data
     * @return WP_REST_Response
     */
    protected function success(array $data): WP_REST_Response
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];
        do_action('fws_rest_response', $this->request, $response, 200);
        return new WP_REST_Response($response, 200);
    }


    /**
     * Terminate current request with failure.
     *
     * @param string $message
     * @param array $data
     * @param int $status
     * @return WP_REST_Response
     */
    protected function fail(string $message, array $data = [], int $status = 200): WP_REST_Response
    {
        $response = [
            'success' => false,
            'data' => $data,
            'error' => $message,
        ];
        do_action('fws_rest_response', $this->request, $response, $status);
        return new WP_REST_Response($response, intval($status) ?: 200);
    }

}
