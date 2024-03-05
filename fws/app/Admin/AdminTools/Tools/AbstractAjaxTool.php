<?php
declare(strict_types=1);

namespace FWS\Admin\AdminTools\Tools;

abstract class AbstractAjaxTool extends AbstractTool
{

    // allowed execution time in seconds
    protected $timeLimit = 30;

    // internal property, name of transient for storing tasks
    protected $tasksTransient;


    /**
     * Constructor.
     */
    public function __construct()
    {
        // call parent
        parent::__construct();

        // set transient key
        $this->tasksTransient = 'AdminTool_' . $this->className;
    }


    /**
     * Execute tool.
     * Overridden method from parent class.
     */
    public function execute(): void
    {
        // should handle request?
        $method = sanitize_text_field($_SERVER['REQUEST_METHOD'] ?? '');
        if ($method === 'POST') {
            $this->handledSuccessfully = $this->handleForm();
            if ($this->handledSuccessfully) {
                $this->renderJS();
            }
        }

        // echo form
        $this->renderForm();
    }


    /**
     * Handle ajax request.
     * No request params are available, script must take them from transient.
     *
     * @return array like [
     *   'success'  => true,
     *   'content' => 'created new item X, <br>created new item Y, ...',
     *   'done'    => true,
     * ] (all keys are optional)
     */
    public function executeAjax(): array
    {
        // load tasks list
        $tasks = get_transient($this->tasksTransient);
        if (!is_array($tasks)) {
            $tasks = [];
        }
        $tasks = array_values($tasks);
        $tasksCount = count($tasks);

        // loop
        $contentBuffer = [];
        foreach ($tasks as $key => $data) {
            // execute single job
            $contentBuffer[] = $this->executeAjaxTask($data);

            // exclude from transient
            unset($tasks[$key]);
            set_transient($this->tasksTransient, array_values($tasks));

            // check time and exit to prevent script termination
            if (time() - $this->timestamp > $this->timeLimit) {
                $contentBuffer[] = 'Execution timeout, ' . count($tasks) . ' jobs remains to do.</b>';
                return [
                    'content' => implode('<br><br>', array_filter($contentBuffer)),
                    'success' => true,
                    'done' => false, // more jobs to do
                ];
            }
        }

        // success, we finished all jobs before termination
        delete_transient($this->tasksTransient);
        $contentBuffer[] = "<span class=\"tool-results-finished\">Finished. All jobs are processed</span> &nbsp; ($tasksCount in last turn).<br><br>";

        // execute onFinishAllTasks method and append its result to $contentBuffer
        $finalMessages = $this->onFinishAllTasks();
        if (!empty($finalMessages)) {
            $contentBuffer = array_merge($contentBuffer, $finalMessages, ['<br>']);
        }

        // ret
        return [
            'content' => implode('<br><br>', array_filter($contentBuffer)),
            'success' => true,
            'done' => true,
        ];
    }


    /**
     * Execute single ajax task, returned content will be appended to the response content.
     * Descendant class should override this method to handle ajax requests.
     *
     * @param mixed $data
     * @return string
     */
    protected function executeAjaxTask(mixed $data): string
    {
        //..
        return '';
    }


    /**
     * Execute after all ajax tasks are finished.
     * Ideal for some cleaning job.
     *
     * @return array
     */
    protected function onFinishAllTasks(): array
    {
        //..
        return [];
    }


    /**
     * Save array of ajax tasks to database.
     *
     * @param array $tasks
     */
    protected function storeAjaxTasks(array $tasks): void
    {
        set_transient($this->tasksTransient, $tasks, 86400);
    }


    /**
     * Echo javascript support.
     */
    protected function renderJS(): void // phpcs:ignore Inpsyde.CodeQuality.FunctionLength.TooLong
    {
        $currentTool = sanitize_text_field($_REQUEST['tool'] ?? '');
        ?>
        <div class="tool-loader">
            <div class="tool-ani"></div>
            <span>Work in progress, do not close this tab.</span>
        </div>
        <div id="AdminToolsResults" class="tool-results"></div>
        <script type="text/javascript">
            AdminTools = {
                $Results: null,
                Init: function () {
                    AdminTools.$Results = jQuery('#AdminToolsResults');
                },
                Run: function (tool) {
                    AdminTools.Log(true);   // clear window
                    AdminTools.Log('<b>Started "' + tool + '" tool.</b>');  // welcome message
                    AdminTools.Loop(tool, true);
                },
                Loop: function (tool, First) {
                    jQuery.ajax({
                        url: '<?=esc_url_raw(admin_url('admin-ajax.php'))?>',
                        data: {
                            action: 'AdminTools',       // ajax hook for all admin-tools
                            tool: tool,                 // name of tool to run
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: (response) => {
                            // handle server error
                            if (!response || !response.data || !response.data.success) {
                                console.error(response);
                                AdminTools.Log('<u>Error processing (bad response), see console.log.</u>');
                                return;
                            }
                            // handle soft error
                            if (response.data.success !== true) {
                                AdminTools.Log('<u>Error:</u> ' + response.data.content);
                                return;
                            }
                            // success, show message
                            AdminTools.Log(response.data.content);
                            // are we done?
                            if (response.data.done) {
                                jQuery('.fws-tools .tool-loader').hide();
                                return;
                            }
                            // no, play it again, Sam
                            setTimeout(() => {
                                AdminTools.Loop(tool);  // yeeeeee, recursion
                            }, 2000);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            AdminTools.Log('<u>AJAX error: ' + textStatus + ' - ' + errorThrown + '</u>');
                            setTimeout(() => {
                                AdminTools.Loop(tool, false);   // recursion
                            }, 2000);
                        }
                    });
                },
                Log: function (Content) {
                    let Time = new Date;
                    if (Content === true) {
                        AdminTools.$Results.html('');
                    } else {
                        AdminTools.$Results.append('<i>' + Time.toTimeString().split(' ')[0] + '</i><br>' + Content + '<br><br>');
                        AdminTools.$Results.scrollTop(function () {
                            return this.scrollHeight;
                        });
                    }
                }
            };
            AdminTools.Init();
            AdminTools.Run('<?=esc_attr($currentTool)?>');
        </script>
        <hr><br>
        <?php
    }

}
