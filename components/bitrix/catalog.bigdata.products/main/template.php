<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");
$templateData = array(
	//'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);
$injectId = 'bigdata_recommeded_products_'.rand();?>
<script type="application/javascript">
	BX.cookie_prefix = '<?=CUtil::JSEscape(COption::GetOptionString("main", "cookie_name", "BITRIX_SM"))?>';
	BX.cookie_domain = '<?=$APPLICATION->GetCookieDomain()?>';
	BX.current_server_time = '<?=time()?>';

	BX.ready(function(){
		bx_rcm_recommendation_event_attaching(BX('<?=$injectId?>_items'));
	});
</script>
<?if (isset($arResult['REQUEST_ITEMS'])){
	CJSCore::Init(array('ajax'));
	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');?>

	<span id="<?=$injectId?>" class="bigdata_recommended_products_container"></span>
	<script type="application/javascript">
		BX.ready(function(){
			bx_rcm_get_from_cloud(
				'<?=CUtil::JSEscape($injectId)?>',
				<?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>,
				{
					'parameters':'<?=CUtil::JSEscape($signedParameters)?>',
					'template': '<?=CUtil::JSEscape($signedTemplate)?>',
					'site_id': '<?=CUtil::JSEscape(SITE_ID)?>',
					'rcm': 'yes'
				}
			);
		});
	</script>

	<?$frame->end();
	return;
}
if($arResult['ITEMS']){?>
	<input type="hidden" name="bigdata_recommendation_id" value="<?=htmlspecialcharsbx($arResult['RID'])?>">
	<?$blockViewType = $arParams['BLOCK_VIEW'];?>
	<?if ($blockViewType):?>
		<div id="<?=$injectId?>_items" class="catalog_block items block_list margin0 row flexbox">
	<?else:?>
	<span id="<?=$injectId?>_items" class="bigdata_recommended_products_items flexslider loading_state shadow border custom_flex top_right" data-plugin-options='{"animation": "slide", "animationSpeed": 600, "directionNav": true, "controlNav" :false, "animationLoop": true, "slideshow": false, "controlsContainer": ".tabs_slider_navigation.RECOMENDATION_nav", "counts": [4,3,3,2,1]}'>
		<ul class="tabs_slider RECOMENDATION_slides slides catalog_block">
	<?endif;?>
			<?foreach ($arResult['ITEMS'] as $key => $arItem){?>
				<?$strMainID = $this->GetEditAreaId($arItem['ID'] . $key);?>
				<?if ($blockViewType):?>
					<div class="item_block col-4 col-md-3 col-sm-6 col-xs-6 js-notice-block">
						<div class="catalog_item_wrapp item">
							<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>" style="display: none;">
								<?if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])){
									foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
										<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
										<?if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
											unset($arItem['PRODUCT_PROPERTIES'][$propID]);
									}
								}
								$arItem["EMPTY_PROPS_JS"]="Y";
								$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
								if (!$emptyProductProperties){
									$arItem["EMPTY_PROPS_JS"]="N";?>
									<div class="wrapper">
										<table>
											<?foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
												<tr>
													<td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
													<td>
														<?if('L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']	&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']){
															foreach($propInfo['VALUES'] as $valueID => $value){?>
																<label>
																	<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
																</label>
															<?}
														}else{?>
															<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
																foreach($propInfo['VALUES'] as $valueID => $value){?>
																	<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
																<?}?>
															</select>
														<?}?>
													</td>
												</tr>
											<?}?>
										</table>
									</div>
									<?
								}?>
							</div>
							<div class="catalog_item main_item_wrapper item_wrap" id="<?=$strMainID;?>">
								<div>
				<?else:?>
					<li class="catalog_item visible js-notice-block" id="<?=$strMainID;?>">
					<?$arItem["FRONT_CATALOG"]="Y";?>
				<?endif;?>
					<?
					$totalCount = CNext::GetTotalCount($arItem, $arParams);
					$arQuantityData = CNext::GetQuantityArray($totalCount);
					$arItem["RID"]=$arResult["RID"];
					$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true);

					$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

					$strMeasure='';
					if($arItem["OFFERS"]){
						$strMeasure=$arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					}else{
						if (($arParams["SHOW_MEASURE"]=="Y")&&($arItem["CATALOG_MEASURE"])){
							$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
							$strMeasure=$arMeasure["SYMBOL_RUS"];
						}
					}
					?>

					<div class="inner_wrap">
						<div class="image_wrapper_block js-notice-block__image">
							
								<div class="stickers">
									<?$prop = ($arParams["STIKERS_PROP"] ? $arParams["STIKERS_PROP"] : "HIT");?>
									<?foreach(CNext::GetItemStickers($arItem["PROPERTIES"][$prop]) as $arSticker):?>
										<div><div class="<?=$arSticker['CLASS']?>"><?=$arSticker['VALUE']?></div></div>
									<?endforeach;?>
									<?if($arParams["SALE_STIKER"] && $arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
										<div><div class="sticker_sale_text"><?=$arItem["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div></div>
									<?}?>
								</div>
								<?//if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
								<?if( ($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y" || $arParams['GALLERY_ITEM_SHOW'] == 'Y')):?>
									<div class="like_icons">
										<?if($arAddToBasketData["CAN_BUY"] && empty($arItem["OFFERS"]) && $arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
											<div class="wish_item_button" <?=($arAddToBasketData['CAN_BUY'] ? '' : 'style="display:none"');?>>
												<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>"><i></i></span>
												<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>"><i></i></span>
											</div>
										<?endif;?>
										<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
											<div class="compare_item_button">
												<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
												<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
											</div>
										<?endif;?>
										<?if($arParams['GALLERY_ITEM_SHOW'] == 'Y'):?>
											<div class="fast_view_wrapper">
												<span>
													<?if($fast_view_text_tmp = CNext::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
														$fast_view_text = $fast_view_text_tmp;
													else
														$fast_view_text = GetMessage('FAST_VIEW');?>
													<i class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-fid="<?=$arItemIDs["strMainID"];?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" title="<?=$fast_view_text;?>" data-name="fast_view">
													</i>
												</span>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
							<?$arParams['EVENT_TYPE'] = 'catalog_bidData_slider_view'?>
							<?if($arParams['GALLERY_ITEM_SHOW'] == 'Y'):?>
								<?\Aspro\Functions\CAsproNext::showSectionGallery( array('ITEM' => $arItem, 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?>
							<?else:?>
								<?\Aspro\Functions\CAsproNext::showImg($arParams, $arItem);?>
							<?endif;?>
						</div>
						<div class="item_info">
							<div class="item_info--top_block">
								<div class="item-title js-notice-block__title">
									<a href="<?=$arItem["DETAIL_PAGE_URL"]?>?RID=<?=$arResult["RID"]?>" class="dark_link"><span><?=$elementName;?></span></a>
								</div>
								<?if($arParams["SHOW_RATING"] == "Y"):?>
									<div class="rating">
										<?if( $arParams['REVIEWS_VIEW'] ):?>
											<div class="blog-info__rating--top-info EXTENDED">
												<div class="votes_block nstar with-text">
													<div class="ratings">
														<?$message = $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', array('#VALUE#' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'])) : GetMessage('VOTES_RESULT_NONE')?>
														<div class="inner_rating" title="<?=$message?>">
															<?for($i=1;$i<=5;$i++):?>
																<div class="item-rating <?=$i<=$arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ? 'filled' : ''?>"></div>
															<?endfor;?>
														</div>
													</div>
												</div>
											</div>
										<?else:?>
											<?$APPLICATION->IncludeComponent(
												"bitrix:iblock.vote",
												"element_rating_front",
												Array(
													"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
													"IBLOCK_ID" => $arItem["IBLOCK_ID"],
													"ELEMENT_ID" =>$arItem["ID"],
													"MAX_VOTE" => 5,
													"VOTE_NAMES" => array(),
													"CACHE_TYPE" => $arParams["CACHE_TYPE"],
													"CACHE_TIME" => $arParams["CACHE_TIME"],
													"DISPLAY_AS_RATING" => 'vote_avg'
												),
												$component, array("HIDE_ICONS" =>"Y")
											);?>
										<?endif;?>
									</div>
								<?endif;?>
								<div class="sa_block">
								<?=$arQuantityData["HTML"];?>
								</div>
							</div>	
							<div class="item_info--bottom_block>">	
								<div class="cost prices clearfix">
									<?if($arItem["OFFERS"]):?>
										<?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arItem, $item_id, $min_price_id, array(), ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
									<?else:?>
										<?
										if(isset($arItem['PRICE_MATRIX']) && $arItem['PRICE_MATRIX']) // USE_PRICE_COUNT
										{?>
											<?if($arItem['ITEM_PRICE_MODE'] == 'Q' && count($arItem['PRICE_MATRIX']['ROWS']) > 1):?>
												<?=CNext::showPriceRangeTop($arItem, $arParams, GetMessage("CATALOG_ECONOMY"));?>
											<?endif;?>
											<?=CNext::showPriceMatrix($arItem, $arParams, $strMeasure, $arAddToBasketData);?>
										<?
										}
										elseif($arItem["PRICES"])
										{?>
											<?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arItem["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
										<?}?>
									<?endif;?>
								</div>
							</div>
						</div>	
						<div class="footer_button">
							<?=$arAddToBasketData["HTML"]?>
						</div>
					</div>
				<?if ($blockViewType):?>
								</div>
							</div>
						</div>
					</div>
				<?else:?>
					</li>
				<?endif;?>
			<?}?>
	<?if ($blockViewType):?>
		</div>
	<?else:?>
		</ul>
	</span>
	<?endif;?>

	<script type="text/javascript">
		$(document).ready(function(){
			$('.tabs li[data-code="RECOMENDATION"]').show();
			setBasketStatusBtn();
		})
	</script>
<?}else{?>
	<script type="text/javascript">
		$('.tabs li[data-code="RECOMENDATION"]').remove();
	</script>
<?}
$frame->end();?>