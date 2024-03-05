<?php
declare(strict_types=1);

namespace FWS\Admin\AdminTools\Tools;

class ExampleAjaxTool extends AbstractAjaxTool
{

    // unique slug of this tool
    protected static $slug = 'ExampleAjaxTool';

    // title for menu
    protected static $title = 'Example AJAX tool';

    // allowed execution time in seconds
    protected $timeLimit = 3;


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
        // dummy tasks
        $ajaxTasks = array_fill(1, 12, '');

        // store ajax tasks
        $this->storeAjaxTasks($ajaxTasks);

        // signal that ajax can start working
        return true;
    }


    /**
     * Execute single ajax task, returned content will be appended to response content.
     * Descendant class should override this method to handle ajax requests.
     *
     * @param mixed $data
     * @return string
     */
    protected function executeAjaxTask(mixed $data): string
    {
        // do something
        sleep(1);

        // return message
        return 'this task is done.';
    }

}
