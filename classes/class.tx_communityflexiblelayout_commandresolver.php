<?php
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandresolverinterface.php');
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'exceptions/class.tx_communityflexiblelayout_noprofileidexception.php');
require_once(t3lib_extMgm::extPath('community').'classes/class.tx_community_registry.php');
require_once(t3lib_extMgm::extPath('community').'model/class.tx_community_model_usergateway.php');

class tx_communityflexiblelayout_CommandResolver implements tx_communityflexiblelayout_CommandResolverInterface {
	protected $path;
	protected $defaultCommand;
	protected $request;
	/**
	 * @var tx_community_model_UserGateway
	 */
	protected $userGateway;
	
	public function __construct($defaultCommand) {
		$this->path = t3lib_extMgm::extPath('community_flexiblelayout').'classes/commands';
		$this->defaultCommand = $defaultCommand;
		$registry = tx_community_Registry::getInstance('tx_communityflexiblelayout');
		$this->conf = $registry->getConfiguration();
		$this->communityRequest = t3lib_div::_GP('tx_community');
		$this->layoutRequest = t3lib_div::_GP('tx_communityflexiblelayout');
		$this->userGateway = new tx_community_model_UserGateway();
	}

	public function getCommand() {
		if ($this->conf['fixCommand']) {
			$this->layoutRequest['cmd'] = $this->conf['fixCommand'];
		}
		
		/**
		 * @var tx_community_model_User
		 */
		$loggedinUser = $this->userGateway->findCurrentlyLoggedInUser();
		
		// if loggedin user profil, force edit mode
		if (isset($this->communityRequest['user']) && ($this->communityRequest['user'] == $loggedinUser->getUid())) {
			$command = $this->loadCommand('editDashboard');
			return $command;
		}

		// if not loggedin user profil, force show mode
		if (isset($this->communityRequest['user']) && ($this->communityRequest['user'] != $loggedinUser->getUid())) {
			$command = $this->loadCommand('showDashboard');
			return $command;
		}
		
		if (strlen($this->layoutRequest['cmd'])) {
			$cmdName = $this->layoutRequest['cmd'];
			if (($cmdName == 'editDashboard' || $cmdName == 'saveDashboard') && !$GLOBALS['TSFE']->loginUser) {
				$cmdName = 'showDashboard';
			}
			// if we are in show mode and have no profile id given, throw exception
			if ($cmdName == 'showDashboard' && (!isset($this->communityRequest['user']))) {
				throw new tx_communityflexiblelayout_NoProfileIdException();
			}
			$command = $this->loadCommand($cmdName);
			if ($command instanceof tx_communityflexiblelayout_CommandInterface) {
				return $command;
			}
		}
		// if we the defaultCommand = showDashboard and have no profile id given, throw exception
		if ($this->defaultCommand == 'showDashboard' && (!isset($this->communityRequest['user']))) {
			throw new tx_communityflexiblelayout_NoProfileIdException();
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