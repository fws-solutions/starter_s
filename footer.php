<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fws_starter_s
 */

 $footer_scripts = get_field('footer_scripts', 'options') ?? false;

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="copyright">
			<p>&copy; <?php echo date( 'Y' ); ?> Starter Theme</p>
		</div><!-- .copyright -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<!-- FOOTER SCRIPTS -->
<?php 
	if ($footer_scripts) {
		/**
		 * There is no escaping because admin is going to enter the script tags in the whole format
		 * eg. <script src="test.js"></script>
		 */
		echo $footer_scripts;
	}
?>

<?php wp_footer(); ?>

</body>
</html>
