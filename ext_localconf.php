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
	)
);


?>