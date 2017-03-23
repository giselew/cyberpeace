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

$tx_cyberpeace_iplist = array(
	'ctrl' => array (
		'title'     => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist',
		'label'     => 'ipaddr',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'delete' => 'deleted',
		'default_sortby' => 'ORDER BY tstamp DESC',
		'iconfile'  => $iconfilepath . 'Resources/Public/Icons/icon_tx_cyberpeace_iplist.png',
		'searchFields' => 'comment,ipaddr,category',				
	),
	'interface' => array (
		'showRecordFieldList' => 'ipaddr,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock,recurrence,accesscount,lasturl,
			comment,country,isocode,category,abuseiplasttstamp,blockingorigin'
	),
	'columns' => array (
		'ipaddr' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.ipaddr',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim,nospace,unique',
			)
		),
		'blockfe' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.blockfe',
			'config' => array (
					'type' => 'check',
					'default' => '0'
			)
		),
		'blockhtaccess' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.blockhtaccess',
			'config' => array (
					'type' => 'check',
					'default' => '0'
			)
		),
		'blockforever' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.blockforever',
			'config' => array (
					'type' => 'check',
					'default' => '0'
			)
		),
		'crdate' => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.crdate',
			'config' => array (
				'type' => 'input',
				'eval' => 'datetime',
				'readOnly' => 1,
				'default' => time(),
			),
		),
		'tstamp' => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.tstamp',
			'config' => array (
				'type' => 'input',
				'eval' => 'datetime',
				'readOnly' => 1,
				'default' => time(),
			),
		),
		'tstampunblock' => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.tstampunblock',
			'config' => array (
				'type' => 'input',
				'eval' => 'datetime',
				'default' => time(),
			),
				),
		'recurrence' => array (
			'excude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.recurrence',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'comment' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.comment',
			'config' => array (
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
			)
		),
		'blockingorigin' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.blockingorigin',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'accesscount' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.accesscount',
			'config' => array (
				'type' => 'input',
				'eval'     => 'int',
				'default' => 0,
				'readOnly' => 1,
			)
		),
		'lasturl' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.lasturl',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'isocode' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.isocode',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'country' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.country',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'category' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.category',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'abuseiplasttstamp' => array (
			'exclude' => 1,
			'label' => 'LLL:EXT:cyberpeace/Resources/Private/Language/locallang_db.xml:tx_cyberpeace_iplist.abuseiplasttstamp',
			'config' => array (
				'type' => 'input',
				'eval' => 'datetime',
				'default' => 0,
			)
		),
		
	),
	'types' => array (
		'0' => array ('showitem' => 'ipaddr,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock,accesscount,lasturl,comment,
				blockingorigin,country,isocode,category,abuseiplasttstamp')
	),
);
	
return $tx_cyberpeace_iplist;
?>