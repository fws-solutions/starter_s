# FWS Starter _S
*Version: 3.8.3*

> It Only Does Everything.

## Table of Contents

- Installation Instructions
- Starter Config
- CLI
- Working with Common Template Files
- Working with Components Template Files
- SVG Icons
- Media
- SCSS
- JS
- Using Components
- Using ACF with Starter Theme
- FWS Engine

## Installation Instructions
Install FWS CLI globaly.

This only needs to be done once per machine, so if you installed it previously, skip this step.

Although, keep in mind for most recent version of it: [@fws/cli](https://www.npmjs.com/package/@fws/cli).

    npm i @fws/cli -g

Install JS dependencies by running [Node.js](https://nodejs.org/en/) package manager.

    npm install

Install PHP dependencies by running [Composer](https://getcomposer.org/doc/00-intro.md) dependency manager.

    composer install

Install [Advanced Custom Fields](https://www.advancedcustomfields.com/) WordPress plugin as the Starter Theme depends on it. Works better with PRO version.

## Starter Config

Use `.fwsconfig.yml` file to configure top level theme options.

### Global Config

- `theme-name` - set theme full name
- `recovery-mode-emails` - set the fatal error handler email address from admin's to our internal
- `prevent-plugin-update` - enable only logged in users with declared email domain to add/update/remove plugins
- `acf-only-local-editing` - enable acf to edit and manage only on local enviorment


    global:
        theme-name: 'FWS Starter _S'
        recovery-mode-emails:
            - 'nick@forwardslashny.com'
            - 'boris@forwardslashny.com'
            - 'petar@forwardslashny.com'
        prevent-plugin-update:
            enable: true
            domain: forwardslashny.com
        acf-only-local-editing:
            enable: true
            allowed-hosts:
                - '.local'
                - 'localhost/'
                - '.lndo.site'

#### Local Virtual Host

Local enviorment **needs** to be declared in an `.env` file in order for project to work properly.

`.env` file is on git ignore list, but there's also an `.env.example` file that should be cloned and renamed to `.env`.

With this in mind, every team member can be free to name their virual hosts whatever they want to.

    VIRTUAL_HOST_URL=http://project-name.lndo.local/

### ACF Fields Config

More details about `acf-options-page` and `acf-flexible-content` in the **Using Components** section, **Managing Options pages** sub section.

### Styleguide Config

- `pages` - set list of all pages defining
- `colors` set list of all colors, matching
- `icons` - set list of all svg icons
- `fonts` - set list of all fonts, matching variables in scss file
- `titles` - set list of all special titles defining their classes and dummy text
- `buttons` - set list of all buttons defining their classes and dummy text


    styleguide:
        pages:
            -
                title: 'FE Homepage'
                url: '/fe-homepage/'
        colors:
            - 'black'
            - 'white'
        icons:
            - 'ico-arrow-up'
            - 'ico-arrow-down'
        fonts:
            -
                name: 'Open Sans'
                class: 'font-main'
        titles:
            -
                text: 'This is Page Title'
                class: 'page-title'
        buttons:
            -
                text: 'Normal'
                class: 'btn'

Styleguide page **will automatically load** any `_fe-` prefix files.

**This is why it is important to name any variation files using `--` extension to file name.**

For example:
- default file name: `_fe-banner.php`
- varitation file name: `_fe-banner--alt.php`

### Styles and Scripts Version

Note that after `fws build` the `.fwsenqueue.yml` file is generated.

This file is on `.gitignore` list and is generating a timestamp after each **build**.

This **timestamp** is used as a version control for compiled files, that is, for styles and scripts that are enqueued in the theme.

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

## Working with Common Template Files

Below is a list of some basic theme templates and files found in the Starter Theme:

- **index.php**
    - The main template file. It is required in all themes.
    - Any page that does not have template file created for it will default to this one.
- **home.php**
    - The home page template is the front page by default. If you do not set WordPress to use a static front page, this template is used to show latest **Blog** posts.
- **single.php**
    - The single post template is used when a visitor requests **any** single post, in case a post type doesn't have spacific template file for it.
    - For example, if a custom post type Books doesn't find a template file named **single-book.php**, it will default to this one.
    - In general, templates for single posts **should always** be created for **specific post type** as in the Book example above.
    - Alternatevly, it can use this universal **single.php** file, but with conditional rendering handeled within.
    - The example below shows conditional loading of template view for single **Blog** post:
        - `if ( get_post_type() == 'post' ) { get_template_part( 'template-views/blocks/blog-single/blog-single' ); }`
    	- `else { get_template_part( 'template-views/shared/content', get_post_type() ); }`
- **page.php**
    - The page template is used when visitors request individual pages, which are a built-in template.
    - The Starter Theme uses this template **exclusively for ACF Flexible Content**.
- **category.php**
    - The category template is used when visitors request **Blog** posts by category.
- **archive.php**
    - Uses much of the same logic and rules as the **single.php** template file.
    - The archive template is used when visitors request posts by taxnomy term, author, or date.
    - Just like with **single.php**, this template will be overridden if more specific templates are present like **category.php**, **author.php**, **date.php**, etc.
    - This rule obviously exapnds to a post type specific templates, for example, a cutom post type Books will use **archive.php** if it can't find **archive-book.php**.
    - Same as before mentioned file, archive templates **should always** be created for **specific post type** as in the Book example above, unless a conditional rendering logic is written in the base template it self.
- **taxonomy.php**
    - Uses exactly the same logic and rules as the **archive.php** template file, but is specific for a custom taxonomies.
    - In other words, this is the taxonomy term template that is used when a visitor requests a term in a custom taxonomy, where no specific template file is provided.
    - For example, if a custom taxonomy Book Categories doesn't find a template named **taxonomy-book-category.php** it will default to this one.
    - Furthermore, if **taxonomy.php** template file is also not present in the theme, it will default to **archive.php**.
    - This is why it is **important to always** provide specific template files or implement conditional rendering in universal ones.

To learn more about common WP template files, see the links bellow:
- https://developer.wordpress.org/themes/basics/template-files/
- https://developer.wordpress.org/themes/basics/template-hierarchy/

Considering all of the above and the fact that WP for given situation:
- will look for **taxonomy-book-category.php**
- and if not found, will look for **taxonomy.php**
- and if not found, will look for **archive.php**
- and if not found, will look for **index.php**,

it is clear why the suggested **workflow for specific template files is important** when working on complex projects that include **multiple custom post types and taxonomies with different designs and layout**.

To promote these guidelines even more, **another** workflow **rule should always** be followed.

**ALL Common Template Files should NOT contain ANY HTML and should always be written exclusivly with PHP.**

    Example: archive-book.php

    <?php
    get_header();
    do_action( 'fws_starter_s_before_main_content' );

    $book = [
        'title'    => get_the_archive_title(),
        'subtitle' => get_the_archive_description()
    ];
    fws()->render()->templateView( $book, 'book-listing', 'listings' );

    do_action( 'fws_starter_s_after_main_content' );
    get_footer();

## Working with Components Template Files

### Working with PHP Template Views

#### Creating Template Views

There are four types of template views:
- Blocks
- Listings
- Parts
- Shared

To create a new view, execute `fws create-file` command and pass `--block`, `--listing` or `--part` with an argument.

    fws create-file block-name --block
    fws create-file listing-name --listing
    fws create-file part-name --part

Alternatively, it is possible and **recommended** to use short aliases.

    fws cf block-name -b
    fws cf listing-name -l
    fws cf part-name -p

Note that in this case the option argument is passed with one '-' instead of two '--'.

This command will create new module files in appropriate directory `template-views/blocks`, `template-views/listings` or `template-views/parts`:
* .php
* .scss

It will also update appropriate scss file `_blocks.scss`, `_listings.scss` or `_parts.scss` in `src/scss/layout` directory.

**Note:** There are no CLI commends for creating Shared type template views.

#### Deleting PHP Frontend Template Views

Once done with FE development phase, it is required to delete all FE components from `template-views` directory.

To remove them all, , execute `fws remove-fe`

    fws remove-fe

Alternatively, it is possible and **recommended** to use short aliases.

    fws rfe

This command will delete all `.php` files in appropriate directory `template-views/blocks` or `template-views/parts` with `_fe-` prefix.

### Working with Vue Compontents

#### Creating Vue Components

To create a new Vue component, execute `fws creates files` command and pass `--block-vue` or `--part-vue` with an argument.

    fws create-file block-name --block-vue
    fws create-file part-name --part-vue

Alternatively, it is possible and **recommended** to use short aliases.

    fws cf block-name -B
    fws cf part-name -P

Note that in this case the option argument is passed with one '-' instead of two '--'.

This command will create new module file in appropriate directory `src/vue/components/blocks` or `src/vue/components/parts`:
* .vue

#### Naming Conventions

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

## SVG Icons

To generate SVG icons, execute `fws icons` task.

    fws icons

This command will optimize all SVG files in `src/assets/svg` directory directory.

### PHP Usage

Use `inlineSVG` render function to import a SVG file as an inline element in any template.

Use the function as shown in this example:

 `fws()->render()->inlineSVG('ico-happy', 'banner__caption-icon')`.

The function takes two arguments:

- First argument is a name of a file.
- Second argument are additional classes (default: empty string).
- Third argument is boolean to set whether function is used with SVG Icon ACF field (default: false).


    Example:
    <?php echo fws()->render()->inlineSVG('ico-happy', 'banner__caption-icon'); ?>

    Will render:
    <span class="banner__caption-icon svg-icon">
        <svg>...</svg>
    </span>

### SCSS Usage
Use `svg-icon-data($icon, $color, $insert: before)` mixin to create **pseudo** element, converte an SVG file to **Base64 encoding** and set it as a **background image**.

The mixing takes three arguments:

- First argument is a name of a file.
- Second argument is a color of an icon.
- Third argument is whether psuedo element should be `::before` or `::after`. The default value is `::before`.


    Example:
    @include svg-icon-data(ico-happy, $red);

    Will render:
    &::before {
        content: '';
        display: inline-block;
        font-size: 1em;
        width: 1em;
        height: 1em;
        background: url("data:image/svg+xml...") no-repeat center
        background-size: contain;
    }

### Vue Usage

Import SvgIcon.vue file like any other component from `src/vue/components/base/SvgIcon/SvgIcon.vue`.

Use component as shown in this example:

 `<SvgIcon class="banner__caption-icon" iconName="ico-dog"/>`.

The attribute `iconName` is required, pass the name of the svg file from `src/assets/svg`.

Additionally, you can set any other standard HTML attributes, like `class`.

    Example:
    <SvgIcon class="banner__caption-icon" iconName="ico-dog"/>

    Will render:
    <span class="banner__caption-icon svg-icon">
        <svg>...</svg>
    </span>

### ACF Usage

Using SVG files with ACF is made very simple by introducing a custom **FWS ACF SVG field** which is implemented by **already** created and avalible ACF fields and pages:
 - `SVG Icons` - ACF options sub page located under `FWS Settings` ACF top page.
 - `Options: SVG Icons` - field group assigend to SVG Icons sub page.
 - `RE SVG Icons` - field group which can be cloned in your ACF setup. More on this in the section bellow - **Using ACF with Starter Theme**.

**Do not remove any of these fields and pages from this theme.**

Here's a quick summery of how this setup works:

- FWS Settings/SVG Icons page lists all avalible SVG files that are located in `src/assets/svg` direcotry.
- From this page you can choose which icons to enable for RE SVG Icons field.
- RE SVG Field is cloned to a desired field group.
- SVG Icon Field shows up as a button with a popup in which you can choose from enabled icons.

#### Creating SVG Icon Field

When creating a SVG Icon field, simply clone RE SVG Icons reusable field.

See example below:

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/svgAcfClone.png)

In case multiple SVG fields are need in a same group of fields, it can easily be achieved by simply enabling **prefix** cloning option.

See example below:

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/svgAcfCloneDouble.png)

This field will pull all enabled icons from FWS Settings/SVG Icons page.

See the usage flow below:


![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/acfSvg.gif)

#### Using in a Code

SVG ACF field will return a *string* value - the svg file name (without file extension) - which can then be passed into `inlineSVG` function as explained above in **PHP Usage** sub section.

    Example:
    $icon = get_field('fws_svg_icon'); // returns: 'ico-happy'

    <?php echo fws()->render()->inlineSVG($icon, '', true); ?>

    Will render:
    <span class="svg-icon">
        <svg>...</svg>
    </span>

## W3 Validator

To run W3 Validator locally, execute `fws w3 local` command.

    fws w3 local

HTML validity is checked with [W3 Validator](https://validator.w3.org/nu/) API.

This command will only work if local enviorment and virtual host is declared in `.env` file in the property `VIRTUAL_HOST_URL=http://project-name.lndo.local/`.

**This is a must**, your virtual host URL **must be** declared in this manner in order for W3 to work.

Furthermore, W3 Validator has the **only** command that **can be run outside** of the Starter Theme's root directory.

The command for checking any **online/live** URL is:

    fws w3 https://somedomain.com/

Note that you need to pass an actuall domain URL as an argument.

The domain URL **needs to be very strictly formated**. It needs to start with `http` or `https` and needs to end with `/`.

## Media

All images (except logos and icons) should be rendered using declared image size in order to fit the dimensions of a section.

Any media files that are used in frontend phase should be placed in **__demo** folder with **subfolders** for each page.

Any media files that will be static should be placed in **src/assets/images** folder.

Any image should not be larger than 2300px in width, unless there’s a special need for it. Starter Theme comes with predefined image size 'max-width', which **should always** be used for this purpose.

    add_image_size('max-width', 2300, 9999, false);

All **global** image sizes **should** be declared in **`fws/src/Theme/Hooks/BasicSetup.php`** file.

### Cover Image

In order to emulate, using `img` tag, a **background image** with the `cover` size option, and avoid using `object-fit` doing so, refer to `.cover-img` helper CSS class which will do a **pure CSS hack**.

For cover image, parent element **must have `position` rule and `overflow` set to `hidden`**.

`.cover-img` is defined in `_media.scss` file.

    .cover-img {
        min-width: 1000%;
        min-height: 1000%;
        max-width: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.1001);
        transition: $dur $ease;
    }

### Image Sizes

Use `add_image_size` **only** when it makes sense for the project.

**Avoid** creating new image size if particular image appears only once or twice in a project.

For all image sizes that do not need to be declared globally using `add_image_size`, use **FWS's Resizer** feature, that is `newImageSize` function.

Use `newImageSize` function crop an image 'on fly', meaning it will crop passed URL to an approprite image size using WP's default function and upload it to `wp-content/uploads` directory.

Use the function as shown in this example:

    fws()->resizer()->newImageSize($item['url'], 460, 460);

The function takes FIVE arguments:

- `$url` (required) - pass image URL,
- `$width` (required) - pass new width,
- `$height` (required) - pass new height,
- `$crop` (optional) - pass cropping options, defaults to soft crop,
- `$single` (optional) returns an array if false,
- `$upscale` optional) resizes smaller images.


    Example:
    // $url = '/wp-content/uploads/2020/02/some-image.jpg'

    <?php fws()->resizer()->newImageSize($url, 400, 200); ?>

    Will return:
    '/wp-content/uploads/2020/02/some-image-400x200.jpg.jpg'

### Media Wrappers

Working with any media should be done using helper HTML `<div>` wrappers and CSS `.media-wrap`, `.media-wrap--modifer`, `.media-item` and `cover-img` classes.

All the above mentioned classes are defined in `_media.scss` file.

The proper HTML structure should look like this:

    <div class="media-wrap media-wrap--200x200">
        <img class="media-item cover-img" src=".../some-image-200x200.jpg" alt="">
    </div>

Followed by CSS like this:

    // wrapper class
    .media-wrap {
        position: relative;
        overflow: hidden;

        &::before {
            content: '';
            display: block;
            width: 100%;
        }
    }

    // wrapper modifers class
    .media-wrap--square::before {
        padding-top: 100%;
    }

    .media-wrap--400x280::before {
        padding-top: 70%;
    }

    // image class
    .media-item {
        display: block;
    }



![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/08/media-item.jpg)

#### What is actually happening here?

- `.cover-img` class will apply CSS **cover image hack** which positions an image as `absolute`,
- while `.media-item` simply makes sure an `img` tag is a `block` element.
- `.media-wrap` class serves as a positioned `relative` parent element with `overflow: hidden` property.
- `.media-wrap` **adds pseudo `::before`** element which will handle **this wrapper's** height,
- **although** the `::before` **will not** do anything without an additional modifier class.
- Use `.media-wrap--{some-size}` modifer class to explicitaly state a hight of wrapper by setting `padding-top` to `::before` element.
- Since `::before` is a child element of `.media-wrap`, it will expand a wrapper and give it a proper height.

#### Naming and calculating padding percentage

Any element's value of **`padding-top` set in percentage** will be **relative to a parent's `width` value**.

Example:

 - A `div` that has a `width` of `500px`
 - and a child element that has `padding-top` set to `50%`
 - will translate this padding value to `250px`.

With this example in mind, the following forumala can be used to determine a proper percentage in order to get exact aspect ratio for varios images and wrappers sizes:

    (height / width * 100) = padding-top: %;

Considering the CSS example from before, the following naming convention is suggested:

- `.media-wrap--square`
    - has `padding-top: 100%`, as `500 / 500 * 100 = 100`
    - modifier is named 'square' since it can be applied to any element with an aspect ratio 1:1
    - so it makes sense to name a class using words rather than using numbers

- `.media-wrap--400x280`
    - has `padding-top: 70%`, as `280 / 400 * 100 = 70`
    - modifier is named '400x280' since it's dimensions are unique
    - even though these dimensions translate to aspect ratio of 10:7, it would be too confusing to create a modifier named `.media-wrap--10x7`, especially since these values (400x200) would be explicitly set and passed from PHP
    - so in this case, it makes sense to name a class using numbers
    - alternatevly, it is OK to name a class using words that **reference a certain component**, for example `.media-wrap--blog-thumb`, but this can **only be OK if this particular dimensions are being used only for that component**, obviously if same dimensions or aspect ratio is being used in multiple components that don't relate to each other, a number based name makes more sense

In general, a developer is free to name these classes by their best judgement, the point is to write a code that makes sense for everyone.

### Lazy Loading

The Starter Theme comes with [vanilla-lazyload](https://www.npmjs.com/package/vanilla-lazyload) JS plugin.

It should be used throughout the project wherever possible.

Basic usage:

- add `.lazy` class to `img` tag,
- leave `src` attribute empty,
- add `data-src` attribute with an image url to `img` tag.

See the link above for full implementation and documentation.

Example:

    <img class="lazy" src="" data-src="some-image.jpg" alt="">

JS script that is located in `src/js/_site/lazyLoad.js` will initialize vanilla-lazyload plugin on page load.

#### Using Lazy Load with Media Wrapper

Lazy Load plugin is configured to fire a **callback function** which handles image **fade in effect** once an image is loaded.

Furthermore, media wrappers have **additional** modifer classes for **preloader animation** during image loading.

##### Lazy Loading with Preloader

For lazy loding using preloader use `.media-wrap--lazy-loader` modifer class.

This will add `::after` element to media wrapper which will server as preloading animation.

HTML structure:

    <div class="media-wrap media-wrap--square media-wrap--lazy-loader">
        <img class="media-item cover-img lazy" src="" data-src="some-image.jpg" alt="">
    </div>

Followed by CSS:

    // modifier for lazy loading
    .media-wrap--lazy-loader {
        background: $grey-light;

        &::after {
            content: '';
            display: block;
            width: 20px;
            height: 20px;
            background: $grey-light;
            margin-top: -20px;
            animation: lazyPreloading 800ms ease infinite;
            transition: $dur / 2 $ease;
            @include center;
        }

        &.is-hidden::after {
            visibility: hidden;
            opacity: 0;
        }
    }

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/08/ezgif-6-7436f3d5ca20.gif)

##### Lazy Loading with Very Small Thumb

For lazy loding using very small thumb use `.media-wrap--lazy-thumb` modifer class.

Instead of preloader, this will show a **very small thumbnail** image.

This thumbnail **must** be passed in **`src` attribute**.

HTML structure:

    <div class="media-wrap media-wrap--square media-wrap--lazy-thumb">
        <img class="media-item cover-img lazy" src="some-image-20x20.jpg" data-src="some-image.jpg" alt="">
    </div>

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/08/ezgif-6-57bf37b0005a.gif)

### Media Helper Functions

The Starter Theme's FWS Engine includes three helper function to make the use of media wrappers more convinient.

- `mediaItemRegular`
- `mediaItemStatic`
- `mediaItemLazy`

Essentially, all three functions are doing the same, but with a small variation.

#### Media Item Regular

Use `mediaItemRegular` to render image media wrapper `div` as well as `img` with all proper modifer and helper classes.

Use the function as shown in this example:

    fws()->images()->mediaItemRegular( $src, 'square', 'slider__item' );

The function takes FOUR arguments:

- `$src` (required) - pass image URL,
- `$size` (required) - pass size from `__media.scss`, example '400x280', 'square',
- `$classes` (optional) - pass additional classes,
- `$alt` (optional) - pass image alt text.


    Example:

    <?php echo fws()->images()->mediaItemRegular( $src, 'square', 'slider__item' ); ?>

    Will render:
    <div class="slider__item media-wrap media-wrap--square">
        <img class="media-item cover-img" src=".../some-image.jpg" alt="">
    </div>

#### Media Item Static

`mediaItemStatic` does same as `mediaItemRegular`, but it's configured to pull **static theme assets**.

Use the function as shown in this example:

    fws()->images()->mediaItemStatic( 'slide-5.jpg', 'square', 'slider__item', true );

The function takes FOUR arguments:

- `$src`, `$size` and `$classes` - exactly the same as for `mediaItemRegular`,
- `$isDemo` (optional) - set whether to load image from `src/assets/images` or `__demo` directory.

#### Media Item Lazy

`mediaItemLazy` does same as `mediaItemRegular`, but with additional options for lazy loading.

Use the function as shown in this example:

    echo fws()->images()->mediaItemLazy( $src, 'square', 'slider__item' );

The function takes FIVE arguments:

- `$src`, `$size`, `$classes` and `$alt` - exactly the same as for `mediaItemRegular`,
- `$preloadThumb` (optional) - pass crop dimensions as an array(width, height).

If `$preloadThumb` is not set, the function will render media wrapper set for **lazy loading with preloader**.

Alternativly, if it is set, the function will render media wrapper set for **lazy loading with very small thumb**.

    Example:

    echo fws()->images()->mediaItemLazy( $src, 'square', 'slider__item', 'some-alt-text', [20, 20] );

    Will render:
    <div class="slider__item media-wrap--lazy-thumb media-wrap media-wrap--square">
        <img class="media-item cover-img lazy" src="some-image-20x20.jpg" data-src="some-image.jpg" alt="some-alt-text">
    </div>

## SCSS

All Template Views styles should be written in corresponding directory.

All Vue Components styles should be written in `.vue` files.

All global styles should be written in `src/scss` directories.

- For project's global styles everything should be written in `src/scss/site.scss` file and it's imported files.
- For dashboard's global styles everything should be written in `src/scss/admin.scss` file and `src/scss/admin` directory.

Any styles for JS plugins should be imported from node_modules. No direct copy/pasting plugin styles should be practiced in the theme.

    Example: site.js

    @import '../../node_modules/@fancyapps/fancybox/dist/jquery.fancybox';
    @import '../../node_modules/select2/dist/css/select2';
    @import '../../node_modules/perfect-scrollbar/css/perfect-scrollbar';
    @import '../../node_modules/slick-carousel/slick/slick.scss'; //sass-lint:disable-line clean-import-paths

CSS code quality is checked with [Sass Lint](https://github.com/sasstools/sass-lint).

## JS
As this is a WP theme, by default it is relying on jQuery library.

Global JS scripts should be written in `src/js` directories.

- For project's global scripts everything should be invoked from `src/js/site.js` file.
- For dashboard's global scripts everything should be invoked from `src/js/admin.js` file.

The file `site.js` and `admin.js` should contain all load methods, and serve for invoking script's init methods.

    Example: src/js/site.js

    import Menu from './_site/menu';
    import Sliders from './_site/sliders';

    jQuery(function() {
        Menu.init();
        Sliders.init();
    });

All other files should be organized following this folder structure:
- `site` - contains all scripts for a site.
- `admin` - contains all scripts for WP dashboard.
- `shared` - contains all scripts that are shared between `site.js` and `admin.js`.

Vue JS scripts and logic should be written in appropriate files in `src/vue` directories.

Any JS plugins should be installed and imported using NPM. No direct copy/pasting plugin scripts should be practiced in the theme.

For general availability, import in `site.js` or `admin.js` file.

    Example: src/js/site.js

    import 'bootstrap/js/src/util';
    import 'bootstrap/js/src/scrollspy';
    import Menu from './site/menu';
    import Sliders from './site/sliders';

    jQuery(function() {
        Menu.init();
        Sliders.init();
    });

Making a note that we can also import just chunks of packages that we need (as seen above with Bootstrap), meaning we might not want to import an entire library if we only need just a piece of it.

For plugins that are needed per script basis, simply import in a proper script file.

    Example: src/js/site/sliders.js

    const $ = jQuery.noConflict();
    import 'slick-carousel';

    'use strict';
    const Sliders = {
        ...
    }

JS code quality is checked with [ESLint](https://eslint.org/).

## Using Components

### Components File Structure

All components will be created in template-views directory.

Each component has three files:
* (_fe).php *(component HTML structure)*
* .php *(comopnent template)*
* .scss *(component styles)*

#### Frontend (_fe) PHP files

File with a '_fe' prefix is used only for pure frontend HTML structure, no PHP variables, methods or any other logic should be written here *(except helper functions for rendering images)*.

```
<div class="banner" style="background-image: url(<?php echo fws()->images()->assets_src('banner.jpg', true); ?>);">
    <div class="banner__caption">
        <span style="color: white;" class="banner__caption-icon font-ico-happy"></span>
        <h1 class="banner__caption-title">Banner Title</h1>
        <p class="banner__caption-text">Here goes description paragraph</p>
    </div>
</div><!-- .banner -->
```

- **(fe) template-views**
    - Used for writing HTML for each component.
    - Each component should be named with prefix "_fe-" and the rest of the name should be name of the component.
    - When creating a variation of existing block or part use similar naming convention as BEM CSS class naming. More on this in the section bellow - **Frontend Component Variation**.
- **fe-templates**
    - Used for combining frontend components into a single page.
    - Each page should be named with prefix "fe-" and the rest of the name should be name of the page, for example: fe-homepage.php.
    - Each page should never contain anything but a call to a template view.

#### Frontend Component Variation

Creating a variation of existing block or part should use similar naming convention as BEM CSS class naming, for example:

- default: _fe-banner.php,
- variation-1: _fe-banner--big.php,
- variation-2: _fe-banner--about-page.php.

The idea is to always use full name of component "_fe-something", use "--" for chaining and last part of file is arbitrarily.

Furthermore, any *\_fe* component needs to be properly structured and commented in order to achieve most flexible and clean backend integration.

**See examples bellow:**

**Default Component**
```
<div class="box">
    <div class="box__container container">
    	<div class="box__content">
            <h2 class="box__title section-title">Some Title</h2>

            <p class="box__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>

            <a class="box__button btn" href="...">Some button</a>
    	</div>

    	<div class="box__media">
            <img class="box__img" src="..."/>
    	</div>
    </div>
</div>
```

**Variation Component - Bad Example**

```
<div class="box">
    <div class="box__container container">
    	<div class="box__media box__media--reverse">
    	    <img class="box__img" src="..."/>
    	</div>

        <div class="box__content box__content--reverse">
            <h2 class="box__title box__title--reverse section-title">Some Title</h2>

            <p class="box__text box__text--reverse">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
    	</div>
    </div>
</div>
```

What is wrong with this example? A lot of things:

- HTML structure is changed, we have box media showing before box content, this will require backend to write unneccessary rendering logic and code duplication,
- too many BEM--modifier classes,
- button is missing and there is nothing to note that,
- no TODO comments what so ever.

In this example, we can see how a lot of stuff can be very difficult for backend implementation, and without any TODO comments, it is very easy to miss little details that are essential.

**Variation Component - Good Example**

```
// TODO - add 'box--reverse' class
<div class="box box--reverse">
    <div class="box__container container">
    	<div class="box__content">
            <h2 class="box__title section-title">Some Title</h2>

            <p class="box__text">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>

            // TODO - 'box__button' removed
    	</div>

    	<div class="box__media">
            <img class="box__img" src="..."/>
    	</div>
    </div>
</div>
```

The *good example* illustrates much more cleaner and backend friendly component variation structure:

- HTML code is almost identical,
- only one BEM--modifier class is placed at the top level element, this not only makes is easier for backend implementation but also for CSS styling,
- TODO comment is placed for anything that this variation differs from default component.

**General Note:** FE developer should always tend to follow this example and make sure each variation is as similar as possible to the Default Component structure and commented out with TODO comments fully.

#### Blocks and Parts

**Blocks** and **Parts** view types **should always be coded as 'dump' components**, meaning they should **never contain** any functional logic and should **only** be used as **templates** that are **receiving** proper values to render.

These views should also be implemented **as a single file** per each component, meaning that even thought some views will have multiple **_fe** files due to variations, when it comes to BE it should all be written in **one php file** with proper **conditional rendering logic**.

PHP template view file is relying on globally set variables that should be accessed using get_query_var() function.

Template view should **always** map out all `$query_var` values in order to break an array to separate variables.

```
<?php
// get template view values
$query_var = get_query_var( 'content-blocks', [] );

// set and escape template view values
$title = esc_textarea( $query_var['title'] ) ?? '';
$subtitle = esc_textarea( $query_var['subtitle'] ) ?? '';
$image = $query_var['mobile_image'] ?? [];
?>

<div class="banner" style="background-image: url(<?php echo $image['sizes']['max-width']; ?>);">
    <div class="banner__caption">
        <span style="color: white;" class="banner-example__caption-icon font-ico-happy"></span>
        <h1 class="banner__caption-title"><?php echo $title; ?></h1>
        <p class="banner__caption-text"><?php echo $subtitle; ?></p>
    </div>
</div><!-- .banner -->
```

More details about **prop types** and **escaping** in the **this** section, **Rendering Components** sub section below.

#### Listings

**Listings** view type, on the other hand, **can and should** contain some logic, but **only** limited to WP's Loop functionality.

In fact, any Post type **should** have and use **Listings** view type to loop over it's posts.

    Example:

    <?php
    // get template view values
    $query_var = get_query_var( 'content-listings', [] );

    // set and escape template view values
    $title = esc_textarea( $query_var['title'] ) ?? '';
    ?>

    <div class="blog-listing">
        <h1 class="blog-listing__title section-title"><?php echo $title ?></h1>

        <?php
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();

                $blog_article = [
                    'permalink' => get_the_permalink(),
                    'title' => get_the_title()
                ];
                fws()->render()->templateView( $blog_article, 'blog-article', 'parts' );
            }
        } else {
            get_template_part( 'template-views/shared/content', 'none' );
        }
        ?>
    </div>

#### Shared

**Shared** view type is a helper type that servers for default content and other helper wrappers such as `flex-content`. This view type is handeled exclusively manually.

### Quality control

HTML quality is checked with [htmllint](http://htmllint.github.io/).

HTML validity is checked with [W3 Validator](https://validator.w3.org/nu/).

### Rendering Components

Use FWS function *templateView(**array** $view_vals, **string** $view_name, **bool** $is_partial)* with configured *array* variable to map out components variables.

```
$basic_block = [
  'title' => get_field( 'title' ),
  'subtitle'  => get_field( 'subtitle' ),
  'image' => get_field( 'image' )
];

fws()->render()->templateView( $basic_block, 'banner' );
```

#### Prop Types and Escaping

Once a **configured array** variable is passed to template view file, it should **always** map out all it's values in order to break an array to separate variables. As noted in the section, **Components File Structure**, above.

During array mappping to seperate variables, it is **very important** to declare prop types or handle value escaping where needed.

Some of the functions that can be used for **escaping**:
- `esc_attr` - used to escape any HTML attributes (id, class, data-attr, etc.),
- `esc_url` - used to escape any HTML URLs (src, srcset, href, ...),
- `esc_textarea`
    - used to excape any text content that will not contain any HTML elements except <br> tags,
    - these can be used, for example, for ACF text or textarea fields,

**Only fields that are suppose to render HTML directly should NOT be declared with prop type or escaped.**

**This includes, for example, WYSIWYG field or get_post_thumbnail() function.**

Lastly, it is **neccessary** to handle default value fallback in case no value is passed.

Some examples would suggest using this approach `$smth = isset( $smth ) ? $smth : [];`, but our workflow **favors coalescing operator** which would translate the aforementioned
approach to this `$smth ?? [];`;

    Example 1: $smth = isset( $smth ) ? $smth : [];
    Example 2: $smth ?? [];

Both examples do the same thing, but obviously, much shorter code is the reason why *Example 2* is enforced in our workflow.

    See full example of template view block:

    // get template view values
    $query_var = get_query_var( 'content-parts', [] );

    // set and escape template view values
    $id = (int) $query_var['id'] ?? 0;
    $post_class = esc_attr( implode( ' ', $query_var['post_class'] ?? [] ) );
    $permalink = esc_url( $query_var['permalink'] ) ?? '';
    $title = esc_textarea( $query_var['title'] ) ?? '';
    $has_post_thumb = (bool) $query_var['has_post_thumb'] ?? false;
    $post_thumb = $query_var['post_thumb'] ?? '';



## Using ACF with Starter Theme

### General setup

With modular template views it is essential that ACF Flexible Content is organized and implemented in a defined manner.

**Moving away from default Flexible Content implementation...**

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2019/09/flex-content-old.png)

**... and make full use of Clone field.**

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2019/09/flex-content-new.png)

**Each Flexible Content block will use Clone field to copy ALL fields from certain field group.**

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2019/09/flex-content-groups.png)

Making those fields a direct sub fields in a Flexible Content layouts.

Using this system translates very good when it comes to passing Flexible Content values to template views.
Insted of using ACF basic loop, it is required to use standard PHP switch method in a foreach loop.

```
foreach ( get_field( 'content' ) as $fc ) {
  switch ( $fc['acf_fc_layout'] ) {
    case 'banner':
      fws()->render()->templateView( $fc, 'banner' );
      break;
    case 'slider':
      fws()->render()->templateView( $fc, 'slider' );
      break;
    default:
      fws()->render()->templateView( $fc, 'basic-block' );
  }
}
```

In the example above, it is **important to note** that variable that is being passed to *templateView()* function **is not set** as an array like in the previous example, but rather simply passed the current item from the loop.

The reason this is possible is because of **the way ACF values are natively returned**. Meaning, ACF fields are **always stored and returned as an array**.

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/04/fws-acf-setup-new.png)

Naming the fields same names as variables in the template views will make sure that each component gets properly formated array values which it needs for rendering properly.

### Using JSON

The idea behind this approach of modular ACF fields is to take full advantage of ACF [Local JSON](https://www.advancedcustomfields.com/resources/local-json/).

Inspiration for this workflow was drawn from this [post](https://www.awesomeacf.com/how-to-avoid-conflicts-when-using-the-acf-local-json-feature/). Although it has nice ideas it still doesn't resolve the issue when two developers are working on same field groups, in such a scenario **a conflict of JSON files is inevitable**.

Main goal is to **allow multiple developers** to work on field groups simultaneously on local enviroment, with **lowest possible risk** of having conflicts in generated JSON files.

By splitting each Flexible Content block to a separate field group, the workflow is optimized to allow more developers to work in parallel. There is still a risk of creating a conflict if two developers are editing same field group, but in this workflow chances for that are slim.

It is essential to have JSON generating enabled, which is an option set by default.
Another thing to keep in mind, since these fields are being used exclusively for cloning purposes, it is important to set them as **inactive**.

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/02/acf-inactive2.png)

In order to optimize the workflow even further, this Starter Theme comes with hook function **that will automatically sync any changes** in field groups registered by new JSON files.

For example:
- Developer A makes changes in field group Banners.
- Developer B pulls changes into his local enviroment. On first dashboard load, the ACF field groups will get synced and updated as the internal script will detect changes in JSON files.

Lastly, it is necessary to **avoid any conflicts** coming from any changes **made directly on development or live server**. To resolve this, Starter Theme actually comes with another hook **which disables** any field group editing on any other enviroment, **forcing developers** to make all changes exclusively on **local enviroment**.

### Naming conventions and categorizing

The workflow above resolves a lot of problems but it does have a **small drawback**, creating each block's fields as a separate field group **will result in too meny groups** in the dashboard.

Furthermore, it is highly recommended to also create **helper groups** of fields that can be cloned in other block's fields.

For example, the Section Title field...

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2019/09/re-acf.png)

... can be reusable across many different field groups.

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2019/09/fc-acf.png)

Considering **helper group** fields together with **block group** fields, the number of groups in the dashboard will tend to get **very long and unorganized**.

To resolve this issue, this theme comes with a Custom Taxonomy **"Categories"** for ACF plugin, which **should** be used in order to group the field groups together.

Aside from using field group categories it is also **required to follow defined naming convention**.

Every field group for **blocks** should be named with a **prefix 'FC'**.
Every field group for **reusable elements** should be named with a **prefix 'RE'**.

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/02/acf-categories.png)

With these two conventions, it is visually optimised to distinguish which fields are blocks and which helpers, and it is user friendly to use categories to filter out desired groups.

### Managing Flexible Content field

With everything above fully implemented, tha **last thing** to tackle is **Flexible Content** field and potential conflicts which are still **not covered** by the defined workflow.

Cloning separate field groups into Flexible Content blocks **resolves avoiding JSON conflicts** when multiple developers are working **on a separate components (field groups)**, but it is still **necessary** for each developer **to edit same field group for Flexible Content** and make changes simultaneously and therefore create an inevitable JSON conflict.

**Final step** in this workflow is to actually **avoid creating/editing** any **Flexible Content** group fields from the dashboard and make those changes through `.fwsconfig.yml` file.

Starter Theme comes with more helper functions to enable just that, but it is **important to follow the proper formating** of `.fwsconfig.yml` file.

All values must be written under `acf-flexible-content` with defined **group name** as property name that includes the following sub properties.

**The Starter Theme will automatically load any defined group names, unless the `autoload` property is disabled.**

- `autoload`
    - Set whether or not to autoload this flexible content group.
    - If set to `false`, you'll need to use function directly in code somewhere in order to enable the field group.
    - The function in question is `addNewFlexContentGroup($fc);` that is located in **fws/src/ACF.php**.
- `field-name`
    - Filed name that will show on page.
    - See image **Field Name** bellow.
- `location`
    - `param` Set to what type this field group will be for.
    - `value` Set to what type this field group will equal to.
    - Set location where this field group will load.
    - See image **Field Location** bellow.
    - Some of the possible values:
        - "post_type": "post"
        - "page_template": "default"
        - "taxonomy": "category"
        - "options_page": "fws_starter_s-settings"
    - For more information on avalible values, pleaase refer to [ACF Docs](https://www.advancedcustomfields.com/resources/)
    - **Important Note**: Currently, the conditional logic for `param` and `value` is set to equal (`"operator": "=="`). This is hardcoded withing the theme, in order to expand this option and flexibility of `.fwsconfig.yml`, please reffer to `addNewFlexContentGroup` and `registerFlexContent` methods in *fws/src/ACF.php*.
- `hide-on-screen`
    - Set rules for default meta fields that should be hidden on a page.
    - See image **Field Hidden Stuff** bellow.
    - All possible values:
        - permalink
        - the_content
        - excerpt
        - discussion
        - comments
        - revisions
        - slug
        - author
        - format
        - page_attributes
        - featured_image
        - categories
        - tags
        - send-trackbacks
- `layouts` set flex content layouts/blocks that will show for this group
    - `title` This is the name that will show in flex content dropdown, it can be set arbitrarily.
    - `group_id` This must be set to field group id/key value.
    - See images **Field Layout Title** and **Field Layout Group ID** bellow.

***Field Name***
![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/04/field-group-title.png)

***Field Location***
![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/04/field-group-location.png)

***Field Hidden Stuff***
![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/04/field-group-hide.png)

***Field Layout Title***
![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/04/field-group-name.png)

***Field Layout Group ID***
![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/04/filed-group-id.png)

**Example of .fwsconfig.yml**:

    acf-flexible-content:                               # DEFINE ACF FLEXIBLE CONTENT GROUPS AND FIELDS
        default-page-template:                          # define flexible content for default page template
            autoload: true                              # set whether or not to autoload this flexible content group
            field-name: 'Content'                       # filed name that will show on page
            location:                                   # set location where this field group will load
                param: 'page_template'
                value: 'default'
            hide-on-screen: [ 'the_content' ]           # set rules for default meta fields that should be hidden on a page
            layouts:                                    # set flex content layouts/blocks that will show for this group
                -
                    title: 'Banner'
                    group_id: 'group_5d70e7dfa2562'
                -
                    title: 'Basic Block'
                    group_id: 'group_5d70e7ea08bce'
                -
                    title: 'Slider'
                    group_id: 'group_5d70e7f775076'
                -
                    title: 'Vue Block'
                    group_id: 'group_5dcd6b37b67a4'

To sum up, all Flexible Content group fields must be defined as **arrays of an array**.

In the example above, Flexible Content group `default-page-template` is an array value of `acf-flexible-content`.

To register more then one Flexible Content group, it is neccessary to simply add another array into `acf-flexible-content`.

**Example of .fwsconfig.yml** that is showing three Flexible Content groups for different post types:

    acf-flexible-content:
        default-page-template:
            autoload: ...
            field-name: ...
            location: ...
            hide-on-screen: ...
            layouts: ...
        blog-page-template:
            autoload: ...
            ...
        product-page-template:
            autoload: ...
            ...

### Managing Options pages

Having in mind the workflow we have for Flexible Content, it is safe to assume that very similar apporach is used for ACF Options pages, so just like in the examples above it is **important to follow the proper formating** of `.fwsconfig.yml` file.

All values must be written under `acf-options-page`:

- `enable`
    - Set to `true` or `false` in order to enable ACF Options Main page.
    - The name of the Menu Item in the Dashboard will be the of the theme set in `global` property, `theme-name` sub property.
- `subpages`
    - Takes on array of strings, which will be used to create sub pages of ACF Options.
    - See image **Sub pages** bellow.
    - Leave empty array if no sub pages are needed - `subpages: []`.

***Sub pages***

![](https://fwsinternaladm.wpengine.com/wp-content/uploads/2020/04/acf-options.png)

**Example of .fwsconfig.yml**

    acf-options-page:
        enable: true
        subpages:
            - 'Mega Menu'
            - 'Shared Sections'

## FWS Engine

FWS Engine is a default part of this Starter Theme to which **the Starter relies on heavily**.

See `fws` and `fws/src` for its structure and features.

List of Starter **default features** and **classes**:

- `Config\Config` - parse `.fwsconfig.yml` and expose values.
- `Theme\Hooks\BasicSetup` - add basic theme setup and theme supports.
- `Theme\Hooks\CustomSetup` - handle Starter custom features.
- `Theme\Hooks\HeadRemovals` - remove any unnecessary data from `head`.
- `Theme\Hooks\Menus` - register and customize Menus.
- `Theme\Hooks\StylesAndScripts` - enqueue styles and scripts.
- `Theme\Hooks\SectionWrappers` - add wrapper actions.
- `Theme\Hooks\WPLogin` - customize WP login page.
- `Theme\Images` - various public methods for handeling images.
- `Theme\Render` - various public methods rendering views.
- `Theme\Styleguide` - various public methods for handeling images.
- `ACF\Hooks` - customize ACF plugin.
- `ACF\Render` - various public helper methods for rendering ACF fields.
- `ACF\FlexContent` - define flexible content field configuration.
- `WC\Hooks` - configure WooCommerce settings.
- `WC\Render` - various public methods for rendering WooCommerce parts.
- `CF7\Hooks` - hooks for enabaling Contact Form 7 custom html templates.

### WooCommerce Support

All WooCommerce functionality overrides should be written in
 - `fws/src/WC/Render.php` and
 - `fws/src/WC/Hooks.php` files.

All WooCommerce template overrides should be written in `woocommerce` directory.

Before implementing any template overrides, all templates of the **current plugin version** should be **backed up** in `woocommerce/__templates-backup` directory.

**This is important to do because if WooCommerce plugin is updated, you will lose original templates and will not be able to compare any overrides that need updating as well.**

The `woocommerce` root directory should **only contain** files that are being overriden. **By all means, do NOT ever copy entire template structure to this folder**.

### Contact Form 7 Support

Contact Form 7 FWS extension enables usage of HTML and [MJML](https://mjml.io/) files for creating form and email templates.

All CF7 functionality overrides should be written in
 - `fws/src/CF7/Hooks`file.

For admin dashboard:
 - `src/config/admin/js/admin/cf7.js` and
 - `src/config/admin/scss/_cf7.scss` files.

**The drawback of CF7 plugin is that all HTML needs to be written inside the dashboard.**

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-form.png)

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-email.png)

This creates obvious issues when it comes to version control, there is no way to keep this HTML structure on a Git repository and there's an unnecessary risk that database gets lost along with whole written HTML and CSS for forms and emails.

To resolve this problem, Starter comes with a feature which enables writting custom HTML file for creating CF7 form and usage of MJML templating engine for creating emails.

With this in mind, the following workflow should be implemented:
 - All HTML for CF7 form should be written using `.html` format in `src/emails/cf7` directory.
 - All HTML for CF7 emails should be written using `.mjml` format in `src/emails/cf7` directory.

**Note that all `.html` files will be copied over to `dist/cf7` directory, and all `.mjml` files will be compiled into HTML to the same `dist/cf7` directory.**

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-src.png)

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-dist.png)

After everything is configured and created inside a project's theme, a new options for CF7 will be avalible.

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-enabled.png)

As seen in the screenshot, all fields for creating forms and emails are **locked** and a new tab `FWS CF7 Templates` is avalible.

There, we can choose which templates we want to use for an approprite dashboard field.

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-temokates.png)

Once selected, entire HTML content will be copied over to appropriet fields.

**Make sure the form is saved before leaving the page** as this feature only prepopulates the content and not actually saves it.

**Another important thing to note**, before the HTML is appended to proper fields in CF7 dashboard, the entire HTML is run through `lodash.template` compiler in order to cover any dynamic content such as **theme root path** and **site url**.

The avalible **variables** are:
 - `themeRoot` - will return theme's root path,
 - `siteUrl` - will return site's url.

Syntax for placing these variables is `<%= siteUrl %>`.

    Example:

    <mj-button href="<%= siteUrl %>">Go To Website</mj-button>
    <mj-image src="<%= themeRoot %>/src/assets/images/fws-logo-red.png"/>

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-var.png)

![](http://fwsinternaladm.wpengine.com/wp-content/uploads/2020/12/cf7-compiled.png)

As seen in the examples above, these variables will compile to proper values pulled from WP database.

Lastly, the enitre feature can be disabled by simply changing `.fwsconfig.yml` option `cf7-custom-templates` to `false`.

### Custom Post Types and Taxonomies

Registering custom post types and taxonomies must always be done using FWS Engine.

Each custom post type with belonging taxonomies must be placed in a single file inside
`fws/src/CPT` directory. If custom post type is a part of a broader business logic, than it would
make more sense to put it into its own namespace which better describes that feature or component.
If you are using different folder structure, make sure that the namespace reflects that.

Always use `ExampleCPT.php` example file located in `__examples-and-snippets` directory. Copy the file to
`fws/src/CPT` folder and make sure you rename both the file and the Class. Both should be exactly
the same.

Naming format of these files should be followed like this - `CPTBooks.php`, so essentially the `fws` directory should have this path:

    fws/src/CPT/CPTBooks.php

Use `$postConfig` and `$taxConfig` array variable to configure names of custom post type and taxonomies.

Example:

    private $postConfig = [
        'singularName' => 'Custom Post',
        'pluralName'   => 'Custom Posts',
        'dashIcon' => 'dashicons-admin-post'
    ];

    private $taxConfig = [
        [
            'singularName'  => 'Custom Post Category',
            'pluralName'    => 'Custom Post Categories',
        ],
        [
            'singularName'  => 'Custom Post Attribute',
            'pluralName'    => 'Custom Post Attributes',
        ]
    ];

Methods within the CPT class will handle `$postConfig` and `$taxConfig` variable to pull appropriate names,
labels and generate a slug.

Slug and Nice Name are based on the singular name of a custom post type or taxonomy.
FWS will replace any space characters for `_` or `-` character and add appropriate prefix when needed.

Slug is used for registrating custom post type or taxonomy under this name, it will use `_` character and a prefix.

Nice Name is used for URL structure, it will use `-` character and will not include a prefix.

Prefixes are defined as follows:
- for a post type: `cpt_`
- for a taxonomy: `ctax_`

Example:

    private $postConfig = [
        'postSingularName' => 'Book',
        'postPluralName'   => 'Books'
    ];

    private $taxConfig = [
        [
            'singularName'  => 'Book Category',
            'pluralName'    => 'Books Categories',
        ]
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

To init CPT class, it must be inlcuded and initiliaized in `FWS.php` file.


**In `FWS.php` file:**

    <?php
    declare( strict_types = 1 );

    ...
    use FWS\CPT\CPTBooks as CPTBooks;

    protected function __construct()
    {
        ...

        // Theme CPTs
        CPTBooks::init();

        ...
    }


### Utilities

List of all helper functions from this Starter Theme:

- `templateView()` - *Renders template component or part with configured array variable that maps out template view's variables. The method expects configured array, file name and boolean to toggle directory from template-views/component to template-views/part.*
- `linkField()` - *Renders ACF link field with all field params.*
- `inlineSVG()` - *Renders an inline SVG into any template.*
- `postedOn()` - *Prints HTML with meta information for the current post-date/time and author.*
- `pagingNav()` - *Outputs the paging navigation based on the global query.*
- `assetsSrc()` - *Render image src from 'src/assets/images' or `__demo` directory.*
- `mediaItemRegular()` - *Render image media wrapper `div` as well as `img` with all proper modifer and helper classes.*
- `mediaItemStatic()` - *Does same as `mediaItemRegular`, but it's configured to pull static theme assets.*
- `mediaItemLazy()` - *Does same as `mediaItemRegular`, but with additional options for lazy loading.*
- `newImageSize()` - *Create a new image size on 'on fly'.*
- `varDump()` - *A better way to `var_dump()` stuff.*

All helper functions are defined as methods in defined classes that are all loading from **fws/FWS.php** file.

Each method is available through instance of FWS class and instances of other classes located in *fws/src* directory.

Example:
```
fws()->render()->templateView( $data, 'banner' );
echo fws()->images()->assetsSrc( 'dog-md.jpg', true );
echo fws()->acf()->linkField( $button, 'banner__btn btn' )

```

For full description of each method, see appropriate files and examples in the theme.
