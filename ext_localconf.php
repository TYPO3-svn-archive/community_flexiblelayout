<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TX_COMMUNITY['applications']['StartPage'] = array(
	'classReference' => 'EXT:community/controller/class.tx_community_controller_userprofileapplication.php:tx_community_controller_UserProfileApplication',
	'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile',
	'accessControl' => false,
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
			'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_startpage_mygroupswidget.php:tx_communityflexiblelayout_controller_startpage_MyGroupsWidget',
			'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile_myGroups',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index'
		),
		'onlineFriends' => array(
			'classReference' => 'EXT:community/controller/userprofile/class.tx_community_controller_userprofile_onlinefriendswidget.php:tx_community_controller_userprofile_OnlineFriendsWidget',
			'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile_onlineFriends',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index'
		)
	)
);

//$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_community']['getUserProfileActions'][] = 'EXT:community_flexiblelayout/hooks/class.tx_communityflexiblelayout_hook_community.php:tx_communityflexiblelayout_hook_Community';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_community']['tx_community_model_UserProfile']['getProfileUid'][] = 'EXT:community_flexiblelayout/hooks/class.tx_communityflexiblelayout_hook_community.php:tx_communityflexiblelayout_hook_Community';

?>