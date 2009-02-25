<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_ifmembersheet_member"] = array (
	"ctrl" => $TCA["tx_ifmembersheet_member"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,first_name,last_name,birthdate,hobbies,picture,text,occupations"
	),
	"feInterface" => $TCA["tx_ifmembersheet_member"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"first_name" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member.first_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"max" => "30",	
				"eval" => "required,trim",
			)
		),
		"last_name" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member.last_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "48",	
				"max" => "50",	
				"eval" => "required,trim",
			)
		),
		"birthdate" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member.birthdate",		
			"config" => Array (
				"type"     => "input",
				"size"     => "8",
				"max"      => "20",
				"eval"     => "date",
				"checkbox" => "0",
				"default"  => "0"
			)
		),
		"hobbies" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member.hobbies",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"picture" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member.picture",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "gif,png,jpeg,jpg",	
				"max_size" => 800,	
				"uploadfolder" => "uploads/tx_ifmembersheet",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"text" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member.text",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"occupations" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_member.occupations",		
			"config" => Array (
				"type" => "inline",	
				"foreign_table" => "tx_ifmembersheet_occupation",	
				"foreign_field" => "parentid",
				"foreign_table_field" => "parenttable",
				"foreign_label" => "title",
				"foreign_sortby" => "sorting",
				"maxitems" => 10,	
				'appearance' => array(
					'expandSingle' => 1,
					'useSortable' => 1,
					'newRecordLinkAddTitle' => 1,
				),
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, first_name, last_name, birthdate, hobbies, occupations, picture, text;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts]")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_ifmembersheet_occupation"] = array (
	"ctrl" => $TCA["tx_ifmembersheet_occupation"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "hidden,title,since"
	),
	"feInterface" => $TCA["tx_ifmembersheet_occupation"]["feInterface"],
	"columns" => array (
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_occupation.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "trim",
			)
		),
		"since" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:if_membersheet/locallang_db.xml:tx_ifmembersheet_occupation.since",		
			"config" => Array (
				"type"     => "input",
				"size"     => "8",
				"max"      => "20",
				"eval"     => "date",
				"checkbox" => "0",
				"default"  => "0"
			)
		),
		"parentid" => Array (		
			"config" => Array (
				"type" => "passthrough",
			)
		),
		"parenttable" => Array (		
			"config" => Array (
				"type" => "passthrough",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "hidden;;1;;1-1-1, parentid, parenttable, title;;;;2-2-2, since;;;;3-3-3")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>
