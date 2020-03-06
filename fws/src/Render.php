<?php
declare( strict_types=1 );

namespace FWS;

/**
 * Singleton Class Render
 *
 * @package FWS
 * @author  Nikola Topalovic <nick@forwardslashny.com>
 */
class Render
{

	use Main;

	/**
	 * Renders template component or part with configured *array* variable that maps out template view's variables.
	 * The method expects configured array, file name and boolean to toggle directory from template-views/component to template-views/part.
	 *
	 * @param array   $view_vals
	 * @param string  $view_name
	 * @param boolean $is_partial
	 */
	public function templateView( $view_vals, string $view_name, bool $is_partial = false ): void
	{
		$view_type = ! $is_partial ? 'components' : 'partials';
		$view_var_name = 'content-' . $view_type;
		$view_dir = 'template-views/' . $view_type . '/' . $view_name . '/' . $view_name;

		if ( $view_vals ) {
			set_query_var( $view_var_name, $view_vals );
			get_template_part( $view_dir );
		}
	}

	/**
	 * Renders ACF link field with all field params.
	 *
	 * @param array  $link_field
	 * @param string $link_classes
	 *
	 * @return string
	 */
	public function acfLinkField( array $link_field, string $link_classes ): string
	{
		$link_html = '';

		if ( $link_field ) {
			$link_url = $link_field['url'];
			$link_title = $link_field['title'];
			$link_target = $link_field['target'] ? $link_field['target'] : '_self';
			$link_classes = $link_classes ? 'class="' . $link_classes . '"' : '';

			$link_html = '<a ' . $link_classes . ' href="' . esc_url( $link_url ) . '" target="' . esc_attr( $link_target ) . '">' . esc_html( $link_title ) . '</a>';
		}

		return $link_html;
	}

	/**
	 * Renders an inline SVG file.
	 *
	 * @param string $svg_name
	 * @param string $classes
	 *
	 * @return string
	 */
	public function inlineSVG( string $svg_name, string $classes = '' ): string
	{
		$svg_classes = $classes ? $classes . ' ' : '';
		$svg = file_get_contents( get_template_directory_uri() . '/src/assets/svg/' . $svg_name . '.svg' );

		return '<span class="' . $svg_classes . 'svg-icon">' . $svg . '</span>';
	}

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function postedOn( string $format = '' ): string
	{
		$date = get_the_date( $format );
		$link = get_the_permalink();
		$author = get_the_author();
		$author_page_link = get_author_posts_url( get_the_author_meta( 'ID' ) );

		return '<div class="entry-meta"><span class="posted-on">Posted on <a href="' . $link . '" class="post_url"><span>' . $date . '</span></a> by <a class="author_name" href="' . $author_page_link . '">' . $author . '</a></span></div>';
	}

	/**
	 * Outputs the paging navigation based on the global query.
	 */
	public function pagingNav(): void
	{
		global $wp_query, $wp_rewrite;

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

		$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args = [];
		$url_parts = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( [
			'base' => $pagenum_link,
			'format' => $format,
			'total' => $wp_query->max_num_pages,
			'current' => $paged,
			'mid_size' => 3,
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => __( 'Prev', 'starter_s' ),
			'next_text' => __( 'Next', 'starter_s' ),
		] );

		if ( $links ) :

			?>
			<nav class="navigation paging-navigation" role="navigation">
				<div class="pagination loop-pagination">
					<?php echo $links; ?>
				</div><!-- .pagination -->
			</nav><!-- .navigation -->
		<?php
		endif;
	}
}

return Render::getInstance();
