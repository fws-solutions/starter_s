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
