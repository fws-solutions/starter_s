<?php
declare(strict_types=1);

namespace FWS\Admin\AdminTools\Tools;

class ExampleClassicTool extends AbstractTool
{

    // unique slug of this tool
    protected static $slug = 'ExampleClassicTool';

    // title for menu
    protected static $title = 'Example classic tool';


    /**
     * Only forwardslash-admins can use this tool.
     *
     * @return bool
     */
    public static function isAuthorized(): bool
    {
        return current_user_can('administrator') && strpos(wp_get_current_user()->user_email, '@forwardslashny.com') !== false;
    }


    /**
     * Show form.
     */
    protected function renderForm(): void
    {
        ?>
        <h1><?=esc_html(self::getTitle())?></h1>
        <form action="<?=esc_url($this->GenerateFormAction())?>" method="post" enctype="multipart/form-data">
            <button>Submit</button>
        </form>
        <?php
    }


    /**
     * Handle from submission.
     */
    protected function handleForm(): bool
    {
        // do something
        sleep(2);

        // eventually echo notifications
        echo 'Just echoed some debug-info.';

        // successfully handled
        return true;
    }

}
