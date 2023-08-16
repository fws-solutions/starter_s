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
    public function superadminEmails(): array
    {
        return (array) ($this->config['global']['superadmin-emails'] ?? []);
    }


    /**
     * Get list of domains that will be recognized as local environment.
     *
     * @return array
     */
    public function allowedLocalhosts(): array
    {
        return (array) ($this->config['global']['allowed-localhosts'] ?? []);
    }


    /**
     * Plugins and themes can be added/updated/deleted on local environment, or by superadmins on any host.
     *
     * @return bool
     */
    public function pluginsOnlyLocalEditing(): bool
    {
        return boolval($this->config['global']['plugins-only-local-editing'] ?? true);
    }

	/**
	 * ACF only possible to edit and manage on local environment
	 *
	 * @return bool
	 */
	public function acfOnlyLocalEditing(): bool
	{
        return boolval($this->config['global']['acf-only-local-editing'] ?? false);
	}

	/**
	 * Prevent clickjacking security issue.
	 *
	 * @return bool
	 */
	public function clickjackingProtection(): bool
	{
		return boolval($this->config['global']['clickjacking-protection'] ?? true);
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
