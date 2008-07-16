<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_rtgfiles_files"] = Array (
	"ctrl" => $TCA["tx_rtgfiles_files"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,fe_group,title,system,description,keywords,file"
	),
	"feInterface" => $TCA["tx_rtgfiles_files"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		'fe_group' => array (        
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'size' => 5,
				'maxitems' => 20,
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'exclusiveKeys' => '-1,-2',
				'foreign_table' => 'fe_groups'
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "40",	
				"max" => "255",	
				"eval" => "required,trim",
			)
		),
		"system" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files.system",		
			"config" => Array (
				"type" => "select",	
				"items" => Array (
					Array("",0),
				),
				"foreign_table" => "tx_rtgfiles_systems",	
				"foreign_table_where" => "ORDER BY tx_rtgfiles_systems.uid",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
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
		"keywords" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files.keywords",		
			"config" => Array (
				"type" => "text",
				"cols" => "40",	
				"rows" => "3",
			)
		),
		"file" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files.file",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => "",	
				"disallowed" => "php,php3",	
				"max_size" => 10000,	
				"uploadfolder" => "uploads/tx_rtgfiles",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"url" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files.url",		
			"config" => Array (
				"type" => "input",	
				"size" => "40",	
				"max" => "255",	
				"eval" => "trim",
			)
		),
        'clicks' => Array (
            'label' => 'LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files.clicks',
            'config' => Array (
                'type' => 'input',
                'size' => '12',
                'max' => '10',
                'eval' => 'int',
                'checkbox' => '0'
            )
        ),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, system;;;;3-3-3, description;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts], keywords, file, url, fe_group;;;;4-4-4")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "clicks")
	)
);



$TCA["tx_rtgfiles_systems"] = Array (
	"ctrl" => $TCA["tx_rtgfiles_systems"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,description"
	),
	"feInterface" => $TCA["tx_rtgfiles_systems"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_systems.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "40",	
				"max" => "80",	
				"eval" => "required,trim",
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_systems.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "40",	
				"rows" => "3",
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, title;;;;2-2-2, description;;;richtext[paste|bold|italic|underline|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts];3-3-3")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);
?>