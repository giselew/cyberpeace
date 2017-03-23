<?php

if (!defined('TYPO3_MODE')) die('Access denied.');

if (version_compare(TYPO3_version, '6.3', '>')) {
	(class_exists('t3lib_extMgm', FALSE)) ? TRUE : class_alias('\TYPO3\CMS\Core\Utility\ExtensionManagementUtility', 't3lib_extMgm');
	(class_exists('t3lib_div', FALSE)) ? TRUE : class_alias('TYPO3\CMS\Core\Utility\GeneralUtility', 't3lib_div');
}

// Add static files for plugins
t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'CyberPeace');
 
if (version_compare(TYPO3_version, '6.2', '<')) {
	$TCA['tx_cyberpeace_iplist'] = array(
			'ctrl' => array (
					'title'     => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist',
					'label'     => 'ipaddr',
					'tstamp'    => 'tstamp',
					'crdate'    => 'crdate',
					'delete' => 'deleted',
					'default_sortby' => 'ORDER BY tstamp DESC',
					'searchFields' => 'comment,ipaddr,category',
					'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
					'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'Resources/Public/Icons/icon_tx_cyberpeace_iplist.gif',
			)
	);	
	$TCA['tx_cyberpeace_log'] = array(
			'ctrl' => array (
					'title'     => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log',
					'label'     => 'crdate',
					'tstamp'    => 'tstamp',
					'crdate'    => 'crdate',
					'delete' => 'deleted',
					'default_sortby' => 'ORDER BY crdate DESC',
					'searchFields' => 'crdate',						
					'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
					'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY).'Resources/Public/Icons/icon_tx_cyberpeace_log.gif',						
			)
	);
}

t3lib_extMgm::allowTableOnStandardPages('tx_cyberpeace_iplist');
t3lib_extMgm::addLLrefForTCAdescr('tx_cyberpeace_iplist', 'EXT:cyberpeace/Resources/Private/Language/locallang_iplist_csh.xml');

t3lib_extMgm::allowTableOnStandardPages('tx_cyberpeace_log');
t3lib_extMgm::addLLrefForTCAdescr('tx_cyberpeace_log', 'EXT:cyberpeace/Resources/Private/Language/locallang_log_csh.xml');
?>