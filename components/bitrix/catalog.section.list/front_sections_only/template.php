<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?php
global $USER;
if ($USER->IsAuthorized() && $USER->GetLogin() == 'master'):
?>
<?$bCompactViewMobile = $arParams['COMPACT_VIEW_MOBILE'] === 'Y';?>
<?if($arResult['SECTIONS']):?>
	<!-- sections_wrapper -->
	<div class="new_section_tab_block <?=($bCompactViewMobile ? 'compact-view-mobile' : '')?>">
		<?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
			<div class="top_block">
				<h3 class="title_block"><?=$arParams["TITLE_BLOCK"];?></h3>
				<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
			</div>
		<?endif;?>
		<div class="list items">
			<div class="row margin0 flexbox slick-slider">
			<?$reversedSections = array_reverse($arResult['SECTIONS']); // Обратный порядок разделов ?>
				<?foreach($reversedSections as $arSection):	
				
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));?>
					
					<div class="col-md-3 col-sm-4 col-xs-<?=($bCompactViewMobile ? 12 : 6)?> new_section_tab">
						<div class="item" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
							<?if ($arParams["SHOW_SECTION_LIST_PICTURES"]!="N"):?>
								<div class="img shine">
									<?if($arSection["PICTURE"]["SRC"]):?>
										<?$img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
									<?elseif($arSection["~PICTURE"]):?>
										<?$img = CFile::ResizeImageGet($arSection["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"])?>" title="<?=($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"])?>" /></a>
									<?else:?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/svg/catalog_category_noimage.svg" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" /></a>
									<?endif;?>
								</div>
							<?endif;?>
							<div class="name">
								<a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="dark_link"><?=$arSection['NAME'];?></a>
							</div>
							
						</div>
					</div>
				<?endforeach;?>
			</div>
			<div class="left_gif">
				<img src="/images/left.GIF" style="width:36px;">
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(){
			$('.slick-slider').slick({
				dots: false,
				arrows: true,
				slidesToShow: 4,
				prevArrow: `<button type="button" class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6"/></svg></button>`,
				nextArrow: `<button type="button" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6"/></svg></button>`,
				slidesToScroll: 1,
				responsive: [
					{
					breakpoint: 768,
					settings: {
						arrows: true,
						centerMode: false,
						adaptiveHeight: true,
						slidesToShow: 3,
						slidesToScroll: 1,
					}
					},
					{
					breakpoint: 480,
					settings: {
						arrows: true,
						centerMode: false,
						slidesToShow: 2,
						adaptiveHeight: true,
						slidesToScroll: 1,
					}
					}
				]
			});
		});
	</script>
<?endif;?>
<?endif;?>