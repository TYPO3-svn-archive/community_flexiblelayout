<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addStaticFile($_EXTKEY,'static/community_flexiblelayout/', 'Community Flexible Layout');

	// extending fe_users
$feUsersTempColumns = array (
	'tx_communityflexiblelayout_dashboardconfig' => array (
		'exclude' => 1,
		'label' => 'LLL:EXT:community/lang/locallang_db.xml:fe_users.tx_communityflexiblelayout_dashboardconfig',
		'config' => array (
			'type' => 'text',
			'cols' => '30',
			'rows' => '5',
		)
	),
);

	// adding additional columns to fe_users
t3lib_div::loadTCA('fe_users');
t3lib_extMgm::addTCAcolumns('fe_users', $feUsersTempColumns, 1);
t3lib_extMgm::addToAllTCAtypes('fe_users','tx_communityflexiblelayout_dashboardconfig;;;;1-1-1');

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_Dashboard'] = 'layout,select_key';
t3lib_extMgm::addPlugin(array('LLL:EXT:community_flexiblelayout/lang/locallang_db.xml:tt_content.list_type_Dashboard', $_EXTKEY.'_Dashboard'), 'list_type');

?>
