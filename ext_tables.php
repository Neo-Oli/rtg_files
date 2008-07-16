<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_extMgm::allowTableOnStandardPages("tx_rtgfiles_files");


t3lib_extMgm::addToInsertRecords("tx_rtgfiles_files");

$TCA["tx_rtgfiles_files"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_files',		
		'label' => 'title',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',	
		'delete' => 'deleted',	
		'enablecolumns' => Array (		
			'disabled' => 'hidden',
			'fe_group' => 'fe_group',
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY).'tx_rtgfiles_files.gif',
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, fe_group, title, system, description, keywords, file, clicks",
	)
);


t3lib_extMgm::allowTableOnStandardPages("tx_rtgfiles_systems");


t3lib_extMgm::addToInsertRecords("tx_rtgfiles_systems");

$TCA["tx_rtgfiles_systems"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:rtg_files/locallang_db.xml:tx_rtgfiles_systems',		
		'label' => 'title',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		"sortby" => "sorting",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY).'tx_rtgfiles_systems.gif',
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, title, description",
	)
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key';

t3lib_extMgm::addPlugin(array('LLL:EXT:rtg_files/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","Documents");

if (TYPO3_MODE=="BE")	$TBE_MODULES_EXT["xMOD_db_new_content_el"]["addElClasses"]["tx_rtgfiles_pi1_wizicon"] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_rtgfiles_pi1_wizicon.php';
?>