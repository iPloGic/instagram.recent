<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Config\Option,
	\Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Loader;


	$arComponentParameters = [
		"PARAMETERS" => [
			"ID" => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("IPL_INST_PARAMETER_ID"),
				"TYPE" => "STRING",
				"DEFAULT" => 'widget1',
			],
			"ACCESS_TOKEN" => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("IPL_INST_PARAMETER_ACCESS_TOKEN"),
				"TYPE" => "STRING",
				"DEFAULT" => '',
			],
			"NUM" => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("IPL_INST_PARAMETER_NUM"),
				"TYPE" => "STRING",
				"DEFAULT" => '6',
			],
			"EXPANDED" => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("IPL_INST_PARAMETER_EXPANDED"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => 'N',
				"REFRESH" => "Y",
				"SORT" => 10,
			],
			"SHOW_ERRORS" => [
				"PARENT" => "BASE",
				"NAME" => Loc::getMessage("IPL_INST_PARAMETER_SHOW_ERRORS"),
				"TYPE" => "CHECKBOX",
				"DEFAULT" => 'Y',
				"REFRESH" => "Y",
				"SORT" => 10,
			],

			"CACHE_TIME" => [
				"DEFAULT" => 36000,
				"PARENT" => "CACHE_SETTINGS",
			],
			"CACHE_TYPE"  => [
				"PARENT" => "CACHE_SETTINGS",
				"NAME" => Loc::getMessage("COMP_PROP_CACHE_TYPE"),
				"TYPE" => "LIST",
				"VALUES" => [
					"A" => Loc::getMessage("COMP_PROP_CACHE_TYPE_AUTO")." ".Loc::getMessage("COMP_PARAM_CACHE_MAN"), 
					"Y" => Loc::getMessage("COMP_PROP_CACHE_TYPE_YES"), 
					"N" => Loc::getMessage("COMP_PROP_CACHE_TYPE_NO")],
				"DEFAULT" => "N",
				"ADDITIONAL_VALUES" => "N",
				"REFRESH" => "Y"
			],
		],
	];

?>
