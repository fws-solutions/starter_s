# FWS Starter_S
*Version: 3.0.6*

> It Only Does Everything.

## Installation Instructions
Install Forwardslash CLI globaly.

This only needs to be done once per machine, so if you installed it previously, skip this step.

Although, keep in mind for most recent version of it: [forwardslash-cli](https://www.npmjs.com/package/forwardslash-cli).

    npm i forwardslash-cli -g

Install dependencies by running Node.js package manager.

    npm install

## Local Virtual Host

Local enviorment and virtual host **must** be named exactly the same as it is defiend in `gulpfile.js` file in the variable `localURL`.

    const localURL = 'http://somedomain.local/';

## CLI
For the full list of all commands, execute `fws --help`.

    fws --help

### Building Files

To create development version files, execute `fws build-dev` task.

    fws build-dev

To create production version files, execute `fws build` task.

    fws build

*Please note that build tasks will NOT handle SVG icons.*


### Starting Dev Mode

To start *watch mode* and *local server*, execute `fws dev` task.

    fws dev

### Creating PHP Template Views

To create a new view, execute `fws create-file` command and pass `--block` or `--part` with an argument.

    fws create-file component-name --block
    fws create-file part-name --part

Alternatively, it is possible and **recommended** to use short aliases.

    fws cf component-name -b
    fws cf part-name -p

Note that in this case the option argument is passed with one '-' instead of two '--'.

This command will create new module files in appropriate directory `template-views/blocks` or `template-views/parts`:
* .php
* .scss

It will also update appropriate scss file `_blocks.scss` or `_parts.scss` in `src/scss/layout` directory.

### Deleting PHP Frontend Template Views

Once done with FE development phase, it is required to delete all FE components from `template-views` directory.

To remove them all, , execute `fws remove-fe`

    fws remove-fe

Alternatively, it is possible and **recommended** to use short aliases.

    fws rfe

This command will delete all `.php` files in appropriate directory `template-views/blocks` or `template-views/parts` with `_fe-` prefix.

### Creating Vue Compontents

To create a new Vue component, execute `fws creates files` command and pass `--block-vue` or `--part-vue` with an argument.

    fws create-file component-name --block-vue
    fws create-file part-name --part-vue

Alternatively, it is possible and **recommended** to use short aliases.

    fws cf component-name -B
    fws cf part-name -P

Note that in this case the option argument is passed with one '-' instead of two '--'.

This command will create new module file in appropriate directory `src/vue/components/blocks` or `src/vue/components/parts`:
* .vue

Naming convention for Vue files should be as follows:
- each component should be named using PascalCase format,
- each block component should have a prefix 'Block',
- each part component should have a prefix 'Part'.

It is essential to keep in mind these rules when creating the files manually.

When using `create-file` or `cf` command, these rules will be applied automatically.

    Example:
    fws create-file team --block-vue

    or short:
    fws cf team -B

    Will create:
    src/vue/components/blocks/BlockTeam.vue

### SVG Icons

To generate SVG icons, execute `fws icons` task.

    fws icons

This command will optimize all SVG files in `src/assets/svg` directory directory.

#### PHP Usage

Use `inlineSVG` render function to import a SVG file as an inline element in any template.

Use the function as shown in this example:

 `fws()->render->inlineSVG('ico-happy', 'banner__caption-icon')`.

The first argument is the name of the file.

The second argument is additional classes.

    Example:
    <?php echo fws()->render->inlineSVG('ico-happy', 'banner__caption-icon'); ?>

    Will render:
    <span class="banner__caption-icon svg-icon">
        <svg>...</svg>
    </span>

#### Vue Usage

Import SvgIcon.vue file like any other component from `src/vue/components/base/SvgIcon/SvgIcon.vue`.

Use component as shown in this example:

 `<SvgIcon class="banner__caption-icon" iconName="ico-dog"/>`.

The attribute `iconName` is required, pass the name of the svg file from `src/assets/svg`.

Additionally you can set any other standard HTML attributes, like `class`.

    Example:
    <SvgIcon class="banner__caption-icon" iconName="ico-dog"/>

    Will render:
    <span class="banner__caption-icon svg-icon">
        <svg>...</svg>
    </span>

### W3 Validator

To run W3 Validator, execute `fws w3-local` command.

    fws w3-local

HTML validity is checked with [W3 Validator](https://validator.w3.org/nu/) API.

This command will only work if local enviorment and virtual host is named exactly the same as it is defiend in `gulpfile.js` file in the variable `const localURL = 'http://somedomain.local/';`.

**This is a must**, your virtual host URL must be **exactly the same** as `localURL` variable.

Furthermore, W3 Validator has the **only** command that **can be run outside** of the Starter Theme's root directory.

The command for checking any **online/live** URL is:

    fws w3 https://somedomain.com/

Note that you need to pass an actuall domain URL as an argument.

The domain URL **needs to be very strictly formated**. It needs to start with `http` or `https` and needs to end with `/`.

## SCSS
All Template Views styles should be written in corresponding directory.

All Vue Components styles should be written in `.vue` files.

All global styles should be written in `src/scss` directories.

CSS code quality is checked with [Sass Lint](https://github.com/sasstools/sass-lint).

## JS
As this is a WP theme, by default it is relying on jQuery library.

Global JS scripts should be written in `src/js` directories.
The file `site.js` should contain all load methods, and serve for invoking site script's init methods.

    Example:
    import Menu from './_site/menu';
    import Sliders from './_site/sliders';

    jQuery(function() {
    	Menu.init();
    	Sliders.init();
    });

All other files should be organized following this folder structure:
- `_site` - contains all custom written scripts
- `_plugins` - contains all plugins scripts

Vue JS scripts and logic should be written in appropriate files in `src/vue` directories.

JS code quality is checked with [ESLint](https://eslint.org/).

## Using Components

### Components file structure

All components will be created in template-views directory.

Each component has three files:
* (_fe).php *(component HTML structure)*
* .php *(comopnent template)*
* .scss *(component styles)*

*(_fe).php file:*

File with a '_fe' prefix is used only for pure frontend HTML structure, no PHP variables, methods or any other logic should be written here *(except helper functions for rendering images)*.

```
<div class="banner" style="background-image: url(<?php echo fws()->images->assets_src('banner.jpg', true); ?>);">
    <div class="banner__caption">
        <span style="color: white;" class="banner-example__caption-icon font-ico-happy"></span>
        <h1 class="banner__caption-title">Banner Title</h1>
        <p class="banner__caption-text">Here goes description paragraph</p>
    </div>
</div><!-- .banner -->
```

*.php file:*

PHP template view file is relying on globally set variables that should be accessed using get_query_var() function.

Template view should also use extract() function in order to break an array to separate variables.

The idea is to always pass all values using an array.
```
<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var array $image
 */
extract( (array) get_query_var( 'content-blocks' ) );
?>

<div class="banner" style="background-image: url(<?php echo $image['sizes']['max-width']; ?>);">
    <div class="banner__caption">
        <span style="color: white;" class="banner-example__caption-icon font-ico-happy"></span>
        <h1 class="banner__caption-title"><?php echo $title; ?></h1>
        <p class="banner__caption-text"><?php echo $subtitle; ?></p>
    </div>
</div><!-- .banner -->
```

HTML quality is checked with [htmllint](http://htmllint.github.io/).

HTML validity is checked with [W3 Validator](https://validator.w3.org/nu/).


### Rendering components

Use FWS function *templateView(**array or string** $view_vals, **string** $view_name, **bool** $is_partial)* with configured *array* variable to map out components variables.


```
$basic_block = [
  'title' => get_field( 'title' ),
  'subtitle'  => get_field( 'subtitle' ),
  'image' => get_field( 'image' )
];

fws()->render->templateView( $basic_block, 'banner' );
```

## Using ACF with Starter Theme

### General setup

With modular template views it is essential that ACF Flexible Content is organized and implemented in a defined manner.

Moving away from default Flexible Content implementation...

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/flex-content-old.png)

... and make full use of Clone field.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/flex-content-new.png)

Each Flexible Content block will use Clone field to copy **all** fields from certain field group.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/flex-content-groups.png)

Making those fields a direct sub fields in a Flexible Content layouts.

Using this system translates very good when it comes to passing Flexible Content values to template views.
Insted of using ACF basic loop, it is required to use standard PHP switch method in a foreach loop.

```
foreach ( get_field( 'content' ) as $fc ) {
  switch ( $fc['acf_fc_layout'] ) {
    case 'banner':
      fws()->render->templateView( $fc, 'banner' );
      break;
    case 'slider':
      fws()->render->templateView( $fc, 'slider' );
      break;
    default:
      fws()->render->templateView( $fc, 'basic-block' );
  }
}
```

In the example above, it is important to note that variable that is being passed to *templateView()* function is not mapped out as an array like in the previous example, but rather simply passed the current item from the loop.

The reason this is possible is because of the way ACF fields are named in their field groups. Meaning, it is absolutely required to name the fields as variables in the template views.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/flex-content-mapping.png)

Naming the fields same names as variables in the template views will make sure that each component gets properly formated array values which it needs for rendering properly.

### Using JSON

The idea behind this approach of modular ACF fields is to take full advantage of ACF [Local JSON](https://www.advancedcustomfields.com/resources/local-json/).

Inspiration for this workflow was drawn from this [post](https://www.awesomeacf.com/how-to-avoid-conflicts-when-using-the-acf-local-json-feature/). Although it has nice ideas it still doesn't resolve the issue when two developers are working on same field groups, in such a scenario a conflict of JSON files is inevitable.

Main goal is to allow multiple developers to work on field groups simultaneously on local enviroment, with lowest possible risk of having conflicts in generated JSON files.

By splitting each Flexible Content block to a separate field group, the workflow is optimized to allow more developers to work in parallel. There is still a risk of creating a conflict if two developers are editing same field group, but in this workflow chances for that are slim.

It is essential to have JSON generating enabled, which is an option set by default.
Another thing to keep in mind, since these fields are being used exclusively for cloning purposes, it is important to set them as **inactive**.

![](http://internal.forwardslashny.com/wp-content/uploads/2020/02/acf-inactive2.png)

In order to optimize the workflow even further, this Starter Theme comes with hook function that will automatically sync any changes in field groups registered by new JSON files.

For example:
- Developer A makes changes in field group Banners.
- Developer B pulls changes into his local enviroment. On first dashboard load, the ACF field groups will get synced and updated as the internal script will detect changes in JSON files.

Lastly, it is necessary to avoid any conflicts coming from any changes made directly on development or live server. To resolve this, Starter Theme actually comes with another hook which disables any field group editing on any other enviroment, forcing developers to make all changes exclusively on local enviroment.

### Naming conventions and categorizing

The workflow above resolves a lot of problems but it does have a small drawback, creating each block's fields as a separate field group will result in too meny groups in the dashboard.

Furthermore, it is highly recommended to also create helper groups of fields that can be cloned in other block's fields.

For example, the Section Title field...

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/re-acf.png)

... can be reusable across many different field groups.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/fc-acf.png)

Considering helper group fields together with block group fields, the number of groups in the dashboard will tend to get very long and unorganized.

To resolve this issue, this theme comes with a Custom Taxonomy "Categories" for ACF plugin, which should be used in order to group the field groups together.

Aside from using field group categories it is also required to follow defined naming convention.

Every field group for blocks should be named with a prefix 'FC'.
Every field group for reusable elements should be named with a prefix 'RE'.

![](http://internal.forwardslashny.com/wp-content/uploads/2020/02/acf-categories.png)

With these two conventions, it is visually optimised to distinguish which fields are blocks and which helpers, and it is user friendly to use categories to filter out desired groups.

### Managing Flexible Content field

With everything above fully implemented, tha last thing to tackle is Flexible Content field and potential conflicts which are still not covered by the defined workflow.

Cloning separate field groups into Flexible Content blocks resolves avoiding JSON conflicts when multiple developers are working on a separate components (field groups), but it is still necessary for each developer to edit same field group for Flexible Content and make changes simultaneously and therefore create an inevitable JSON conflict.

Final step in this workflow is to actually avoid creating/editing any Flexible Content group fields from the dashboard and make those changes only through PHP.

Starter Theme comes with more helper functions to enable just that, in fact, it will automatically do few things:
  - generate field group as Third Party under the name 'Content' and set it's location to Page Template: Default Template,
  - create one Flexible Content field in this group,
  - loop through all field groups that have Flexible Content category assigned,
  - and create Flexible Content layout for each field group with assigned category and clone all fields from that group to the created layout as sub fields.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/acf-flex-reg.png)

In other words, as soon as the category Flexible Content is assigned to certain field group it will get appended to auto generated Flexible Content field.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/acf-flex-autogen.png)

Few things to keep in mind, when auto generating Flexible Content layouts from assigned field groups, the script that is working under the hood will clean up Label and Name for each field group in the following manner.

Field Group: FC Basic Block will translate into Flexible Content layout:
Label: Basic Block
Name: basic_block

When needed to create more Flexible Content field groups with specific number of options, use registerFlexContent() function with required configuration.

Example:
```
$fieldName = 'New Flex Group';

$location = [
    'param' => 'page_template',
    'value' => 'default'
];

$layouts = [
    [
        'label' => 'New Flex Block',
        'name' => 'new_flex_block',
        'clone_group_key' => 'group_5d70e7dfa2562' // key of the cloning group
    ],
    [
        'label' => 'Another Flex Block',
        'name' => 'another_flex_block',
        'clone_group_key' => 'group_5d70e7ea08bce'
    ]
];

$hideOnScreen = [
    'the_content'
];

fws()->acf->registerFlexContent( $fieldName, $location, $layouts, $hideOnScreen );
```

## FWS framework

FWS framework is a default part of this Starter Theme to which **the starter relies on heavily**.

See `fws` and `fws/src` for it's structure and features.

### WooCommerce support

All WooCommerce functionality overrides should be written in `fws/src/WC.php` and `fws/src/WCHooks.php` files.

All WooCommerce template overrides should be written in `woocommerce` directory.

Before implementing any template overrides, all templates of the **current plugin version** should be **backed up** in `woocommerce/__templates-backup` directory.

**This is important to do because if WooCommerce plugin is updated, you will loose original templates and will not be able to compare any overrides that need updating as well.**

The `woocommerce` root directory should **only contain** files that are being overriden. **By all means, do NOT ever copy entire template structure to this folder**.

### Custom Post Types and Taxonomies

Registrating custom post types and taxonomies must always be done using FWS framework.

Each custom post type with it's taxonomies must be placed in a single file inside `fws/src` directory.

Always use `CTPName.php` example file located in `__wp_snippets` directory.

Use `$private` array variable to configure names of custom post type and taxonomies.

Example:

    private $params = [
        'postSingularName' => 'Custom Post',
        'postPluralName'   => 'Custom Posts',
        'taxSingularName'  => 'Custom Post Category',
        'taxPluralName'    => 'Custom Post Categories',
    ];

Methods within the CPT class will handle `$params` varibale to pull appropriate names, labels and generate a slug.

Slug and Nice Name are being based on singular name of a custom post type or taxonomy. FWS will replace any space characters for `_` or `-` character and add appropriate prefix when needed.

Slug is used for registrating custom post type or taxonomy under this name, it will use `_` character and a prefix.

Nice Name is used for URL structure, it will use `-` character and will not include a prefix.

Prefixes are defined as follows:
- for post type: `cpt_`
- for taxonomy: `ctax_`

Example:

    private $params = [
        'postSingularName' => 'Book',
        'postPluralName'   => 'Books',
        'taxSingularName'  => 'Book Category',
        'taxPluralName'    => 'Books Categories',
    ];

This will result in custom post type and taxonomy being registrated under the slugs:

    cpt_book
    ctax_book_category

and it will set `rewrite` rules for pretty URLs using Nice Name conversion:

    somedomain.com/book/post-title
    somedomain.com/book-category/category-title

For the rest of a custom post type and taxonomies configuration, see functions:

- `cptInit()`
- `cptInitTax()`

Always make a new function for additional taxonomies for a custom post type.

When in need for a taxonomy that is shared accross multiple post types, create a seperate class file.

### Utilities

List of all helper functions from this Starter Theme:

- Render.php
    - `templateView()` - *Renders template component or part with configured array variable that maps out template view's variables. The method expects configured array, file name and boolean to toggle directory from template-views/component to template-views/part.*
    - `acfLinkField()` - *Renders ACF link field with all field params.*
    - `inlineSVG()` - *Renders an inline SVG into any template.*
    - `postedOn()` - *Prints HTML with meta information for the current post-date/time and author.*
    - `pagingNav()` - *Outputs the paging navigation based on the global query.*
- Images.php
    - `assets_src()` - *Render image src from 'src/assets/images' or `__demo` directory.*
- ACF.php
    - `registerFlexContent()` - *Register new flexible content field group.*

All helper functions are defined as methods in defined classes that are all loading from *fws/FWS.php* file.

Each method is available through instance of FWS class and instances of other classes located in *fws/src* directory.

Example:
```
fws()->render->templateView( $view_vals, $view_name, $is_partial );
fws()->images->assets_src( $image_file, $is_demo );
fws()->acf->registerFlexContent( $fieldName, $location, $layouts, $hideOnScreen );
```

For full description of each method, see appropriate files and examples in the theme.

For creating a new class file inside `fws/src` directory, reference `Example.php` file located in `__wp-snippets` directory.

To make methods available for direct calls, add on the top of the `FWS.php` a `@property` comment.

![](http://internal.forwardslashny.com/wp-content/uploads/2020/03/fws-inc-example.png
)
