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

?>

	</div><!-- #content -->

<style>
	.pas {
		background: #eee;
	}

	.preloader {
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-6 offset-3">
			<div class="pas media-wrap media-wrap--square">
				<?php get_template_part('template-views/parts/preloader/preloader'); ?>

				<img class="media-item cover-img lazyload" src="''" data-src="https://upload.wikimedia.org/wikipedia/commons/1/1e/Caerte_van_Oostlant_4MB.jpg" alt="" />
			</div>
		</div>
	</div>
</div>

	<footer id="colophon" class="site-footer">
		<div class="copyright">
			<p>&copy; <?php echo date( 'Y' ); ?> Starter Theme</p>
		</div><!-- .copyright -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
