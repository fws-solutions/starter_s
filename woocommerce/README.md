### WooCommerce support

All WooCommerce functionality overrides should be written in `fws/src/WC.php` and `fws/src/WCHooks.php` files.

All WooCommerce template overrides should be written in `woocommerce` directory.

Before implementing any template overrides, all templates of the **current plugin version** should be **backed up** in `woocommerce/__templates-backup` directory.

**This is important to do because if WooCommerce plugin is updated, you will loose original templates and will not be able to compare any overrides that need updating as well.**

The `woocommerce` root directory should **only contain** files that are being overriden. **By all means, do NOT ever copy entire template structure to this folder**.
