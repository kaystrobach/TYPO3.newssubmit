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
		'News' => 'create',
	)
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'T3ext.' . $_EXTKEY,
	'NewsFeEdit',
	array(
		'News' => 'list, new, create, thankyou, edit, update',
	),
	// non-cacheable actions
	array(
		'News' => 'create, edit, update',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
	'T3ext\\Newssubmit\\Property\\TypeConverter\\UploadedFileReferenceConverter'
);
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
	'T3ext\\Newssubmit\\Property\\TypeConverter\\ObjectStorageConverter'
);