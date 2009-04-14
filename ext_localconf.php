<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TX_COMMUNITY['applications']['StartPage'] = array(
	//'classReference' => 'EXT:community/controller/class.tx_community_controller_userprofileapplication.php:tx_community_controller_UserProfileApplication',
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_startpageapplication.php:tx_communityflexiblelayout_controller_StartPageApplication',
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
			'classReference' => 'EXT:community/controller/userprofile/class.tx_community_controller_userprofile_mygroupswidget.php:tx_community_controller_userprofile_MyGroupsWidget',
			'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile_myGroups',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index'
		),
		'myFriends' => array(
			'classReference' => 'EXT:community/controller/userprofile/class.tx_community_controller_userprofile_myfriendswidget.php:tx_community_controller_userprofile_MyFriendsWidget',
			'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile_myFriends',
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
		),
		'lastVisitors' => array(
			'classReference' => 'EXT:community/controller/userprofile/class.tx_community_controller_userprofile_lastvisitorswidget.php:tx_community_controller_userprofile_LastVisitorsWidget',
			'label' => 'LLL:EXT:community/lang/locallang_applications.xml:userProfile_lastVisitors',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index'
		),
		'statusWidget' => array(
			'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_statuswidget.php:tx_communityflexiblelayout_controller_StatusWidget',
			'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_statusWidget',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index',
		),
		'contentWidget' => array(
			'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_contentwidget.php:tx_communityflexiblelayout_controller_ContentWidget',
			'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_contentWidget',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index',
		),
		'coaspecialWidget' => array(
			'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_coaspecialwidget.php:tx_communityflexiblelayout_controller_CoaspecialWidget',
			'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_coaspecialWidget',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index',
		),
		'coaspecialWidget2' => array(
			'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_coaspecialwidget2.php:tx_communityflexiblelayout_controller_CoaspecialWidget2',
			'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_coaspecialWidget',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index',
		),
		'coaspecialWidget3' => array(
			'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_coaspecialwidget3.php:tx_communityflexiblelayout_controller_CoaspecialWidget3',
			'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_coaspecialWidget',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index',
		)		
	)
);

$TX_COMMUNITY['applications']['userProfile']['accessControl'] = false;

$TX_COMMUNITY['applications']['userProfile']['widgets']['personalInformation2'] = array(
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_personalinformationwidget.php:tx_communityflexiblelayout_controller_PersonalInformationWidget',
	'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:userProfile_personalInformation',
	'actions' => array(
		'index'
	),
	'defaultAction' => 'index',
	'accessControl' => array(
		'read' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:privacy_userProfile_personalInformationWidget_read'
	)
);

$TX_COMMUNITY['applications']['userProfile']['widgets']['contentWidget'] = array(
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_contentwidget.php:tx_communityflexiblelayout_controller_ContentWidget',
	'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_contentWidget',
	'accessControl' => false,
	'actions' => array(
		'index'
	),
	'defaultAction' => 'index',
);

$TX_COMMUNITY['applications']['userProfile']['widgets']['coaspecialWidget'] = array(
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_coaspecialwidget.php:tx_communityflexiblelayout_controller_CoaspecialWidget',
	'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_coaspecialWidget',
	'accessControl' => false,
	'actions' => array(
		'index'
	),
	'defaultAction' => 'index',
);

$TX_COMMUNITY['applications']['userProfile']['widgets']['coaspecialWidget2'] = array(
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_coaspecialwidget2.php:tx_communityflexiblelayout_controller_CoaspecialWidget2',
	'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_coaspecialWidget',
	'accessControl' => false,
	'actions' => array(
		'index'
	),
	'defaultAction' => 'index',
);

$TX_COMMUNITY['applications']['userProfile']['widgets']['coaspecialWidget3'] = array(
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_coaspecialwidget3.php:tx_communityflexiblelayout_controller_CoaspecialWidget3',
	'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_coaspecialWidget',
	'accessControl' => false,
	'actions' => array(
		'index'
	),
	'defaultAction' => 'index',
);

$TX_COMMUNITY['applications']['userProfile']['widgets']['statusWidget'] = array(
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_statuswidget.php:tx_communityflexiblelayout_controller_StatusWidget',
	'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_statusWidget',
	'accessControl' => false,
	'actions' => array(
		'index'
	),
	'defaultAction' => 'index',
);

$TX_COMMUNITY['applications']['userProfile']['widgets']['lastVisitors']['accessControl'] = array(
	'read' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:privacy_userProfile_lastVisitors_read'
);


$TX_COMMUNITY['applications']['connectionManager'] = array(
	'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_connectionmanagerapplication.php:tx_communityflexiblelayout_controller_ConnectionManagerApplication',
	'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:connectionManager',
	'accessControl' => false,
	'actions' => array(
		'index'
	),
	'defaultAction' => 'index',
	'widgets' => array(
		'statusWidget' => array(
			'classReference' => 'EXT:community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_statuswidget.php:tx_communityflexiblelayout_controller_StatusWidget',
			'label' => 'LLL:EXT:community_flexiblelayout/lang/locallang_application.xml:startPage_statusWidget',
			'accessControl' => false,
			'actions' => array(
				'index'
			),
			'defaultAction' => 'index',
		),
	)
);


//$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_community']['getUserProfileActions'][] = 'EXT:community_flexiblelayout/hooks/class.tx_communityflexiblelayout_hook_community.php:tx_communityflexiblelayout_hook_Community';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_community']['tx_community_model_UserProfile']['getProfileUid'][] = 'EXT:community_flexiblelayout/hooks/class.tx_communityflexiblelayout_hook_community.php:tx_communityflexiblelayout_hook_Community';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_ajaxupload']['observer'][] = 'EXT:community_flexiblelayout/hooks/class.tx_communityflexiblelayout_hook_observer.php:tx_communityflexiblelayout_hook_Observer';
?>