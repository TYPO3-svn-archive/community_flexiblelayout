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

require_once(t3lib_extMgm::extPath('ajaxupload').'interfaces/interface.tx_ajaxupload_observer.php');
require_once(t3lib_extMgm::extPath('community').'model/class.tx_community_model_usergateway.php');

class tx_communityflexiblelayout_hook_Observer implements tx_ajaxupload_Observer {

	public function onSuccess(array $resultArray) {
		if ($resultArray['uploaderId'] == 'tx_communityflexiblelaout_perso') {
			$userGroups = $GLOBALS['TSFE']->fe_user->user['usergroup'];
			$userGroups = t3lib_div::trimExplode(',', $userGroups);
			// is confirmed mit einverständniserklärung
			if (in_array(28, $userGroups)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'fe_users',
					'uid = ' . $GLOBALS['TSFE']->fe_user->user['uid'],
					array(
						'usergroup'	=> '29',
						'identity_card'	=> $resultArray['newImageName']
					)
				);
			} else {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'fe_users',
					'uid = ' . $GLOBALS['TSFE']->fe_user->user['uid'],
					array(
						'usergroup'	=> '27',
						'identity_card'	=> $resultArray['newImageName']
					)
				);
			}
		}
		if ($resultArray['uploaderId'] == 'tx_communityflexiblelaout_einver') {
			$userGroups = $GLOBALS['TSFE']->fe_user->user['usergroup'];
			$userGroups = t3lib_div::trimExplode(',', $userGroups);
			// is confirmed mit perso
			if (in_array(27, $userGroups)) {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'fe_users',
					'uid = ' . $GLOBALS['TSFE']->fe_user->user['uid'],
					array(
						'usergroup'	=> '29',
						'okfile'	=> $resultArray['newImageName']
					)
				);
			} else {
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery(
					'fe_users',
					'uid = ' . $GLOBALS['TSFE']->fe_user->user['uid'],
					array(
						'usergroup'	=> '28',
						'okfile'	=> $resultArray['newImageName']
					)
				);
			}
		}
	}

	public function onError(array $resultArray) {
	}

	public function onDelete(array $resultArray) {
	}

	/**
	 * this function checks if it is allowed to upload files
	 *
	 * @param string $uploaderId
	 * @return int 0,1 or -1 0 = denied, 1=allowed, -1=not competent
	 */
	public function checkAccess($uploaderId) {
		return ($uploaderId == 'tx_communityflexiblelaout_perso' || $uploaderId == 'tx_communityflexiblelaout_einver') ? 1 : -1;
	}

	public function checkAccessForUploadButton($uploaderId) {
		return $this->checkAccess($uploaderId);
	}
}

?>
