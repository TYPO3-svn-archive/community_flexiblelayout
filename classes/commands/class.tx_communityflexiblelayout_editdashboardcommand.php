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
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_applicationmanager.php');

/**
 * Show Dashboard Command (model)
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_editDashboardCommand implements tx_communityflexiblelayout_CommandInterface {
	protected $commandName = 'editDashboard';
	/**
	 * @var tx_community_ApplicationManager
	 */
	protected $communityApplicationManager;
	protected $widgets = array();
	protected $cols = array();
	
	public function __construct() {
		$this->communityApplicationManager = tx_community_ApplicationManager::getInstance();
	}

	public function execute() {
		$widgets = $this->communityApplicationManager->getAllWidgets();
		foreach ($widgets as $widget) {
			if ($widget instanceof tx_community_CommunityApplicationWidget) {
				$this->widgets[] = $widget;
			}
		}
		
		for ($i=0; $i < count($this->widgets); $i++) {
			$this->cols[$widget->getLayoutContainer()][] = $widget;
		}
		
		for ($i=0; $i < count($cols); $i++) {
			usort($this->cols[$i], array($this, "sortByPosition"));
		}
	}
	
	protected function sortByPosition($a, $b) {
        $aval = $a->getPosition();
        $bval = $b->getPosition();
        if ($aval == $bval) {
            return 0;
        }
        return ($aval > $bval) ? +1 : -1;
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