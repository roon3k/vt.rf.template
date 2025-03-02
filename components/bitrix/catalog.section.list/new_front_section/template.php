<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? $bCompactViewMobile = $arParams['COMPACT_VIEW_MOBILE'] === 'Y'; ?>
<?php
global $USER;
if ($USER->IsAuthorized() && $USER->GetLogin() == 'master'):
?>
<?
$rsParentSection = CIBlockSection::GetByID($arResult['SECTION']['ID']);
if ($arParentSection = $rsParentSection->GetNext()) {
	$arFilter = array('IBLOCK_ID' => $arParentSection['IBLOCK_ID'], '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'], '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'], '>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']); // выберет потомков без учета активности
	$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter);
	
	while ($arSect = $rsSect->GetNext()) {
		$arResult['SECTIONS'][] = $arSect;
		
	}
}

?>

<? if ($arResult['SECTIONS']): ?>
	<div class="new_section_tab_block <?= ($bCompactViewMobile ? 'compact-view-mobile' : '') ?>">
		<div class="list items">
			<div id="section-carousel" class="owl-carousel">
				<?php
				// Перемещаем элемент с ID, равным $arResult['SECTION']['ID'], на первое место
				foreach ($arResult['SECTIONS'] as $key => $arSection) {
					if ($arSection['ID'] == $arParams['SECTION_VER']) {
						// Удаляем элемент с массива
						$selectedSection = $arResult['SECTIONS'][$key];
						unset($arResult['SECTIONS'][$key]);
				
						// Вставляем элемент в начало массива
						$arResult['SECTIONS'] = array($key => $selectedSection) + $arResult['SECTIONS'];
						break;
					}
				}
				// Теперь выводим секции
				foreach (array_reverse($arResult['SECTIONS']) as $arSection): ?>
				
					<div class="item-section" data-sect="<?= $arSection['ID'] ?>">
						<div class="item <?= ($arSection['ID'] == $arParams['SECTION_VER'] ? 'active' : '') ?>" id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
							<div class="img shine">
								<?php if ($arSection["PICTURE"]): ?>
									<?php $img = CFile::ResizeImageGet($arSection["PICTURE"], array("width" => 120, "height" => 120), BX_RESIZE_IMAGE_EXACT, true); ?>
									<a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= $img["src"] ?>" title="<?= $arSection["NAME"] ?>" /></a>
								<?php elseif ($arSection["~PICTURE"]): ?>
									<?php $img = CFile::ResizeImageGet($arSection["~PICTURE"], array("width" => 120, "height" => 120), BX_RESIZE_IMAGE_EXACT, true); ?>
									<a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= $img["src"] ?>" title="<?= $arSection["NAME"] ?>" /></a>
								<?php else: ?>
									<a href="<?= $arSection["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/catalog_category_noimage.svg" alt="<?= $arSection["NAME"] ?>" title="<?= $arSection["NAME"] ?>" /></a>
								<?php endif; ?>
							</div>
							<div class="name">
								<a href="<?= $arSection['SECTION_PAGE_URL']; ?>" class="dark_link"><?= $arSection['NAME']; ?></a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	
	<script>
		$(document).ready(function() {
			var $carousel = $('#section-carousel');
			
			// Предзагрузка изображений перед инициализацией карусели
			var images = [];
			$('#section-carousel img').each(function() {
				var imgSrc = $(this).attr('src');
				if (imgSrc) {
					var img = new Image();
					img.src = imgSrc;
					images.push(img);
				}
			});
			
			// Находим активный элемент и его индекс
			var activeItem = $('.item-section[data-sect="<?= $arParams['SECTION_VER'] ?>"]');
			var activeIndex = activeItem.length ? activeItem.index() : 0;
			
			// Инициализируем карусель
			$carousel.owlCarousel({
				items: 4,
				loop: true,
				margin: 20,
				nav: true,
				dots: false,
				center: true,
				startPosition: activeIndex, // Устанавливаем начальную позицию
				navText: [
					`<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6"/></svg>`,
					`<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6"/></svg>`
				],
				responsive: {
					0: {
						items: 2,
						margin: 10,
						center: true
					},
					480: {
						items: 2,
						margin: 15,
						center: true
					},
					768: {
						items: 3,
						margin: 15,
						center: true
					},
					992: {
						items: 4,
						margin: 20,
						center: true
					}
				},
				onInitialized: function() {
					// Обновляем карусель без пересборки
					setTimeout(function() {
						$carousel.trigger('refresh.owl.carousel');
					}, 100);
				}
			});
			
			// Добавляем обработчик клика на элементы
			$('.item-section').on('click', function(e) {
				// Если клик был не по ссылке, а по самому элементу
				if (!$(e.target).is('a')) {
					e.preventDefault();
					var index = $(this).index();
					
					// Плавно прокручиваем к выбранному элементу
					$carousel.trigger('to.owl.carousel', [index, 300]);
					
					// Добавляем класс active выбранному элементу и убираем у остальных
					$('.item-section .item').removeClass('active');
					$(this).find('.item').addClass('active');
					
					// Если нужно, можно также обновить URL страницы
					// var sectionUrl = $(this).find('a').attr('href');
					// history.pushState(null, null, sectionUrl);
				}
			});
		});
	</script>
	
	<style>
	/* Стили для карусели разделов */
	#section-carousel {
		padding: 0 40px;
		position: relative;
	}

	#section-carousel .owl-nav {
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

	#section-carousel .owl-prev, 
	#section-carousel .owl-next {
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

	#section-carousel .owl-prev {
		left: -20px;
	}

	#section-carousel .owl-next {
		right: -20px;
	}

	#section-carousel .owl-prev:hover, 
	#section-carousel .owl-next:hover {
		background: #f5f5f5 !important;
	}

	#section-carousel .owl-item {
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

	.item-section .item.active {
		box-shadow: 0 10px 20px rgba(0,0,0,0.1);
		border: 2px solid #4086F1;
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
<? endif; ?>
<? endif; ?>