<?php
declare( strict_types = 1 );

namespace FWS\CF7;

use FWS\SingletonHook;

/**
 * CF7 Class for hooks. No methods are available for direct calls.
 *
 * @package FWS\WC
 */
class Hooks extends SingletonHook
{

	/** @var self */
	protected static $instance;

	/**
	 * Register CF7 custom panel
	 */
	public function customPanel($panels)
	{
		$panels['custom-fields'] = array(
			'title' => 'HTML Template',
			'callback' => [ $this, 'panelMarkdown' ],
		);

		return $panels;
	}

	/**
	 * Markdown for custom panel
	 *
	 * @param $post
	 *
	 * @return void
	 */
	public function panelMarkdown ($post): void
	{
		$templates = $this->getHtmlTemplates();
		$selected = get_post_meta($post->id(), 'cf7_html_temp', true);

		?>
		<h2><?php echo esc_html( __( 'HTML Template', 'contact-form-7' ) ); ?></h2>
		<fieldset>
			<label for="wpcf7-html-temp"><b><?php echo esc_html( __( 'Choose HTML Template:', 'contact-form-7' ) ); ?></b></label>

			<select name="html-templates" id="wpcf7-html-temp">
				<?php foreach ($templates as $temp) : ?>
					<option value="<?php echo $temp; ?>" <?php $this->markSelected($selected, $temp); ?>><?php echo $temp; ?></option>
				<?php endforeach; ?>
			</select>
		</fieldset>
		<?php
	}

	/**
	 * Render selected attribute
	 *
	 * @param string $selected
	 * @param string $temp
	 *
	 * @return void
	 */
	private function markSelected(string $selected, string $temp): void
	{
		echo $selected === $temp ? 'selected="selected"' : '';
	}

	/**
	 * Get all template files
	 *
	 * @return array
	 */
	private function getHtmlTemplates(): array
	{
		$temps = [];

		foreach ( glob( get_template_directory() . "/template-forms/*.html" ) as $temp ) {
			$temp = explode('/', $temp);
			array_push($temps, end($temp));
		}

		return $temps;
	}

	/**
	 * Save meta value
	 *
	 * @param $post_id
	 *
	 * @return void
	 */
	public function saveTemplateOption($post_id): void
	{
		if (array_key_exists('html-templates', $_POST)) {
			update_post_meta(
				$post_id,
				'cf7_html_temp',
				$_POST['html-templates']
			);
		}
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks(): void
	{
		add_action( 'wpcf7_editor_panels', [ $this, 'customPanel' ] );
		add_action('save_post', [ $this, 'saveTemplateOption' ]);
	}
}
