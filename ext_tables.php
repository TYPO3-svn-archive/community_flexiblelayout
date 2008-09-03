<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY,'static/community_flexiblelayout/', 'Community Flexible Layout');

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_Dashboard'] = 'layout,select_key';
t3lib_extMgm::addPlugin(array('LLL:EXT:community_flexiblelayout/lang/locallang_db.xml:tt_content.list_type_Dashboard', $_EXTKEY.'_Dashboard'), 'list_type');

?>
