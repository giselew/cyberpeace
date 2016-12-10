<?php
if (version_compare(TYPO3_version, '6.3', '>')) {
	(class_exists('t3lib_extMgm', FALSE)) ? TRUE : class_alias('\TYPO3\CMS\Core\Utility\ExtensionManagementUtility', 't3lib_extMgm');
}

$extensionClassesPath = t3lib_extMgm::extPath('cyberpeace') . '';
$default = array();
return $default;
?>