<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2008 Ingo Fabbri <typo3@tcsoft.net>
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

require_once (PATH_tslib . 'class.tslib_pibase.php');
require_once (PATH_t3lib . 'class.t3lib_stdgraphic.php');

/**
 * Plugin 'Member Infosheet - List' for the 'if_membersheet' extension.
 *
 * @author	Ingo Fabbri <typo3@tcsoft.net>
 * @package	TYPO3
 * @subpackage	tx_ifmembersheet
 */
class tx_ifmembersheet_pi1 extends tslib_pibase {
	var $prefixId = 'tx_ifmembersheet_pi1'; // Same as class name
	var $scriptRelPath = 'pi1/class.tx_ifmembersheet_pi1.php'; // Path to this script relative to the extension dir.
	var $extKey = 'if_membersheet'; // The extension key.
	var $templateCode;
	var $ffVars;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj = 1; // Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!
		$this->pi_initPIflexForm();
		
		$this->initializeFlexformVars();
		
		if (! file_exists( $this->ffVars['template'] ))
			$this->conf['template'] = $this->conf['template'] == '' ? t3lib_extMgm::siteRelPath( 'if_membersheet' ) . 'pi1/template.html' : $this->conf['template'];
		else
			$this->conf['template'] = $this->ffVars['template'];
		
		$this->templateCode = $this->cObj->fileResource( $this->conf['template'] );
		
		$content = '';
		
		switch ($this->ffVars['viewMode']) {
			case 'LIST' :
				$content = $this->listView();
				break;
			case 'SINGLE' :
				$content = $this->singleView();
				break;
		}
		
		return $this->pi_wrapInBaseClass( $content );
	}
	
	function initializeFlexformVars() {
		$this->ffVars['singlePID'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'singlePID', 'sLIST' ) );
		$this->ffVars['storagePID'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'storagePID', 'sDEF' ) );
		$this->ffVars['viewMode'] = $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'viewMode', 'sDEF' );
		
		$this->ffVars['dim']['thumbLMaxW'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'thumbMaxW', 'sLIST' ) );
		$this->ffVars['dim']['thumbLMaxH'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'thumbMaxH', 'sLIST' ) );
		$this->ffVars['dim']['thumbSMaxW'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'thumbMaxW', 'sSINGLE' ) );
		$this->ffVars['dim']['thumbSMaxH'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'thumbMaxH', 'sSINGLE' ) );
		$this->ffVars['dim']['SMaxW'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'maxW', 'sSINGLE' ) );
		$this->ffVars['dim']['SMaxH'] = intval( $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'maxH', 'sSINGLE' ) );
		
		$this->ffVars['template'] = $this->pi_getFFvalue( $this->cObj->data['pi_flexform'], 'template', 'sDEF' );
	}
	
	function listView() {
		$template['total'] = $this->cObj->getSubpart( $this->templateCode, '###TEMPLATE_MEMBERSHEET_LIST###' );
		$template['item'] = $this->cObj->getSubpart( $template['total'], '###ITEM###' );
		
		$where = 'pid=' . $this->ffVars['storagePID'] . ' AND NOT deleted AND NOT hidden';
		$recs = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows( 'uid,first_name,last_name,picture', 'tx_ifmembersheet_member', $where, '', 'last_name ASC' );
		
		if ($recs == '')
			return '';
		
		foreach ($recs as $row) {
			$markerArray['###FIRST_NAME###'] = $row['first_name'];
			$markerArray['###LAST_NAME###'] = $this->pi_list_linkSingle( $row['last_name'], $row['uid'], false, array(), false, $this->ffVars['singlePID'] );
			$markerArray['###PICTURE###'] = $this->renderPicture( false, 'uploads/tx_ifmembersheet/' . $row['picture'], $this->ffVars['dim'] );
			
			$this->substituteLabelMarkers( $markerArray );
			$list_items .= $this->cObj->substituteMarkerArrayCached( $template['item'], $markerArray );
		}
		$subpartArray['###ITEM###'] = $list_items;
		
		return $this->cObj->substituteMarkerArrayCached( $template['total'], $markerArray, $subpartArray );
	}
	
	function singleView() {
		$template['total'] = $this->cObj->getSubpart( $this->templateCode, '###TEMPLATE_MEMBERSHEET_SINGLE###' );
		$template['occupation_item'] = $this->cObj->getSubpart( $template['total'], '###OCCUPATION_ITEM###' );
		
		$where = 'pid=' . $this->ffVars['storagePID'] . ' AND uid=' . $this->piVars['showUid'] . ' AND NOT deleted AND NOT hidden';
		$recs = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows( 'uid,first_name,last_name,birthdate,hobbies,picture,text, FLOOR(DATEDIFF(NOW(), FROM_UNIXTIME(birthdate))/365) as age', 'tx_ifmembersheet_member', $where, '', 'last_name ASC' );
		
		if ($recs == '')
			return '';
		
		$row = $recs[0];
		
		$markerArray['###FIRST_NAME###'] = $row['first_name'];
		$markerArray['###LAST_NAME###'] = $row['last_name'];
		$markerArray['###BIRTHDATE###'] = strftime( '%d.%m.%Y', $row['birthdate'] );
		$markerArray['###AGE###'] = $row['age'];
		$markerArray['###HOBBIES###'] = $row['hobbies'];

		$occupations = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows( 'uid,title,since', 'tx_ifmembersheet_occupation', 'parentid=' . $row['uid'] . ' AND NOT deleted AND NOT hidden', '', 'sorting ASC' );
		foreach ($occupations as $ocp){
			$markerArray['###OCCUPATION###'] = $ocp['title'];
			$markerArray['###OCCUPATION_SINCE###'] = $ocp['since'] != 0 ? strftime( '%Y', $ocp['since'] ) : '-';
			$markerArray['###OCCUPATION_COMBINED###'] = $ocp['title'] . ' ' . ($ocp['since'] != 0 ? $this->pi_getLL( 'pi_text_since' ) . strftime( ' %Y', $ocp['since'] ) : '');	
		
			$occupation_items .= $this->cObj->substituteMarkerArrayCached( $template['occupation_item'], $markerArray );
		}
		$subpartArray['###OCCUPATION_ITEM###'] = $occupation_items;
		
		$markerArray['###PICTURE###'] = $this->renderPicture( true, 'uploads/tx_ifmembersheet/' . $row['picture'], $this->ffVars['dim'] );
		$markerArray['###TEXT###'] = $this->pi_RTEcssText( $row['text'] );
		
		$this->substituteLabelMarkers( $markerArray );
		return $this->cObj->substituteMarkerArrayCached( $template['total'], $markerArray, $subpartArray );
	}
	
	function renderPicture($single, $path, $aDim) {
		$img = '';
		
		$this->renderThumbnailPicture( $img, $path, $single ? $aDim['thumbSMaxW'] : $aDim['thumbLMaxW'], $single ? $aDim['thumbSMaxH'] : $aDim['thumbLMaxH'] );
		if ($single)
			$this->renderFullsizePicture( $img, $path, $aDim['SMaxW'], $aDim['SMaxH'] );
		return $this->cObj->IMAGE( $img );
	}
	
	function renderFullsizePicture(&$img, $path, $maxW, $maxH) {
		
		if ($this->cObj->data['tx_perfectlightbox_activate']) {
			$img['stdWrap.']['typolink.']['parameter.']['cObject'] = 'IMG_RESOURCE';
			$img['stdWrap.']['typolink.']['parameter.']['cObject.']['file'] = $path;
			$img['stdWrap.']['typolink.']['parameter.']['cObject.']['file.']['maxW'] = $maxW;
			$img['stdWrap.']['typolink.']['parameter.']['cObject.']['file.']['maxH'] = $maxH;
			$img['stdWrap.']['typolink.']['ATagParams'] = 'rel="lightbox"';
		} else {
			$img['imageLinkWrap'] = 1;
			$img['imageLinkWrap.']['enable'] = 1;
			$img['imageLinkWrap.']['bodyTag'] = '<body bgColor="white" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onClick="self.close()">';
			$img['imageLinkWrap.']['wrap'] = '|';
			$img['imageLinkWrap.']['width'] = "{$maxW}m";
			$img['imageLinkWrap.']['width'] = "{$maxH}m";
			$img['imageLinkWrap.']['JSwindow'] = 1;
			$img['imageLinkWrap.']['JSwindow.']['newWindow'] = 1;
			$img['imageLinkWrap.']['JSwindow.']['expand'] = '0,0';
		}
	}
	
	function renderThumbnailPicture(&$img, $path, $maxW, $maxH) {
		$img[] = 'IMAGE';
		$img['file'] = $path;
		$img['file.']['maxW'] = $maxW;
		$img['file.']['maxH'] = $maxH;
	}
	
	function substituteLabelMarkers(&$mArr) {
		$mArr['###LABEL_LAST_NAME###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_last_name' ), $this->conf['labels.'] );
		$mArr['###LABEL_FIRST_NAME###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_first_name' ), $this->conf['labels.'] );
		$mArr['###LABEL_BIRTHDATE###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_birthdate' ), $this->conf['labels.'] );
		$mArr['###LABEL_AGE###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_age' ), $this->conf['labels.'] );
		$mArr['###LABEL_HOBBIES###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_hobbies' ), $this->conf['labels.'] );
		$mArr['###LABEL_OCCUPATION###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_occupation' ), $this->conf['labels.'] );
		$mArr['###LABEL_OCCUPATION_SINCE###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_occupation_since' ), $this->conf['labels.'] );
		$mArr['###LABEL_PICTURE###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_picture' ), $this->conf['labels.'] );
		$mArr['###LABEL_TEXT###'] = $this->cObj->stdWrap( $this->pi_getLL( 'pi_label_text' ), $this->conf['labels.'] );
	}
}

if (defined( 'TYPO3_MODE' ) && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/if_membersheet/pi1/class.tx_ifmembersheet_pi1.php']) {
	include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/if_membersheet/pi1/class.tx_ifmembersheet_pi1.php']);
}

?>
