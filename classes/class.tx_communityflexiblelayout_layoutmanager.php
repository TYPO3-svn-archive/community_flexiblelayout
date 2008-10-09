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

class tx_communityflexiblelayout_LayoutManager {
	protected $table = 'tx_communityflexiblelayout_dashboardconfig';
	
	public function __construct() {
	}

	public function getConfiguration($communityId, $profileType, $profileId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'configuration',
			$this->table,
			'community_id = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($communityId, $this->table) . ' AND profile_type = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($profileType, $this->table) . ' AND profile_id = ' . intval($profileId) 
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			return $data['configuration'];
		}
		return serialize($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']['controller.']['dashboard.']['defaultConfiguration.'][$profileType.'.']);
	}

	public function setConfiguration($communityId, $profileType, $profileId, $configuration) {
		$oldConfig = $this->getConfiguration($communityId, $profileType, $profileId);
		$data = array(
			'community_id'	=> $GLOBALS['TYPO3_DB']->quoteStr($communityId, $this->table),
			'profile_type'	=> $GLOBALS['TYPO3_DB']->quoteStr($profileType, $this->table),
			'profile_id'	=> intval($profileId),
			'configuration'	=> $configuration,
		); 
		
		if ($oldConfig === null) {
			$res = $GLOBALS['TYPO3_DB']->exec_INSERTquery(
				$this->table,
				$data
			);
			return ($GLOBALS['TYPO3_DB']->sql_insert_id());
		} else {
			$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery(
				$this->table,
				'community_id = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($communityId, $this->table) . ' AND profile_type = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($profileType, $this->table) . ' AND profile_id = ' . intval($profileId), 
				$data
			);
			return ($GLOBALS['TYPO3_DB']->sql_affected_rows());
		}
	}
}
?>