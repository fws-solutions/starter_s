<?php
declare( strict_types = 1 );

namespace FWS\Theme;

use FWS\Singleton;

/**
 * Singleton Class Render
 *
 * @package FWS\Theme
 * @author  Nikola Topalovic <nick@forwardslashny.com>
 */
class Render extends Singleton
{

	/** @var self */
	protected static $instance;

	/**
	 * Renders an inline SVG file.
	 *
	 * @param string $svgFileName
	 * @param string $classes
	 *
	 * @return string
	 */
	public function inlineSVG( string $svgFileName, string $classes = '' ): string
	{
		$svgFilePath = get_template_directory_uri() . '/src/assets/svg/' . $svgFileName . '.svg';

		$svg = file_get_contents( $svgFilePath );

		if ( ! $svg ) {
			return '<span style="display: block; color: white; font-weight: bold; background-color: red; padding: 10px; text-align: center;">No SVG file found:<br><span style="font-weight: normal">' . $svgFilePath . '</span></span>';
		}

		// This will remove all id="" attributes from the svg
		$svg = preg_replace( "/id=(\'|\")[^\'|\"]+(\'|\")/m", '', $svg );

		return '<span class="' . $classes . ' svg-icon">' . $svg . '</span>';
	}

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @param string $format
	 *
	 * @return string
	 */
	public function getPostedOn( string $format = '' ): string
	{
		$date = get_the_date( $format );
		$link = get_the_permalink();
		$author = get_the_author();
		$authorPageLink = get_author_posts_url( get_the_author_meta( 'ID' ) );

		return '<div class="entry-meta"><span class="posted-on">Posted on <a href="' . $link . '" class="post_url"><span>' . $date . '</span></a> by <a class="author_name" href="' . $authorPageLink . '">' . $author . '</a></span></div>';
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
		$pagenumLink = html_entity_decode( get_pagenum_link() );
		$queryArgs = [];
		$urlParts = explode( '?', $pagenumLink );

		if ( isset( $urlParts[1] ) ) {
			wp_parse_str( $urlParts[1], $queryArgs );
		}

		$pagenumLink = remove_query_arg( array_keys( $queryArgs ), $pagenumLink );
		$pagenumLink = trailingslashit( $pagenumLink ) . '%_%';

		$format = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenumLink, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( [
			'base' => $pagenumLink,
			'format' => $format,
			'total' => $wp_query->max_num_pages,
			'current' => $paged,
			'mid_size' => 3,
			'add_args' => array_map( 'urlencode', $queryArgs ),
			'prev_text' => __( 'Prev', 'fws_starter_s' ),
			'next_text' => __( 'Next', 'fws_starter_s' ),
		] );

		if ( $links ) {
			$this->templateView( $links, 'page-nav', 'parts' );
		}
	}

	/**
	 * Renders template component or part with configured *array* variable that maps out template view's variables.
	 * The method expects configured array, file name and boolean to toggle directory from template-views/component to template-views/part.
	 *
	 * @param array  $args Args to pass to the view
	 * @param string $name View name (file/dir name without extension)
	 * @param string $type Type of the view (view parent directory): 'blocks' (default), 'parts'...
	 */
	public function templateView( array $args, string $name, string $type = 'blocks' ): void
	{
		$viewVarName = 'content-' . $type;
		$viewPath = 'template-views/' . $type . '/' . $name . '/' . $name;

		if ( $args ) {
			set_query_var( $viewVarName, $args );
			get_template_part( $viewPath );
		}
	}

	/**
	 * Default page Header
	 *
	 * @param string $title
	 * @param string $subtitle
	 * @param bool   $isScreenReader
	 *
	 * @return void
	 */
	public function pageDefaultHeader( string $title, string $subtitle = '', bool $isScreenReader = false ): void
	{
		echo '<header class="page-header">';
		echo '<h1 class="page-title' . ( $isScreenReader ? ' screen-reader-text">' : '">' ) . $title . '</h1>';
		if ( $subtitle ) {
			echo '<div class="archive-description">' . $subtitle . '</div>';
		}
		echo '</header><!-- .page-header -->';
	}

	/**
	 * var_dump function with <pre> tag
	 *
	 * @param $value
	 *
	 * @return void
	 */
	public function varDump( $value ): void
	{
		// Add support for symfony/var-dumper library
		if ( function_exists( 'dump' ) ) {
			dump( $value );

			return;
		}

		echo '<pre style="position: relative; z-index: 100001; background-color: #999;">';
		var_dump( $value );
		echo '</pre>';
	}
}
