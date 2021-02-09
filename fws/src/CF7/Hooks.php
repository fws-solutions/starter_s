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

	private $htmlTempFiles = '/dist/cf7/*.html';
	private $templateIDs = [
		'form_id' => 'cf7-form-temp',
		'email_admin_id' => 'cf7-email-admin-temp',
		'email_user_id' => 'cf7-email-user-temp'
	];

	/**
	 * Register CF7 custom panel
	 */
	public function customPanel($panels)
	{
		$panels['html-template'] = array(
			'title' => 'FWS CF7 Templates',
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
		$IDs = $this->templateIDs;

		$form_selected = get_post_meta($post->id(), $IDs['form_id'], true);
		$email_admin_selected = get_post_meta($post->id(), $IDs['email_admin_id'], true);
		$email_user_selected = get_post_meta($post->id(), $IDs['email_user_id'], true);
		?>

		<div id="cf7-html-wrap" class="cf7-html-wrap container-fluid">
			<div class="cf7-html-title">
				<img class="cf7-html-logo" src="<?php echo fws()->images()->assetsSrc('fws-logo-red.png'); ?>" alt="">
				<h2 class="cf7-html-title-texts"><?php echo esc_html( __( 'CF7 Templates', 'contact-form-7' ) ); ?></h2>
			</div>

			<div class="row">
				<div class="col-md-3">
					<?php $this->getSelectField($IDs['form_id'], 'Form:', $templates, $form_selected); ?>
					<?php $this->getSelectField($IDs['email_admin_id'], 'Email Admin:', $templates, $email_admin_selected); ?>
					<?php $this->getSelectField($IDs['email_user_id'], 'Email User:', $templates, $email_user_selected); ?>
				</div>

				<div class="col-md-3">
					<?php $this->getPreviewField($IDs['form_id'], 'Form HTML Preview:'); ?>
				</div>

				<div class="col-md-3">
					<?php $this->getPreviewField($IDs['email_admin_id'], 'Email Admin HTML Preview:'); ?>
				</div>

				<div class="col-md-3">
					<?php $this->getPreviewField($IDs['email_user_id'], 'Email User HTML Preview:'); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get Select Field
	 *
	 * @param string $id
	 * @param string $title
	 * @param array $templates
	 * @param string $selected
	 *
	 * @return void
	 */
	private function getSelectField($id, $title, $templates, $selected): void
	{
		?>
		<fieldset class="cf7-select-templates-wrap">
			<label class="cf7-select-templates-label" for="<?php echo $id; ?>"><b><?php echo $title; ?></b></label>

			<select name="<?php echo $id; ?>-selected" id="<?php echo $id; ?>">
				<?php foreach ($templates as $temp) : ?>
					<option value="<?php echo $temp; ?>" <?php $this->markSelected($selected, $temp); ?>><?php echo $temp; ?></option>
				<?php endforeach; ?>
			</select>
		</fieldset>
		<?php
	}

	/**
	 * Get Preview Field
	 *
	 * @param string $id
	 * @param string $title
	 *
	 * @return void
	 */
	private function getPreviewField($id, $title): void
	{
		?>
		<div class="cf7-html-temp-preview-wrap">
			<label for="<?php echo $id; ?>-preview"><?php echo $title; ?></label>
			<textarea id="<?php echo $id; ?>-preview" class="cf7-html-temp-preview" disabled></textarea>
		</div>
		<?php
	}

	/**
	 * Render selected attribute
	 *
	 * @param $selected
	 * @param string $temp
	 *
	 * @return void
	 */
	private function markSelected($selected, string $temp): void
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

		foreach ( glob( get_template_directory() . $this->htmlTempFiles ) as $temp ) {
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
		foreach	($this->templateIDs as $id) {
			if (array_key_exists($id . '-selected', $_POST)) {
				update_post_meta(
					$post_id,
					$id,
					$_POST[$id . '-selected']
				);
			}
		}
	}

	/**
	 * CF7 Add Body Class
	 */
	public function addClassToBody($classes) {
		$classes = explode(' ', $classes);

		$classes = array_merge($classes, [
			'fws-cf7-init'
		]);

		return implode(' ', array_unique($classes));
	}

	/**
	 * Drop your hooks here.
	 */
	protected function hooks(): void
	{
		add_action( 'wpcf7_editor_panels', [ $this, 'customPanel' ] );
		add_action('save_post', [ $this, 'saveTemplateOption' ]);
		add_filter('admin_body_class', [ $this, 'addClassToBody' ]);
	}
}
