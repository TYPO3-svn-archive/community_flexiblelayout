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

require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandresolverinterface.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_profilefactory.php');

class tx_communityflexiblelayout_CommandResolver implements tx_communityflexiblelayout_CommandResolverInterface {
	protected $path;
	protected $defaultCommand;
	protected $request;
	/**
	 * @var tx_community_model_AbstractProfile
	 */
	protected $profile;
	
	public function __construct($defaultCommand) {
		$this->path				= t3lib_extMgm::extPath('community_flexiblelayout').'classes/commands';
		$this->defaultCommand	= $defaultCommand;
		$this->request			= t3lib_div::_GP('tx_communityflexiblelayout');
		
		$registry				= tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->conf				= $registry->getConfiguration();

		try {
			$this->profile		= tx_community_ProfileFactory::createProfile($this->conf['profileType']);
		} catch (Exception $exception) {
			throw $exception;
		}	
	}

	public function getCommand() {
		// first prio: saveDashboard command in request and profile is editable
		if ($this->profile->isEditable() && ($this->request['cmd'] == 'saveDashboard')) {
			$command = $this->loadCommand('saveDashboard');
			return $command;
		}
		// second prio: fixCommand
		if ($this->conf['fixCommand']) {
			$cmdName = $this->conf['fixCommand'];
			if ($cmdName == 'editDashboard' && !$this->profile->isEditable()) {
				$cmdName = 'showDashboard';
			}
			$command = $this->loadCommand($cmdName);
			return $command;
		}

		// third prio: isEditable ? editDasboard : showDashboard;
		$cmdName = $this->profile->isEditable() ? 'editDashboard' : 'showDashboard';

		$command = $this->loadCommand($cmdName);
		return $command;
	}

	protected function loadCommand($cmdName) {
		$class = "tx_communityflexiblelayout_{$cmdName}Command";
		$file  = "{$this->path}/class.".strtolower($class).".php";
		if (!file_exists($file)) {
			return false;
		}
		include_once $file;
		if (!class_exists($class)) {
			return false;
		}
		$command = new $class();
		return $command;
	}
}
?>