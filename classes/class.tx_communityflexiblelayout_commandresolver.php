<?php
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandresolverinterface.php');

class tx_communityflexiblelayout_CommandResolver implements tx_communityflexiblelayout_CommandResolverInterface {
	protected $path;
	protected $defaultCommand;
	protected $request;
	
	public function __construct($defaultCommand) {
		$this->path = t3lib_extMgm::extPath('community_flexiblelayout').'classes/commands';
		$this->defaultCommand = $defaultCommand;
		$registry = tx_donation_Registry::getInstance('');
		$this->conf = $registry->get('configuration');
		$this->request = t3lib_div::_GP('tx_communityflexiblelayout');
	}

	public function getCommand() {
		if ($this->conf['fixCommand']) {
			$this->request['cmd'] = $this->conf['fixCommand'];
		}
		
		/**
		 * @TODO: Umbau: Arbeite mit user object von communityManager, nicht mit $GLOBALS['TSFE']->fe_user->user
		 */
		if (isset($this->request['profileId']) && ($this->request['profileId'] == $GLOBALS['TSFE']->fe_user->user['uid'])) {
			$command = $this->loadCommand('editDashboard');
			return $command;
		}
		
		/**
		 * @TODO: Umbau: Arbeite mit user object von communityManager, nicht mit $GLOBALS['TSFE']->fe_user->user
		 */
		if (isset($this->request['profileId']) && ($this->request['profileId'] != $GLOBALS['TSFE']->fe_user->user['uid'])) {
			$command = $this->loadCommand('showDashboard');
			return $command;
		}
		
		/**
		 * @TODO: Umbau: Arbeite mit user object von communityManager, nicht mit $GLOBALS['TSFE']->fe_user->user
		 */
		if (strlen($this->request['cmd'])) {
			$cmdName = $this->request['cmd'];
			if (($cmdName == 'editDashboard' || $cmdName == 'saveDashboard') && !$GLOBALS['TSFE']->loginUser) {
				$cmdName = 'showDashboard';
			}
			$command = $this->loadCommand($cmdName);
			if ($command instanceof tx_communityflexiblelayout_CommandInterface) {
				return $command;
			}
		}
		$command = $this->loadCommand($this->defaultCommand);
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