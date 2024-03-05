### Custom Post Types and Taxonomies

Each custom post type with belonging taxonomies must be placed in a single file inside
`fws/src/CPT` directory. If custom post type is a part of a broader business logic, than it would
make more sense to put it into its own namespace which better describes that feature or component.
If you are using different folder structure, make sure that the namespace reflects that.

Always use `ExampleCPT.php` example file located in `__examples-and-snippets` directory. Copy the file to
`fws/src/CPT` folder and make sure you rename both the file and the Class. Both should be exactly
the same.

Naming format of these files should be followed like this - `CPTBooks.php`, so essentially the `fws` directory should have this path:

    fws/src/CPT/CPTBooks.php

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
