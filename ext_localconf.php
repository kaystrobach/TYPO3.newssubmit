<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Newssubmit',
	array(
		'News' => 'new, create, thankyou',
	),
	// non-cacheable actions
	array(
		'News' => 'new, create',
	)
);
