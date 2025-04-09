<?
global $arTheme;
$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
$bAnimation = (bool)$slideshowSpeed;
?>
<div class="brands_slider_wrapp flexslider loading_state clearfix" data-plugin-options='{"animation": "slide", "directionNav": true, "itemMargin":30, "controlNav" :false, "animationLoop": true, <?=($bAnimation ? '"slideshow": true,' : '"slideshow": false,')?> <?=($slideshowSpeed >= 0 ? '"slideshowSpeed": '.$slideshowSpeed.',' : '')?> <?=($animationSpeed >= 0 ? '"animationSpeed": '.$animationSpeed.',' : '')?> "counts": [5,4,3,2,1]}'>
	<ul class="brands_slider slides">
		<?foreach($arResult["ITEMS"] as $arItem){?>
			<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			
				$bShowLink = !($arParams['HIDE_LINK_WHEN_NO_DETAIL'] && !trim($arItem['DETAIL_TEXT']));
			?>
			<li class="visible" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<?if ($bShowLink):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
				<?endif;?>
					<?if (is_array($arItem["PREVIEW_PICTURE"])):?>
						<img class="noborder" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"] ?? $arItem["NAME"];?>" title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"] ?? $arItem["NAME"]?>" />
					<?elseif (is_array($arItem["DETAIL_PICTURE"])):?>
						<img class="noborder" src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["DETAIL_PICTURE"]["ALT"] ?? $arItem["NAME"];?>" title="<?=$arItem["DETAIL_PICTURE"]["TITLE"] ?? $arItem["NAME"]?>" />
					<?else:?>
						<span><?=$arItem["NAME"]?></span>
					<?endif;?>
				<?if ($bShowLink):?>
				</a>
				<?endif;?>
			</li>
		<?}?>
	</ul>
</div>