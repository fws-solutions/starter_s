<?php
declare( strict_types = 1 );

namespace FWS\WC;

use FWS\Singleton;

/**
 * WC Class. No methods are available for direct calls.
 *
 * @package FWS\WC
 */
class Render extends Singleton
{

	/** @var self */
	protected static $instance;

	/**
	 * Sample implementation of the WooCommerce Mini Cart.
	 *
	 * You can add the WooCommerce Mini Cart to header.php like so:
	 * <code> fws()->wc()->headerCart(); </code>
	 */
	public function headerCart(): void
	{
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( is_cart() ? 'current-menu-item' : '' ); ?>">
				<?php $this->cartLink(); ?>
			</li>
			<li>
				<?php the_widget( 'WC_Widget_Cart', [ 'title' => '' ] ); ?>
			</li>
		</ul>
		<?php
	}

	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 */
	public function cartLink(): void
	{
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'fws_starter_s' ); ?>">
			<?php
			$item_count_text = sprintf(
			/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'fws_starter_s' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			<span class="count"><?php echo esc_html( $item_count_text ); ?></span> </a>
		<?php
	}
}
