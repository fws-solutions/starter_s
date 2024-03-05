<?php
declare(strict_types=1);

namespace FWS\CPT;

use FWS\Singleton;

// phpcs:disable WordPress.WP.I18n.NonSingularStringLiteralText -- dynamically created labels


/**
 * Abstract custom-taxonomy registration class.
 *
 * @package FWS
 */
abstract class AbstractTax extends Singleton
{

    // descriptive name of this taxonomy (in singular form)
    protected $singularName = 'Custom Post Category';

    // descriptive name of this taxonomy (in plural form)
    protected $pluralName = 'Custom Post Categories';

    // for which custom-post-type this taxonomy should be associated (as slug)
    protected $forPostType = 'CPT';

    // prefix for slug
    protected $slugPrefix = 'ctax_';

    // whether the taxonomy is hierarchical
    protected $isHierarchical = true;

    // whether the taxonomy is intended for use publicly either via the admin interface or by front-end users
    protected $isPublic = true;

    // set as false to hide meta-box from post-type sidebar
    protected $showMetaBox = null;

    // whether to display a column for the taxonomy on its post type listing screens
    protected $showAdminColumn = true;

    // whether to include the taxonomy in the REST API
    protected $showInREST = true;

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
        add_action('init', [$this, 'registerTaxonomy']);
        add_action('save_post', [$this, 'saveMetaFields'], 10, 2); // store additional meta data
    }


    /**
     * Registers a custom taxonomy.
     * This method is listener of "init" action hook.
     */
    public function registerTaxonomy(): void
    {
        $labels = [
            'name'              => _x($this->pluralName, 'ctax_plural_name', 'fws_admin'),
            'singular_name'     => _x($this->singularName, 'ctax_singular_name', 'fws_admin'),
            'search_items'      => __('Search ' . $this->pluralName, 'fws_admin'),
            'all_items'         => __('All ' . $this->pluralName, 'fws_admin'),
            'parent_item'       => __('Parent ' . $this->singularName, 'fws_admin'),
            'parent_item_colon' => __('Parent:' . $this->singularName, 'fws_admin'),
            'edit_item'         => __('Edit ' . $this->singularName, 'fws_admin'),
            'update_item'       => __('Update ' . $this->singularName, 'fws_admin'),
            'add_new_item'      => __('Add New ' . $this->singularName, 'fws_admin'),
            'new_item_name'     => __('New ' . $this->singularName, 'fws_admin'),
            'menu_name'         => __($this->pluralName, 'fws_admin'),
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => $this->isHierarchical,
            'public'            => $this->isPublic,
            'show_admin_column' => $this->showAdminColumn,
            'meta_box_cb'       => $this->showMetaBox,
            'show_in_rest'      => $this->showInREST,
            'rewrite'           => $this->createRewriteArg(),
        ];

        register_taxonomy($this->slug, $this->forPostType, $args);
    }


    /**
     * Create a slug for registration.
     * Override this method to customize slug creation.
     *
     * @return string
     */
    protected function createSlug(): string
    {
        $Slug = str_replace(' ', '_', strtolower($this->singularName));
        return $this->slugPrefix . $Slug;
    }


    /**
     * Create a slug for "rewrite" instruction.
     * Override this method to customize this argument creation.
     * It can return:
     *  - false: to prevent rewrite
     *  - array: [
     *       'slug' string: customize the permastruct slug
     *       'with_front' bool: whether the permastruct should be prepended with WP_Rewrite::$front
     *       'hierarchical' bool: either hierarchical rewrite tag or not
     *       'ep_mask' int: endpoint mask to assign, defaults to EP_NONE
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
        // update_post_meta($postId, 'subtitle', $_POST['subtitle']);
    }

}
