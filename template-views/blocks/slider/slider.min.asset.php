<?php
$version = fws()->config()->enqueueVersion();

return array(
	'handle'       => 'slider.min-script',
	'version'      => $version,
	'dependencies' => [
		'jquery',
		'wp-blocks',
		'wp-i18n'
	]
);
