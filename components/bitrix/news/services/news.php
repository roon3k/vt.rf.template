<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(true);?>
<?// intro text?>
<div class="text_before_items">
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		"",
		Array(
			"AREA_FILE_SHOW" => "page",
			"AREA_FILE_SUFFIX" => "inc",
			"EDIT_TEMPLATE" => ""
		)
	);?>
</div>
<?if((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (strtolower($_REQUEST['ajax']) == 'y'))
{
	$APPLICATION->RestartBuffer();
}?>
<?
// get section items count and subsections
$arItemFilter = CNext::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams, false);
$arSubSectionFilter = CNext::GetCurrentSectionSubSectionFilter($arResult["VARIABLES"], $arParams, false);
$itemsCnt = CNextCache::CIBlockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arItemFilter, array());
$arSubSections = CNextCache::CIBlockSection_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y", "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arSubSectionFilter, false, array("ID"));

global $NavNum; 
$context = \Bitrix\Main\Application::getInstance()->getContext();
if($NavNum){
	$pagen = $NavNum;
}else{
	$pagen = 2;
}
$numPage = $context->getRequest()->get("PAGEN_".$pagen) ?? 1;
$arGroup =  array("iNumPage" => $numPage, "nPageSize" => $arParams['NEWS_COUNT']);
$arSelect = array('ID', 'NAME','PREVIEW_TEXT', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DATE_CREATE');
$arElement = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y"), $arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), $arItemFilter, false, $arGroup, $arSelect);
if($pagen != $NavNum){
	$NavNum = $pagen - 1;
}
foreach($arElement as $element):
	$arSchema[] = array(
		"@context" => "https://schema.org",
		"@type" => "NewsArticle",
		"url" => $_SERVER['SERVER_NAME'].$element['DETAIL_PAGE_URL'],
		"publisher" => array(
			"@type" => "Organization",
			  "name" => $arSite['NAME']
		),
		"headline" => $element['NAME'],
		"articleBody" => $element['PREVIEW_TEXT'],
		"image" => array($_SERVER['SERVER_NAME'].CFile::GetPath($element['PREVIEW_PICTURE']), $_SERVER['SERVER_NAME'].CFile::GetPath($element['DETAIL_PICTURE'])),
		"datePublished" => $element['DATE_CREATE']
	);
endforeach;
?>
<script type="application/ld+json">
	<?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?>
</script>
<?
// rss
if($arParams['USE_RSS'] !== 'N'){
	CNext::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
}
?>
<?if(!$itemsCnt && !$arSubSections):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?// sections?>
	<?@include_once('page_blocks/'.$arParams["SECTIONS_TYPE_VIEW"].'.php');?>

	<?// section elements?>
	<?if(strlen($arParams["FILTER_NAME"])):?>
		<?$arTmpFilter = $GLOBALS[$arParams["FILTER_NAME"]];?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = array_merge((array)$GLOBALS[$arParams["FILTER_NAME"]], $arItemFilter);?>
	<?else:?>
		<?$arParams["FILTER_NAME"] = "arrFilterServ";?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = $arItemFilter;?>
	<?endif;?>

	<?@include_once('page_blocks/'.$arParams["SECTION_ELEMENTS_TYPE_VIEW"].'.php');?>

	<?if(strlen($arParams["FILTER_NAME"])):?>
		<?$GLOBALS[$arParams["FILTER_NAME"]] = $arTmpFilter;?>
	<?endif;?>
	
<?endif;?>