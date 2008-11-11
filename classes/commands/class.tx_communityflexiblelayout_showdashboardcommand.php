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

require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandinterface.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'classes/class.tx_communityflexiblelayout_layoutmanager.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'exceptions/class.tx_communityflexiblelayout_exception_noaccess.php');

require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_applicationmanager.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_accessmanager.php');
require_once(t3lib_extMgm::extPath('community').'controller/class.tx_community_controller_abstractcommunityapplication.php');

/**
 * Show Dashboard Command (model)
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_showDashboardCommand extends tx_community_controller_AbstractCommunityApplication implements tx_communityflexiblelayout_CommandInterface  {
	protected $commandName = 'showDashboard';
	/**
	 * @var tx_community_ApplicationManager
	 */
	protected $communityApplicationManager;
	/**
	 * @var tx_community_model_AbstractProfile
	 */
	protected $profile;
	/**
	 * @var tx_community_AccessManager
	 */
	protected $accessManager;
	protected $widgets = array();
	protected $cols = array();
	protected $request;
	protected $allowed = false;
	protected $completeProfile = true;
	
	public function __construct() {
		$this->communityApplicationManager = tx_community_ApplicationManager::getInstance();
		
		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->conf = $registry->getConfiguration();
		$this->request = t3lib_div::GParrayMerged('tx_community');
		$this->accessManager = tx_community_AccessManager::getInstance();
		
		$this->name = $this->conf['profileType'];
		
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		parent::__construct();
		parent::tslib_pibase();

		$this->accessManager->addResource($this);
	}

	public function execute() {
		$this->logger = tx_communitylogger_Logger::getInstance($this->commandName);
		$this->logger->info('loaded');
		$this->logger->debug('configuration: ' . print_r($this->conf, true));

		// check with the accessManager
		if ($this->accessManager->isAllowed($this)) {
			$this->allowed = true;
		}
		
		if ($this->conf['fixProfileType']) {
			$this->conf['profileType'] = $this->conf['fixProfileType'];
		}
		
		try {
			$this->profile		= tx_community_ProfileFactory::createProfile($this->conf['profileType']);
		} catch (Exception $exception) {
			$this->logger->fatal($exception->__toString());
			throw $exception;
		}	
		
		$profileId = $this->profile->getUid();
		
		// check if it is the users own profile
		if ($this->conf['profileType'] == 'userProfile' && $this->profile->isEditable()) {
			$this->allowed = true;
		}
		
		// check if it is the fixColumn and startPage
		if ($this->conf['profileType'] == 'StartPage' && strlen($this->conf['fixColumn'])) {
			$this->allowed = true;
		}
		
		// @TODO:  fix group permissions by AccessManger
		// @FIXME: this is a evil hack to make the group profile visible, we disable
		//         the AccessManager and ACLs now for the groupprofile!
		if ($this->conf['profileType'] == 'groupProfile' && ($this->profile->getGrouptype() < tx_community_model_Group::TYPE_SECRET)) {
			$this->allowed = true;
		}
		
		if ($this->allowed == false) {
			throw new tx_communityflexiblelayout_exception_NoAccess();
		}
		
		$widgets = $this->communityApplicationManager->getWidgetsByApplication($this->conf['profileType']);
		$disabledWidgets = t3lib_div::trimExplode(',', $this->conf['disabledWidgets.'][$this->conf['profileType']]);
		if (!is_array($disabledWidgets)) {
			$disabledWidgets = array();
		}
		
		$this->logger->debug('COUNT($widgets): ' . count($widgets));
		$application = $this->communityApplicationManager->getApplication($this->conf['profileType']);
		$config = $application->getCommunityTypoScriptConfiguration();
		foreach ($widgets as $widgetName => $widget) {
			$this->logger->debug($widgetName . ' loaded');
			$widget->initialize($this->data, $config);
			$widget->setCommunityApplication($application);
			
			if ($widget instanceof tx_community_CommunityApplicationWidget && (!in_array($widgetName, $disabledWidgets))) {
				if ($widget instanceof tx_community_acl_AclResource && ($this->conf['profileType'] != 'groupProfile')) {
					$this->accessManager->addResource($widget);
					if (!$this->accessManager->isAllowed($widget)) {
						$this->logger->debug('BLOCKED: ' . $widget->getName());
						$this->completeProfile = false;
						continue;
					}
				}
				$this->logger->debug('ADDED: ' . $widget->getName());
				$this->widgets[$widgetName] = $widget;
			}
		}
				
		/**
		 * @var tx_communityflexiblelayout_LayoutManager
		 */
		$layoutManager = new tx_communityflexiblelayout_LayoutManager();
		$config = $layoutManager->getConfiguration($this->conf['communityID'], $this->conf['profileType'], $profileId);

		$config = unserialize($config);
		if (is_array($config)) {
			foreach($config as $c) {
				$parts = t3lib_div::trimExplode(',', $c);
				$newConfig[$parts[2]] = array(
					'col'	=> $parts[0],
					'pos'	=> $parts[1],
					'id'	=> $parts[2]
				);
			}
			$config = $newConfig;
		} else {
			$config = array();
		}
		foreach ($this->widgets as $widgetName => $widget) {
			if (is_array($config[$widgetName])) {
				$this->cols[$config[$widgetName]['col']]['pos'.$config[$widgetName]['pos']] = $widget;
			} else {
				$this->cols[$widget->getLayoutContainer()][] = $widget;
			}
		}
		foreach ($this->cols as $k => $v) {
			ksort($this->cols[$k]);
		}
	}
	
	public function isAllowed() {
		return $this->allowed;
	}
	
	public function isProfileComplete() {
		return $this->completeProfile;
	}
	
	public function getCommandName() {
		return $this->commandName;
	}
	
	public function getWidgetsByCol($col) {
		return $this->cols[$col];
	}
		
	public function getAllWidgets() {
		return $this->cols;
	}
}
?>