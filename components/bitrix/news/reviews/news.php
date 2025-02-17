<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?// intro text?>
<div class="text_before_items"><?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?></div>
<?
$arItemFilter = CNext::GetIBlockAllElementsFilter($arParams);
$itemsCnt = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());
?>

<?$this->SetViewTarget('product_share');?>
	<?if($arParams['USE_RSS'] !== 'N'):?>
		<div class="colored_theme_hover_bg-block">
			<?=CNext::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);?>
		</div>
	<?endif;?>
<?$this->EndViewTarget();?>

<?if($arParams["SHOW_ADD_REVIEW_BUTTON"] == "Y"):?>
	<?
	$additionalParams = array("iblock_type" => $arParams["IBLOCK_TYPE"], "set_name" => "Y", "deactivate" => ( !empty($arParams["FORM_CREATE_DEACTIVATED"]) ? $arParams["FORM_CREATE_DEACTIVATED"] : "Y") );
	$additionalFormParams = urlencode(serialize($additionalParams));
	?>
	<div class="add_review">
		<div class="button_wrap">
			<span><span class="btn btn-default btn-lg wides dyn-jsform" data-param-additional="<?=$additionalFormParams?>" data-event="jqm" data-param-form_id="REVIEW" data-name="send_review"><?=(strlen($arParams["ADD_REVIEW_BUTTON"]) ? $arParams["ADD_REVIEW_BUTTON"] : GetMessage('ADD_REVIEW'))?></span></span>
		</div>
	</div>
<?endif;?>

<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?// section elements?>
	<?global $arTheme;?>
	<?$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["REVIEWS_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);?>
	<?@include_once('page_blocks/'.$sViewElementsTemplate.'.php');?>
<?endif;?>