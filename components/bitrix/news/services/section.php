<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
// get section items count and subsections
$arSectionFilter = CNext::GetCurrentSectionFilter($arResult["VARIABLES"], $arParams);
$arSection = CNextCache::CIblockSection_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), "MULTI" => "N")), $arSectionFilter, false, array('ID', 'DESCRIPTION', 'PICTURE', 'DETAIL_PICTURE', 'IBLOCK_ID', 'UF_TOP_SEO'));
$arItemFilter = CNext::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams);

if($arSection && !$arItemFilter['SECTION_ID'])
{
	$arItemFilter['SECTION_ID'] = $arSection['ID'];
}
$itemsCnt = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arItemFilter, array());
CNext::AddMeta(
	array(
		'og:description' => $arSection['DESCRIPTION'],
		'og:image' => (($arSection['PICTURE'] || $arSection['DETAIL_PICTURE']) ? CFile::GetPath(($arSection['PICTURE'] ? $arSection['PICTURE'] : $arSection['DETAIL_PICTURE'])) : false),
	)
);
$arSubSectionFilter = CNext::GetCurrentSectionSubSectionFilter($arResult["VARIABLES"], $arParams, $arSection['ID']);
$arSubSections = CNextCache::CIblockSection_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y")), $arSubSectionFilter, false, array("ID", "DEPTH_LEVEL"));

global $arRegion;
?>
<?if(!$arSection && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_NOTFOUND")?></div>
<?elseif(!$arSection && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CNext::goto404Page();?>
<?else:?>
	<?// rss
	if($arParams['USE_RSS'] !== 'N'){
		CNext::ShowRSSIcon(CComponentEngine::makePathFromTemplate($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss_section'], array_map('urlencode', $arResult['VARIABLES'])));
	}?>
	<?if(!$arSubSections && !$itemsCnt):?>
		<div class="alert alert-warning"><?=GetMessage("SECTION_EMPTY")?></div>
	<?endif;?>

	<?//seo
	$arParams["LANDING_IBLOCK_ID"] = (!isset($arParams["LANDING_IBLOCK_ID"]) || !$arParams["LANDING_IBLOCK_ID"] ? CNextCache::$arIBlocks[SITE_ID]["aspro_next_catalog"]["aspro_next_catalog_info"][0] : $arParams["LANDING_IBLOCK_ID"]);
	$arParams["TIZERS_IBLOCK_ID"] = (!isset($arParams["TIZERS_IBLOCK_ID"]) || !$arParams["TIZERS_IBLOCK_ID"] ? CNextCache::$arIBlocks[SITE_ID]["aspro_next_content"]["aspro_next_tizers"][0] : $arParams["TIZERS_IBLOCK_ID"]);

	/*fix*/
	$current_url =  $APPLICATION->GetCurDir();
	$real_url = $current_url;
	$current_url =  str_replace(array('%25', '&quot;', '&#039;'), array('%', '"', "'"), $current_url); // for utf-8 fix some problem
	$encode_current_url = urlencode($current_url);
	$gaps_encode_current_url = str_replace(' ', '%20', $current_url);
	$encode_current_url_slash = str_replace(array('%2F', '+'), array('/', '%20'), $encode_current_url);
	$urldecodedCP = iconv("windows-1251", "utf-8//IGNORE", $current_url);
	$urldecodedCP_slash = str_replace(array('%2F'), array('/'), rawurlencode($urldecodedCP));
	$replacements = array('"' ,'%27', '%20', '%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%3F', '%23', '%5B', '%5D');// for fix some problem  with spec chars in prop
	$entities = array("&quot;", '&#039;', ' ', '!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "?", "#", "[", "]");
	$replacedSpecChar = str_replace($entities, $replacements, $current_url);
	/**/

	$arSeoItems = CNextCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'CACHE' => array("MULTI" => "Y", "TAG" => CNextCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"], "ACTIVE" => "Y", "PROPERTY_FILTER_URL" => array($real_url, $current_url, $gaps_encode_current_url, $urldecodedCP_slash, $encode_current_url_slash, $replacedSpecChar)), false, false, array("ID", "NAME", "IBLOCK_ID", "PROPERTY_FILTER_URL", "PROPERTY_LINK_REGION", "PREVIEW_TEXT"));
	$arSeoItem = $arTmpRegionsLanding = array();
	
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
	$arItemFilter = array('INCLUDE_SUBSECTIONS' => 'Y', 'SECTION_ID' => $arSection['ID']);

	$arElement = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "Y"), $arParams['SORT_BY1'] => $arParams['SORT_ORDER1'], $arParams['SORT_BY2'] => $arParams['SORT_ORDER2']), $arItemFilter, false, $arGroup, $arSelect);

	if($pagen != $NavNum){
		$NavNum = $pagen - 1;
	}

	foreach ($arElement as $element):
		$arSchema[] = array(
			"@context" => "https://schema.org",
			"@type" => "Service",
			"name" => $element["NAME"],
			"description" => $element["PREVIEW_TEXT"]
		);
	endforeach;?>
	<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
	<?
	if($arSeoItems)
	{
		$iLandingItemID = 0;
		foreach($arSeoItems as $arItem)
		{
			if(!is_array($arItem['PROPERTY_LINK_REGION_VALUE']))
				$arItem['PROPERTY_LINK_REGION_VALUE'] = (array)$arItem['PROPERTY_LINK_REGION_VALUE'];

			if(!$arSeoItem)
			{
				if($arItem['PROPERTY_LINK_REGION_VALUE'])
				{
					if($arRegion && in_array($arRegion['ID'], $arItem['PROPERTY_LINK_REGION_VALUE']))
						$arSeoItem = $arItem;
				}
				else
				{
					$arSeoItem = $arItem;
				}

				if($arSeoItem)
				{
					$iLandingItemID = $arSeoItem['ID'];
					$arSeoItem = CNextCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'CACHE' => array("MULTI" => "N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["LANDING_IBLOCK_ID"]))), array("IBLOCK_ID" => $arParams["LANDING_IBLOCK_ID"], "ID" => $iLandingItemID), false, false, array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT", "DETAIL_PICTURE", "PROPERTY_FILTER_URL", "PROPERTY_LINK_REGION", "PROPERTY_FORM_QUESTION", "PROPERTY_SECTION_SERVICES", "PROPERTY_TIZERS", "PROPERTY_SECTION", "DETAIL_TEXT", "PROPERTY_I_ELEMENT_PAGE_TITLE", "PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_ALT", "PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_TITLE", "PROPERTY_I_SKU_PAGE_TITLE", "PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_ALT", "PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_TITLE", "ElementValues"));

					$arIBInheritTemplates = array(
						"ELEMENT_PAGE_TITLE" => $arSeoItem["PROPERTY_I_ELEMENT_PAGE_TITLE_VALUE"],
						"ELEMENT_PREVIEW_PICTURE_FILE_ALT" => $arSeoItem["PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_ALT_VALUE"],
						"ELEMENT_PREVIEW_PICTURE_FILE_TITLE" => $arSeoItem["PROPERTY_I_ELEMENT_PREVIEW_PICTURE_FILE_TITLE_VALUE"],
						"SKU_PAGE_TITLE" => $arSeoItem["PROPERTY_I_SKU_PAGE_TITLE_VALUE"],
						"SKU_PREVIEW_PICTURE_FILE_ALT" => $arSeoItem["PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_ALT_VALUE"],
						"SKU_PREVIEW_PICTURE_FILE_TITLE" => $arSeoItem["PROPERTY_I_SKU_PREVIEW_PICTURE_FILE_TITLE_VALUE"],
					);
				}
			}

			if($arItem['PROPERTY_LINK_REGION_VALUE'])
			{
				if(!$arRegion || !in_array($arRegion['ID'], $arItem['PROPERTY_LINK_REGION_VALUE']))
					$arTmpRegionsLanding[] = $arItem['ID'];
			}
		}
	}
	?>

	<div class="main-section-wrapper">
		<?if($arSection['UF_TOP_SEO'] && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
			<div class="text_before_items">
				<p class="introtext"><?=$arSection['UF_TOP_SEO'];?></p>
			</div>
		<?endif;?>

		<?if($arSubSections):?>
			<?// sections list?>
			<?@include_once('page_blocks/'.$arParams["SECTION_TYPE_VIEW"].'.php');?>
		<?endif;?>
		<?// section elements?>
		<?@include_once('page_blocks/'.$arParams["SECTION_ELEMENTS_TYPE_VIEW"].'.php');?>

		<?if($arSection['DESCRIPTION'] && strpos($_SERVER['REQUEST_URI'], 'PAGEN') === false):?>
			<div class="text_after_items">
				<?=$arSection['DESCRIPTION'];?>
			</div>
		<?endif;?>
	</div>
<?endif;?>

<?if(\Bitrix\Main\Loader::includeModule("sotbit.seometa")):?>
	<?$APPLICATION->IncludeComponent(
		"sotbit:seo.meta",
		".default",
		array(
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"SECTION_ID" => $arSection['ID'],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
		)
	);?>
<?endif;?>