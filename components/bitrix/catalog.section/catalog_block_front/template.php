<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<? $this->setFrameMode(true); ?>
<? use \Bitrix\Main\Localization\Loc,
\Bitrix\Main\Web\Json; ?>
<? if (count($arResult["ITEMS"]) >= 1) { ?>

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
	//echo "<pre>";print_r($arResult["ITEMS"]);echo "</pre>";
	?>
	<? if (!isset($arParams["AJAX_REQUEST"])) { ?>
		<div class="top_wrapper items_wrapper">
			<div class="fast_view_params" data-params="<?= urlencode(serialize($arTransferParams)); ?>"></div>
			<div class="catalog_block items row margin0 flexbox ajax_load block">
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

				<div class="col-m-20 col-lg-<?= $col; ?> col-md-4 col-sm-<?= floor(12 / round($arParams['LINE_ELEMENT_COUNT'] / 2)) ?> item item_block js-notice-block"
					data-col="<?= $col; ?>">
					<div class="catalog_item_wrapp">
						<div class="basket_props_block" id="bx_basket_div_<?= $arItem["ID"]; ?>_<?= $arParams["FILTER_HIT_PROP"] ?>"
							style="display: none;">
							<? if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
								foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) { ?>
									<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
										value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
									<? if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
										unset($arItem['PRODUCT_PROPERTIES'][$propID]);
								}
							}
							$arItem["EMPTY_PROPS_JS"] = "Y";
							$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
							if (!$emptyProductProperties) {
								$arItem["EMPTY_PROPS_JS"] = "N"; ?>
								<div class="wrapper">
									<table>
										<? foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo) { ?>
											<tr>
												<td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
												<td>
													<? if ('L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']) {
														foreach ($propInfo['VALUES'] as $valueID => $value) { ?>
															<label>
																<input type="radio"
																	name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
																	value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
															</label>
														<? }
													} else { ?>
														<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]">
															<?
															foreach ($propInfo['VALUES'] as $valueID => $value) { ?>
																<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
															<? } ?>
														</select>
													<? } ?>
												</td>
											</tr>
										<? } ?>
									</table>
								</div>
								<?
							} ?>
						</div>
						<?
						// stickers
						$arParams["STIKERS_PROP"] = $arParams["STIKERS_PROP"] ?: 'HIT';
						$bShowHitStickers = $arParams["STIKERS_PROP"] && isset($arItem['DISPLAY_PROPERTIES'][$arParams["STIKERS_PROP"]]) && $arItem["DISPLAY_PROPERTIES"][$arParams["STIKERS_PROP"]]["VALUE"];
						$bShowSaleStickers = $arParams["SALE_STIKER"] && isset($arItem['DISPLAY_PROPERTIES'][$arParams["SALE_STIKER"]]) && $arItem['DISPLAY_PROPERTIES'][$arParams["SALE_STIKER"]]["VALUE"];
						?>
						<div class="catalog_item item_wrap main_item_wrapper"
							id="<?= $this->GetEditAreaId($arItem['ID']); ?>_<?= $arParams["FILTER_HIT_PROP"] ?>">
							<div class="inner_wrap">
								<div class="image_wrapper_block js-notice-block__image">
									<? if ($bShowHitStickers || $bShowSaleStickers): ?>
										<div class="stickers">
											<? if ($bShowHitStickers): ?>
												<? foreach (CNext::GetItemStickers($arItem["DISPLAY_PROPERTIES"][$arParams["STIKERS_PROP"]]) as $arSticker): ?>
													<div>
														<div class="<?= $arSticker['CLASS'] ?>"><?= $arSticker['VALUE'] ?></div>
													</div>
												<? endforeach; ?>
											<? endif; ?>
											<? if ($bShowSaleStickers): ?>
												<div>
													<div class="sticker_sale_text">
														<?= $arItem["DISPLAY_PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]; ?></div>
												</div>
											<? endif; ?>
										</div>
									<? endif; ?>

									<?/*
														   <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb shine">
															   <?
															   if($arParams["SET_SKU_TITLE"] === "Y" && $arItem['OFFERS']){
																   $a_alt = ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"] && strlen($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $skuName ));
																   $a_title = ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"] && strlen($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $skuName ));
															   }
															   else{
																   $a_alt = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] ));
																   $a_title = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] ));
															   }
															   ?>
															   <?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
																   <img class="noborder" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
															   <?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
																   <?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 170, "height" => 170 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
																   <img class="noborder" src="<?=$img["src"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
															   <?else:?>
																   <img class="noborder" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
															   <?endif;?>
															   <?if($fast_view_text_tmp = CNext::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
																   $fast_view_text = $fast_view_text_tmp;
															   else
																   $fast_view_text = GetMessage('FAST_VIEW');?>
														   </a>
														   <div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-fid="<?=$this->GetEditAreaId($arItem['ID']);?>_<?=$arParams["FILTER_HIT_PROP"]?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" data-name="fast_view"><?=$fast_view_text;?></div>
														   */ ?>
									<? $arParams['EVENT_TYPE'] = 'front_tabs_block_view' ?>
									<? if ($arParams['GALLERY_ITEM_SHOW'] == 'Y'): ?>
										<? if ($bUseSkuProps && $arItem["OFFERS"]): ?>
											<?//print_r($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]); ?>
											<? \Aspro\Functions\CAsproNext::showSectionGallery(array('ITEM' => $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS'])); ?>
										<? else: ?>
											<? \Aspro\Functions\CAsproNext::showSectionGallery(array('ITEM' => $arItem, 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS'])); ?>
										<? endif; ?>
									<? else: ?>
										<? \Aspro\Functions\CAsproNext::showImg($arParams, $arItem); ?>
									<? endif; ?>
								</div>
								<div class="item_info <?= $arParams["TYPE_SKU"] ?>">
									<div class="item_info--top_block">
										<div class="item-title">
											<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
												class="dark_link js-notice-block__title"><span><?= $elementName; ?></span></a>
										</div>
										<? if ($arItem["DISPLAY_PROPERTIES"]["SUB_TITLE"]["VALUE"]): ?>
											<div class="item_info--preview_text muted999">
												<? if (!is_array($arItem["DISPLAY_PROPERTIES"]["SUB_TITLE"]["~VALUE"])) {
													echo $arItem["DISPLAY_PROPERTIES"]["SUB_TITLE"]["VALUE"];
												} else {
													echo $arItem["DISPLAY_PROPERTIES"]["SUB_TITLE"]["~VALUE"]["TEXT"];
												} ?>
											</div>
										<? endif; ?>
										<? if ($arParams["SHOW_RATING"] == "Y"): ?>
											<div class="rating">
												<?//$frame = $this->createFrame('dv_'.$arItem["ID"])->begin(''); ?>
												<? if ($arParams['REVIEWS_VIEW']): ?>
													<? \Aspro\Functions\CAsproNext::showBlockHtml([
														'FILE' => 'catalog/detail_rating_extended.php',
														'PARAMS' => [
															'MESSAGE' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', array('#VALUE#' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'])) : GetMessage('VOTES_RESULT_NONE'),
															'RATING_VALUE' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ?? 0,
															'REVIEW_COUNT' => isset($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']) ? intval($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']) : 0,
														]
													]); ?>
												<? else: ?>
													<? $APPLICATION->IncludeComponent(
														"bitrix:iblock.vote",
														"element_rating_front",
														array(
															"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
															"IBLOCK_ID" => $arItem["IBLOCK_ID"],
															"ELEMENT_ID" => $arItem["ID"],
															"MAX_VOTE" => 5,
															"VOTE_NAMES" => array(),
															"CACHE_TYPE" => $arParams["CACHE_TYPE"],
															"CACHE_TIME" => $arParams["CACHE_TIME"],
															"DISPLAY_AS_RATING" => 'vote_avg'
														),
														$component,
														array("HIDE_ICONS" => "Y")
													); ?>
												<? endif; ?>
												<?//$frame->end(); ?>
											</div>
										<? endif; ?>
										<div class="sa_block" data-stores='<?= Json::encode($arParams["STORES"]) ?>'>
											<?= $arQuantityData["HTML"]; ?>
											<div class="article_block" <? if (isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']): ?>data-name="<?= $arItem['ARTICLE']['NAME']; ?>"
													data-value="<?= $arItem['ARTICLE']['VALUE']; ?>" <? endif; ?>>
												<? if (isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']) { ?>
													<div><?= $arItem['ARTICLE']['NAME']; ?>: <?= $arItem['ARTICLE']['VALUE']; ?></div>
												<? } ?>
											</div>
										</div>
									</div>
									<div class="item_info--bottom_block">
										<div class="cost prices clearfix">
											<? if ($arItem["OFFERS"]) { ?>
												<div class="with_matrix <?= ($arParams["SHOW_OLD_PRICE"] == "Y" ? 'with_old' : ''); ?>"
													style="display:none;">
													<div class="price price_value_block"><span class="values_wrapper"></span></div>
													<? if ($arParams["SHOW_OLD_PRICE"] == "Y"): ?>
														<div class="price discount"></div>
													<? endif; ?>
													<? if ($arParams["SHOW_DISCOUNT_PERCENT"] == "Y") { ?>
														<div class="sale_block matrix" style="display:none;">
															<div class="sale_wrapper">
																<? if ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] != "Y"): ?>
																	<div class="text">
																		<span class="title"><?= GetMessage("CATALOG_ECONOMY"); ?></span>
																		<span class="values_wrapper"></span>
																	</div>
																<? else: ?>
																	<div class="value">-<span></span>%</div>
																	<div class="text">
																		<span class="title"><?= GetMessage("CATALOG_ECONOMY"); ?></span>
																		<span class="values_wrapper"></span>
																	</div>
																<? endif; ?>
																<div class="clearfix"></div>
															</div>
														</div>
													<? } ?>
												</div>
												<? if ($arCurrentSKU): ?>
													<div class="ce_cmp_visible">
														<? \Aspro\Functions\CAsproSku::showItemPrices($arParamsCE_CMP, $arItem, $item_id, $min_price_id, $arItemIDs, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
													</div>
												<? endif; ?>
												<div class="js_price_wrapper price">
													<? if ($arCurrentSKU) { ?>
														<?
														$item_id = $arCurrentSKU["ID"];
														$arCurrentSKU['PRICE_MATRIX'] = $arCurrentSKU['PRICE_MATRIX_RAW'];
														$arCurrentSKU['CATALOG_MEASURE_NAME'] = $arCurrentSKU['MEASURE'];
														if (isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
														{ ?>
															<? if ($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1): ?>
																<?= CNext::showPriceRangeTop($arCurrentSKU, $arParams, GetMessage("CATALOG_ECONOMY")); ?>
															<? endif; ?>
															<?= CNext::showPriceMatrix($arCurrentSKU, $arParams, $strMeasure, $arAddToBasketData); ?>
															<? $arMatrixKey = array_keys($arCurrentSKU['PRICE_MATRIX']['MATRIX']);
															$min_price_id = current($arMatrixKey); ?>
															<?
														} else {
															$arCountPricesCanAccess = 0;
															$min_price_id = 0; ?>
															<? \Aspro\Functions\CAsproItem::showItemPrices($arParams, $arCurrentSKU["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
														<? } ?>
													<? } else { ?>
														<? \Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, array(), ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
													<? } ?>
												</div>
											<? } else { ?>
												<?
												$item_id = $arItem["ID"];
												if (isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
												{ ?>
													<? if ($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1): ?>
														<?= CNext::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY")); ?>
													<? endif; ?>
													<?= CNext::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData); ?>
													<? $arMatrixKey = array_keys($arItem['PRICE_MATRIX']['MATRIX']);
													$min_price_id = current($arMatrixKey); ?>
													<?
												} elseif ($arItem["PRICES"]) {
													$arCountPricesCanAccess = 0;
													$min_price_id = 0; ?>
													<? \Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y")); ?>
												<? } ?>
											<? } ?>
										</div>

										<? if ($arParams["SHOW_DISCOUNT_TIME"] == "Y") { ?>
											<? $arUserGroups = $USER->GetUserGroupArray(); ?>
											<? if ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] != 'Y' || ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] == 'Y' && !$arItem['OFFERS'])): ?>
												<? $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", $min_price_id, SITE_ID);
												$arDiscount = array();
												if ($arDiscounts)
													$arDiscount = current($arDiscounts);
												if ($arDiscount["ACTIVE_TO"]) { ?>
													<div class="view_sale_block <?= ($arQuantityData["HTML"] ? '' : 'wq'); ?>">
														<div class="count_d_block">
															<span class="active_to hidden"><?= $arDiscount["ACTIVE_TO"]; ?></span>
															<div class="title"><?= GetMessage("UNTIL_AKC"); ?></div>
															<span class="countdown values"><span class="item"></span><span
																	class="item"></span><span class="item"></span><span
																	class="item"></span></span>
														</div>
														<? if ($arQuantityData["HTML"]): ?>
															<div class="quantity_block">
																<div class="title"><?= GetMessage("TITLE_QUANTITY_BLOCK"); ?></div>
																<div class="values">
																	<span class="item">
																		<span class="value"><?= $totalCount; ?></span>
																		<span class="text"><?= GetMessage("TITLE_QUANTITY"); ?></span>
																	</span>
																</div>
															</div>
														<? endif; ?>
													</div>
												<? } ?>
											<? else: ?>
												<? $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", array(), SITE_ID);
												$arDiscount = array();
												if ($arDiscounts)
													$arDiscount = current($arDiscounts);
												?>
												<div class="view_sale_block <?= ($arQuantityData["HTML"] ? '' : 'wq'); ?>"
													<?= ($arDiscount["ACTIVE_TO"] ? '' : 'style="display:none;"'); ?>>
													<div class="count_d_block">
														<span
															class="active_to hidden"><?= ($arDiscount["ACTIVE_TO"] ? $arDiscount["ACTIVE_TO"] : ""); ?></span>
														<div class="title"><?= GetMessage("UNTIL_AKC"); ?></div>
														<span class="countdown values"><span class="item"></span><span
																class="item"></span><span class="item"></span><span
																class="item"></span></span>
													</div>
													<? if ($arQuantityData["HTML"]): ?>
														<div class="quantity_block">
															<div class="title"><?= GetMessage("TITLE_QUANTITY_BLOCK"); ?></div>
															<div class="values">
																<span class="item">
																	<span class="value"><?= $totalCount; ?></span>
																	<span class="text"><?= GetMessage("TITLE_QUANTITY"); ?></span>
																</span>
															</div>
														</div>
													<? endif; ?>
												</div>
											<? endif; ?>
										<? } ?>
									</div>
								</div>
								<!-- <div class="footer_button <?= ($arItem["OFFERS"] && $arItem['OFFERS_PROP'] ? 'has_offer_prop' : ''); ?> inner_content js_offers__<?= $arItem['ID']; ?>_<?= $arParams["FILTER_HIT_PROP"] ?>"> -->
								
								<div class="d-relative sku_props mainpage_item_wrapper">
									<? if ($arItem["OFFERS"]) { ?>
										<? if (!empty($arItem['OFFERS_PROP'])) { ?>
											<div class="bx_catalog_item_scu wrapper_sku"
												id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>" data-site_id="<?= SITE_ID; ?>"
												data-id="<?= $arItem["ID"]; ?>"
												data-offer_id="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"]; ?>"
												data-propertyid="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PROPERTIES"]["CML2_LINK"]["ID"]; ?>"
												data-offer_iblockid="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"]; ?>">
												<? $arSkuTemplate = array(); ?>
												<? $arSkuTemplate = CNext::GetSKUPropsArray($arItem['OFFERS_PROPS_JS'], $arResult["SKU_IBLOCK_ID"], $arParams["DISPLAY_TYPE"], $arParams["OFFER_HIDE_NAME_PROPS"], "N", $arItem, $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS']); ?>
												<? foreach ($arSkuTemplate as $code => $strTemplate) {
													if (!isset($arItem['OFFERS_PROP'][$code]))
														continue;
													echo '<div class="item_wrapper">', str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate), '</div>';
												} ?>
											</div>
											<? $arItemJSParams = CNext::GetSKUJSParams($arResult, $arParams, $arItem); ?>
										<? } ?>
									<? } ?>
									<?/*
														<div class="counter_wrapp">
															<div class="button_block">
																<!--noindex-->
																	<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-default basket read_more"><?=\Bitrix\Main\Config\Option::get('aspro.next', "EXPRESSION_READ_MORE_OFFERS_DEFAULT", GetMessage("CATALOG_READ_MORE"));?></a>
																<!--/noindex-->
															</div>
														</div>
														*/ ?>
								<? if (!$arItem["OFFERS"]): ?>
									<? if ($arParams["SHOW_BUY_BTN"] == "Y"): ?>
										<div
											class="counter_wrapp wrapp_ctl <?= ($arItem["OFFERS"] && $arParams["TYPE_SKU"] == "TYPE_1" ? 'woffers' : '') ?>">
											<? if (($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]): ?>
												<div class="counter_block" data-offers="<?= ($arItem["OFFERS"] ? "Y" : "N"); ?>"
													data-item="<?= $arItem["ID"]; ?>">
													<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>"
														<?= isset($arAddToBasketData["SET_MIN_QUANTITY_BUY"]) && $arAddToBasketData["SET_MIN_QUANTITY_BUY"] ? "data-min='" . $arAddToBasketData["MIN_QUANTITY_BUY"] . "'" : ""; ?>>-</span>
													<input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>"
														name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
														value="<?= $arAddToBasketData["MIN_QUANTITY_BUY"] ?>" />
													<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>"
														<?= ($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='" . $arAddToBasketData["MAX_QUANTITY_BUY"] . "'" : "") ?>>+</span>
												</div>
											<? endif; ?>
											<div id="<?= $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>"
												class="button_block <?= (($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/) || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : ""); ?>">
												<!--noindex-->
												<?= $arAddToBasketData["HTML"] ?>
												<!--/noindex-->
											</div>
										</div>
									<? else: ?>
										<div class="counter_wrapp wrapp_ctl">
											<div class="button_block">
												<!--noindex-->
												<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"
													class="btn btn-default basket read_more"><?= \Bitrix\Main\Config\Option::get('aspro.next', "EXPRESSION_READ_MORE_OFFERS_DEFAULT", GetMessage("CATALOG_READ_MORE")); ?></a>
												<!--/noindex-->
											</div>
										</div>
									<? endif; ?>
									<?
									if (isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
									{ ?>
										<? if ($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1): ?>
											<? $arOnlyItemJSParams = array(
												"ITEM_PRICES" => $arItem["ITEM_PRICES"],
												"ITEM_PRICE_MODE" => $arItem["ITEM_PRICE_MODE"],
												"ITEM_QUANTITY_RANGES" => $arItem["ITEM_QUANTITY_RANGES"],
												"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
												"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
												"ID" => $arItemIDs["strMainID"],
											) ?>
											<script type="text/javascript">
												var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
											</script>
										<? endif; ?>
									<? } ?>
								<? elseif ($arItem["OFFERS"]): ?>
									<? if (empty($arItem['OFFERS_PROP'])) { ?>
										<div class="offer_buy_block buys_wrapp woffers">
											<?
											$arItem["OFFERS_MORE"] = "Y";
											$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small read_more1', $arParams); ?>
											<!--noindex-->
											<?= $arAddToBasketData["HTML"] ?>
											<!--/noindex-->
										</div>
									<? } else { ?>
										<div class="offer_buy_block">
											<?
											$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IS_OFFER'] = 'Y';
											$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
											//$arAddToBasketData = CNext::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
											?>
											<div class="counter_wrapp wrapp_ctl">
												<? if (($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]): ?>
													<div class="counter_block"
														data-item="<?= $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"]; ?>">
														<span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>"
															<?= isset($arAddToBasketData["SET_MIN_QUANTITY_BUY"]) && $arAddToBasketData["SET_MIN_QUANTITY_BUY"] ? "data-min='" . $arAddToBasketData["MIN_QUANTITY_BUY"] . "'" : ""; ?>>-</span>
														<input type="text" class="text"
															id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>"
															name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>"
															value="<?= $arAddToBasketData["MIN_QUANTITY_BUY"] ?>" />
														<span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>"
															<?= ($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='" . $arAddToBasketData["MAX_QUANTITY_BUY"] . "'" : "") ?>>+</span>
													</div>
												<? endif; ?>
												<div id="<?= $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>"
													class="button_block <?= (($arAddToBasketData["ACTION"] == "ORDER"/*&& !$arItem["CAN_BUY"]*/) || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_LIST"] || $arAddToBasketData["ACTION"] == "SUBSCRIBE" ? "wide" : ""); ?>">
													<!--noindex-->
													<?= $arAddToBasketData["HTML"] ?>
													<!--/noindex-->
												</div>
											</div>
										</div>
										<?
										if (isset($arCurrentSKU['PRICE_MATRIX']) && $arCurrentSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
										{ ?>
											<? if ($arCurrentSKU['ITEM_PRICE_MODE'] == 'Q' && count($arCurrentSKU['PRICE_MATRIX']['ROWS']) > 1): ?>
												<? $arOnlyItemJSParams = array(
													"ITEM_PRICES" => $arCurrentSKU["ITEM_PRICES"],
													"ITEM_PRICE_MODE" => $arCurrentSKU["ITEM_PRICE_MODE"],
													"ITEM_QUANTITY_RANGES" => $arCurrentSKU["ITEM_QUANTITY_RANGES"],
													"MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
													"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
													"ID" => $arItemIDs["strMainID"],
													"NOT_SHOW" => "Y",
												) ?>
												<script type="text/javascript">
													var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
												</script>
											<? endif; ?>
										<? } ?>
									<? } ?>
									<div class="counter_wrapp wrapp_ctl ce_cmp_visible">
										<div id="<?= $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS'] . "_cmp"; ?>"
											class="button_block wide">
											<a class="btn btn-default basket read_more" rel="nofollow"
												data-item="<?= $arItem['ID'] ?>"
												href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= GetMessage('CATALOG_READ_MORE') ?></a>
										</div>
									</div>
								<? endif; ?>
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