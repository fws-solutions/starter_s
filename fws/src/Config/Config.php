<?php
declare( strict_types = 1 );

namespace FWS\Config;

use FWS\ACF\FlexContent;
use FWS\Singleton;
use Symfony\Component\Yaml\Parser;

/**
 * Class Config
 *
 * @package FWS\Config
 */
class Config extends Singleton
{

	/** @var self */
	protected static $instance;

	/** @var string */
	private $configFileName = '.fwsconfig.yml';

	/** @var string */
	private $enqFileName = '.fwsenqueue.yml';

	/** @var Parser */
	private $parser;

	/** @var array */
	private $config = [];

	/** @var array */
	private $enqVersion = [];

	/** @var string */
	private $cf7CustomTemplates = [];

	/** @var FlexContent[] */
	private $flexContent = [];

	/**
	 * Config constructor.
	 */
	protected function __construct()
	{
		$this->parser = new Parser;

		$filePath = get_template_directory() . DIRECTORY_SEPARATOR;

		// Load theme settings '.fwsconfig.yml' file
		$configFilePath = $filePath . $this->configFileName;
		$this->loadYmlFile($configFilePath, 'config');

		// Load theme enq version '.fwsenqueue.yml' file
		$enqFilePath = $filePath . $this->enqFileName;
		$this->loadYmlFile($enqFilePath, 'enqVersion');
	}

	/**
	 * theme full name
	 *
	 * @return string
	 */
	public function themeName(): string
	{
		return (string) $this->config['global']['theme-name'] ?? '';
	}

	/**
	 * the fatal error handler email addresses
	 *
	 * @return array
	 */
	public function recoveryModeEmails(): array
	{
		return (array) $this->config['global']['recovery-mode-emails'] ?? [];
	}

	/**
	 * Is allowed to add/update/remove plugins
	 *
	 * @return bool
	 */
	public function preventPluginUpdate(): bool
	{
		return (bool) $this->config['global']['prevent-plugin-update']['enable'] ?? false;
	}

	/**
	 * Only users with email address with this domain can add/update/remove plugins
	 *
	 * @return string
	 */
	public function pluginUpdatesAllowedDomain(): string
	{
		return (string) $this->config['global']['prevent-plugin-update']['domain'] ?? '';
	}

	/**
	 * ACF only possible to edit and manage on local environment
	 *
	 * @return bool
	 */
	public function acfOnlyLocalEditing(): bool
	{
		return (bool) $this->config['global']['acf-only-local-editing']['enable'] ?? false;
	}

	/**
	 * ACF editing allowed for these hosts only
	 *
	 * @return array
	 */
	public function acfOnlyLocalEditingAllowedHosts(): array
	{
		return (array) $this->config['global']['acf-only-local-editing']['allowed-hosts'] ?? [];
	}

	/**
	 * Is ACF Options Page enabled
	 *
	 * @return bool
	 */
	public function acfOptionsPage(): bool
	{
		return (bool) $this->config['acf-options-page']['enable'] ?? true;
	}

	/**
	 * ACF Options Subpages
	 *
	 * @return array
	 */
	public function acfOptionsSubpages(): array
	{
		return (array) $this->config['acf-options-page']['subpages'] ?? [];
	}

	/**
	 * Styleguide Options
	 *
	 * @return array
	 */
	public function styleguideConfig(): array
	{
		return (array) $this->config['styleguide'] ?? [];
	}

	/**
	 * Enqueue Version
	 *
	 * @return string
	 */
	public function enqueueVersion(): string
	{
		return (string) $this->enqVersion['enqueue-version'] ?? '';
	}

	/**
	 * Enqueue Version
	 *
	 * @return string
	 */
	public function cf7CustomTemplates(): string
	{
		return (string) $this->config['global']['cf7-custom-templates'] ?? '';
	}

	/**
	 * @return FlexContent[]
	 */
	public function acfFlexibleContent(): array
	{
		if ( empty( $this->flexContent ) && ! empty( $this->config['acf-flexible-content'] ) ) {
			$this->flexContent = array_map( [ $this, 'mapFlexContent' ], $this->config['acf-flexible-content'] );
		}

		return $this->flexContent;
	}

	/**
	 * @param array $args
	 *
	 * @return FlexContent
	 */
	private function mapFlexContent( array $args )
	{
		return new FlexContent( $args );
	}

	/**
	 * Load YML File
	 *
	 * @param string $enqFilePath
	 *
	 * @return void
	 */
	private function loadYmlFile( string $filePath, string $property ): void
	{
		if ( file_exists( $filePath ) ) {
			$this->$property = $this->parser->parse( file_get_contents( $filePath ) );
		}
	}
}
