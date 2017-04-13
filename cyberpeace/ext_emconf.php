<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cyberpeace".
 *
 * Auto generated 13-04-2017 18:23
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'CyberPeace',
	'description' => 'CyberPeace allows to block IPs listed in the abuseipdb.com live-database using .htaccess. You can redirect or block IPs found in EXT:toctoc_comments IP block lists. You can block unwanted crawlers, spiders or bots and bad IPs found in the TYPO3-syslog as well.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.2',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearcacheonload' => 0,
	'author' => 'Gisele Wendl',
	'author_email' => 'gisele.wendl@toctoc.ch',
	'author_company' => 'TocToc Internetmanagement',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-8.9.99',
			'php' => '5.3.7-7.9.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'toctoc_comments' => '7.3.0-99.99.99',
		),
	),
	'_md5_values_when_last_written' => 'a:49:{s:9:"ChangeLog";s:4:"3dae";s:16:"ext_autoload.php";s:4:"a06e";s:12:"ext_icon.gif";s:4:"e2ec";s:12:"ext_icon.png";s:4:"9e23";s:17:"ext_localconf.php";s:4:"1bd2";s:14:"ext_tables.php";s:4:"2206";s:14:"ext_tables.sql";s:4:"90d5";s:7:"tca.php";s:4:"b71b";s:42:"Configuration/TCA/tx_cyberpeace_iplist.php";s:4:"77b5";s:39:"Configuration/TCA/tx_cyberpeace_log.php";s:4:"71dd";s:26:"Documentation/Includes.txt";s:4:"6d5f";s:23:"Documentation/Index.rst";s:4:"56f3";s:26:"Documentation/Settings.yml";s:4:"1ce4";s:38:"Documentation/Administration/Index.rst";s:4:"c3fb";s:63:"Documentation/Administration/AddingTyposcriptTemplate/Index.rst";s:4:"d6f9";s:37:"Documentation/Configuration/Index.rst";s:4:"3aaf";s:57:"Documentation/Configuration/BlockByAbuseipdbCom/Index.rst";s:4:"f8d3";s:52:"Documentation/Configuration/BlockByCrawler/Index.rst";s:4:"b3e1";s:63:"Documentation/Configuration/BlockByLocalIPBlockTables/Index.rst";s:4:"b929";s:51:"Documentation/Configuration/BlockBySysLog/Index.rst";s:4:"b0cc";s:55:"Documentation/Configuration/MainConfiguration/Index.rst";s:4:"ecbf";s:35:"Documentation/Images/cyberpeace.png";s:4:"4cb9";s:39:"Documentation/Images/cyberpeaceflow.png";s:4:"a7f8";s:41:"Documentation/Images/cyberpeaceteaser.png";s:4:"d610";s:34:"Documentation/Images/exceldays.png";s:4:"6055";s:42:"Documentation/Images/exceldistribution.png";s:4:"65fb";s:48:"Documentation/Images/hookcheckdatasubmission.png";s:4:"087c";s:36:"Documentation/Introduction/Index.rst";s:4:"3e2c";s:45:"Documentation/Introduction/Features/Index.rst";s:4:"7357";s:52:"Documentation/Introduction/HavingQuestions/Index.rst";s:4:"6105";s:49:"Documentation/Introduction/WhatDoesItDo/Index.rst";s:4:"8895";s:37:"Documentation/KnownProblems/Index.rst";s:4:"a59a";s:35:"Documentation/UsersManual/Index.rst";s:4:"cfed";s:53:"Documentation/UsersManual/ConfigurationInEm/Index.rst";s:4:"a6fa";s:50:"Documentation/UsersManual/ExcelReporting/Index.rst";s:4:"0eec";s:49:"Documentation/UsersManual/HowDoesItWork/Index.rst";s:4:"77c5";s:54:"Documentation/UsersManual/InstallingAndSetup/Index.rst";s:4:"56ec";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"8cac";s:51:"Resources/Private/Language/locallang_iplist_csh.xml";s:4:"3c5f";s:48:"Resources/Private/Language/locallang_log_csh.xml";s:4:"32be";s:52:"Resources/Public/Icons/icon_tx_cyberpeace_iplist.gif";s:4:"f6df";s:52:"Resources/Public/Icons/icon_tx_cyberpeace_iplist.png";s:4:"aecc";s:49:"Resources/Public/Icons/icon_tx_cyberpeace_log.gif";s:4:"2675";s:49:"Resources/Public/Icons/icon_tx_cyberpeace_log.png";s:4:"da2b";s:45:"Resources/Public/xls/last 100 log entries.ods";s:4:"c775";s:46:"Resources/Public/xls/last 100 log entries.xlsx";s:4:"1400";s:31:"pi1/class.tx_cyberpeace_pi1.php";s:4:"d351";s:20:"static/constants.txt";s:4:"57f6";s:16:"static/setup.txt";s:4:"adec";}',
);

?>