<?php
require_once(t3lib_extMgm::extPath('community_flexiblelayout').'interfaces/class.tx_communityflexiblelayout_commandinterface.php');

class tx_communityflexiblelayout_showDashboardCommand implements tx_communityflexiblelayout_CommandInterface {
	protected $commandName = 'showDashboard';
	
	public function __construct() {
	}

	public function execute() {
		return 'tx_communityflexiblelayout_showDashboardCommand';
	}
	
	public function getCommandName() {
		return $this->commandName;
	}
}
?>