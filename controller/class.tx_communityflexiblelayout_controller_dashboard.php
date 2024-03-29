<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 Frank Nägler <typo3@naegler.net>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once(t3lib_extMgm::extPath('community_flexiblelayout').'classes/class.tx_communityflexiblelayout_commandresolver.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_showdashboardview.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_editdashboardview.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_savedashboardview.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'view/class.tx_communityflexiblelayout_errorview.php');

require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_applicationmanager.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_localizationmanager.php');
require_once(t3lib_extMgm::extPath('community').'classes/exception/class.tx_community_exception_noprofileid.php');
require_once(t3lib_extMgm::extPath('community').'classes/exception/class.tx_community_exception_unknownprofile.php');
require_once(t3lib_extMgm::extPath('community').'classes/exception/class.tx_community_exception_unknownprofiletype.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_accessmanager.php');
require_once(t3lib_extMgm::extPath('community').'model/class.tx_community_model_usergateway.php');
require_once(t3lib_extMgm::extPath('community').'controller/class.tx_community_controller_abstractcommunityapplication.php');
require_once(t3lib_extMgm::extPath('community').'controller/class.tx_community_controller_abstractcommunityapplicationwidget.php');
require_once(t3lib_extMgm::extPath('community').'view/class.tx_community_view_abstractview.php');


require_once(t3lib_extMgm::extPath('community_logger').'classes/class.tx_communitylogger_logger.php');

/**
 * Dashboard Controller
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_controller_Dashboard {
	public $prefixId      = 'tx_communityflexiblelayout_controller_Dashboard';		// Same as class name
	public $scriptRelPath = 'controller/class.tx_communityflexiblelayout_controller_dashboard.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'community_flexiblelayout';	// The extension key.

	/**
	 * @var tx_communitylogger_Logger
	 */
	protected $logger;

	/**
	 * constructor for class tx_communityflexiblelayout_controller_Dashboard
	 */
	public function __construct() {
		$this->logger = tx_communitylogger_Logger::getInstance($this->extKey);
		$this->logger->info('loaded');
		if (!($GLOBALS['TX_COMMUNITY']['applicationManager'] instanceof tx_community_ApplicationManager)) {
			$applicationManagerClass = t3lib_div::makeInstanceClassName('tx_community_ApplicationManager');
			
			$applicationManager      = call_user_func(array($applicationManagerClass, 'getInstance'));
			$GLOBALS['TX_COMMUNITY']['applicationManager'] = $applicationManager;
		}
	}

	public function execute($content, array $configuration) {
		$this->request = t3lib_div::GParrayMerged('tx_community');
		$this->logger->debug("\$configuration['profileType'] = " . $configuration['profileType']);
		if (($configuration['profileType'] == 'userProfile' || $configuration['profileType'] == 'StartPage') && (!isset($this->request['user']))) {
			$userGateway = t3lib_div::makeInstance('tx_community_model_UserGateway');
			$user = $userGateway->findCurrentlyLoggedInUser();
			if (!is_null($user)) {
				$GLOBALS['_GET']['tx_community']['user'] = $user->getUid();
			}
		}
		$this->request = t3lib_div::GParrayMerged('tx_community');

		$profileId = (isset($this->request['user'])) ? (int) $this->request['user'] : (int) $this->request['group'];
		
			// hook implementation for changeing the configuration
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_communityflexiblelayout']['getConfiguration'])) {
			foreach($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['tx_communityflexiblelayout']['getConfiguration'] as $classReference) {
				$hookObject = & t3lib_div::getUserObj($classReference);
				if ($hookObject instanceof tx_communityflexiblelayout_ConfigurationProvider) {
					$configuration = $hookObject->getConfiguration($configuration, $profileId, $this);
				}

			}
		}

		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$registry->setConfiguration($configuration);

		try {
			$cmdResolver = new tx_communityflexiblelayout_CommandResolver($configuration['defaultCommand']);
			$model = $cmdResolver->getCommand();
			$model->execute();
			$cmdName = $model->getCommandName();
			$this->logger->debug("\$cmdName: $cmdName");
			$this->logger->debug("\$request1: ".print_r($_GET,1));
			$this->logger->debug("\$request2: ".print_r($_POST,1));
			$viewName = "tx_communityflexiblelayout_".ucfirst($cmdName)."View";
		} catch (tx_community_exception_NoProfileId $exception) {
			$this->logger->fatal($exception->__toString());
			$viewName = 'tx_communityflexiblelayout_ErrorView';
			$model = $exception;
		} catch (tx_community_exception_UnknownProfile $exception) {
			$this->logger->fatal($exception->__toString());
			$viewName = 'tx_communityflexiblelayout_ErrorView';
			$model = $exception;
		} catch (tx_community_exception_UnknownProfileType $exception) {
			$this->logger->fatal($exception->__toString());
			$viewName = 'tx_communityflexiblelayout_ErrorView';
			$model = $exception;
		} catch (tx_communityflexiblelayout_exception_NoAccess $exception) {
			$this->logger->fatal($exception->__toString());
			$viewName = 'tx_communityflexiblelayout_ErrorView';
			$model = $exception;
		} catch (Exception $exception) {
			$this->logger->fatal($exception->__toString());
			die ('unhandled exception: ' . $exception);
		}
		$view = new $viewName($model);
		return $view->render();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MOlDE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php']);
}

?>
