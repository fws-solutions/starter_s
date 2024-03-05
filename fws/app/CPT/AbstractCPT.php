<?php
declare(strict_types=1);

namespace FWS\CPT;

use FWS\Singleton;

// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText -- dynamically created labels


/**
 * Abstract custom-post-type registration class.
 */
abstract class AbstractCPT extends Singleton
{

    // descriptive name of this post type (in singular form)
    protected $singularName = 'CPT';

    // descriptive name of this post type (in plural form)
    protected $pluralName = 'CPTs';

    // prefix for slug
    protected $slugPrefix = 'cpt_';

    // URL to the icon / base64-encoded SVG using a data URI / name of a Dashicons helper class / 'none' to add via CSS
    protected $menuIcon = 'dashicons-admin-post';

    // whether the post type is hierarchical
    protected $isHierarchical = true;

    // whether the taxonomy is intended for use publicly either via the admin interface or by front-end users
    protected $isPublic = true;

    // where to show the post type in the admin menu
    protected $showInMenu = true;

    // makes this post type available for selection in navigation menus
    protected $showInNavMenus = true;

    // if boolean whether there should be post type archives, or if a string, the archive slug to use
    protected $hasArchive = true;

    // whether to include the post type in the REST API
    protected $showInREST = true;

    // array of core features the post type supports (title, editor, comments, revisions, trackbacks, author, excerpt, page-attributes, thumbnail, custom-fields, post-formats')
    protected $supports = ['title', 'thumbnail', 'editor'];

    // internal properties
    protected $slug;


    /**
     * Protected constructor.
     */
    protected function __construct()
    {
        // calculate slug
        $this->slug = $this->createSlug();

        // set hooks
        add_action('init', [$this, 'registerPostType']);
        add_action('save_post', [$this, 'saveMetaFields'], 10, 2); // store additional meta data
        add_filter('acf/fields/relationship/result/name=' . $this->slug, [$this, 'relationshipOptionsResultFilter'], 10, 4); // nice ACF search results
    }


    /**
     * Registers a custom post type.
     * This method is listener of "init" action hook.
     */
    public function registerPostType(): void
    {
        $labels = [
            'name'               => _x($this->pluralName, 'cpt_plural_name', 'fws_admin'),
            'singular_name'      => _x($this->singularName, 'cpt_singular_name', 'fws_admin'),
            'all_items'          => __('All ' . $this->pluralName, 'fws_admin'),
            'add_new'            => __('Add New', 'fws_admin'),
            'add_new_item'       => __('Add New ' . $this->singularName, 'fws_admin'),
            'edit'               => __('Edit', 'fws_admin'),
            'edit_item'          => __('Edit ' . $this->singularName, 'fws_admin'),
            'new_item'           => __('New ' . $this->singularName, 'fws_admin'),
            'view'               => __('View ' . $this->singularName, 'fws_admin'),
            'view_item'          => __('View ' . $this->singularName, 'fws_admin'),
            'search_term'        => __('Search ' . $this->pluralName, 'fws_admin'),
            'parent'             => __('Parent ' . $this->singularName, 'fws_admin'),
            'not_found'          => __('No ' . $this->pluralName . ' found', 'fws_admin'),
            'not_found_in_trash' => __('No ' . $this->pluralName . ' in Trash', 'fws_admin'),
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => $this->isHierarchical,
            'public'            => $this->isPublic,
            'show_in_menu'      => $this->showInMenu,
            'show_in_nav_menus' => $this->showInNavMenus,
            'has_archive'       => $this->hasArchive,
            'show_in_rest'      => $this->showInREST,
            'rewrite'           => $this->createRewriteArg(),
            'menu_icon'         => $this->menuIcon,
            'supports'          => $this->supports,
        ];

        register_post_type($this->slug, $args);
    }


    /**
     * Create a slug for registration.
     * Override this method to customize slug creation.
     *
     * @return string
     */
    protected function createSlug(): string
    {
        $slug = str_replace(' ', '_', strtolower($this->singularName));
        return $this->slugPrefix . $slug;
    }


    /**
     * Create a slug for "rewrite" instruction.
     * Override this method to customize this argument creation.
     * It can return:
     *  - false: to prevent rewrite
     *  - array: [
     *       'slug' string: customize the permastruct slug
     *       'with_front' bool: whether the permastruct should be prepended with WP_Rewrite::$front
     *       'feeds' bool: whether the feed permastruct should be built for this post type
     *       'pages' bool: whether the permastruct should provide for pagination
     *       'ep_mask' int: endpoint mask to assign, defaults to EP_PERMALINK
     *    ]
     *
     * @return array
     */
    protected function createRewriteArg(): array
    {
        $Slug = str_replace(' ', '-', strtolower($this->singularName));
        return [
            'slug' => $Slug,
        ];
    }


    /**
     * Hook in saving form in admin area to store additional meta data.
     * This method is listener of "save_post" action hook.
     *
     * @param int      $postId
     * @param \WP_Post $post
     */
    public function saveMetaFields(int $postId, \WP_Post $post): void
    {
        // skip wrong post-types
        if ($post->post_type !== $this->slug) {
            return;
        }

        // example:
        // update_post_meta($PostId, 'subtitle', $_POST['subtitle']);
    }


    /**
     * Extend product name in ACF "relationship search" results to make it more user-friendly.
     * This method is listener of "acf/fields/relationship/result/name=POST_TYPE" filter hook.
     *
     * @param string   $title
     * @param \WP_Post $post
     * @param string   $field
     * @param int      $postId
     * @return string
     */
    public function relationshipOptionsResultFilter(string $title, \WP_Post $post, string $field, int $postId): string
    {
        /*
        example:
        $class= 'thumbnail';
        $thumbnail= acf_get_post_thumbnail($post->ID, [17, 17]);
        if ($thumbnail['type'] === 'icon' ) {
            $class .= ' -' . $thumbnail['type'];
        }
        $title = '<div class="' . $class . '">' . $thumbnail['html'] . '</div>';
        $product = wc_get_product($post->ID);
        if ($product->get_type() === 'variation') {
            $parent = wc_get_product($product->get_parent_id('edit'));
            if (!$parent) {
                return $title;
            }
            $title .= ' - '.$parent->get_title();
            foreach($product->get_attributes() as $key => $value) {
                $title .= ' - '.$value;
            }
        }
        */
        return $title;
    }

}
