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
			/* Стили для Owl Carousel */
			.main-sections-owl {
				margin: 0 -10px;
			}

			.main-sections-owl .owl-stage-outer {
				padding: 10px 0;
			}

			.main-sections-owl .item-section {
				padding: 0 10px;
			}

			.main-sections-owl .item {
				text-align: center;
				padding: 10px;
				border-radius: 8px;
				transition: all 0.3s ease;
				cursor: pointer;
			}

			.main-sections-owl .item:hover {
				transform: scale(1.05);
				z-index: 2;
				position: relative;
			}

			.main-sections-owl .img {
				margin-bottom: 10px;
				overflow: hidden;
				border-radius: 8px;
				display: flex;
				justify-content: center;
				align-items: center;
			}

			.main-sections-owl .img img {
				max-width: 100%;
				height: auto;
				width: 100px;
				height: 100px;
			}

			.main-sections-owl .name {
				font-size: 14px;
				line-height: 1.2;
				margin-top: 8px;
				height: 34px;
				overflow: hidden;
			}

			/* Стили для стрелок навигации */
			.main-sections-owl .owl-nav button {
				position: absolute;
				top: 50%;
				transform: translateY(-50%);
				background: rgba(255, 255, 255, 0.8) !important;
				color: #333 !important;
				width: 36px;
				height: 36px;
				border-radius: 50% !important;
				display: flex;
				align-items: center;
				justify-content: center;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
				transition: all 0.3s ease;
				margin: 0 !important;
			}

			.main-sections-owl .owl-nav button:hover {
				background: #fff !important;
				box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
			}

			.main-sections-owl .owl-prev {
				left: -18px;
			}

			.main-sections-owl .owl-next {
				right: -18px;
			}

			.main-sections-owl .owl-dots {
				margin-top: 15px;
				text-align: center;
			}

			.main-sections-owl .owl-dot {
				display: inline-block;
				margin: 0 4px;
			}

			.main-sections-owl .owl-dot span {
				width: 8px;
				height: 8px;
				border-radius: 50%;
				background: #ddd;
				display: block;
				transition: all 0.3s ease;
			}

			.main-sections-owl .owl-dot.active span,
			.main-sections-owl .owl-dot:hover span {
				background: #888;
			}

			/* Адаптивность для мобильных устройств */
			@media (max-width: 767px) {
				.main-sections-owl .owl-nav button {
					width: 30px;
					height: 30px;
				}

				.main-sections-owl .owl-prev {
					left: -15px;
				}

				.main-sections-owl .owl-next {
					right: -15px;
				}

				.main-sections-owl .name {
					font-size: 12px;
					height: 30px;
				}
			}

			@media (max-width: 480px) {
				.main-sections-owl .owl-nav button {
					width: 28px;
					height: 28px;
				}

				.main-sections-owl .owl-prev {
					left: -14px;
				}

				.main-sections-owl .owl-next {
					right: -14px;
				}
			}
		</style>

		<div class="list items">
			<div class="main-sections-owl owl-carousel">
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
		document.addEventListener('DOMContentLoaded', function() {
			$('.main-sections-owl').owlCarousel({
				items: 4,
				loop: false,
				margin: 10,
				nav: true,
				dots: true,
				lazyLoad: true,
				autoplay: false,
				smartSpeed: 700,
				navText: [
					'<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6" /></svg>',
					'<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6" /></svg>'
				],
				responsive: {
					0: {
						items: 2,
						margin: 8,
						nav: true,
						dots: false
					},
					480: {
						items: 2,
						margin: 10
					},
					768: {
						items: 3
					},
					992: {
						items: 4
					}
				},
				onInitialized: function() {
					// Дополнительная обработка после инициализации, если нужно
				}
			});
		});
	</script>
<? endif; ?>