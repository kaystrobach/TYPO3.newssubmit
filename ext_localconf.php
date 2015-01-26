<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'MiniFranske.' . $_EXTKEY,
	'Newssubmit',
	array(
		'News' => 'new, create, thankyou',
	),
	// non-cacheable actions
	array(
		'News' => 'new, create',
	)
);
