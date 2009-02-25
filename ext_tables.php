<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$TCA["tx_ifmembersheet_member"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member',		
		'label'     => 'last_name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => "ORDER BY last_name",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_ifmembersheet_member.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, first_name, last_name, birthdate, hobbies, occupations, picture, text",
	)
);

$TCA["tx_ifmembersheet_occupation"] = array (
	"ctrl" => array (
		'title'     => 'LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_occupation',		
		'label'     => 'title',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => "sorting",	
		'delete' => 'deleted',	
		'enablecolumns' => array (		
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_ifmembersheet_occupation.gif',
	),
	"feInterface" => array (
		"fe_admin_fieldList" => "hidden, title, since, parentid, parenttable",
	)
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1'] = 'pi_flexform,tx_perfectlightbox_activate';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/pi1/flexform_ds.xml');

t3lib_extMgm::addPlugin(array('LLL:EXT:if_membersheet/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","Member Infosheet");

if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_ifmembersheet_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_ifmembersheet_pi1_wizicon.php';
?>
