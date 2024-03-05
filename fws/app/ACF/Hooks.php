<?php
declare(strict_types=1);

namespace FWS\ACF;

use FWS\Singleton;
use FWS\Theme\Security;
use WP_Term;

/**
 * Class Hooks
 *
 * @package FWS\ACF
 */
class Hooks extends Singleton
{

    /**
     * Constructor.
     */
    protected function __construct()
    {
        // Actions
        add_action('init', [$this, 'acfInit']);
        add_action('admin_menu', [$this, 'fieldGroupCategorySubmenu']);
        add_action('acf/import_field_group', [$this, 'loadGroupCategoryJson']);
        add_action('manage_acf-field-group_posts_custom_column', [$this, 'fieldGroupCategoryColumnHtml'], 10, 2);

        add_action('admin_init', [$this, 'automaticJsonSync']);
        add_action('pre_post_update', [$this, 'preventEditingGroups'], 10, 2);
        add_action('admin_notices', [$this, 'editNotAllowedNotice']);

        // Filters
        add_filter('acf/fields/flexible_content/layout_title', [$this, 'flexibleContentLayoutTitle'], 10, 1);
        add_filter('acf/prepare_field_group_for_export', [$this, 'saveGroupCategoryJson']);
        add_filter('parent_file', [$this, 'fieldGroupCategorySubmenuHighlight']);
        add_filter('manage_edit-acf-field-group_columns', [$this, 'fieldGroupCategoryColumn'], 11);
        add_filter('views_edit-acf-field-group', [$this, 'fieldGroupCategoryViews'], 9);
        add_filter('acf/get_taxonomies', [$this, 'fieldGroupCategoryExclude'], 10, 1);
    }


    /**
     * Init stuff.
     */
    public function acfInit(): void
    {
        // Register Custom Taxonomy Categories for ACF
        $this->registerAcfCategoryTaxonomy();

        // Register Options Main Page
        $this->registerOptionsPages();

        // Add Flexible Content Groups
        $this->checkForFlexContentGroups();
    }


    /**
     * Register Custom Taxonomy Category for ACF.
     */
    private function registerAcfCategoryTaxonomy(): void
    {
        register_taxonomy(
            'acf-field-group-category',
            ['acf-field-group'],
            [
                'hierarchical' => true,
                'public' => false,
                'show_ui' => 'ACFE',
                'show_admin_column' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_tagcloud' => false,
                'rewrite' => false,
                'labels' => [
                    'name' => _x('Categories', 'Category'),
                    'singular_name' => _x('Categories', 'Category'),
                    'search_items' => __('Search categories', 'acfe'),
                    'all_items' => __('All categories', 'acfe'),
                    'parent_item' => __('Parent category', 'acfe'),
                    'parent_item_colon' => __('Parent category:', 'acfe'),
                    'edit_item' => __('Edit category', 'acfe'),
                    'update_item' => __('Update category', 'acfe'),
                    'add_new_item' => __('Add New category', 'acfe'),
                    'new_item_name' => __('New category name', 'acfe'),
                    'menu_name' => __('category', 'acfe'),
                ],
            ]
        );
    }


    /**
     * Register Options Pages.
     */
    private function registerOptionsPages(): void
    {
        $options_page = fws()->fwsConfig()->acfOptionsPage();
        $options_sub_pages = fws()->fwsConfig()->acfOptionsSubpages();

        // Register Options Main Page
        if ($options_page) {
            $this->registerAcfOptionsPage('FWS Settings', 'FWS Settings');

            // Register Options Sub Pages
            foreach ($options_sub_pages as $sub_page) {
                $this->registerAcfOptionsSubpage($sub_page, $sub_page);
            }
        }
    }


    /**
     * Register ACF Options page.
     *
     * @param string $page_title
     * @param string $menu_title
     */
    private function registerAcfOptionsPage(string $page_title, string $menu_title): void
    {
        acf_add_options_page([
            'page_title' => $page_title,
            'menu_title' => $menu_title,
            'menu_slug' => 'fws-settings',
            'capability' => 'edit_posts',
            'icon_url' => get_template_directory_uri() . '/src/assets/images/fws-icon.png',
            'redirect' => false,
        ]);
    }


    /**
     * Register ACF Options sub page.
     *
     * @param string $page_title
     * @param string $menu_title
     */
    private function registerAcfOptionsSubpage(string $page_title, string $menu_title): void
    {
        acf_add_options_sub_page([
            'page_title' => $page_title,
            'menu_title' => $menu_title,
            'parent_slug' => 'fws-settings',
        ]);
    }


    /**
     * Check config file for Flexible Content groups.
     */
    private function checkForFlexContentGroups(): void
    {
        $flexContents = fws()->fwsConfig()->acfFlexibleContent();

        foreach ($flexContents as $slug => $flexContent) {
            if ($flexContent->isAutoload()) {
                $flexContent->register($slug);
            }
        }
    }


    /**
     * Listener of "acf/get_taxonomies" filter hook.
     *
     * @param array $taxonomies
     * @return array
     */
    public function fieldGroupCategoryExclude(array $taxonomies): array
    {
        if (empty($taxonomies)) {
            return $taxonomies;
        }

        foreach ($taxonomies as $key => $taxonomy) {
            if ($taxonomy !== 'acf-field-group-category') {
                continue;
            }
            unset($taxonomies[$key]);
        }

        return $taxonomies;
    }


    /**
     * Listener of "views_edit-acf-field-group" filter hook.
     *
     * @param array $views
     * @return array
     */
    public function fieldGroupCategoryViews(array $views): array
    {
        $terms = get_terms('acf-field-group-category', ['hide_empty' => false]);
        if (!is_array($terms)) {
            return $views;
        }
        foreach ($terms as $term) {
            if (!$term instanceof WP_Term) {
                continue;
            }
            $groups = get_posts([
                'post_type' => 'acf-field-group',
                'posts_per_page' => -1,
                'suppress_filters' => false,
                'post_status' => ['publish', 'acf-disabled'],
                'taxonomy' => 'acf-field-group-category',
                'term' => $term->slug,
                'fields' => 'ids',
            ]);

            $count = count($groups);

            $html = '';
            if ($count > 0) {
                $html = ' <span class="count">(' . $count . ')</span>';
            }

            global $wp_query;
            $class = '';
            if (isset($wp_query->query_vars['acf-field-group-category']) && $wp_query->query_vars['acf-field-group-category'] === $term->slug) {
                $class = ' class="current"';
            }

            $url = admin_url('edit.php?acf-field-group-category=' . $term->slug . '&post_type=acf-field-group');
            $views['category-' . $term->slug] = '<a href="' . $url . '" ' . $class . '>' . $term->name . $html . '</a>';
        }

        return $views;
    }


    /**
     * Listener of "manage_acf-field-group_posts_custom_column" action hook.
     *
     * @param string $column
     * @param int $postID
     */
    public function fieldGroupCategoryColumnHtml(string $column, int $postID): void
    {
        if ($column !== 'acf-field-group-category') {
            return;
        }
        $terms = get_the_terms($postID, 'acf-field-group-category');
        if (!$terms) {
            echo '—';
            return;
        }

        $categories = [];
        if (is_array($terms)) {
            foreach ($terms as $term) {
                $url = admin_url('edit.php?acf-field-group-category=' . $term->slug . '&post_type=acf-field-group');
                $categories[] = '<a href="' . $url . '">' . $term->name . '</a>';
            }
        }

        echo wp_kses_post(implode(' ', $categories));
    }


    /**
     * Listener of "manage_edit-acf-field-group_columns" action hook.
     *
     * @param array $columns
     * @return array
     */
    public function fieldGroupCategoryColumn(array $columns): array
    {
        $newColumns = [];
        foreach ($columns as $key => $value) {
            if ($key === 'acf-key') {
                $newColumns['acf-field-group-category'] = __('Categories');
            }

            $newColumns[$key] = $value;
        }

        return $newColumns;
    }


    /**
     * Listener of "parent_file" action hook.
     *
     * @param string $parentFile
     * @return string
     */
    public function fieldGroupCategorySubmenuHighlight(string $parentFile): string
    {
        global $current_screen, $pagenow;

        if ($current_screen->taxonomy === 'acf-field-group-category' && ($pagenow === 'edit-tags.php' || $pagenow === 'term.php')) {
            $parentFile = 'edit.php?post_type=acf-field-group';
        }

        return $parentFile;
    }


    /**
     * Add submenu for field group categories.
     */
    public function fieldGroupCategorySubmenu(): void
    {
        if (!acf_get_setting('show_admin')) {  // @phpstan-ignore-line
            return;
        }

        add_submenu_page(
            'edit.php?post_type=acf-field-group',
            __('Categories'),
            __('Categories'),
            (string) acf_get_setting('capability'),
            'edit-tags.php?taxonomy=acf-field-group-category'
        );
    }


    /**
     * Customize Flexible Content Title.
     *
     * @param string $title
     * @return string
     */
    public function flexibleContentLayoutTitle(string $title): string
    {
        return '<h4 class="acf-fc-title">' . esc_html($title) . '</h4>';
    }


    /**
     * Automatic ACF group sync on admin page open.
     */
    public function automaticJsonSync(): void // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
    {
        // Bail if not on the right admin page
        if (
            acf_maybe_get_GET('post_type') !== 'acf-field-group'
            && get_post_type(acf_maybe_get_GET('post')) !== 'acf-field-group'
        ) {
            return;
        }

        // Bail to prevent redirect loop
        if (acf_maybe_get_GET('acfsynccomplete')) {
            return;
        }

        // Remove hook to prevent redirect loop
        remove_action('pre_post_update', [$this, 'preventEditingGroups'], 10);

        $sync = [];

        $groups = acf_get_field_groups();

        // Bail if no field groups
        if (empty($groups)) {
            return;
        }

        // Find JSON field groups which have not yet been imported
        foreach ($groups as $group) {
            $local = acf_maybe_get($group, 'local', false);
            $modified = acf_maybe_get($group, 'modified', 0);
            $private = acf_maybe_get($group, 'private', false);

            if ($private) {
                // ignore if is private
                continue;
            } elseif ($local !== 'json') {
                // ignore not local "json"
                continue;
            } elseif (!$group['ID']) {
                // append to sync if not yet in database
                $sync[$group['key']] = $group;
            } elseif ($modified && $modified > get_post_modified_time('U', true, $group['ID'], true)) {
                // append to sync if "json" modified time is newer than database
                $sync[$group['key']] = $group;
            }
        }

        // Bail if nothing to sync
        if (empty($sync)) {
            return;
        }

        // Disable filters to ensure ACF loads raw data from DB
        acf_disable_filters();
        acf_enable_filter('local');

        // Disable JSON
        // - this prevents a new JSON file being created and causing a 'change' to theme files - solves git anoyance
        acf_update_setting('json', false);

        $new_ids = [];

        // Do the sync
        foreach ($sync as $group) {
            // Append fields.
            $group['fields'] = acf_get_fields($group);

            // Import field group.
            $group = acf_import_field_group($group);

            // Append imported ID.
            $new_ids[] = $group['ID'];
        }

        // Redirect with a notice
        wp_safe_redirect(admin_url('edit.php?post_type=acf-field-group&acfsynccomplete=' . implode(',', $new_ids)));
        exit;
    }


    /**
     * Display Admin error notice on ACF group pages on any server but local
     */
    public function editNotAllowedNotice(): void
    {
        global $current_screen;

        // Show only on ACF group edit page
        if ($current_screen->post_type !== 'acf-field-group') {
            return;
        }

        // Bail if disabled in .fwsconfig.yml
        if (!fws()->fwsConfig()->acfOnlyLocalEditing()) {
            return;
        }

        // Bail if current host is allowed
        if (Security::isLocalEnvironment()) {
            return;
        }
        ?>

        <div class="notice notice-error">
            <p><strong>You are not allowed to edit ACF fields on this server!</strong></p>
            <p>Your local changes will be synced with this server through GIT/theme deployment.</p>
        </div>
        <?php
    }


    /**
     * Prevent editing ACF groups on any server byt local
     *
     * @param int $postID
     * @param array $data
     */
    public function preventEditingGroups(int $postID, array $data): void
    {
        // Bail if on wrong post_type page
        if ($data['post_type'] !== 'acf-field-group') {
            return;
        }

        // Bail if rule disabled in .fwsconfig.yml
        if (!fws()->fwsConfig()->acfOnlyLocalEditing()) {
            return;
        }

        // Bail if current host is allowed
        if (Security::isLocalEnvironment()) {
            return;
        }

        // Redirect to prevent saving post data
        wp_safe_redirect(admin_url('edit.php?post_type=acf-field-group'));

        // Die, just in case
        die('You are not allowed to edit ACF fields on this server!');
    }


    /**
     * Save field group category in JSON while syncing
     *
     * @param array $field_group
     * @return array
     */
    public function saveGroupCategoryJson(array $field_group): array
    {
        $post_ids = get_posts([
            'name' => $field_group['key'],
            'post_type' => 'acf-field-group',
            'post_status' => 'acf-disabled',
            'posts_per_page' => 1,
            'fields' => 'ids',
        ]);

        if (!isset($post_ids[0])) {
            return $field_group;
        }

        $terms = wp_get_object_terms($post_ids[0], ['acf-field-group-category'], ['hide_empty' => true]);

        if (is_array($terms) && !empty($terms)) {
            $terms = array_map(
                static function (WP_Term $term): array {
                    return [
                        'slug' => $term->slug,
                        'name' => $term->name,
                    ];
                },
                $terms
            );
            $field_group['acf-field-group-category'] = $terms;
        }

        return $field_group;
    }


    /**
     * Load field group category from JSON while syncing
     *
     * This will create the categories that doesn't exist already.
     * Matching is done by slug.
     *
     * @param array $field_group
     */
    public function loadGroupCategoryJson(array $field_group): void
    {
        if (empty($field_group['acf-field-group-category'])) {
            return;
        }

        $post_ids = get_posts([
            'name' => $field_group['key'],
            'post_type' => 'acf-field-group',
            'post_status' => 'acf-disabled',
            'posts_per_page' => 1,
            'fields' => 'ids',
        ]);

        if (!isset($post_ids[0])) {
            return;
        }

        foreach ($field_group['acf-field-group-category'] as $category) {
            $term = get_term_by('slug', $category['slug'], 'acf-field-group-category');

            // Create the term if doesn't exist
            if (!$term instanceof WP_Term) {
                $term_res = wp_insert_term($category['name'], 'acf-field-group-category');
                $term_id = is_array($term_res) ? $term_res['term_id'] : 0;
            } else {
                $term_id = $term->term_id;
            }
            if ($term_id) {
                wp_add_object_terms($post_ids[0], $term_id, 'acf-field-group-category');
            }
        }
    }

}
