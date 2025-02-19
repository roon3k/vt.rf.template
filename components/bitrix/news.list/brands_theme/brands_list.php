<?if($arResult["ITEMS"]):?>
	<div class="brand_wrapper">
		<?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
			<div class="top_block">
				<h3 class="title_block"><?=$arParams["TITLE_BLOCK"];?></h3>
				<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
			</div>
		<?endif;?>
		<div class="row margin0">
			<div class="brands_list">
				<?foreach( $arResult["ITEMS"] as $arItem ){
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					$bShowLink = !($arParams['HIDE_LINK_WHEN_NO_DETAIL'] && !trim($arItem['DETAIL_TEXT']));
					?>
					<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6 item <?=(!is_array($arItem["PREVIEW_PICTURE"]) && !is_array($arItem["DETAIL_PICTURE"]) ? 'wti' : '')?>">
						<div id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<?if ($bShowLink):?>
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<?endif;?>
								<?if (is_array($arItem["PREVIEW_PICTURE"])):?>
									<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=($arItem["PREVIEW_PICTURE"]["ALT"] ?? $arItem["NAME"]);?>" title="<?=($arItem["PREVIEW_PICTURE"]["TITLE"] ?? $arItem["NAME"]);?>" />
								<?elseif (is_array($arItem["DETAIL_PICTURE"])):?>
									<img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=($arItem["DETAIL_PICTURE"]["ALT"] ?? $arItem["NAME"]);?>" title="<?=($arItem["DETAIL_PICTURE"]["TITLE"] ?? $arItem["NAME"]);?>" />
								<?else:?>
									<span><?=$arItem["NAME"]?></span>
								<?endif;?>
							<?if ($bShowLink):?>
							</a>
							<?endif;?>
						</div>
					</div>
				<?}?>
			</div>
		</div>
	</div>
<?endif;?>