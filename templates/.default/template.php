<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var customComponent $component */

use \Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Application;

//echo "<pre>"; print_r($arResult); echo "</pre>";

if (count($arResult["ITEMS"])) { ?>
	<div class="row instagram-recent">
		<? foreach($arResult["ITEMS"] as $arItem) { 
			$img = ($arItem["TYPE"] == "VIDEO" ? $arItem["IMG"]["TRUM"] : $arItem["IMG"]["STANDART"]);
			?>
			<div class="instagram-post-wrapper">
				<a href="<?=$arItem["URL"]?>" target="_blank">
					<div class="instagram-post" style="background-image: url('<?=$img?>');">
						<div class="blackout"></div>
						<? if ($arItem["TYPE"] == "CAROUSEL_ALBUM") { ?>
						<div class="album"><img src="<?=$templateFolder?>/img/album.svg"></div>
						<? } ?>
					</div>
				</a>
			</div>
		<? } ?>
		<div style="clear:both;"></div>
	</div>
<? } ?>