<?php
declare(strict_types=1);

namespace FWS\Admin\AdminTools\Tools;

abstract class AbstractTool
{

    // unique slug of this tool
    protected static $slug = 'MyAbstractTool';

    // title for menu
    protected static $title = 'My abstract tool';

    // internal property, timestamp of request
    protected $timestamp;

    // internal property, basename of current class
    protected $className;

    // internal property, result of handling request
    protected $handledSuccessfully;


    /**
     * Constructor.
     */
    public function __construct()
    {
        // base class name
        $this->className = array_reverse(explode('\\', get_class($this)))[0];

        // mark starting time
        $this->timestamp = time();
    }


    /**
     * Return HTML for submenu.
     *
     * @return string
     */
    public static function renderSubMenu(): string
    {
        return '';
    }


    /**
     * Return slug of this tool.
     *
     * @return string
     */
    public static function getSlug(): string
    {
        return static::$slug;
    }


    /**
     * Return title of this tool.
     *
     * @return string
     */
    public static function getTitle(): string
    {
        return static::$title;
    }


    /**
     * Perform some actions before sending HTML content to manipulate with HTTP headers.
     * Useful for redirections or downloads.
     */
    public function executeEarly(): void
    {
        //..
    }


    /**
     * Execute tool.
     */
    public function execute(): void
    {
        // should handle request?
        $method = sanitize_text_field($_SERVER['REQUEST_METHOD'] ?? '');
        if ($method === 'POST') {
            $this->handledSuccessfully = $this->handleForm();
            $this->showConfirmation();
        }

        // echo form
        $this->renderForm();
    }


    /**
     * Show form.
     * Descendant classes should override this.
     */
    protected function renderForm(): void
    {
        ?>
        <h2><?=esc_html(self::getTitle())?></h2>
        <form action="<?=esc_url($this->generateFormAction())?>" method="post" enctype="multipart/form-data">
            ...
        </form>
        <?php
    }


    /**
     * Helper method, calculate value for form-action attribute.
     *
     * @return string
     */
    protected function generateFormAction(): string
    {
        $phpSelf = sanitize_text_field($_SERVER['PHP_SELF'] ?? '');
        $queryString = sanitize_text_field($_SERVER['QUERY_STRING'] ?? '');
        return "$phpSelf?$queryString";
    }


    /**
     * Handle from submission.
     * Descendant classes should override this.
     *
     * @return bool  success
     */
    protected function handleForm(): bool
    {
        //..
        return true;
    }


    /**
     * Echo messages on handling finalization.
     */
    protected function showConfirmation(): void
    {
        ?>
        <h3>Done.</h3>
        <br>
        <hr>
        <hr>
        <br><br>
        <?php
    }


    /**
     * Return true if current user can use this tool.
     * Descendant classes can refine this check.
     *
     * @return bool
     */
    public static function isAuthorized(): bool
    {
        return current_user_can('administrator'); // by default: any admin

        // examples:
        //  return wp_get_current_user()->user_email === get_option('admin_email');                     // only main administrator
        //  return explode('@', wp_get_current_user()->user_email ?: '')[1] === 'forwardslashny.com';   // anyone with email at forwardslashny.com
    }


    /**
     * Helper function, confirms upload submission.
     *
     * @param string $key
     * @return null|string
     */
    protected function isValidUpload(string $key = 'upfile'): ?string
    {
        if (!isset($_FILES[$key]['error']) || is_array($_FILES[$key]['error'])) {
            return 'Invalid parameters.';
        }
        switch ($_FILES[$key]['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return 'No file sent.';
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'Exceeded filesize limit.';
            default:
                return 'Unknown errors.';
        }
        return null;
    }

}
