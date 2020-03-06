<?php
declare( strict_types=1 );

namespace FWS;

/**
 * WC Class. No methods are available for direct calls.
 *
 * @package FWS
 * @author  Boris Djemrovski <boris@forwardslashny.com>
 */
class WC
{

	use Main;

	/**
	 * Main constructor.
	 */
	private function __construct()
	{
		// Bail if WooCommerce plugin isn't activated
		if ( ! function_exists( 'WC' ) ) {
			return;
		}
	}

	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 */
	public function cartLink(): void
	{
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'starter_s' ); ?>">
			<?php
			$item_count_text = sprintf(
			/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'starter_s' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
			<span class="count"><?php echo esc_html( $item_count_text ); ?></span> </a>
		<?php
	}

	/**
	 * Sample implementation of the WooCommerce Mini Cart.
	 *
	 * You can add the WooCommerce Mini Cart to header.php like so:
	 * <code> fws()->wc->headerCart(); </code>
	 */
	public function headerCart(): void
	{
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php $this->cartLink(); ?>
			</li>
			<li>
				<?php
				$instance = [
					'title' => '',
				];

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return 'wc';
	}
}

return Example::getInstance();
