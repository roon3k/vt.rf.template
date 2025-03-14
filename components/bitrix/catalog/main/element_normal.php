<?
use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;

CNext::AddMeta(
	array(
		'og:description' => $arElement['PREVIEW_TEXT'],
		'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['DETAIL_PICTURE'] ? $arElement['DETAIL_PICTURE'] : $arElement['PREVIEW_PICTURE'])) : false),
	)
);

$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["CATALOG_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);
$hide_left_block = ($arTheme["LEFT_BLOCK_CATALOG_DETAIL"]["VALUE"] == "Y" ? "N" : "Y");
$arWidePage = array("element_3", "element_4", "element_5");

//set offer view type
$typeTmpDetail = 0;
if($arSection['UF_ELEMENT_DETAIL'])
	$typeTmpDetail = $arSection['UF_ELEMENT_DETAIL'];
else
{
	if($arSection["DEPTH_LEVEL"] > 2)
	{
		$sectionParent = CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arSection["IBLOCK_SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL"));
		if($sectionParent['UF_ELEMENT_DETAIL'] && !$typeTmpDetail)
			$typeTmpDetail = $sectionParent['UF_ELEMENT_DETAIL'];

		if(!$typeTmpDetail)
		{
			$sectionRoot = CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL"));
			if($sectionRoot['UF_ELEMENT_DETAIL'] && !$typeTmpDetail)
				$typeTmpDetail = $sectionRoot['UF_ELEMENT_DETAIL'];
		}
	}
	else
	{
		$sectionRoot = CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL"));
		if($sectionRoot['UF_ELEMENT_DETAIL'] && !$typeTmpDetail)
			$typeTmpDetail = $sectionRoot['UF_ELEMENT_DETAIL'];
	}
}
if($typeTmpDetail)
{
	$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpDetail));
	if($arType = $rsTypes->GetNext())
		$typeDetail = $arType['XML_ID'];
	if($typeDetail)
		$sViewElementTemplate = $typeDetail;
}

/* set force CATALOG_PAGE_DETAIL from theme */
if (
	class_exists('\Aspro\Functions\CAsproNextCustom') &&
	method_exists('\Aspro\Functions\CAsproNextCustom', 'setPageDetail')
) {
	$sViewElementTemplate = \Aspro\Functions\CAsproNextCustom::setPageDetail($sViewElementTemplate);
}
/* */

if(in_array($sViewElementTemplate, $arWidePage))
	$hide_left_block = "Y";
?>
<?$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", $hide_left_block)?>
<?if($arParams["USE_SHARE"] == "Y" && $arElement/* && !in_array($sViewElementTemplate, $arWidePage)*/):?>
	<?$this->SetViewTarget('product_share');?>
	<div class="line_block share top <?=($arParams['USE_RSS'] == 'Y' ? 'rss-block' : '');?>">
		<?$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')));?>
	</div>
	<?$this->EndViewTarget();?>
<?endif;?>
<?$isWideBlock = (isset($arParams["DIR_PARAMS"]["HIDE_LEFT_BLOCK"]) ? $arParams["DIR_PARAMS"]["HIDE_LEFT_BLOCK"] : "");?>
<?if($arParams['AJAX_MODE'] == 'Y' && strpos($_SERVER['REQUEST_URI'], 'bxajaxid') !== false):?>
	<script type="text/javascript">
		setStatusButton();
	</script>
<?endif;?>

<?
$sViewBigDataBottomTemplate = $arParams["BIGDATA_EXT_BOTTOM"] ? $arParams["BIGDATA_EXT_BOTTOM"] : "bigdata_bottom_1";
$sViewBigDataExtTemplate = $arParams["BIGDATA_EXT"] ? $arParams["BIGDATA_EXT"] : "bigdata_1";

global $arBigData;
$arBigData = array(
	'SECTION_ID' => $arSection['ID'],
	'BIG_DATA_SHOW_FROM_SECTION' => $arParams['BIG_DATA_SHOW_FROM_SECTION'],
);?>

<div class="catalog_detail detail js-notice-block<?=($isWideBlock == "Y" ? " fixed_wrapper" : "");?> <?=$sViewElementTemplate;?>" itemscope itemtype="http://schema.org/Product">
	<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
</div>

<?CNext::checkBreadcrumbsChain($arParams, $arSection, $arElement);?>
<div class="clearfix"></div>


<?/*fix title after ajax form start*/
$arAdditionalData = $arNavParams = array();

$postfix = '';
global $arSite;
if(\Bitrix\Main\Config\Option::get("aspro.next", "HIDE_SITE_NAME_TITLE", "N")=="N")
	$postfix = ' - '.$arSite['SITE_NAME'];

$arAdditionalData['TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle());
$arAdditionalData['WINDOW_TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle('title').$postfix);

// dirty hack: try to get breadcrumb call params
for ($i = 0, $cnt = count($APPLICATION->buffer_content_type); $i < $cnt; $i++){
	if ($APPLICATION->buffer_content_type[$i]['F'][1] == 'GetNavChain'){
		$arNavParams = $APPLICATION->buffer_content_type[$i]['P'];
	}
}
if ($arNavParams){
	$arAdditionalData['NAV_CHAIN'] = $APPLICATION->GetNavChain($arNavParams[0], $arNavParams[1], $arNavParams[2], $arNavParams[3], $arNavParams[4]);
}
?>
<script type="text/javascript">
	if(!$('.js_seo_title').length)
		$('<span class="js_seo_title" style="display:none;"></span>').appendTo($('body'));
	BX.addCustomEvent(window, "onAjaxSuccess", function(e){
		var arAjaxPageData = <?=CUtil::PhpToJSObject($arAdditionalData, true, true, true);?>;

		//set title from offers
		if(typeof ItemObj == 'object' && Object.keys(ItemObj).length)
		{
			if('TITLE' in ItemObj && ItemObj.TITLE)
			{
				arAjaxPageData.TITLE = ItemObj.TITLE;
				arAjaxPageData.WINDOW_TITLE = ItemObj.WINDOW_TITLE;
			}
		}

		if (arAjaxPageData.TITLE)
			$('h1').html(arAjaxPageData.TITLE);
		if (arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE)
		{
			$('.js_seo_title').html(arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE); //seo fix for spec symbol
			BX.ajax.UpdateWindowTitle($('.js_seo_title').html());
		}

		if (arAjaxPageData.NAV_CHAIN)
			BX.ajax.UpdatePageNavChain(arAjaxPageData.NAV_CHAIN);
		$('.catalog_detail input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').html());
	});
</script>
<?/*fix title after ajax form end*/?>
<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.history.js');?>