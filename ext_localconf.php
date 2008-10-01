<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TX_COMMUNITY['applications']['StartPage'] = array(
	'classReference' => 'EXT:community/controller/class.tx_community_controller_userprofileapplication.php:tx_community_controller_UserProfileApplication',
	'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile',
	'accessControl' => array(
		'read' => 'LLL:EXT:community/lang/locallang_privacy.xml:privacy_userProfile_read'
	),
	'widgets' => array(
		'friendsBirthdayList' => array(
			'classReference' => 'EXT:community/controller/userprofile/class.tx_community_controller_userprofile_friendsbirthdaylistwidget.php:tx_community_controller_userprofile_FriendsBirthdayListWidget',
			'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile_friendsBirthdayList',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index'
		),
		'myGroups' => array(
			'classReference' => 'EXT:community/controller/userprofile/class.tx_community_controller_userprofile_mygroupswidget.php:tx_community_controller_userprofile_MyGroupsWidget',
			'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile_myGroups',
			'accessControl' => array(
				'read' => 'LLL:EXT:community/lang/locallang_privacy.xml:privacy_userProfile_myGroupsWidget_read'
			),
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index'
		)
	)
);


?>