<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="module-cart delayed">
	<table class="colored">
		<thead>
			<tr>
				<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader)
				{
					if ($arHeader["id"] == "DELETE"){$bDeleteColumn = true;}
					if ($arHeader["id"] == "TYPE"){$bTypeColumn = true;}
					if ($arHeader["id"] == "DISCOUNT"){$bDiscountColumn = true;}
				}?>
				<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
					if (in_array($arHeader["id"], array("TYPE", "DISCOUNT", "SUMM"))) // some header columns are shown differently
					{continue;}
					elseif ($arHeader["id"] == "PROPS"){$bPropsColumn = true;continue;}
					elseif ($arHeader["id"] == "DELAY"){$bDelayColumn = true;continue;}
					elseif ($arHeader["id"] == "WEIGHT"){ $bWeightColumn = true;}
					elseif ($arHeader["id"] == "DELETE"){ continue;}
					if ($arHeader["id"] == "NAME"):
					?>
						<td class="thumb-cell"></td><td class="name-th">
					<?elseif ($arHeader["id"] == "PRICE"):?><td class="price-th">
					<?else:?><td class="<?=strToLower($arHeader["id"])?>-th"><?endif;?><?=getColumnName($arHeader)?></td>
				<?endforeach;?>
				<?if ($bDeleteColumn):?><td class="remove-cell"></td><?endif;?>
			</tr>
		</thead>

		<tbody>
			<?foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
				if (isset($arItem["NOT_AVAILABLE"]) && $arItem["NOT_AVAILABLE"] == true):?>
				<tr data-id="<?=$arItem["ID"]?>" data-iblockid="<?=$arItem["IBLOCK_ID"]?>" product-id="<?=$arItem["PRODUCT_ID"]?>">
					<? foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
						if (in_array($arHeader["id"], array("PROPS", "DELAY", "DELETE", "TYPE", "DISCOUNT"))) continue; // some values are not shown in columns in this template
						if ($arHeader["id"] == "NAME"):
							$bPreviewPicture = ($arItem["PREVIEW_PICTURE"]["SRC"] ?? '') !== '';
							$bDetailPicture = ($arItem["DETAIL_PICTURE"]["SRC"] ?? '') !== '';
							$bShowDetailURL = ($arItem["DETAIL_PAGE_URL"] ?? '') !== '';
						?>
							<td class="thumb-cell">
								<?if ($bPreviewPicture):?>
									<?if ($bShowDetailURL):?><a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="thumb"><?endif;?>
										<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"];?>" alt="<?=(is_array($arItem["PREVIEW_PICTURE"]["ALT"])?$arItem["PREVIEW_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["PREVIEW_PICTURE"]["TITLE"])?$arItem["PREVIEW_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?if ($bShowDetailURL):?></a><?endif;?>
								<?elseif ($bDetailPicture):?>
									<?if ($bShowDetailURL):?><a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="thumb"><?endif;?>
										<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"];?>" alt="<?=(is_array($arItem["DETAIL_PICTURE"]["ALT"])?$arItem["DETAIL_PICTURE"]["ALT"]:$arItem["NAME"]);?>" title="<?=(is_array($arItem["DETAIL_PICTURE"]["TITLE"])?$arItem["DETAIL_PICTURE"]["TITLE"]:$arItem["NAME"]);?>" />
									<?if ($bShowDetailURL):?></a><?endif;?>
								<?else:?>
									<?if ($bShowDetailURL):?><a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="thumb"><?endif;?>
										<img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arItem["NAME"];?>" title="<?=$arItem["NAME"];?>" width="70" height="70" />
									<?if ($bShowDetailURL):?></a><?endif;?>
								<?endif;?>
								<?if (!empty($arItem["BRAND"])):?><div class="ordercart_brand"><img src="<?=$arItem["BRAND"];?>" /></div><?endif;?>
							</td>
							
							<td class="name-cell">	
								<?if ($bShowDetailURL):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?endif;?><?=$arItem["NAME"]?><?if ($bShowDetailURL):?></a><?endif;?><br />
								<?if ($bPropsColumn):?>	
									<div class="item_props">
										<? foreach ($arItem["PROPS"] as $val) {
												if (is_array($arItem["SKU_DATA"])) {
													$bSkip = false;
													foreach ($arItem["SKU_DATA"] as $propId => $arProp) { if ($arProp["CODE"] == $val["CODE"]) { $bSkip = true; break; } } 
													if ($bSkip) continue;
												} echo '<span class="item_prop"><span class="name">'.$val["NAME"].':&nbsp;</span><span class="property_value">'.$val["VALUE"].'</span></span>';
											}?>
									</div>
								<?endif;?>
								
								<?if (is_array($arItem["SKU_DATA"]) && $arItem["PROPS"]):
									foreach ($arItem["SKU_DATA"] as $propId => $arProp):
										$isImgProperty = false; // is image property
										foreach ($arProp["VALUES"] as $id => $arVal) { if (isset($arVal["PICT"]) && !empty($arVal["PICT"])) { $isImgProperty = true; break; } }
										$full = (count($arProp["VALUES"]) > 5) ? "full" : "";
										if ($isImgProperty): // iblock element relation property
										?>
											<div class="bx_item_detail_scu_small_noadaptive <?=$full?>">
												<span class="titles"><?=$arProp["NAME"]?>:</span>
												<div class="bx_scu_scroller_container">
													<div class="bx_scu values">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>">
														<?	foreach ($arProp["VALUES"] as $valueId => $arSkuValue){
																$selected = "";
																foreach ($arItem["PROPS"] as $arItemProp) { 
																	if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
																		{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"]) $selected = "class=\"bx_active\""; }
																};?>
																<li <?=$selected?>>
																	<?/*<a href="javascript:void(0);"><span style="background-image:url(<?=$arSkuValue["PICT"]["SRC"]?>)"></span></a>*/?>
																	<span><?=$arSkuValue["NAME"]?></span>
																</li>
														<?}?>
														</ul>
													</div>
												</div>
											</div>
										<?else:?>
											<div class="bx_item_detail_size_small_noadaptive <?=$full?>">
												<span class="titles">
													<?=$arProp["NAME"]?>:
												</span>

												<div class="bx_size_scroller_container">
													<div class="bx_size values">
														<ul id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>">
															<?foreach ($arProp["VALUES"] as $valueId => $arSkuValue) {
																$selected = "";
																foreach ($arItem["PROPS"] as $arItemProp) {
																	if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"]) 
																	{ if ($arItemProp["VALUE"] == $arSkuValue["NAME"]) $selected = "class=\"bx_active\""; }
																}?>
																<li <?=$selected?>><span><?=$arSkuValue["NAME"]?></span></li>
															<?}?>
														</ul>
													</div>
												</div>
											</div>
										<?endif;
									endforeach;
								endif;
								?>
								<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" value="Y"/>
							</td>	
						<?elseif ($arHeader["id"] == "QUANTITY"):?>
							<td class="count-cell">	
								<?=$arItem["QUANTITY"]?><?if (isset($arItem["MEASURE_TEXT"]) && $arParams["SHOW_MEASURE"]=="Y"):?> <?=$arItem["MEASURE_TEXT"];?>.<?endif;?>
								<?
									$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
									$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
								?>
								<input
									type="hidden" 
									id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
									name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
									size="2"
									data-id="<?=$arItem["ID"];?>" 
									maxlength="18"
									min="0"
									<?=$max?>
									step="<?=$ratio?>"
									value="<?=$arItem["QUANTITY"]?>"
								>
								<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" > 
							</td>
						<?elseif ($arHeader["id"] == "SUMM"):?>
							<?/*<td class="summ-cell"><div class="cost prices"><div class="price"><?=$arItem["SUMM_FORMATED"];?></div></div></td>	*/?>
						<?elseif ($arHeader["id"] == "PRICE"):?>
							<td class="cost-cell <?=( $bTypeColumn ? 'notes' : '' );?>">
								<div class="cost prices clearfix">
									<?if (strlen($arItem["NOTES"]) > 0 && $bTypeColumn):?>
										<div class="price_name"><?=$arItem["NOTES"]?></div>
									<?endif;?>
									<?if( doubleval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0 && $bDiscountColumn ){?>
										<div class="price"><?=$arItem["PRICE_FORMATED"]?></div>
										<div class="price discount"><strike><?=$arItem["FULL_PRICE_FORMATED"]?></strike></div>
										<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
										<input type="hidden" name="item_price_discount_<?=$arItem["ID"]?>" value="<?=$arItem["FULL_PRICE"]?>" />
										<div class="sale_block">
											<?if($arItem["DISCOUNT_PRICE_PERCENT"] && $arItem["DISCOUNT_PRICE_PERCENT"]<100){?>
												<div class="value">-<?=$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"];?></div>
											<?}?>
											<div class="text"><?=GetMessage("ECONOMY")?> <?=SaleFormatCurrency(round($arItem["DISCOUNT_PRICE"]), $arItem["CURRENCY"]);?></div>
											<div class="clearfix"></div>
										</div>
									<?}else{?>
										<div class="price"><?=$arItem["PRICE_FORMATED"];?></div>
										<input type="hidden" name="item_price_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]?>" />
									<?}?>
									<input type="hidden" name="item_summ_<?=$arItem["ID"]?>" value="<?=$arItem["PRICE"]*$arItem["QUANTITY"]?>" />
								</div>
							</td>
						<?elseif ($arHeader["id"] == "WEIGHT"):?>	
							<td class="weight-cell"><?=$arItem["WEIGHT_FORMATED"]?></td>
						<?else:?>	
							<td class="cell"><?=$arItem[$arHeader["id"]]?></td>
						<?endif;?>
					<?endforeach;?>
					<?if ($bDeleteColumn):?>
						<td class="remove-cell"><a class="remove" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" title="<?=GetMessage("SALE_DELETE")?>"><i></i></a></td>
					<?endif;?>
				</tr>
				<?
				endif;
			endforeach;
			?>
		</tbody>
	</table>
</div>
<?