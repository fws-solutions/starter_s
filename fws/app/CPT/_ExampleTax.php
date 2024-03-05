<?php
declare(strict_types=1);

namespace FWS\CPT;

// phpcs:disable Inpsyde.CodeQuality.Psr4.WrongFilename -- this is demo class


/**
 * Example custom taxonomy class.
 * This will create "ctax_favorites_folder" taxonomy for "post" post-type.
 *
 * File name is intentionally prefixed with "_" to prevent loading by autoloader.
 *
 * @package FWS
 */
class ExampleTax extends AbstractTax
{

    protected $singularName = 'Favorites Folder';

    protected $pluralName = 'Favorites Folders';

    protected $forPostType = 'post';

}
