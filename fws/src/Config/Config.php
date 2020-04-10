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
 * @author Boris Djemrovski <boris@forwardslashny.com>
 */
class Config extends Singleton
{

	/** @var self */
	protected static $instance;

	/** @var string */
	private $filename = '.fwsconfig.yml';

	/** @var Parser */
	private $parser;

	/** @var array */
	private $config = [];

	/** @var FlexContent[] */
	private $flexContent = [];

	/**
	 * Config constructor.
	 */
	protected function __construct()
	{
		$this->parser = new Parser;

		$configFilePath = get_template_directory() . DIRECTORY_SEPARATOR . $this->filename;

		if ( file_exists( $configFilePath ) ) {
			$this->config = $this->parser->parse( file_get_contents( $configFilePath ) );
		}
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
	 * local env url
	 *
	 * @return string
	 */
	public function virtualHost(): string
	{
		return (string) $this->config['global']['virtual-host'] ?? '';
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
}
