<?php
declare( strict_types = 1 );

namespace FWS\ACF;

/**
 * Class FlexContent
 *
 * @package FWS\ACF
 */
class FlexContent
{

	/** @var bool */
	private $autoload;

	/** @var string */
	private $fieldName;

	/** @var array */
	private $location;

	/** @var array */
	private $hideOnScreen;

	/** @var array */
	private $layouts;

	/**
	 * FlexContent constructor.
	 *
	 * @param array $args
	 */
	public function __construct( array $args )
	{
		$this->autoload = (bool) $args['autoload'] ?? false;
		$this->fieldName = (string) $args['field-name'] ?? '';
		$this->location = [
			'param' => (string) $args['location']['param'] ?? 'page_template',
			'value' => (string) $args['location']['value'] ?? 'default'
		];
		$this->hideOnScreen = (array) $args['hide-on-screen'] ?? [ 'the_content' ];
		$this->layouts = $this->mapLayouts( (array) $args['layouts'] );
	}

	/**
	 * Map the layouts array to a format we need
	 *
	 * @param array $layouts
	 *
	 * @return array
	 */
	private function mapLayouts( array $layouts ): array
	{
		$mappedLayouts = [];

		foreach ( $layouts as $layout ) {
			if ( !acf_get_field_group( $layout['group_id'] ) ) {
				continue;
			}

			$label = $layout['title'] ?? '';
			$name = str_replace( '-', '_', sanitize_title( $label ) );
			$groupID = $layout['group_id'] ?? '';

			// Skip if missing any of the required fields
			if ( ! $label || ! $name || ! $groupID ) {
				continue;
			}

			$mappedLayouts[ $layout['group_id'] ] = [
				'label' => $label,
				'name' => $name,
				'clone_group_key' => $layout['group_id'],
			];
		}

		return $mappedLayouts;
	}

	/**
	 * Register new flexible content field group.
	 *
	 * @param string $slug
	 */
	public function register( string $slug ): void
	{
		$key = 'fc_' . str_replace( '-', '_', sanitize_title( $slug ) );

		$args = [
			'key' => $key,
			'title' => $this->getFieldName(),
			'fields' => [
				[
					'key' => "field_$key",
					'label' => '',
					'name' => str_replace( '-', '_', sanitize_title( $this->getFieldName() ) ),
					'type' => 'flexible_content',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => [
						'width' => '',
						'class' => '',
						'id' => '',
					],
					'layouts' => [],
					'button_label' => 'Add Section',
					'min' => '',
					'max' => '',
				],
			],
			'location' => [
				[
					[
						'param' => $this->getLocationParam(),
						'operator' => '==',
						'value' => $this->getLocationValue(),
					],
				],
			],
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => $this->getHideOnScreen(),
			'active' => true,
			'description' => '',
			'modified' => 1567782198,
		];

		foreach ( $this->getLayouts() as $layout ) {
			$label = $layout['label'];
			$name = $layout['name'];
			$key = $layout['clone_group_key'];

			$args['fields'][0]['layouts']["layout_$key"] = [
				'key' => "layout_$key",
				'name' => $name,
				'label' => $label,
				'display' => 'block',
				'sub_fields' => [
					[
						'key' => "layout_field_$key",
						'label' => '',
						'name' => $name,
						'type' => 'clone',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => [
							'width' => '',
							'class' => '',
							'id' => '',
						],
						'acfe_permissions' => '',
						'clone' => [
							0 => $layout['clone_group_key'],
						],
						'display' => 'seamless',
						'layout' => 'block',
						'prefix_label' => 0,
						'prefix_name' => 0,
					],
				],
				'min' => '',
				'max' => '',
			];
		}

		acf_add_local_field_group( $args );
	}

	/**
	 * @return string
	 */
	public function getFieldName(): string
	{
		return $this->fieldName;
	}

	/**
	 * @return string
	 */
	public function getLocationParam(): string
	{
		return $this->location['param'] ?? '';
	}

	public function getLocationValue(): string
	{
		return $this->location['value'] ?? '';
	}

	/**
	 * @return array
	 */
	public function getHideOnScreen(): array
	{
		return $this->hideOnScreen;
	}

	/**
	 * @return array
	 */
	public function getLayouts(): array
	{
		return $this->layouts;
	}

	/**
	 * @return bool
	 */
	public function isAutoload(): bool
	{
		return $this->autoload;
	}
}
