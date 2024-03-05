<?php
declare(strict_types=1);

namespace FWS\REST\Routes\Example;

use FWS\REST\AbstractRestRoute;
use WP_REST_Response;


/**
 * Example REST handler.
 */
class GET extends AbstractRestRoute
{

    // this is simple getter route, no need for user check
    protected $userMustBeLoggedIn = false;

    // this is simple getter route, no need for nonce check
    protected $skipNonceValidation = true;


    /**
     * Execute request and return response.
     */
    public function execute(): WP_REST_Response
    {
        // you can place param validation in "execute" method too
        $id = intval($this->request->get_param('id') ?? 0);
        if ($id <= 0) {
            return $this->fail('param "id" is required.');
        }

        // calculate is it even or odd
        $is_odd = $id % 2 === 1;

        // send response
        $data = [
            'is_odd' => $is_odd,
            'class' => static::class,
        ];
        return $this->success($data);
    }

}
