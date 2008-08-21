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


class tx_communityflexiblelayout_TemplateEngineAdapter {
	/**
	 * @var tx_donation_HtmlTemplateView
	 */
	protected $templateEngine;
	
	public function __construct($htmlFile, $subpart, $prefix = '') {
		$this->templateEngine = null;
		//new tx_donation_HtmlTemplateView($htmlFile, $subpart, $prefix = '');
	}
	
	public function loadHtmlFile($htmlFile) {
		return $this->templateEngine->loadHtmlFile($htmlFile);
	}
	
	public function setViewHelperIncludePath($path) {
		return $this->templateEngine->setViewHelperIncludePath($path);
	}
	
	public function loadViewHelper($helper, $arguments = array()) {
		return $this->templateEngine->loadViewHelper($helper, $arguments);
	}
	
	public function render() {
		return $this->templateEngine->render();
	}
	
	public function workOnSubpart($subpart) {
		return $this->templateEngine->workOnSubpart($subpart);
	}
	
	public function getSubpart($subpartName, $alternativeTemplate = '') {
		return $this->templateEngine->getSubpart($subpartName, $alternativeTemplate);
	}
	
	public function addMarker($marker, $content) {
		return $this->templateEngine->addMarker($marker, $content);
	}
	
	public function addMarkerArray($markers) {
		return $this->templateEngine->addMarkerArray($markers);
	}
	
	public function addSubpart($subpartMarker, $content) {
		return $this->templateEngine->addSubpart($subpartMarker, $content);
	}
	
	public function addVariable($key, $value) {
		return $this->templateEngine->addVariable($key, $value);
	}
	
	public function addLoop($loopName, $variables) {
		return $this->templateEngine->addLoop($loopName, $variables);
	}
	
	public function getMarkersFromSubpart($subpart) {
		return $this->templateEngine->getMarkersFromSubpart($subpart);
	}
	
	public function getCObj() {
		return $this->templateEngine->getCObj();
	}
	
	public function getHelperMarkers($helperMarker, $subpart) {
		return $this->templateEngine->getHelperMarkers($helperMarker, $subpart);
	}
	
	public function getVariableMarkers($variableMarker, $subpart) {
		return $this->templateEngine->getVariableMarkers($variableMarker, $subpart);
	}
}
?>