<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?php
?>
<? $this->setFrameMode(true); ?>
<? $bCompactViewMobile = $arParams['COMPACT_VIEW_MOBILE'] === 'Y'; ?>
<? if ($arResult['SECTIONS']): ?>
	<!-- sections_wrapper -->
	<div class="new_section_tab_block <?= ($bCompactViewMobile ? 'compact-view-mobile' : '') ?>">
		<? if ($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]): ?>
			<div class="top_block">
				<h3 class="title_block"><?= $arParams["TITLE_BLOCK"]; ?></h3>
				<a href="<?= SITE_DIR . $arParams["ALL_URL"]; ?>"><?= $arParams["TITLE_BLOCK_ALL"]; ?></a>
			</div>
		<? endif; ?>
		
		<style>
			/* Стили для компактной карусели */
			#main-sections-carousel .item-section {
				padding: 10px;
				transition: all 0.3s ease;
			}
			
			#main-sections-carousel .item {
				text-align: center;
				padding: 10px;
				border-radius: 8px;
				transition: all 0.3s ease;
				cursor: pointer;
			}
			
			/* Эффект увеличения при наведении на весь элемент */
			#main-sections-carousel .item:hover {
				transform: scale(1.05);
				
				z-index: 2;
				position: relative;
			}
			
			#main-sections-carousel .img {
				margin-bottom: 10px;
				overflow: hidden;
				border-radius: 8px;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			
			#main-sections-carousel .img img {
				max-width: 100%;
				height: auto;
			}
			
			#main-sections-carousel .name {
				font-size: 14px;
				line-height: 1.2;
				margin-top: 8px;
				height: 34px;
				overflow: hidden;
			}
			
			/* Стили для стрелок навигации */
			#main-sections-carousel .owl-nav {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				pointer-events: none;
			}
			
			#main-sections-carousel .owl-prev,
			#main-sections-carousel .owl-next {
				position: absolute;
				top: 50%;
				transform: translateY(-50%);
				background: rgba(255, 255, 255, 0.8) !important;
				color: #333 !important;
				width: 36px;
				height: 36px;
				border-radius: 50%;
				display: flex !important;
				align-items: center;
				justify-content: center;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
				transition: all 0.3s ease;
				pointer-events: auto;
				z-index: 10;
			}
			
			#main-sections-carousel .owl-prev:hover,
			#main-sections-carousel .owl-next:hover {
				background: #fff !important;
				box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
			}
			
			#main-sections-carousel .owl-prev {
				left: -18px;
			}
			
			#main-sections-carousel .owl-next {
				right: -18px;
			}
			
			/* Адаптивность для мобильных устройств */
			@media (max-width: 767px) {
				#main-sections-carousel .name {
					font-size: 12px;
					height: 30px;
				}
				
				#main-sections-carousel .owl-prev {
					left: -10px;
				}
				
				#main-sections-carousel .owl-next {
					right: -10px;
				}
			}
		</style>
		
		<div class="list items">
			<div class="row margin0 flexbox owl-carousel" id="main-sections-carousel">
				<? $reversedSections = array_reverse($arResult['SECTIONS']); // Обратный порядок разделов 
				?>
				<? foreach ($reversedSections as $arSection):

					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM'))); ?>

					<div class="item-section">
						<div class="item" id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
							<div class="img shine">
								<? if ($arSection["PICTURE"]["SRC"]): ?>
									<? $img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array("width" => 120, "height" => 120), BX_RESIZE_IMAGE_EXACT, true); ?>
									<a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= $img["src"] ?>" alt="<?= ($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"]) ?>" title="<?= ($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"]) ?>" /></a>
								<? elseif ($arSection["~PICTURE"]): ?>
									<? $img = CFile::ResizeImageGet($arSection["~PICTURE"], array("width" => 120, "height" => 120), BX_RESIZE_IMAGE_EXACT, true); ?>
									<a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= $img["src"] ?>" alt="<?= ($arSection["PICTURE"]["ALT"] ? $arSection["PICTURE"]["ALT"] : $arSection["NAME"]) ?>" title="<?= ($arSection["PICTURE"]["TITLE"] ? $arSection["PICTURE"]["TITLE"] : $arSection["NAME"]) ?>" /></a>
								<? else: ?>
									<a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/catalog_category_noimage.svg" alt="<?= $arSection["NAME"] ?>" title="<?= $arSection["NAME"] ?>" /></a>
								<? endif; ?>
							</div>
							<div class="name">
								<a href="<?= $arSection['SECTION_PAGE_URL']; ?>" class="dark_link"><?= $arSection['NAME']; ?></a>
							</div>

						</div>
					</div>
				<? endforeach; ?>
			</div>

		</div>
	</div>
	<script>
		$(document).ready(function() {
			// Предзагрузка изображений перед инициализацией карусели
			var images = [];
			$('#main-sections-carousel img').each(function() {
				var imgSrc = $(this).attr('src');
				if (imgSrc) {
					var img = new Image();
					img.src = imgSrc;
					images.push(img);
				}
			});
			
			var $carousel = $('#main-sections-carousel');
			
			$carousel.owlCarousel({
				items: 4,
				loop: true,
				margin: 10,
				nav: true,
				lazyLoad: false,
				autoHeight: false,
				smartSpeed: 400,
				navText: [
					`<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6"/></svg>`,
					`<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6"/></svg>`
				],
				responsive: {
					0: {
						items: 2,
						nav: true
					},
					480: {
						items: 2,
						nav: true
					},
					768: {
						items: 3,
						nav: true
					},
					992: {
						items: 4,
						nav: true
					}
				},
				onInitialized: function() {
					// Принудительно загружаем все изображения в карусели
					setTimeout(function() {
						$carousel.trigger('refresh.owl.carousel');
					}, 100);
				}
			});
		});
	</script>
<? endif; ?>