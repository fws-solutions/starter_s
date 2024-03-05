<?php

declare(strict_types=1);

$args = [
	'parameter' => 'value',
];

$query = new WP_Query($args);
?>

<!--WP Query-->
<?php if ($query->have_posts()) : ?>
	<?php while ($query->have_posts()) : ?>
		<?php $query->the_post(); ?>
		<?php the_title(); ?>
	<?php endwhile; ?>
<?php endif; ?>

<?php wp_reset_postdata(); ?>

<!-- String translate -->
<?php esc_html_e('Some text that can be translated.', 'fws_starter_s'); ?>
