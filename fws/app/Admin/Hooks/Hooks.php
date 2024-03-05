<?php
declare(strict_types=1);

namespace FWS\Admin\Hooks;

use FWS\Singleton;


/**
 * Class Hooks
 */
class Hooks extends Singleton
{

    /**
     * Constructor.
     */
    protected function __construct()
    {
        add_action('admin_notices', [$this, 'showPhpErrorsInAdminNotices']);
        add_action('add_meta_boxes', [$this, 'removeMetaBoxes'], 99);
    }


    /**
     * When PHP error/warning occurs WP will add class "php-error" to <body> tag of dashboard to make some space on top for it.
     * Problem is that most of these messages are suppressed so user has no clue what happens.
     * These method will display last error/warning message in "admin notice" box.
     */
    public function showPhpErrorsInAdminNotices(): void
    {
        $error_get_last = error_get_last();
        // reproducing condition from "/wp-admin/admin-header.php" (201)
        if (
            $error_get_last && \WP_DEBUG && constant('WP_DEBUG_DISPLAY') && ini_get('display_errors')
            && (E_NOTICE !== $error_get_last['type'] || 'wp-config.php' !== wp_basename($error_get_last['file']))
        ) {
            // echo message box
            echo '<div id="message" class="error notice"><p><b style="color:maroon">PHP message: &nbsp; </b>' . json_encode($error_get_last) . '</p></div>';
        }
    }


    /**
     * Hide WYSIWYG edit meta box for "product" post type.
     */
    public function removeMetaBoxes(): void
    {
        remove_meta_box('postexcerpt', 'product', 'normal');
        remove_post_type_support('product', 'editor');
        remove_meta_box('commentsdiv', 'product', 'normal');
    }

}
