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
				padding: 0 40px;
				/* Добавляем отступы для стрелок */
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
				width: 100px;
				height: 100px;
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
				left: 5px;
				/* Изменено с -18px на 5px */
			}

			.custom-slider-next {
				right: 5px;
				/* Изменено с -18px на 5px */
			}

			/* Адаптивность для мобильных устройств */
			@media (max-width: 767px) {
				.custom-slider-container {
					padding: 0 30px;
					/* Уменьшаем отступы на мобильных */
				}

				#main-sections-carousel .name {
					font-size: 12px;
					height: 30px;
				}

				.custom-slider-prev {
					left: 2px;
					/* Изменено с -10px на 2px */
				}

				.custom-slider-next {
					right: 2px;
					/* Изменено с -10px на 2px */
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
						<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6" />
						</svg>
					</button>
					<button class="custom-slider-next">
						<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
							<path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6" />
						</svg>
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
				// Увеличиваем отступ на маленьких экранах для стрелок
				let arrowSpace = 80; // Стандартный отступ для стрелок (по 40px с каждой стороны)

				// На маленьких экранах увеличиваем отступ
				if (window.innerWidth < 480) {
					arrowSpace = 90; // Больше места для стрелок на маленьких экранах
				} else if (window.innerWidth < 768) {
					arrowSpace = 85; // Для средних экранов
				}

				const containerWidth = slider.parentElement.clientWidth - arrowSpace;


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

				// Добавляем отступ между элементами
				const itemGap = 5; // отступ между элементами

				// Учитываем отступ при расчете ширины
				itemWidth = (containerWidth - (itemGap * (itemsPerView - 1))) / itemsPerView;

				// Применяем новую ширину и отступы к элементам
				sliderItems.forEach((item, index) => {
					item.style.width = `${itemWidth}px`;

					// Добавляем отступ справа для всех элементов кроме последнего
					if (index < sliderItems.length - 1) {
						item.style.marginRight = `${itemGap}px`;
					} else {
						item.style.marginRight = '0';
					}
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

			// Переменные для отслеживания свайпов
			let touchStartX = 0;
			let touchCurrentX = 0;
			let touchStartTime = 0;
			let isDragging = false;
			let startTransform = 0;
			let lastDelta = 0;
			let velocity = 0;
			let lastMoveTime = 0;
			
			// Начало касания
			slider.addEventListener('touchstart', function(e) {
				if (isAnimating) return;
				
				touchStartX = e.touches[0].clientX;
				touchCurrentX = touchStartX;
				touchStartTime = Date.now();
				lastMoveTime = touchStartTime;
				isDragging = true;
				velocity = 0;
				
				// Запоминаем начальную позицию слайдера
				startTransform = getCurrentTransform();
				
				// Останавливаем анимацию во время свайпа
				slider.style.transition = 'none';
				
				e.preventDefault();
			}, {passive: false});
			
			// Перемещение пальца
			slider.addEventListener('touchmove', function(e) {
				if (!isDragging) return;
				
				const currentX = e.touches[0].clientX;
				const delta = currentX - touchStartX;
				const now = Date.now();
				const dt = now - lastMoveTime;
				
				// Расчет мгновенной скорости свайпа
				if (dt > 0) {
					velocity = (currentX - touchCurrentX) / dt;
				}
				
				touchCurrentX = currentX;
				lastMoveTime = now;
				lastDelta = delta;
				
				// Применяем перемещение с учетом границ
				const maxOffset = Math.max(0, totalItems - itemsPerView) * itemWidth;
				let newOffset = startTransform + delta;
				
				// Добавляем сопротивление при выходе за границы
				if (newOffset > 0) {
					newOffset = newOffset / 3;
				} else if (newOffset < -maxOffset) {
					const overscroll = -(newOffset + maxOffset);
					newOffset = -maxOffset - overscroll / 3;
				}
				
				slider.style.transform = `translateX(${newOffset}px)`;
			}, {passive: true});
			
			// Отпускание пальца
			slider.addEventListener('touchend', function(e) {
				if (!isDragging) return;
				isDragging = false;
				
				// Восстанавливаем анимацию
				slider.style.transition = 'transform 0.3s ease-out';
				
				const touchDuration = Date.now() - touchStartTime;
				const delta = lastDelta;
				
				// Определяем направление свайпа
				if (Math.abs(delta) > 20 || Math.abs(velocity) > 0.5) {
					// Смотрим, сколько элементов нужно прокрутить на основе скорости и расстояния
					let slideChange = 0;
					
					if (Math.abs(delta) > 50) {
						// Базовое определение по расстоянию
						slideChange = Math.sign(delta);
					}
					
					// Добавляем влияние скорости для быстрых свайпов
					if (Math.abs(velocity) > 0.5 && touchDuration < 300) {
						slideChange = Math.sign(velocity) * Math.min(3, Math.floor(Math.abs(velocity) / 0.5));
					}
					
					// Применяем изменение с учетом границ
					if (slideChange !== 0) {
						const maxPosition = Math.max(0, totalItems - itemsPerView);
						const newPosition = Math.max(0, Math.min(maxPosition, currentPosition - slideChange));
						
						// Обновляем позицию
						currentPosition = newPosition;
						slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
					} else {
						// Возвращаем к ближайшему слайду
						snapToSlide();
					}
				} else {
					// Возвращаемся к ближайшему слайду если свайп был слишком слабым
					snapToSlide();
				}
				
				// Обновляем кнопки после свайпа
				updateNavButtons();
			}, {passive: true});
			
			// Функция для получения текущего смещения слайдера
			function getCurrentTransform() {
				const style = window.getComputedStyle(slider);
				const matrix = new WebKitCSSMatrix(style.transform);
				return matrix.e; // Горизонтальное смещение
			}
			
			// Функция для выравнивания по ближайшему слайду
			function snapToSlide() {
				const currentOffset = getCurrentTransform();
				const closestSlide = Math.round(-currentOffset / itemWidth);
				const maxPosition = Math.max(0, totalItems - itemsPerView);
				
				currentPosition = Math.max(0, Math.min(maxPosition, closestSlide));
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
			}
		});
	</script>
<? endif; ?>