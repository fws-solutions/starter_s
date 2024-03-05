<?php
declare(strict_types=1);

namespace FWS\Admin\AdminTools;

/**
 * "AdminTools" feature.
 */
class AdminTools
{

    // list of available tools
    protected $tools = [];

    // instance of selected tool
    protected $currentTool;


    /**
     * Static constructor.
     */
    public static function init(): void
    {
        new self();
    }


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->registerHooks();
    }


    /**
     * Load tools data from configuration.
     */
    protected function loadTools(): void
    {
        // skip if already loaded
        if (!empty($this->tools)) {
            return;
        }

        // load config
        $path = FWS_DIR . '/fws/config/admin.tools.php';
        $config = is_file($path) ? include $path : [];
        if (!is_array($config)) {
            return;
        }

        // validate all classes
        foreach ($config as $class) {
            if (class_exists($class) && $class::isAuthorized()) {
                $slug = $class::getSlug();
                if (isset($this->tools[$slug])) {
                    echo '<div id="message" class="error notice"><p>Duplicated AdminTools slug: "' . esc_html($slug) . '".</p></div>';
                }
                $this->tools[$slug] = $class;
            }
        }
    }


    /**
     * Register hooks.
     */
    protected function registerHooks(): void
    {
        // register admin sub-page in "site settings" group
        add_action('admin_menu', function () {
            $pageId = add_submenu_page('fws-settings', 'Tools', 'Admin Tools', 'manage_options', 'fws-tools', [$this, 'displayPage'], 9);
            add_action("load-$pageId", [$this, 'onPageLoad']);
        }, 333);

        // register ajax hooks
        add_action('wp_ajax_AdminTools', [$this, 'execAjax']);
        add_action('wp_ajax_nopriv_AdminTools', [$this, 'execAjax']);
    }


    /**
     * Perform some tasks before page appears.
     */
    public function onPageLoad(): void
    {
        // enqueue assets
        add_action('admin_enqueue_scripts', [$this, 'setupAdminStylesAndScripts']);

        // load all registered tools
        $this->loadTools();

        // instantiate selected tool
        $param = sanitize_text_field($_REQUEST['tool'] ?? '');
        if ($param && isset($this->tools[$param])) {
            $this->currentTool = new $this->tools[$param]();
            $this->currentTool->executeEarly();
        }
    }


    /**
     * Add custom Styles and Scripts to Login page and Admin Dashboard.
     */
    public function setupAdminStylesAndScripts(): void
    {
        $version = fws()->fwsConfig()->enqueueVersion();
        wp_enqueue_style('fws_starter_s-admin_tools-style', get_template_directory_uri() . '/fws/app/Admin/AdminTools/Assets/admin_tools.css', [], $version);
    }


    /**
     * Show page.
     */
    public function displayPage(): void
    {
        ?>
        <div class="fws-tools">
            <div class="tools-menu">
                <ul class="tools-list">
                    <?php
                    $this->displayMenu(); ?>
                </ul>
            </div>
            <div class="tool-window">
                <?php
                $this->displayTool(); ?>
            </div>
        </div>
        <?php
    }


    /**
     * Show tools menu.
     */
    protected function displayMenu(): void
    {
        $pageURL = home_url('/wp-admin/admin.php?page=fws-tools');
        $currentTool = sanitize_text_field($_REQUEST['tool'] ?? '');
        foreach ($this->tools as $slug => $class) {
            echo '<li class="' . esc_attr($slug === $currentTool ? 'cur-tool' : '') . '">';
            echo '<a href="' . esc_url("$pageURL&tool=$slug") . '">' . esc_html($class::getTitle()) . '</a>';
            echo wp_kses_post($slug === $currentTool ? $class::renderSubMenu() : '');
            echo '</li>';
        }
    }


    /**
     * Show selected tool, or intro if nothing selected.
     */
    protected function displayTool(): void
    {
        // display intro
        $param = sanitize_text_field($_REQUEST['tool'] ?? '');
        if (!$param) {
            $this->displayIntro();
            return;
        }

        // handle invalid tool name
        if (!isset($this->tools[$param])) {
            echo '<div style="padding:4em; font:italic 20px Arial; color:#c88">Unknown tool.</div>';
            return;
        }

        // execute selected tool
        $this->currentTool->execute();
    }


    /**
     * Show introduction page.
     */
    protected function displayIntro(): void
    {
        echo '<div style="padding:4em; font:italic 20px Arial; color:silver">Select tool from left menu.</div>';
    }


    /**
     * Execute tool as ajax handler.
     */
    public function execAjax(): void
    {
        $this->loadTools();
        $tool = sanitize_text_field($_POST['tool'] ?? '');

        // white-list
        if (!isset($this->tools[$tool])) {
            wp_send_json_error([
                'success' => false,
                'log' => 'Unknown tool "' . $tool . '".',
            ]);
        }

        // load tool & execute
        $tool = new $this->tools[$tool]();
        $result = $tool->executeAjax();
        $result += [
            'success' => true,
            'content' => '',
            'done' => true,
        ];
        wp_send_json_success($result);
    }

}
