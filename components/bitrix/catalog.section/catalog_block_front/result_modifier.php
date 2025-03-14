<?
use Bitrix\Main\Type\Collection;
use Bitrix\Currency\CurrencyTable;
use Bitrix\Iblock;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
$arDefaultParams = array(
	'TYPE_SKU' => 'N',
	'OFFER_TREE_PROPS' => array('-'),
);
$arParams = array_merge($arDefaultParams, $arParams);

if(isset($arParams['STORES'])) {
	foreach($arParams['STORES'] as $key => $store) {
		if(!$store) {
			unset($arParams['STORES'][$key]);
		}
	}
}

if ('TYPE_1' !== $arParams['TYPE_SKU'] )
	$arParams['TYPE_SKU'] = 'N';

if (!is_array($arParams['OFFER_TREE_PROPS']))
	$arParams['OFFER_TREE_PROPS'] = array($arParams['OFFER_TREE_PROPS']);
foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
{
	$value = (string)$value;
	if ('' == $value || '-' == $value)
		unset($arParams['OFFER_TREE_PROPS'][$key]);
}
if (empty($arParams['OFFER_TREE_PROPS']) && isset($arParams['OFFERS_CART_PROPERTIES']) && is_array($arParams['OFFERS_CART_PROPERTIES']))
{
	$arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_CART_PROPERTIES'];
	foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
	{
		$value = (string)$value;
		if ('' == $value || '-' == $value)
			unset($arParams['OFFER_TREE_PROPS'][$key]);
	}
}

/* hide compare link from module options */
if(CNext::GetFrontParametrValue('CATALOG_COMPARE') == 'N')
	$arParams["DISPLAY_COMPARE"] = 'N';
/**/

if(CNext::GetFrontParametrValue('SHOW_DELAY_BUTTON') == 'N')
	$arParams["DISPLAY_WISH_BUTTONS"] = 'N';

/*stores product*/
$arStores=CNextCache::CCatalogStore_GetList(array(), array("ACTIVE" => "Y"), false, false, array());
$arResult["STORES_COUNT"] = (count($arStores) && (CNext::GetFrontParametrValue("USE_STORE_QUANTITY")== "Y"));

if (!empty($arResult['ITEMS'])){
	$arConvertParams = array();
	if ('Y' == $arParams['CONVERT_CURRENCY'])
	{
		if (!CModule::IncludeModule('currency'))
		{
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		}
		else
		{
			$arResultModules['currency'] = true;
			if($arResult['CURRENCY_ID'])
			{
				$arConvertParams['CURRENCY_ID'] = $arResult['CURRENCY_ID'];
			}
			else
			{
				$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
				if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
				{
					$arParams['CONVERT_CURRENCY'] = 'N';
					$arParams['CURRENCY_ID'] = '';
				}
				else
				{
					$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
					$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				}
			}
		}
	}

	$arEmptyPreview = false;
	$strEmptyPreview = $this->GetFolder().'/images/no_photo.png';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview))
	{
		$arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview);
		if (!empty($arSizes))
		{
			$arEmptyPreview = array(
				'SRC' => $strEmptyPreview,
				'WIDTH' => intval($arSizes[0]),
				'HEIGHT' => intval($arSizes[1])
			);
		}
		unset($arSizes);
	}
	unset($strEmptyPreview);

	$arSKUPropList = array();
	$arSKUPropIDs = array();
	$arSKUPropKeys = array();
	$boolSKU = false;
	$strBaseCurrency = '';
	$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

	if ($arResult['MODULES']['catalog'])
	{
		if (!$boolConvert)
			$strBaseCurrency = CCurrency::GetBaseCurrency();

		$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);

		$boolSKU = !empty($arSKU) && is_array($arSKU);
		if ($boolSKU && !empty($arParams['OFFER_TREE_PROPS']) && 'TYPE_1' == $arParams['TYPE_SKU'])
		{
			$arSKUPropList = CIBlockPriceTools::getTreeProperties(
				$arSKU,
				$arParams['OFFER_TREE_PROPS'],
				array(
					//'PICT' => $arEmptyPreview,
					'NAME' => '-'
				)
			);
			$arResult["SKU_IBLOCK_ID"]=$arSKU["IBLOCK_ID"];

			$arNeedValues = array();
			foreach($arResult['ITEMS'] as $arItem){
				if($arItem['OFFERS']){
					foreach($arItem['OFFERS'] as &$arOffer){
						foreach($arSKUPropList as &$arProperty){
							if(isset($arOffer['DISPLAY_PROPERTIES'][$arProperty['CODE']])){
								if(!isset($arNeedValues[$arProperty['ID']]))
									$arNeedValues[$arProperty['ID']] = array();
								$valueId = (
									$arProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST
									? $arOffer['DISPLAY_PROPERTIES'][$arProperty['CODE']]['VALUE_ENUM_ID']
									: $arOffer['DISPLAY_PROPERTIES'][$arProperty['CODE']]['VALUE']
								);
								$arNeedValues[$arProperty['ID']][$valueId] = $valueId;
								unset($valueId);
							}
						}
						unset($arProperty);
					}
					unset($arOffer);
				}
			}
			if(is_array($arNeedValues) && count($arNeedValues)>0){
				CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);
				$arSKUPropIDs = array_keys($arSKUPropList);
			}

			if (empty($arSKUPropIDs))
				$arParams['TYPE_SKU'] = 'N';
			else
				$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);
		}
	}

	$arNewItemsList = array();
	$firstSkuOption = \CNext::GetFrontParametrValue("SHOW_FIRST_SKU_PICTURE") == "Y";
	foreach ($arResult['ITEMS'] as $key => $arItem)
	{

		if(is_array($arItem['PROPERTIES']['CML2_ARTICLE']['VALUE']))
		{
			$arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] = reset($arItem['PROPERTIES']['CML2_ARTICLE']['VALUE']);
			$arResult['ITEMS'][$key]['PROPERTIES']['CML2_ARTICLE']['VALUE'] = $arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'];
			if($arItem['DISPLAY_PROPERTIES']['CML2_ARTICLE'])
			{
				$arItem['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE'] = reset($arItem['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE']);
				$arResult['ITEMS'][$key]['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE'] = $arItem['DISPLAY_PROPERTIES']['CML2_ARTICLE']['VALUE'];
			}
		}

		$arItem['CHECK_QUANTITY'] = false;
		if (!isset($arItem['CATALOG_MEASURE_RATIO']))
			$arItem['CATALOG_MEASURE_RATIO'] = 1;
		if (!isset($arItem['CATALOG_QUANTITY']))
			$arItem['CATALOG_QUANTITY'] = 0;
		$arItem['CATALOG_QUANTITY'] = (
			0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
			? floatval($arItem['CATALOG_QUANTITY'])
			: intval($arItem['CATALOG_QUANTITY'])
		);
		$arItem['CATALOG'] = false;
		if (!isset($arItem['CATALOG_SUBSCRIPTION']) || 'Y' != $arItem['CATALOG_SUBSCRIPTION'])
			$arItem['CATALOG_SUBSCRIPTION'] = 'N';

		if(($arItem['DETAIL_PICTURE'] && $arItem['PREVIEW_PICTURE']) || (!$arItem['DETAIL_PICTURE'] && $arItem['PREVIEW_PICTURE']))
			$arItem['DETAIL_PICTURE'] = $arItem['PREVIEW_PICTURE'];

		$arItem['GALLERY'] = CNext::getSliderForItemExt($arItem, $arParams['ADD_PICT_PROP'], 'Y' == $arParams['ADD_DETAIL_TO_GALLERY_IN_LIST']);
		array_splice($arItem['GALLERY'], $arParams['MAX_GALLERY_ITEMS']);

		if ($arResult['MODULES']['catalog'])
		{
			$arItem['CATALOG'] = true;
			if (!isset($arItem['CATALOG_TYPE']))
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
			if (
				(CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
				&& !empty($arItem['OFFERS'])
			)
			{
				$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
			}
			switch ($arItem['CATALOG_TYPE'])
			{
				case CCatalogProduct::TYPE_SET:
					$arItem['OFFERS'] = array();
					$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
					break;
				case CCatalogProduct::TYPE_SKU:
					break;
				case CCatalogProduct::TYPE_PRODUCT:
				default:
					$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
					break;
			}
		}
		else
		{
			$arItem['CATALOG_TYPE'] = 0;
			$arItem['OFFERS'] = array();
		}

		if(isset($arItem["PROPERTIES"]) && $arItem["PROPERTIES"]){
			$arTmpProps=array();
			if(isset($arItem["PROPERTIES"]["HIT"]))
				$arTmpProps["HIT"]=$arItem["PROPERTIES"]["HIT"];
			if(isset($arItem["PROPERTIES"][$arParams["SALE_STIKER"]]))
				$arTmpProps[$arParams["SALE_STIKER"]]=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]];
			if(isset($arItem["PROPERTIES"]["EXTENDED_REVIEWS_COUNT"]))
				$arTmpProps["EXTENDED_REVIEWS_COUNT"]=$arItem["PROPERTIES"]["EXTENDED_REVIEWS_COUNT"];
			if(isset($arItem["PROPERTIES"]["EXTENDED_REVIEWS_RAITING"]))
				$arTmpProps["EXTENDED_REVIEWS_RAITING"]=$arItem["PROPERTIES"]["EXTENDED_REVIEWS_RAITING"];

			unset($arItem["PROPERTIES"]);
			$arItem["PROPERTIES"]=$arTmpProps;
			unset($arTmpProps);
		}


		if ($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
		{
			$arMatrixFields = $arSKUPropKeys;
			$arMatrix = array();

			$arNewOffers = array();
			$boolSKUDisplayProperties = false;
			$arItem['OFFERS_PROP'] = false;

			$arDouble = array();
			foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
			{
				$arOffer['ID'] = intval($arOffer['ID']);
				if (isset($arDouble[$arOffer['ID']]))
					continue;
				$arRow = array();
				foreach ($arSKUPropIDs as $propkey => $strOneCode)
				{
					$arCell = array(
						'VALUE' => 0,
						'SORT' => PHP_INT_MAX,
						'NA' => true
					);
					if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
					{
						$arMatrixFields[$strOneCode] = true;
						$arCell['NA'] = false;
						if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE'])
						{
							$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
							$arCell['VALUE'] = $intValue;
						}
						elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
						{
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
						}
						elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
						{
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
						}
						$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
					}
					$arRow[$strOneCode] = $arCell;
				}
				$arMatrix[$keyOffer] = $arRow;

				CIBlockPriceTools::clearProperties($arOffer['DISPLAY_PROPERTIES'], $arParams['OFFER_TREE_PROPS']);

				CIBlockPriceTools::setRatioMinPrice($arOffer, false);

				$offerPictures = CIBlockPriceTools::getDoublePicturesForItem($arOffer, $arParams['OFFER_ADD_PICT_PROP']);

				$arOffer['OWNER_PICT'] = empty($offerPictures['PICT']);
				$arOffer['PREVIEW_PICTURE_FIELD'] = $arOffer['PREVIEW_PICTURE'];
				$arOffer['PREVIEW_PICTURE'] = false;
				$arOffer['PREVIEW_PICTURE_SECOND'] = false;
				$arOffer['SECOND_PICT'] = true;
				if (!$arOffer['OWNER_PICT'])
				{
					if (empty($offerPictures['SECOND_PICT']))
						$offerPictures['SECOND_PICT'] = $offerPictures['PICT'];
					$arOffer['PREVIEW_PICTURE'] = $offerPictures['PICT'];
					$arOffer['PREVIEW_PICTURE_SECOND'] = $offerPictures['SECOND_PICT'];
				}

				if ('' != $arParams['OFFER_ADD_PICT_PROP'] && isset($arOffer['DISPLAY_PROPERTIES'][$arParams['OFFER_ADD_PICT_PROP']]))
					unset($arOffer['DISPLAY_PROPERTIES'][$arParams['OFFER_ADD_PICT_PROP']]);

				if($arParams["USE_MAIN_ELEMENT_SECTION"] != "Y")
				{
					if($arOffer["DETAIL_PAGE_URL"])
					{
						$arTmpUrl = explode("?", $arOffer["DETAIL_PAGE_URL"]);
						$arOffer["DETAIL_PAGE_URL"] = str_replace($arTmpUrl[0], $arItem["DETAIL_PAGE_URL"], $arOffer["DETAIL_PAGE_URL"]);
					}
				}

				$arDouble[$arOffer['ID']] = true;
				$arNewOffers[$keyOffer] = $arOffer;

			}


			$arItem['OFFERS'] = $arNewOffers;

			$arUsedFields = array();
			$arSortFields = array();

			$arPropSKU = $arItem['OFFERS_PROPS_JS'] = array();

			foreach ($arSKUPropIDs as $propkey => $strOneCode)
			{
				$boolExist = $arMatrixFields[$strOneCode];
				foreach ($arMatrix as $keyOffer => $arRow)
				{
					if ($boolExist)
					{
						if (!isset($arItem['OFFERS'][$keyOffer]['TREE']))
							$arItem['OFFERS'][$keyOffer]['TREE'] = array();
						$arItem['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
						$arItem['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
						$arUsedFields[$strOneCode] = true;
						$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;

						$arPropSKU[$strOneCode][$arMatrix[$keyOffer][$strOneCode]["VALUE"]] = $arSKUPropList[$strOneCode]["VALUES"][$arMatrix[$keyOffer][$strOneCode]["VALUE"]];
					}
					else
					{
						unset($arMatrix[$keyOffer][$strOneCode]);
					}
				}
				if($arPropSKU[$strOneCode])
				{
					// sort sku prop values
					Collection::sortByColumn($arPropSKU[$strOneCode], array("SORT" => array(SORT_NUMERIC, SORT_ASC), "NAME" => array(SORT_STRING, SORT_ASC)));
					$arItem['OFFERS_PROPS_JS'][$strOneCode] = array(
						"ID" => $arSKUPropList[$strOneCode]["ID"],
						"CODE" => $arSKUPropList[$strOneCode]["CODE"],
						"NAME" => $arSKUPropList[$strOneCode]["NAME"],
						"SORT" => $arSKUPropList[$strOneCode]["SORT"],
						"PROPERTY_TYPE" => $arSKUPropList[$strOneCode]["PROPERTY_TYPE"],
						"USER_TYPE" => $arSKUPropList[$strOneCode]["USER_TYPE"],
						"LINK_IBLOCK_ID" => $arSKUPropList[$strOneCode]["LINK_IBLOCK_ID"],
						"SHOW_MODE" => $arSKUPropList[$strOneCode]["SHOW_MODE"],
						"VALUES" => $arPropSKU[$strOneCode]
					);
				}
			}
			$arItem['OFFERS_PROP'] = $arUsedFields;
			// $arItem['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');
			$arItem['OFFERS_PROP_CODES'] = (!empty($arParams["OFFERS_CART_PROPERTIES"]) ? base64_encode(serialize(array_keys($arParams["OFFERS_CART_PROPERTIES"]))) : '');

			Collection::sortByColumn($arItem['OFFERS'], $arSortFields);

			$arMatrix = array();
			$intSelected = -1;
			$arItem['MIN_PRICE'] = false;
			$arItem['MIN_BASIS_PRICE'] = false;

			if(!$arItem['OFFERS_PROP'])
			{
				//set min price when USE_PRICE_COUNT
				if($arParams['USE_PRICE_COUNT'] == 'Y')
				{
					$arPriceTypeID = array();
					foreach($arItem['OFFERS'] as $keyOffer => $arOffer)
					{
						//format prices when USE_PRICE_COUNT
						if(function_exists('CatalogGetPriceTableEx') && (isset($arOffer['PRICE_MATRIX'])) && !$arOffer['PRICE_MATRIX'])
						{
							if($arOffer['PRICES'])
							{
								foreach($arOffer['PRICES'] as $priceKey => $arOfferPrice)
								{
									if($arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']])
									{
										$arPriceTypeID[] = $arOfferPrice['PRICE_ID'];
										$arOffer['PRICES'][$priceKey]['GROUP_NAME'] = $arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']];
									}
								}
							}
							$arOffer["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);
							if(count($arOffer['PRICE_MATRIX']['ROWS']) <= 1)
							{
								$arOffer['PRICE_MATRIX'] = '';
							}
						}
						$arItem['OFFERS'][$keyOffer] = array_merge($arOffer, CNext::formatPriceMatrix($arOffer));
					}
				}
				$arItem['MIN_PRICE'] = CNext::getMinPriceFromOffersExt(
					$arItem['OFFERS'],
					$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
				);
			}
			else
			{
				foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
				{
					if ($arItem['OFFER_ID_SELECTED'] > 0){
						$foundOffer = ($arItem['OFFER_ID_SELECTED'] == $arOffer['ID']);
					}
					else{
						$foundOffer = $arOffer['CAN_BUY'];
					}

					if ($foundOffer && $intSelected == -1){
						$intSelected = $keyOffer;
					}
					unset($foundOffer);

					$arSKUProps = $arSKUArticle = false;
					if (!empty($arOffer['DISPLAY_PROPERTIES']))
					{
						$boolSKUDisplayProperties = true;
						$arSKUProps = array();
						foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp)
						{
							if ('F' == $arOneProp['PROPERTY_TYPE'])
								continue;
							$arSKUProps[] = array(
								'NAME' => $arOneProp['NAME'],
								'VALUE' => $arOneProp['DISPLAY_VALUE'],
								'CODE' => $arOneProp['CODE'],
							);
						}
						unset($arOneProp);
					}

					$totalCount = CNext::GetTotalCount($arOffer, $arParams);
					$arOffer['IS_OFFER'] = 'Y';
					$arOffer['IBLOCK_ID'] = $arResult['IBLOCK_ID'];

					$arPriceTypeID = array();
					if($arOffer['PRICES'])
					{
						foreach($arOffer['PRICES'] as $priceKey => $arOfferPrice)
						{
							if($arOfferPrice['CAN_BUY'] == 'Y')
								$arPriceTypeID[] = $arOfferPrice['PRICE_ID'];
							if($arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']])
								$arOffer['PRICES'][$priceKey]['GROUP_NAME'] = $arOffer['CATALOG_GROUP_NAME_'.$arOfferPrice['PRICE_ID']];
						}
					}

					//format offer prices when USE_PRICE_COUNT
					$sPriceMatrix = '';
					if($arParams['USE_PRICE_COUNT'] == 'Y')
					{
						if(function_exists('CatalogGetPriceTableEx') && (isset($arOffer['PRICE_MATRIX'])) && !$arOffer['PRICE_MATRIX'] && $arPriceTypeID)
						{
							if(
								$intSelected == $keyOffer ||
								(
									$keyOffer == 0 &&
									!$arItem['OFFERS_SELECTED']
								)
							) {

								$arOffer["PRICE_MATRIX"] = CatalogGetPriceTableEx($arOffer["ID"], 0, $arPriceTypeID, 'Y', $arConvertParams);
								if(count($arOffer['PRICE_MATRIX']['ROWS']) <= 1)
								{
									$arOffer['PRICE_MATRIX'] = '';
								}
							}
						}
						$arOffer = array_merge($arOffer, CNext::formatPriceMatrix($arOffer));
						$sPriceMatrix = CNext::showPriceMatrix($arOffer, $arParams, $arOffer['~CATALOG_MEASURE_NAME']);
					}

					$arAddToBasketData = CNext::GetAddToBasketArray($arOffer, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small read_more1', $arParams);
					$arAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.$arOfferProps.'" data-item', $arAddToBasketData["HTML"]);

					$arOneRow = array(
						'ID' => $arOffer['ID'],
						'NAME' => $arOffer['~NAME'],
						'TREE' => $arOffer['TREE'],
						'DISPLAY_PROPERTIES' => $arSKUProps,
						'ARTICLE' => $arSKUArticle,
						// 'PRICE' => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
						'PRICE' => $arOffer['MIN_PRICE'],
						'SHOW_DISCOUNT_TIME_EACH_SKU' => $arParams['SHOW_DISCOUNT_TIME_EACH_SKU'],
						'PRICES' => $arOffer['PRICES'],
						'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
						'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
						'SHOW_ARTICLE_SKU' => $arParams['SHOW_ARTICLE_SKU'],
						'ARTICLE_SKU' => ($arParams['SHOW_ARTICLE_SKU'] == 'Y' ? (isset($arItem['PROPERTIES']['CML2_ARTICLE']['VALUE']) && $arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] ? $arItem['PROPERTIES']['CML2_ARTICLE']['NAME'].': '.$arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] : '') : ''),
						'PRICE_MATRIX' => $sPriceMatrix,
						'PRICE_MATRIX_RAW' => $arOffer["PRICE_MATRIX"],
						'BASIS_PRICE' => $arOffer['MIN_PRICE'],
						'OWNER_PICT' => $arOffer['OWNER_PICT'],
						'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE'],
						'PREVIEW_PICTURE_SECOND' => $arOffer['PREVIEW_PICTURE_SECOND'],
						'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
						'MAX_QUANTITY' => $totalCount,
						'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
						'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
						'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
						'CAN_BUY' => ($arAddToBasketData['CAN_BUY'] ? 'Y' : $arOffer['CAN_BUY']),
						'CATALOG_SUBSCRIBE' => $arOffer['CATALOG_SUBSCRIBE'],
						'AVAILIABLE' => CNext::GetQuantityArray($totalCount),
						'URL' => $arOffer['DETAIL_PAGE_URL'],
						'SHOW_MEASURE' => ($arParams["SHOW_MEASURE"]=="Y" ? "Y" : "N"),
						'SHOW_ONE_CLICK_BUY' => "N",
						'ONE_CLICK_BUY' => GetMessage("ONE_CLICK_BUY"),
						'OFFER_PROPS' => $arOfferProps,
						'NO_PHOTO' => $arEmptyPreview,
						'CONFIG' => $arAddToBasketData,
						'HTML' => $arAddToBasketData["HTML"],
						'PRODUCT_QUANTITY_VARIABLE' => $arParams["PRODUCT_QUANTITY_VARIABLE"],
						'SUBSCRIPTION' => true,
						'ITEM_PRICE_MODE' => $arOffer['ITEM_PRICE_MODE'],
						'ITEM_PRICES' => $arOffer['ITEM_PRICES'],
						'ITEM_PRICE_SELECTED' => $arOffer['ITEM_PRICE_SELECTED'],
						'ITEM_QUANTITY_RANGES' => $arOffer['ITEM_QUANTITY_RANGES'],
						'ITEM_QUANTITY_RANGE_SELECTED' => $arOffer['ITEM_QUANTITY_RANGE_SELECTED'],
						'ITEM_MEASURE_RATIOS' => $arOffer['ITEM_MEASURE_RATIOS'],
						'ITEM_MEASURE_RATIO_SELECTED' => $arOffer['ITEM_MEASURE_RATIO_SELECTED'],
					);

					if($arOneRow["PRICE"]["DISCOUNT_DIFF"]){
						$percent=round(($arOneRow["PRICE"]["DISCOUNT_DIFF"]/$arOneRow["PRICE"]["VALUE"])*100, 2);
						$arOneRow["PRICE"]["DISCOUNT_DIFF_PERCENT_RAW"]="-".$percent."%";
					}
					$arMatrix[$keyOffer] = $arOneRow;

					if(($arOffer['DETAIL_PICTURE'] && $arOffer['PREVIEW_PICTURE']) || (!$arOffer['DETAIL_PICTURE'] && $arOffer['PREVIEW_PICTURE']))
						$arOffer['DETAIL_PICTURE'] = $arOffer['PREVIEW_PICTURE'];

					if ($arParams['GALLERY_ITEM_SHOW'] == 'Y') {
						$arItem['OFFERS'][$keyOffer]['GALLERY'] = CNext::getSliderForItemExt($arOffer, $arParams['OFFER_ADD_PICT_PROP'], true);

						if ($arItem['GALLERY']) {
							$arItem['OFFERS'][$keyOffer]['GALLERY'] = array_merge($arItem['OFFERS'][$keyOffer]['GALLERY'], $arItem['GALLERY']);
						}
						if ($arItem['OFFERS'][$keyOffer]['GALLERY']) {
							array_splice($arItem['OFFERS'][$keyOffer]['GALLERY'], $arParams['MAX_GALLERY_ITEMS']);
							array_splice($arItem['GALLERY'], $arParams['MAX_GALLERY_ITEMS']);
						}
					}
				}

				if (-1 == $intSelected){
					$intSelected = 0;
				}

				if (!$arMatrix[$intSelected]['OWNER_PICT']){
					$arItem['PREVIEW_PICTURE'] = $arMatrix[$intSelected]['PREVIEW_PICTURE'];
					$arItem['PREVIEW_PICTURE_SECOND'] = $arMatrix[$intSelected]['PREVIEW_PICTURE_SECOND'];
				}

				$arItem['JS_OFFERS'] = $arMatrix;
				$arItem['OFFERS_SELECTED'] = $intSelected;
				$arItem['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;
			}

			$arFirstSkuPicture = array();
			$bNeedFindPicture = (($arParams['GALLERY_ITEM_SHOW'] == 'Y') && empty($arItem['GALLERY']) && $firstSkuOption );
			if( $bNeedFindPicture ){
				foreach ($arItem['OFFERS'] as $keyOffer => $arOffer)
				{
					if(($arOffer['DETAIL_PICTURE'] && $arOffer['PREVIEW_PICTURE']) || (!$arOffer['DETAIL_PICTURE'] && $arOffer['PREVIEW_PICTURE']))
						$arOffer['DETAIL_PICTURE'] = $arOffer['PREVIEW_PICTURE'];

					$arFirstSkuPicture = CNext::getSliderForItemExt($arOffer, '', true);
					if(!empty( $arFirstSkuPicture )){
						$arItem['GALLERY'] = $arFirstSkuPicture;
						break;
					}
				}
			}

			// get MIN_PRICE
			$arItem['MIN_PRICE'] = CNext::getMinPriceFromOffersExt(
				$arItem['OFFERS'],
				$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
			);

			// get MIN_PRICE_ID
			$minItemPriceID = 0;
			$minItemPrice = 0;
			$minItemPriceFormat = "";
			foreach ($arItem['OFFERS'] as $keyOffer => $arOffer){
				if($arOffer["MIN_PRICE"]["CAN_ACCESS"]){
					if($arOffer["MIN_PRICE"]["DISCOUNT_VALUE"] < $arOffer["MIN_PRICE"]["VALUE"]){
						$minOfferPrice = $arOffer["MIN_PRICE"]["DISCOUNT_VALUE"];
						$minOfferPriceFormat = $arOffer["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"];
						$minOfferPriceID = $arOffer["MIN_PRICE"]["PRICE_ID"];
					}
					else{
						$minOfferPrice = $arOffer["MIN_PRICE"]["VALUE"];
						$minOfferPriceFormat = $arOffer["MIN_PRICE"]["PRINT_VALUE"];
						$minOfferPriceID = $arOffer["MIN_PRICE"]["PRICE_ID"];
					}

					if($minItemPrice > 0 && $minOfferPrice < $minItemPrice){
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
						$minItemPriceID = $minOfferPriceID;
						$minItemID = $arOffer["ID"];
					}
					elseif($minItemPrice == 0){
						$minItemPrice = $minOfferPrice;
						$minItemPriceFormat = $minOfferPriceFormat;
						$minItemPriceID = $minOfferPriceID;
						$minItemID = $arOffer["ID"];
					}
				}
			}
			$arItem['MIN_PRICE']["MIN_PRICE_ID"]=$minItemPriceID;
			$arItem['MIN_PRICE']["MIN_ITEM_ID"]=$minItemID;

			if($arParams["SET_SKU_TITLE"] === "Y"){
				if(isset($arItem["OFFERS_SELECTED"])){
					$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem['OFFERS'][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'], $arItem['OFFERS'][$arItem["OFFERS_SELECTED"]]['ID']);
					$arItem['OFFERS'][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES'] = $ipropValues->getValues();
				}
			}
		}

		//set min price when USE_PRICE_COUNT
		if($arParams['USE_PRICE_COUNT'] == 'Y' && !$arItem['OFFERS'])
		{
			$arItem["FIX_PRICE_MATRIX"] = CNext::checkPriceRangeExt($arItem);
		}

		if (
			$arResult['MODULES']['catalog']
			&& $arItem['CATALOG']
			&&
				($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
				|| $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
		)
		{
			CIBlockPriceTools::setRatioMinPrice($arItem, false);
			$arItem['MIN_BASIS_PRICE'] = $arItem['MIN_PRICE'];
		}

		if (!empty($arItem['DISPLAY_PROPERTIES']))
		{
			foreach ($arItem['DISPLAY_PROPERTIES'] as $propKey => $arDispProp)
			{
				if ('F' == $arDispProp['PROPERTY_TYPE'])
					unset($arItem['DISPLAY_PROPERTIES'][$propKey]);

			}
		}

		//format prices when USE_PRICE_COUNT
		$arItem = array_merge($arItem, CNext::formatPriceMatrix($arItem));

		$arItem['ARTICLE']=false;
		if (!empty($arItem['DISPLAY_PROPERTIES']))
		{
			foreach ($arItem['DISPLAY_PROPERTIES'] as $propKey => $arDispProp)
			{
				if($propKey=="CML2_ARTICLE"){
					$arItem['ARTICLE']=$arDispProp;
				}
				if ('F' == $arDispProp['PROPERTY_TYPE'])
					unset($arItem['DISPLAY_PROPERTIES'][$propKey]);
			}
		}
		$arItem['LAST_ELEMENT'] = 'N';

		if($arParams['IBINHERIT_TEMPLATES']){
			\Aspro\Next\Property\IBInherited::modifyItemTemplates($arParams, $arItem);
		}

		$arNewItemsList[$key] = $arItem;
	}

	$arNewItemsList[$key]['LAST_ELEMENT'] = 'Y';
	$arResult['ITEMS'] = $arNewItemsList;
	unset($arNewItemsList);

	if($arSKUPropList)
	{
		foreach($arSKUPropList as $prop => $arProps)
		{
			unset($arSKUPropList[$prop]["USER_TYPE_SETTINGS"]);
			unset($arSKUPropList[$prop]["VALUES"]);
		}

	}

	$arResult['SKU_PROPS'] = $arSKUPropList;
	unset($arSKUPropList);

	$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

	$arResult['CURRENCIES'] = array();
	if ($arResult['MODULES']['currency'])
	{
		if ($boolConvert)
		{
			$currencyFormat = CCurrencyLang::GetFormatDescription($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
			$arResult['CURRENCIES'] = array(
				array(
					'CURRENCY' => $arResult['CONVERT_CURRENCY']['CURRENCY_ID'],
					'FORMAT' => array(
						'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
						'DEC_POINT' => $currencyFormat['DEC_POINT'],
						'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
						'DECIMALS' => $currencyFormat['DECIMALS'],
						'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
						'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
					)
				)
			);
			unset($currencyFormat);
		}
		else
		{
			$currencyIterator = CurrencyTable::getList(array(
				'select' => array('CURRENCY')
			));
			while ($currency = $currencyIterator->fetch())
			{
				$currencyFormat = CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
				$arResult['CURRENCIES'][] = array(
					'CURRENCY' => $currency['CURRENCY'],
					'FORMAT' => array(
						'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
						'DEC_POINT' => $currencyFormat['DEC_POINT'],
						'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
						'DECIMALS' => $currencyFormat['DECIMALS'],
						'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
						'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
					)
				);
			}
			unset($currencyFormat, $currency, $currencyIterator);
		}
	}
}?>