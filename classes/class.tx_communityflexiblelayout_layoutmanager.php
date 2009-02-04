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

	public function hasConfiguration($communityId, $profileType, $profileId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'configuration',
			$this->table,
			'community_id = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($communityId, $this->table) . ' AND profile_type = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($profileType, $this->table) . ' AND profile_id = ' . intval($profileId) 
		);
		return ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0);
	}
	
	protected function TSConf2Array($tsconf) {
		if (is_array($tsconf)) {
			foreach($tsconf as $config) {
				$parts = t3lib_div::trimExplode(',', $config);
				$newConfig[$parts[2]] = array(
					'col'	=> $parts[0],
					'pos'	=> $parts[1],
					'id'	=> $parts[2]
				);
			}
			return $newConfig;
		}
		return false;
	}
	
	protected function Array2TSConf($configuration) {
		$returnData = array();
		foreach ($configuration as $key => $dataArray) {
			$returnData[] = "{$dataArray['col']},{$dataArray['pos']},{$dataArray['id']}";
		}
		return $returnData;
	}

	public function getConfiguration($communityId, $profileType, $profileId) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'configuration',
			$this->table,
			'community_id = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($communityId, $this->table) . ' AND profile_type = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($profileType, $this->table) . ' AND profile_id = ' . intval($profileId) 
		);
		$userConfiguration = null;
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res) > 0) {
			$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$userConfiguration = $this->TSConf2Array(unserialize($data['configuration']));
		}

			// get default widget positions
		$defaultConfig = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_communityflexiblelayout.']['controller.']['dashboard.']['defaultConfiguration.'][$profileType.'.'];
		$defaultConfig = $this->TSConf2Array($defaultConfig);

			// get users widget positions
		if ($userConfiguration !== null) {
				// now we must check if we have new widgets which 
				// not available in users config. in this case, we
				// add the missing widgets with the defaul configuration
			foreach ($defaultConfig as $widgetId => $widgetConfig) {
				if (!array_key_exists($widgetId, $userConfiguration)) {
					$userConfiguration[$widgetId] = $widgetConfig;
				}
			}

				// now we must check if there is a widget with the same
				// position in the same column
				// in this case we musst set the current widget on
				// the next free position
			$columns = array();
			foreach ($userConfiguration as $widgetId => $widgetConfig) {
				 $columns[$widgetConfig['col']][] = $widgetConfig;
			}
			foreach ($columns as $columnId => &$columnConfigs) {
				$idStack = array();
				foreach ($columnConfigs as &$columnConfig) {
					if (!in_array($columnConfig['pos'], $idStack)) {
						$idStack[] = $columnConfig['pos'];
					} else {
						while (in_array($columnConfig['pos'], $idStack)) {
							$columnConfig['pos']++;
						}
						$idStack[] = $columnConfig['pos'];
					}
				}
			}
			foreach ($columns as $colId => $items) {
				foreach ($items as $index => $config) {
					$tempConfiguration[$config['id']] = $config;
				}
			}
			$userConfiguration = $tempConfiguration;
		} else {
			$userConfiguration = $defaultConfig;
		}
		
		$userConfiguration = $this->Array2TSConf($userConfiguration);

		return serialize($userConfiguration);
	}

	public function setConfiguration($communityId, $profileType, $profileId, $configuration) {
		$data = array(
			'community_id'	=> $GLOBALS['TYPO3_DB']->quoteStr($communityId, $this->table),
			'profile_type'	=> $GLOBALS['TYPO3_DB']->quoteStr($profileType, $this->table),
			'profile_id'	=> intval($profileId),
			'configuration'	=> $configuration,
		); 
		
		if (!$this->hasConfiguration($communityId, $profileType, $profileId)) {
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
	
	public function putWidgetToClipboard($communityId, $profileType, $profileId, $widgetId) {
		$configuration = $this->getConfiguration($communityId, $profileType, $profileId);
		$configuration = unserialize($configuration);
		$configuration = $this->TSConf2Array($configuration);
		
		if (array_key_exists($widgetId, $configuration)) {
			$configuration[$widgetId]['col'] = 0;
		}
		$returnData = $this->Array2TSConf($configuration);
		
		return $this->setConfiguration($communityId, $profileType, $profileId, serialize($returnData));
	}
}
?>
