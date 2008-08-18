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

/**
 * User Profile Application Controller
 *
 * @author	Frank Nägler <typo3@naegler.net>
 * @package TYPO3
 * @subpackage community_flexiblelayout
 */
class tx_communityflexiblelayout_controller_Dashboard {
	public $prefixId      = 'tx_communityflexiblelayout_controller_Dashboard';		// Same as class name
	public $scriptRelPath = 'controller/class.tx_communityflexiblelayout_controller_dashboard.php';	// Path to this script relative to the extension dir.
	public $extKey        = 'community_flexiblelayout';	// The extension key.

	public $cObj;

	/**
	 * constructor for class tx_communityflexiblelayout_controller_Dashboard
	 */
	public function __construct() {

	}

	public function execute($content, array $configuration) {
		$cmdResolver = new tx_communityflexiblelayout_CommandResolver($configuration['defaultCommand']);
		$command = $cmdResolver->getCommand();
		$cmdName = $command->getCommandName();
		switch ($cmdName) {
			case 'showDashboard':
			default:
				
			break;
		}
		return $command->execute();
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MOlDE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/community_flexiblelayout/controller/class.tx_communityflexiblelayout_controller_dashboard.php']);
}

?>