<?php

if (!defined ('TYPO3_MODE')) die('Access denied.');

if (version_compare(TYPO3_version, '6.3', '>')) {
	(class_exists('t3lib_extMgm', FALSE)) ? TRUE : class_alias('\TYPO3\CMS\Core\Utility\ExtensionManagementUtility', 't3lib_extMgm');
	(class_exists('t3lib_div', FALSE)) ? TRUE : class_alias('TYPO3\CMS\Core\Utility\GeneralUtility', 't3lib_div');
}

if (version_compare(TYPO3_version, '7.6', '<')) {
	$iconfilepath = t3lib_extMgm::extRelPath('cyberpeace');
} else {
	$iconfilepath = 'EXT:cyberpeace/';
}

$langfilepath = '/Resources/Private/Language/';
$tx_cyberpeace_log = array(
	'ctrl' => array (
		'title'     => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log',
		'label'     => 'crdate',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'delete' => 'deleted',
		'default_sortby' => 'ORDER BY crdate DESC',
		'iconfile'  => $iconfilepath . 'Resources/Public/Icons/icon_tx_cyberpeace_log.png',
		'searchFields' => 'crdate',				
	),
	'interface' => array (
		'showRecordFieldList' => 'uid,crdate,tstamp,totalips,allowedips,allowedcrawlers,unblockedwaitforrecheck,blockedcrawlers,blockedbylocalbl,
		blockedbyimportedbl,blockedbyabuseipdb,blockedbysyslog,counthtaccess,logcomment'
	),
	'columns' => array (
		'crdate' => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.crdate',
			'config' => array (
				'type' => 'input',
				'eval' => 'datetime',
				'readOnly' => 1,
				'default' => time(),
				'renderType' => 'inputDateTime',					
			),
		),
		'totalips'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.totalips',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'allowedips'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.allowedips',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'allowedcrawlers'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.allowedcrawlers',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
			'unblockedwaitforrecheck'  => array (
					'excude' => 1,
					'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.unblockedwaitforrecheck',
					'config' => array (
							'type' => 'input',
							'eval'     => 'int',
							'default' => 0,
							'readOnly' => 1,
					)
			),
		'blockedcrawlers'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.blockedcrawlers',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'blockedbylocalbl'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.blockedbylocalbl',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'blockedbyimportedbl'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.blockedbyimportedbl',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'blockedbyabuseipdb'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.blockedbyabuseipdb',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'blockedbysyslog'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.blockedbysyslog',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'counthtaccess'  => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.counthtaccess',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'logcomment' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_log.logcomment',
			'config' => array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
	),
	'types' => array (
		'0' => array ('showitem' => 'crdate,totalips,allowedips,allowedcrawlers,unblockedwaitforrecheck,blockedcrawlers,blockedbylocalbl,
		blockedbyimportedbl,blockedbyabuseipdb,blockedbysyslog,counthtaccess,logcomment')
	),
);
	
return $tx_cyberpeace_log;
?>