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
			/* Стили для кастомного слайдера */
			.custom-slider-container {
				position: relative;
				width: 100%;
				overflow: hidden;
				padding: 0 40px; /* Добавляем отступы для стрелок */
			}
			
			.custom-slider {
				display: flex;
				transition: transform 0.4s ease;
			}
			
			.custom-slider-item {
				flex: 0 0 auto;
				transition: all 0.3s ease;
			}
			
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
			.custom-slider-nav {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				pointer-events: none;
			}
			
			.custom-slider-prev,
			.custom-slider-next {
				position: absolute;
				top: 50%;
				transform: translateY(-50%);
				background: rgba(255, 255, 255, 0.8);
				color: #333;
				width: 36px;
				height: 36px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
				transition: all 0.3s ease;
				pointer-events: auto;
				z-index: 10;
				cursor: pointer;
				border: none;
			}
			
			.custom-slider-prev:hover,
			.custom-slider-next:hover {
				background: #fff;
				box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
			}
			
			.custom-slider-prev {
				left: 5px; /* Изменено с -18px на 5px */
			}
			
			.custom-slider-next {
				right: 5px; /* Изменено с -18px на 5px */
			}
			
			/* Адаптивность для мобильных устройств */
			@media (max-width: 767px) {
				.custom-slider-container {
					padding: 0 30px; /* Уменьшаем отступы на мобильных */
				}
				
				#main-sections-carousel .name {
					font-size: 12px;
					height: 30px;
				}
				
				.custom-slider-prev {
					left: 2px; /* Изменено с -10px на 2px */
				}
				
				.custom-slider-next {
					right: 2px; /* Изменено с -10px на 2px */
				}
			}
		</style>
		
		<div class="list items">
			<div class="custom-slider-container">
				<div class="custom-slider" id="main-sections-carousel">
					<? $reversedSections = array_reverse($arResult['SECTIONS']); // Обратный порядок разделов 
					?>
					<? foreach ($reversedSections as $arSection):

						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM'))); ?>

						<div class="custom-slider-item item-section">
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
				<div class="custom-slider-nav">
					<button class="custom-slider-prev">
						<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6"/></svg>
					</button>
					<button class="custom-slider-next">
						<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6"/></svg>
					</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Предзагрузка изображений перед инициализацией слайдера
			const images = [];
			document.querySelectorAll('#main-sections-carousel img').forEach(function(imgEl) {
				const imgSrc = imgEl.getAttribute('src');
				if (imgSrc) {
					const img = new Image();
					img.src = imgSrc;
					images.push(img);
				}
			});
			
			// Инициализация слайдера
			const slider = document.querySelector('.custom-slider');
			const sliderItems = document.querySelectorAll('.custom-slider-item');
			const prevBtn = document.querySelector('.custom-slider-prev');
			const nextBtn = document.querySelector('.custom-slider-next');
			
			let currentPosition = 0;
			let itemsPerView = 4;
			let itemWidth = 0;
			let totalItems = sliderItems.length;
			let isAnimating = false;
			
			// Функция обновления размеров слайдера
			function updateSizes() {
				const containerWidth = slider.parentElement.clientWidth;
				
				// Определяем количество элементов в видимой области в зависимости от размера экрана
				if (window.innerWidth < 480) {
					itemsPerView = 2;
				} else if (window.innerWidth < 768) {
					itemsPerView = 2;
				} else if (window.innerWidth < 992) {
					itemsPerView = 3;
				} else {
					itemsPerView = 4;
				}
				
				// Устанавливаем ширину элементов
				itemWidth = containerWidth / itemsPerView;
				
				sliderItems.forEach(item => {
					item.style.width = `${itemWidth}px`;
				});
				
				// Ограничиваем позицию слайдера
				adjustPosition();
			}
			
			function adjustPosition() {
				// Ограничиваем крайние позиции для предотвращения пустого пространства
				const maxPosition = Math.max(0, totalItems - itemsPerView);
				currentPosition = Math.min(Math.max(0, currentPosition), maxPosition);
				
				// Перемещаем слайдер
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
			}
			
			// Обработчики кнопок навигации
			function prevSlide() {
				if (isAnimating) return;
				isAnimating = true;
				
				currentPosition = Math.max(0, currentPosition - 1);
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
				
				// Защита от быстрых множественных кликов
				setTimeout(() => {
					isAnimating = false;
				}, 400); // Время анимации в стилях
			}
			
			function nextSlide() {
				if (isAnimating) return;
				isAnimating = true;
				
				const maxPosition = Math.max(0, totalItems - itemsPerView);
				currentPosition = Math.min(currentPosition + 1, maxPosition);
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
				
				setTimeout(() => {
					isAnimating = false;
				}, 400);
			}
			
			// Привязываем обработчики событий
			prevBtn.addEventListener('click', prevSlide);
			nextBtn.addEventListener('click', nextSlide);
			
			// Инициализация слайдера и обработка изменений размера окна
			updateSizes();
			window.addEventListener('resize', updateSizes);
			
			// Поддержка свайпов для мобильных устройств
			let touchStartX = 0;
			let touchEndX = 0;
			
			slider.addEventListener('touchstart', function(e) {
				touchStartX = e.changedTouches[0].screenX;
			}, {passive: true});
			
			slider.addEventListener('touchend', function(e) {
				touchEndX = e.changedTouches[0].screenX;
				handleSwipe();
			}, {passive: true});
			
			function handleSwipe() {
				const swipeThreshold = 50; // минимальное расстояние для регистрации свайпа
				if (touchEndX - touchStartX > swipeThreshold) {
					// Свайп вправо
					prevSlide();
				} else if (touchStartX - touchEndX > swipeThreshold) {
					// Свайп влево
					nextSlide();
				}
			}
		});
	</script>
<? endif; ?>