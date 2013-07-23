<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Newssubmit',
	'News Submit'
);

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'News Submit');

$tmp_newssubmit_columns = array(

);

t3lib_extMgm::addTCAcolumns('tx_news_domain_model_news',$tmp_newssubmit_columns);

$TCA['tx_news_domain_model_news']['columns'][$TCA['tx_news_domain_model_news']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:newssubmit/Resources/Private/Language/locallang_db.xml:tx_news_domain_model_news.tx_extbase_type.Tx_Newssubmit_News','Tx_Newssubmit_News');

$TCA['tx_news_domain_model_news']['types']['Tx_Newssubmit_News']['showitem'] = $TCA['tx_news_domain_model_news']['types']['1']['showitem'];
$TCA['tx_news_domain_model_news']['types']['Tx_Newssubmit_News']['showitem'] .= ',--div--;LLL:EXT:newssubmit/Resources/Private/Language/locallang_db.xml:tx_newssubmit_domain_model_news,';
$TCA['tx_news_domain_model_news']['types']['Tx_Newssubmit_News']['showitem'] .= '';

?>