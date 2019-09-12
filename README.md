# _S WP Starter
*Version: 2.0.0*

> Do Not Underestimate The Power Of
  WordPress.



## Installation Instructions

Install dependencies by running Node.js package manager.

       npm install


## Gulp Tasks
### Building Files

To create development version files, execute `gulp build-dev` task.

    gulp build-dev

To create production version files, execute `gulp build` task.

    gulp build

*please note that build tasks will NOT generate font icons*


### Starting Dev Mode

To start *watch mode* and *local server*, execute `gulp watch` task.

    gulp watch

### Creating Views

To create a new view, execute `gulp cf` task and pass `--component` or `--partial` with an argument.

    gulp cf --component component-name
    gulp cf --partial partial-name

This command will create new module files in appropriate directory `template-views/components` or `template-views/partial`:
* .php
* .scss

It will also update appropriate scss file `_components.scss` or `_partials.scss` in `assets/sass/layout` directory.

### Generate Font Icons

To generate font icons, execute `gulp fonticons` task.

    gulp fonticons

This command will generate fonts:
 * .woff
 * .woff2
 * .ttf

 in `dist/icons` directory based on svg files from `src/assets/svg` directory.

 It will also update `_icon-font.scss` file in `src/scss/base` directory.

See this file for css classes you can use to display font icons.

In order to show icons, all you need to do is add class `"icon font-ico-heart"`

    <span class="icon font-ico-heart"></span>

## SCSS
All components and parts styles should be written in corresponding directory.

All global styles should be written in `src/sass` directories.

CSS code quality is checked with [Sass Lint](https://github.com/sasstools/sass-lint)

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
<div class="banner" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/__demo/banner.jpg);">
    <div class="banner__caption">
        <span style="color: white;" class="banner-example__caption-icon font-ico-happy"></span>
        <h1 class="banner__caption-title">Banner Title</h1>
        <p class="banner__caption-text">Here goes description paragraph</p>
    </div>
</div><!-- .banner -->
```

*.php file:*
```
<?php
/**
 * @var string $title
 * @var string $subtitle
 * @var array $image
 */
extract( (array) get_query_var( 'content-components' ) );
?>

<div class="banner" style="background-image: url(<?php echo $image['sizes']['max-width']; ?>);">
    <div class="banner__caption">
        <span style="color: white;" class="banner-example__caption-icon font-ico-happy"></span>
        <h1 class="banner__caption-title"><?php echo $title; ?></h1>
        <p class="banner__caption-text"><?php echo $subtitle; ?></p>
    </div>
</div><!-- .banner -->
```

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

Making those fields a direct sub fields in a Flexible Content blocks.

Using this system translates very good when it comes to passing Flexible Content values to template views.
Insted of using ACF basic loop, it is required to use standard PHP switch method in a foreach loop.

```
foreach ( get_field( 'flexible_content_0' ) as $fc ) {
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

It is essential to have JSON generating enabled, which is an option set by default if ACF Extended plugin is not being used (more on this plugin below).
If ACF Extended plugin is installed, it is necessary to set JSON generating per each field group.
Another thing to keep in mind, since these fields are being used exclusively for cloning purposes, it is important to set them as **inactive**.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/acf-inactive.png)

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

To resolve this issue, a new plugin is highly recommended to be used as the standard part of the development - [Advanced Custom Fields: Extended](https://wordpress.org/plugins/acf-extended/).

This plugin offers multiple new functionalities and advantages, but for the purposes of resolving group fields organisation it is enough to only use field group categories taxonomy in order to group the field groups together.

Aside from using field group categories it is also required to follow defined naming convention.

Every field group for blocks should be named with a prefix 'FC'.
Every field group for reusable elements should be named with a prefix 'RE'.

![](http://internal.forwardslashny.com/wp-content/uploads/2019/09/fc-cat-acf.png)

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

Needless to state that this part of the workflow assumes that ACF: Extended plugin is being used.
