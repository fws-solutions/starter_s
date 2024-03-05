<?php
declare(strict_types=1);

namespace FWS\User\Roles;

use FWS\User\AbstractRole;


/**
 * Class Administrator.
 * This class will cover behavior of standard "administrator" user-role.
 * We don't have to register that role.
 */
class Administrator extends AbstractRole
{

    // slug of role
    protected $roleSlug = 'administrator';

} // all methods can be removed if there is nothing to alter
