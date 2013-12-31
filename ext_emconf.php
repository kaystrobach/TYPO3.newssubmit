<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "newssubmit".
 *
 * Auto generated 18-07-2013 03:51
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'News Submit',
	'description' => 'Submit News and store them',
	'category' => 'plugin',
	'author' => 'Kay Strobach',
	'author_email' => 'typo3@kay-strobach.de',
	'author_company' => '',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
			'extbase' => '*',
			'fluid' => '*',
			'typo3' => '4.5.0-6.2.99',
			'news' => '*'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);
