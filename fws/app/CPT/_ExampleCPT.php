<?php
declare(strict_types=1);

namespace FWS\CPT;

// phpcs:disable Inpsyde.CodeQuality.Psr4.WrongFilename -- this is demo class


/**
 * Example custom-post-type class.
 * This will create "cpt_albums" post-type.
 *
 * File name is intentionally prefixed with "_" to prevent loading by autoloader.
 */
class ExampleCPT extends AbstractCPT
{

    protected $singularName = 'Album';

    protected $pluralName = 'Albums';

    protected $menuIcon = 'dashicons-images-alt2';

}
