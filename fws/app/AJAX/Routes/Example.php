<?php
declare(strict_types=1);

namespace FWS\AJAX\Routes;

use FWS\AJAX\AbstractAjaxRoute;


/**
 * Class Example is an example how to make simple AJAX route.
 *
 * In this example you can see how to:
 *  - extend "validate" method to append validation of supplied "id" parameter
 *  - override "execute" method to write your own logic and send response
 *  - lower guard to allow access for unrecognized users
 */
class Example extends AbstractAjaxRoute
{

    // allow unrecognized users to consume this route
    protected $userMustBeLoggedIn = false;


    /**
     * Validate current request.
     */
    public function validate(): void
    {
        // perform parent checks
        parent::validate();

        // validate "id" param
        $id = intval($_REQUEST['id'] ?? 0);
        if ($id <= 0) {
            $this->fail('param "id" is required');
        }
    }


    /**
     * Execute request and send response.
     */
    public function execute(): void
    {
        // calculate is it even or odd
        $id = intval($_REQUEST['id'] ?? 0);
        $is_odd = $id % 2 === 1;

        // send response
        $data = [
            'odd' => $is_odd,
        ];
        $this->success($data);
    }

}
