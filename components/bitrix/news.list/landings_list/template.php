<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<?if($arResult['ITEMS']):?>
	<?
	$compare_field = (isset($arParams["COMPARE_FIELD"]) && $arParams["COMPARE_FIELD"] ? $arParams["COMPARE_FIELD"] : "DETAIL_PAGE_URL");
	$bProp = (isset($arParams["COMPARE_PROP"]) && $arParams["COMPARE_PROP"] == "Y");
	$bShowSections = $arParams['USE_LANDINGS_GROUP'] === 'Y';
	?>
	<div class="items landings_list landings_list--grid clearfix">
		<?if ($arParams["TITLE_BLOCK"] && !$bShowSections):?>
			<h4><?=$arParams["TITLE_BLOCK"];?></h4>
		<?endif;?>

		<?foreach ($arResult['SECTIONS'] as $arSection): ?>
			<?$i = 0;?>
			<div class="landings__section">
				<?if ($arSection['NAME'] && $bShowSections):?>
					<div class="landings__section-title landings__section-title--bold"><?=$arSection['NAME'];?></div>
				<?endif;?>

				<div class="landings__section-items row margin0">
					<?foreach ($arSection['ITEMS'] as $arItem):?>
						<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

						++$i;
						$bHidden = $i > $arParams["SHOW_COUNT"] && $arParams["SHOW_COUNT"] >= 1;
						$url = $arItem[$compare_field];
						if ($bProp) {
							$url = $arItem["PROPERTIES"][$compare_field]["VALUE"];
						}
						?>
						<a class="landings__item col-md-4 col-sm-6 col-xs-12<?=$bHidden ? ' hidden_items' : '';?><?=($APPLICATION->GetCurDir() === $url) ? ' active' : ''?>" href="<?=$url;?>" ><?=$arItem['NAME'];?></a>
					<?endforeach?>
				</div>

				<?if ($bHidden):?>
					<button type="button" class="landings__show-all more icons_fa" data-opened="N" data-text="<?=GetMessage("HIDE");?>"><?=GetMessage("SHOW_ALL");?></button type="button">
				<?endif?>
			</div>
		<?endforeach;?>
	</div>
<?endif?>