<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_ifmembersheet_pi1 = < plugin.tx_ifmembersheet_pi1.CSS_editor
',43);


t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_ifmembersheet_pi1.php','_pi1','list_type',0);

//$GLOBALS['T3_VAR']['ext']['dynaflex']['tt_content'][] = 'EXT:if_membersheet/pi1/class.tx_ifmembersheet_pichange.php:tx_ifmembersheet_pichange';
?>