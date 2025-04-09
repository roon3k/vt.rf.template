<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
$arItemFilter = CNext::GetIBlockAllElementsFilter($arParams);
$itemsCnt = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());
$arElement = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y")), $arItemFilter, false, false, array('ID', 'NAME','PREVIEW_TEXT'));?>

	<?$arSchema = array(
		"@context" => "https://schema.org",
		"@type" => "FAQPage",
		"mainEntity" => []
	)?>
	<?foreach ($arElement as $element):
		$arSchema['mainEntity'][] = array(
			"@type" => "Question",
			"name" => $element['NAME'],
			"acceptedAnswer" => array(
				"@type" => "Answer",
				"text" => $element['PREVIEW_TEXT']
			)
		);
	endforeach;?>
	
	<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
<?
// rss
if($arParams['USE_RSS'] !== 'N'){
	CNext::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
}
?>
<?if(!$itemsCnt):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
<?else:?>
	<?@include_once('page_blocks/'.$arParams["SECTION_ELEMENTS_TYPE_VIEW"].'.php');?>	
<?endif;?>