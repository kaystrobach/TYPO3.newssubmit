<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "newssubmit".
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'News Submit',
	'description' => 'Submit News and store them',
	'category' => 'plugin',
	'author' => 'Kay Strobach, Frans Saris',
	'author_email' => 'kay.strobach@typo3.org, franssaris@gmail.com',
	'author_company' => '',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.0.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.4-7.6.99',
			'news' => '3.2'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);
