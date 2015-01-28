<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "newssubmit".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'News Submit',
	'description' => 'Submit News and store them',
	'category' => 'plugin',
	'author' => 'Kay Strobach, Frans Saris',
	'author_email' => 'typo3@kay-strobach.de, franssaris@gmail.com',
	'author_company' => '',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
			'extbase' => '*',
			'fluid' => '*',
			'typo3' => '6.2.4-6.2.99',
			'news' => '*'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);
