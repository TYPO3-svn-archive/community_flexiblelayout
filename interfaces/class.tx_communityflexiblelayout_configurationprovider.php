<?php
interface tx_communityflexiblelayout_ConfigurationProvider {
	public function getConfiguration($configuration, $profileId, tx_communityflexiblelayout_controller_Dashboard $dashboardController);
}
?>