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
			'extbase' => '1.3.0-4.7.99',
			'fluid' => '1.3.0-4.7.99',
			'typo3' => '4.5.0-4.7.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:26:{s:12:"ext_icon.gif";s:4:"6759";s:17:"ext_localconf.php";s:4:"97c3";s:14:"ext_tables.php";s:4:"6458";s:14:"ext_tables.sql";s:4:"0abb";s:24:"ext_typoscript_setup.txt";s:4:"e6d6";s:21:"ExtensionBuilder.json";s:4:"5fe6";s:37:"Classes/Controller/NewsController.php";s:4:"147a";s:29:"Classes/Domain/Model/News.php";s:4:"bf05";s:44:"Classes/Domain/Repository/NewsRepository.php";s:4:"dc8d";s:44:"Configuration/ExtensionBuilder/settings.yaml";s:4:"e73b";s:26:"Configuration/TCA/News.php";s:4:"f743";s:38:"Configuration/TypoScript/constants.txt";s:4:"61b9";s:34:"Configuration/TypoScript/setup.txt";s:4:"0cdc";s:40:"Resources/Private/Language/locallang.xml";s:4:"79ae";s:70:"Resources/Private/Language/locallang_csh_tx_news_domain_model_news.xml";s:4:"69c0";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"59b9";s:38:"Resources/Private/Layouts/Default.html";s:4:"37fb";s:42:"Resources/Private/Partials/FormErrors.html";s:4:"f435";s:47:"Resources/Private/Partials/News/FormFields.html";s:4:"4144";s:41:"Resources/Private/Templates/News/New.html";s:4:"91f5";s:46:"Resources/Private/Templates/News/Thankyou.html";s:4:"7fd7";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:52:"Resources/Public/Icons/tx_news_domain_model_news.gif";s:4:"905a";s:44:"Tests/Unit/Controller/NewsControllerTest.php";s:4:"bbcb";s:36:"Tests/Unit/Domain/Model/NewsTest.php";s:4:"c8e0";s:14:"doc/manual.sxw";s:4:"8d2d";}',
);
