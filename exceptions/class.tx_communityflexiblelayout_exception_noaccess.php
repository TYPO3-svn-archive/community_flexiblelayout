<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008 Frank Naegler <typo3@naegler.net>
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

class tx_communityflexiblelayout_exception_NoAccess extends Exception {

	function __construct() {
		parent::__construct('no access', 5000);
	}

	function __toString() {
		return "tx_communityflexiblelayout_exception_NoAccess at line {$this->getLine()} on {$this->getFile()} Message: {$this->getMessage()}";
	}
}


?>