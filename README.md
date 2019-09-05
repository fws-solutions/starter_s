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
