<?php
$version = fws()->config()->enqueueVersion();

return array(
	'handle'       => 'basic-block.min-script',
	'version'      => $version,
	'dependencies' => [
		'jquery',
		'wp-blocks',
		'wp-i18n'
	]
);
