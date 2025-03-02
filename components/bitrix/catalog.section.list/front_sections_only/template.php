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
			<div id="front-sections-carousel" class="owl-carousel">
			<?$reversedSections = array_reverse($arResult['SECTIONS']); // Обратный порядок разделов ?>
				<?foreach($reversedSections as $arSection):	
				
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));?>
					
					<div class="item-section">
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
		</div>
	</div>
	<script>
		$(document).ready(function(){
			var $carousel = $('#front-sections-carousel');
			
			// Предзагрузка изображений перед инициализацией карусели
			var images = [];
			$('#front-sections-carousel img').each(function() {
				var imgSrc = $(this).attr('src');
				if (imgSrc) {
					var img = new Image();
					img.src = imgSrc;
					images.push(img);
				}
			});
			
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
					480: {
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
				},
				onInitialized: function() {
					// Принудительно обновляем карусель после инициализации
					setTimeout(function() {
						$carousel.trigger('refresh.owl.carousel');
					}, 100);
				}
			});
		});
	</script>

	<style>
	/* Стили для карусели разделов */
	#front-sections-carousel {
		padding: 0 40px;
		position: relative;
	}

	#front-sections-carousel .owl-nav {
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

	#front-sections-carousel .owl-prev, 
	#front-sections-carousel .owl-next {
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

	#front-sections-carousel .owl-prev {
		left: -20px;
	}

	#front-sections-carousel .owl-next {
		right: -20px;
	}

	#front-sections-carousel .owl-prev:hover, 
	#front-sections-carousel .owl-next:hover {
		background: #f5f5f5 !important;
	}

	#front-sections-carousel .owl-item {
		padding: 10px;
	}

	.item-section {
		text-align: center;
	}

	.item-section .item {
		background: #fff;
		border-radius: 12px;
		padding: 15px;
		transition: all 0.3s ease;
		height: 100%;
	}

	.item-section .item:hover {
		box-shadow: 0 10px 20px rgba(0,0,0,0.05);
	}

	.item-section .img {
		margin-bottom: 10px;
		display: flex;
		justify-content: center;
	}

	.item-section .img img {
		max-width: 100%;
		height: auto;
	}

	.item-section .name {
		font-size: 14px;
		font-weight: 500;
		line-height: 1.3;
	}

	/* Удаляем ненужные элементы */
	.left_gif {
		display: none;
	}

	/* Адаптивные стили */
	@media (max-width: 768px) {
		.item-section .item {
			padding: 10px;
		}
		
		.item-section .name {
			font-size: 13px;
		}
	}
	</style>
<?endif;?>
<?endif;?>