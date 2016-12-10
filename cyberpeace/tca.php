<?php

if (!defined ('TYPO3_MODE')) die('Access denied.');

if (version_compare(TYPO3_version, '6.2', '<')) {
	$scriptelem = 'script';
	$scriptcontent = 'wizard_edit.php';

	$TCA['tx_cyberpeace_iplist'] = array (
		'ctrl' => $TCA['tx_cyberpeace_iplist']['ctrl'],
		'interface' => array (
			'showRecordFieldList' => 'ipaddr,blockfe,blockhtaccess,blockforever,crdate,tstamp,tstampunblock,recurrence,accesscount,lasturl,comment,
				country,isocode,category,abuseiplasttstamp,blockingorigin'
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
	
	$TCA['tx_cyberpeace_log'] = array (
		'ctrl' => $TCA['tx_cyberpeace_log']['ctrl'],
		'interface' => array (
			'showRecordFieldList' => 'uid,pid,crdate,tstamp,totalips,allowedips,allowedcrawlers,blockedcrawlers,blockedbylocalbl,
			blockedbyimportedbl,blockedbyabuseipdb,blockedbysyslog,counthtaccess,unblockedwaitforrecheck,logcomment'
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
}

?>