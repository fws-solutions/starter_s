<?php
declare(strict_types=1);

namespace FWS\Admin\AdminTools\Tools;


/**
 * Set of some common tools for QA engineers.
 */
class QaTools extends AbstractTool
{

    // unique slug of this tool
    protected static $slug = 'QaTools';

    // title for menu
    protected static $title = 'QA tools';


    /**
     * Show form.
     */
    protected function renderForm(): void
    {
        ?>
        <style>
            .qaToolSubmit {
                background-color: green;
                color: white;
                border: none;
                padding: 4px;
                width: 100px;
                vertical-align: bottom
            }
            .qaToolDiv {
                background-color: #f8f8f8;
                border: 1px solid #ccc;
                margin: 2em 1em 0 0;
                padding: 1em 1em 2em 2em;
            }
        </style>
        <h1>QA tools</h1>

        <div class="qaToolDiv">
            <h2 style="margin-bottom:4px;">Add to Cart</h2>
            <i>Add n random number of products to cart</i><br>
            <form action="admin.php?page=fws-tools&tool=QaTools" method="post" style="margin-top:6px">
                <input type="number" name="addToCartNum" min="1" pattern="[1-9]" value="<?=intval($_POST['addToCartNum'] ?? 101)?>">
                &nbsp;
                <button type="submit" name="action" value="addToCart" class="qaToolSubmit">
                    <span class="dashicons dashicons-saved"></span>
                    Add
                </button>
            </form>
            <div style="font-size: 90%; color: gray; margin-top: 1em;">
                This will try to blindly add products but some of them can be rejected so your cart can contains less products then specified.
            </div>
        </div>

        <div class="qaToolDiv">
            <h2 style="margin-bottom:4px;">Clear Cart</h2>
            <i>Remove all products from cart</i>
            <form action="admin.php?page=fws-tools&tool=QaTools" method="post" style="margin-top:6px">
                <button type="submit" name="action" value="clearCart" class="qaToolSubmit">
                    <span class="dashicons dashicons-saved"></span>
                    Clear
                </button>
            </form>
        </div>
        <?php
    }


    /**
     * Handle from submission.
     */
    protected function handleForm(): bool
    {
        $action = sanitize_text_field($_POST['action'] ?? '');
        if ($action && !class_exists('WC')) {
            self::GenerateMessage("WooCommerce plugin missing.", false);
            return false;
        }

        switch ($action) {
            case 'addToCart':
                return self::handleAddToCartForm();
            case 'clearCart':
                return self::handleClearCart();
            default:
                return false;
        }
    }


    /**
     * Adding N product numbers to the cart.
     *
     * @return bool
     */
    protected function handleAddToCartForm(): bool
    {
        $count = intval($_POST['addToCartNum'] ?? 0);
        $userId = get_current_user_id();
        if (!$count || !$userId) {
            $this->generateMessage("Something went wrong", false);
            return false;
        }
        $certainNumberIDs = $this->getRandomProducts($count);

        // suppress total recalculation until finished, to improve speed
        remove_action('woocommerce_add_to_cart', [WC()->cart, 'calculate_totals'], 20);

        // loop
        foreach ($certainNumberIDs as $id) {
            WC()->cart->add_to_cart($id, 1);
        }

        // re-enable totals
        add_action('woocommerce_add_to_cart', [WC()->cart, 'calculate_totals'], 20, 0);
        WC()->cart->calculate_totals();

        // success
        $this->generateMessage("Added " . count($certainNumberIDs) . " products to cart.", true);
        return true;
    }


    /**
     * Empty cart.
     *
     * @return bool
     */
    protected function handleClearCart(): bool
    {
        WC()->cart->empty_cart();
        $this->generateMessage("Removed all products from cart.", true);
        return true;
    }


    /**
     * Get all product and return the expected number of products.
     *
     * @param int $count
     * @return array
     */
    protected function getRandomProducts(int $count): array
    {
        // get ids of all products
        $args = [
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type'   => 'product',
            'fields'      => 'ids',
        ];
        $ids = get_posts($args);

        // shuffle and return slice
        shuffle($ids);
        return array_slice($ids, 0, $count);
    }


    /**
     * Generate an Error Message or Confirmation Message.
     *
     * @param string $message
     * @param bool   $success
     */
    protected function generateMessage(string $message, bool $success): void
    {
        ?>
        <div style="background-color:<?=esc_attr($success ? '#d4edda' : '#f8d7da')?>; padding:10px; font-weight:600">
            <span class="dashicons <?=esc_attr($success ? 'dashicons-saved' : 'dashicons-no-alt')?>"></span>
            <?=wp_kses_post($message)?>
        </div>
        <?php
    }

}
