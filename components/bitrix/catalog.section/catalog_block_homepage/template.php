<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<? $this->setFrameMode(true); ?>
<? use \Bitrix\Main\Localization\Loc,
\Bitrix\Main\Web\Json; ?>

<? 
// Подключаем вспомогательный файл для работы с ценами
require_once(__DIR__ . '/price_helper.php');
?>

<? if (count($arResult["ITEMS"]) >= 1) { ?>
	<pre style="display: none;">
		<?
		print_r($arResult["ITEMS"]);
		?>
	</pre>
	<?
	$currencyList = '';
	if (!empty($arResult['CURRENCIES'])) {
		$templateLibrary[] = 'currency';
		$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
	}
	$templateData = array(
		'TEMPLATE_LIBRARY' => $templateLibrary,
		'CURRENCIES' => $currencyList
	);
	unset($currencyList, $templateLibrary);
	?>
	<? if (!isset($arParams["AJAX_REQUEST"])) { ?>
		<div class="top_wrapper items_wrapper">
			<div class="fast_view_params" data-params="<?= urlencode(serialize($arTransferParams)); ?>"></div>
			<div class="catalog_block items row margin0 flexbox ajax_load block owl-carousel" id="item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?>">
			<? } ?>

			<?
			$arOfferProps = implode(';', (array) $arParams['OFFERS_CART_PROPERTIES']);

			// params for catalog elements compact view
			$arParamsCE_CMP = $arParams;
			$arParamsCE_CMP['TYPE_SKU'] = 'N';
			?>
			<? foreach ($arResult["ITEMS"] as $arItem) { ?>
				<? $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

				$item_id = $arItem["ID"];
				$strMeasure = '';

				// Получаем базовую цену товара с помощью CPrice::GetBasePrice
				$basePrice = CPrice::GetBasePrice($item_id);
				
				$totalCount = CNext::GetTotalCount($arItem, $arParams);
				$arQuantityData = CNext::GetQuantityArray($totalCount, array('ID' => $item_id), "N", $arItem["PRODUCT"]["TYPE"], (($arItem["OFFERS"] || $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || !$arResult['STORES_COUNT']) ? false : true));

				$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID']) . "_" . $arParams["FILTER_HIT_PROP"];
				$arItemIDs = CNext::GetItemsIDs($arItem);

				if ($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]) {
					if (isset($arItem["ITEM_MEASURE"]) && (is_array($arItem["ITEM_MEASURE"]) && $arItem["ITEM_MEASURE"]["TITLE"])) {
						$strMeasure = $arItem["ITEM_MEASURE"]["TITLE"];
					} else {
						$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
						$strMeasure = $arMeasure["SYMBOL_RUS"];
					}
				}
				$bUseSkuProps = ($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP']));

				$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

				if ($bUseSkuProps) {
					if (!$arItem["OFFERS"]) {
						$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
					} elseif ($arItem["OFFERS"]) {

						$currentSKUIBlock = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];
						$currentSKUID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];

						$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
						$totalCount = CNext::GetTotalCount($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $arParams);
						$arQuantityData = CNext::GetQuantityArray($totalCount, array('ID' => $currentSKUID), "N", $arItem["PRODUCT"]["TYPE"], (($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || !$arResult['STORES_COUNT']) ? false : true));


						$arItem["DETAIL_PAGE_URL"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PAGE_URL"];
						if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
							$arItem["PREVIEW_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"];
						if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
							$arItem["DETAIL_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PICTURE"];

						if ($arParams["SET_SKU_TITLE"] === "Y") {
							$skuName = ((isset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['NAME']);
							$arItem["NAME"] = $elementName = $skuName;
						}

						$item_id = $currentSKUID;

						// ARTICLE
						if ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) {
							$arItem["ARTICLE"]["NAME"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["NAME"];
							$arItem["ARTICLE"]["VALUE"] = (is_array($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) ? reset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]);
						}

						$arCurrentSKU = $arItem["JS_OFFERS"][$arItem["OFFERS_SELECTED"]];
						$strMeasure = $arCurrentSKU["MEASURE"];

						$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IS_OFFER'] = 'Y';
						$offerIblockID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'];
						$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'] = $arParams['IBLOCK_ID'];//fix add props to basket
						$arAddToBasketData = CNext::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
						$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'] = $offerIblockID;
					}
				} else {
					$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, array(), 'small', $arParams);
				}
				switch ($arParams["LINE_ELEMENT_COUNT"]) {
					case '2':
						$col = 6;
						break;
					case '4':
						$col = 3;
						break;
					default:
						$col = 4;
						break;
				}
				?>

				<div class="item item_block js-notice-block" data-col="<?= $col; ?>">
					<div class="catalog_item_wrapp">
						<div class="catalog_item item_wrap" id="<?= $this->GetEditAreaId($arItem['ID']); ?>_<?= $arParams["FILTER_HIT_PROP"] ?>">
							<div class="inner_wrap">
								<div class="image_wrapper_block">
									<div class="stickers">
										<?
										// Проверяем через DISPLAY_PROPERTIES и поддерживаем множественные значения
										if(isset($arItem["DISPLAY_PROPERTIES"]["HIT"]) && !empty($arItem["DISPLAY_PROPERTIES"]["HIT"]["VALUE_XML_ID"])) {
											$hitValues = (array)$arItem["DISPLAY_PROPERTIES"]["HIT"]["VALUE_XML_ID"];
											foreach($hitValues as $xmlId) {
												$productType = '';
												$labelClass = '';
												
												switch($xmlId) {
													case "HIT":
														$productType = "Хит продаж";
														$labelClass = "label-hit";
														break;
													case "RECOMMEND":
														$productType = "Рекомендуем";
														$labelClass = "label-recommend";
														break;
													case "NEW":
														$productType = "Новинка";
														$labelClass = "label-new";
														break;
												}
												
												if($productType): ?>
													<div class="section_label <?=$labelClass?>">
														<?=$productType?>
													</div>
												<? endif;
											}
										} ?>
									</div>
									
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="product-image-wrapper">
										<? if($arItem["PREVIEW_PICTURE"]["SRC"]): ?>
											<img class="product-image" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$elementName?>" />
										<? elseif($arItem["DETAIL_PICTURE"]["SRC"]): ?>
											<img class="product-image" src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$elementName?>" />
										<? else: ?>
											<img class="product-image" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$elementName?>" />
										<? endif; ?>
									</a>
								</div>

								<div class="item_info">
									<h3 class="item-title">
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$elementName?></a>
									</h3>

									<div class="price-block">
										<div class="price">
											<?
											// Получаем цену с помощью нашей функции
											$priceData = getProductPrice($item_id);
											
											if (!empty($priceData)) {
												if ($priceData['HAS_DISCOUNT']) {
													?>
													<span class="current-price"><?=$priceData['FORMATTED_DISCOUNT_PRICE']?></span>
													<span class="old-price"><?=$priceData['FORMATTED_PRICE']?></span>
													<?
												} else {
													?>
													<span class="current-price"><?=$priceData['FORMATTED_PRICE']?></span>
													<?
												}
											} elseif(!empty($arItem["MIN_PRICE"]) && $arItem["MIN_PRICE"]["VALUE"] > $arItem["MIN_PRICE"]["DISCOUNT_VALUE"]) {
												?>
												<span class="current-price"><?=$arItem["MIN_PRICE"]["PRINT_DISCOUNT_VALUE"]?></span>
												<span class="old-price"><?=$arItem["MIN_PRICE"]["PRINT_VALUE"]?></span>
												<?
											} elseif(!empty($arItem["MIN_PRICE"])) {
												?>
												<span class="current-price"><?=$arItem["MIN_PRICE"]["PRINT_VALUE"]?></span>
												<?
											} else {
												?>
												<span class="current-price">Цена по запросу</span>
												<?
											}
											?>
										</div>
									</div>

									<div class="buttons-block">
										<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-default">Подробнее</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<? } ?>
			<? if (!isset($arParams["AJAX_REQUEST"])) { ?>
			</div>
		</div>
	<? } ?>

	<? if ($arParams["AJAX_REQUEST"] == "Y") { ?>
		<div class="wrap_nav">
		<? } ?>

		<div class="bottom_nav hidden1" <?= ($arParams["AJAX_REQUEST"] == "Y" ? "style='display: none; '" : ""); ?>>
			<? if ($arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"]) { ?>
				<div class="nav-inner-wrapper" data-page="<?= ($arResult['NAV_RESULT']->PAGEN + 1); ?>">
					<?= $arResult["NAV_STRING"] ?>
				</div>
			<? } ?>
		</div>

		<? if ($arParams["AJAX_REQUEST"] == "Y") { ?>
		</div>
	<? } ?>
<? } ?>

<script>
	$(document).ready(function() {
		var $carousel = $('#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?>');
		
		$carousel.owlCarousel({
			items: 4,
			loop: true,
			margin: 20,
			nav: true,
			dots: false,
			navText: [
				`<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6"/></svg>`,
				`<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6"/></svg>`
			],
			responsive: {
				0: {
					items: 2,
					margin: 10
				},
				576: {
					items: 2,
					margin: 15
				},
				768: {
					items: 3,
					margin: 15
				},
				992: {
					items: 4,
					margin: 20
				}
			}
		});
	});
</script>

<style>
.catalog_item_wrapp {
    background: #fff;
    border-radius: 20px;
    padding: 20px;
    transition: all 0.3s ease;
    height: 100%;
}

.catalog_item_wrapp:hover {
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
}

.inner_wrap {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.image_wrapper_block {
    position: relative;
    margin-bottom: 15px;
    width: 100%;
    height: 250px;
}

.product-image-wrapper {
    display: block;
    width: 100%;
    height: 100%;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
}

.stickers {
    position: absolute;
    top: -25px!important;
    left: 10px;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.section_label {
    padding: 0!important;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    min-width: 100px;
    white-space: nowrap;
}

.label-hit {
    background: #FF4B4B;
    color: #fff;
}

.label-recommend {
    background: #4086F1;
    color: #fff;
}

.label-new {
    background: #27AE60;
    color: #fff;
}

.item_info {
    padding: 0;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.item-title {
    margin: 0 0 15px;
    font-size: 16px;
    font-weight: 500;
    line-height: 1.3;
    flex-grow: 1;
}

.item-title a {
    color: #333;
    text-decoration: none;
}

.price-block {
    margin-bottom: 15px;
}

.price {
    display: flex;
    align-items: baseline;
}

.current-price {
    font-size: 20px;
    font-weight: bold;
    color: #333;
}

.old-price {
    font-size: 14px;
    color: #999;
    text-decoration: line-through;
    margin-left: 8px;
}

.buttons-block {
    margin-top: auto;
}

.btn.btn-default {
    display: block;
    width: 100%;
    padding: 10px;
    text-align: center;
    background: #4086F1;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.btn.btn-default:hover {
    background: #3476e0;
}

/* Стили для карусели */
#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> {
    padding: 0 40px;
    position: relative;
}

#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-nav {
    position: absolute;
    top: 50%;
    width: 100%;
    left: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    pointer-events: none;
    z-index: 1;
}

#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-prev, 
#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-next {
    width: 40px;
    height: 40px;
    background: #fff !important;
    border-radius: 50% !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    display: flex !important;
    align-items: center;
    justify-content: center;
    pointer-events: auto;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}

#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-prev {
    left: -20px;
}

#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-next {
    right: -20px;
}

#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-prev:hover, 
#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-next:hover {
    background: #f5f5f5 !important;
}

#item-carousel_<?=$arResult['ORIGINAL_PARAMETERS']['GLOBAL_FILTER']['PROPERTY_HIT']?> .owl-item {
    padding: 10px;
}

/* Адаптивные стили */
@media (max-width: 768px) {
    .image_wrapper_block {
        height: 200px;
    }

    .item-title {
        font-size: 14px;
    }
    
    .current-price {
        font-size: 16px;
    }
    
    .old-price {
        font-size: 12px;
    }

    .catalog_item_wrapp {
        padding: 15px;
    }
}

@media (max-width: 576px) {
    .image_wrapper_block {
        height: 180px;
    }
}

.catalog_item .image_wrapper_block {
	margin: 0!important;
}
.image_wrapper_block, .image_wrapper_block a {
	height: 170px !important;
}

/* Добавляем стили для фиксированной высоты названия товара */
.catalog_item .item-title {
    height: 40px !important; /* Фиксированная высота для названия */
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2; /* Ограничиваем двумя строками */
    -webkit-box-orient: vertical;
    /* margin-bottom: 10px !important; Одинаковый отступ после названия */
	margin-top: 10px !important;
}

/* Стили для фиксированной высоты карточки в целом */
.catalog_item_wrapp {
    height: auto;
    display: flex;
}

.catalog_item {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

/* Растягиваем блок цены к нижнему краю карточки */
.catalog_item .cost.prices {
    margin-top: auto;
    padding-top: 10px;
}

/* Для мобильных устройств */
@media (max-width: 768px) {
    .catalog_item .item-title {
        height: 36px !important; /* Меньшая высота для мобильных */
        -webkit-line-clamp: 2;
    }
	.stickers {
		left: -20px!important;
		top: 5px!important;
	}
}

@media (max-width: 576px) {
    .catalog_item .item-title {
        font-size: 13px;
    }
	.stickers {
		left: -20px!important;
		top: 5px!important;
	}
}
</style>