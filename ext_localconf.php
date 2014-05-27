<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'T3ext.' . $_EXTKEY,
	'Newssubmit',
	array(
		'News' => 'new, create, thankyou, list, edit, update',
	),
	// non-cacheable actions
	array(
		'News' => 'new, create',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
	'T3ext\\Newssubmit\\Property\\TypeConverter\\UploadedFileReferenceConverter'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
	'T3ext\\Newssubmit\\Property\\TypeConverter\\ObjectStorageConverter'
);