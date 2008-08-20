<?php
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandresolverinterface.php');

class tx_communityflexiblelayout_CommandResolver implements tx_communityflexiblelayout_CommandResolverInterface {
	private $path;
	private $defaultCommand;

	public function __construct($defaultCommand) {
		$this->path = t3lib_extMgm::extPath('community_flexiblelayout').'classes/commands';
		$this->defaultCommand = $defaultCommand;
		$registry = tx_donation_Registry::getInstance('');
		$this->conf = $registry->get('configuration');
	}

	public function getCommand() {
		if ($this->conf['fixCommand']) {
			$command = $this->loadCommand($this->conf['fixCommand']);
			return $command;
		}
		
		if (t3lib_div::_GP('profileId') == $GLOBALS['TSFE']->fe_user->user['uid']) {
			$command = $this->loadCommand('editDashboard');
			return $command;
		}
		
		if (strlen(t3lib_div::_GP('cmd'))) {
			$cmdName = t3lib_div::_GP('cmd');
			if (($cmdName == 'editDashboard' || $cmdName == 'saveDashboard') && !$GLOBALS['TSFE']->fe_user->user['uid']) {
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