<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<?php
?>
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
				/* Важно для обрезки выходящего за границы контента */
				padding: 0;
				/* Убираем отступы, чтобы контент не заходил за край */
			}

			.custom-slider {
				display: flex;
				transition: transform 0.4s ease;
				justify-content: center;
				width: 100%;
				/* Добавляем внутренние отступы вместо отступов контейнера */
				padding: 0 40px;
			}

			.custom-slider-item {
				flex: 0 0 auto;
				transition: all 0.3s ease;
				display: flex;
				justify-content: center;
			}

			/* Стили для компактной карусели */
			#main-sections-carousel .item-section {
				padding: 10px;
				transition: all 0.3s ease;
				text-align: center;
			}

			#main-sections-carousel .item {
				text-align: center;
				padding: 15px;
				border-radius: 12px;
				transition: all 0.3s ease;
				cursor: pointer;
				background: #fff;
				height: 100%;
				width: 100%;
				max-width: 200px;
				margin: 0 auto;
			}

			/* Эффект увеличения при наведении на весь элемент */
			#main-sections-carousel .item:hover {
				transform: scale(1.05);
				z-index: 2;
				position: relative;
				box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
			}

			#main-sections-carousel .item.active {
				box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
				border: 2px solid #318582;
			}

			#main-sections-carousel .img {
				margin-bottom: 10px;
				overflow: hidden;
				display: flex;
				justify-content: center;
				align-items: center;
			}

			#main-sections-carousel .img img {
				max-width: 100%;
				height: auto;
				margin: 0 auto;
				width: 100px;
				height: 100px;
			}

			#main-sections-carousel .name {
				font-size: 14px;
				font-weight: 500;
				line-height: 1.3;
				margin-top: 8px;
				height: 34px;
				overflow: hidden;
				text-align: center;
			}

			/* Стили для стрелок навигации - должны быть поверх контента */
			.custom-slider-nav {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				pointer-events: none;
				z-index: 100;
				/* Увеличиваем z-index чтобы стрелки были поверх элементов */
			}

			.custom-slider-prev,
			.custom-slider-next {
				position: absolute;
				top: 50%;
				transform: translateY(-50%);
				background: rgba(255, 255, 255, 0.9);
				color: #333;
				width: 36px;
				height: 36px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				box-shadow: 0 4px 8px rgba(0,0,0,0.15);
				transition: all 0.3s ease;
				pointer-events: auto;
				z-index: 101;
				cursor: pointer;
				border: none;
			}

			.custom-slider-prev {
				left: 2px;
			}

			.custom-slider-next {
				right: 2px;
			}

			/* Стили для лоадера */
			.section-loader {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				background: rgba(255, 255, 255, 0.9);
				z-index: 200;
				/* Лоадер должен быть поверх всего */
			}

			.section-loader .spinner {
				width: 40px;
				height: 40px;
				border: 4px solid #f3f3f3;
				border-top: 4px solid #4086F1;
				border-radius: 50%;
				animation: spin 1s linear infinite;
				margin-bottom: 10px;
			}

			.section-loader .loading-text {
				font-size: 14px;
				color: #333;
			}

			@keyframes spin {
				0% {
					transform: rotate(0deg);
				}

				100% {
					transform: rotate(360deg);
				}
			}

			/* Создаем градиентные маски по краям для визуального обозначения обрезки */
			.custom-slider-container::before,
			.custom-slider-container::after {
				content: "";
				position: absolute;
				top: 0;
				width: 40px;
				height: 100%;
				z-index: 99;
				/* Выше элементов, но ниже стрелок */
				pointer-events: none;
				/* Чтобы клики проходили сквозь маску */
			}

			.custom-slider-container::before {
				left: 0;
				background: linear-gradient(to right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0));
			}

			.custom-slider-container::after {
				right: 0;
				background: linear-gradient(to left, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0));
			}

			/* Удаляем ненужные элементы */
			.left_gif {
				display: none;
			}

			/* Адаптивные стили */
			@media (max-width: 767px) {
				.custom-slider-prev,
				.custom-slider-next {
					width: 30px;
					height: 30px;
				}
				
				.custom-slider-prev {
					left: 0;
				}
				
				.custom-slider-next {
					right: 0;
				}
				
				.custom-slider-container::before,
				.custom-slider-container::after {
					width: 30px;
				}
				
				.custom-slider {
					padding: 0 35px;
				}

				.custom-slider-container::before,
				.custom-slider-container::after {
					width: 30px;
					/* Уменьшаем ширину маски */
				}

				#main-sections-carousel .name {
					font-size: 13px;
					height: 30px;
				}

				#main-sections-carousel .item {
					padding: 10px;
				}
			}
		</style>

		<div class="list items">
			<div class="custom-slider-container" id="section-carousel-wrapper">
				<div id="section-carousel-loader" class="section-loader">
					<div class="spinner"></div>
					<div class="loading-text">Загрузка изображений...</div>
				</div>
				<div class="custom-slider" id="main-sections-carousel">
					<? $reversedSections = array_reverse($arResult['SECTIONS']); // Обратный порядок разделов 
					?>
					<? foreach ($reversedSections as $arSection):

						$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
						$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM'))); ?>

						<div class="custom-slider-item item-section" data-sect="<?= $arSection['ID'] ?>">
							<div class="item" id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
								<? if ($arParams["SHOW_SECTION_LIST_PICTURES"] != "N"): ?>
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
								<? endif; ?>
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
			const sliderContainer = document.querySelector('.custom-slider-container');
			const slider = sliderContainer.querySelector('.custom-slider');
			const sliderItems = Array.from(sliderContainer.querySelectorAll('.custom-slider-item'));
			const prevBtn = sliderContainer.querySelector('.slider-arrow-prev');
			const nextBtn = sliderContainer.querySelector('.slider-arrow-next');
			const totalItems = sliderItems.length;
			let itemsPerView = 1;
			let itemWidth = 0;
			let currentPosition = 0;
			let isAnimating = false;

			// Функция для установки начального активного элемента
			function setInitialActiveItem() {
				// Проверяем, есть ли в URL параметр section_id
				const urlParams = new URLSearchParams(window.location.search);
				const sectionId = urlParams.get('section_id');
				
				if (sectionId) {
					// Находим элемент с указанным section_id
					const activeItemIndex = sliderItems.findIndex(item => 
						item.getAttribute('data-sect') === sectionId
					);
					
					if (activeItemIndex >= 0) {
						// Добавляем активный класс
						sliderItems[activeItemIndex].querySelector('.item').classList.add('active');
						
						// Прокручиваем слайдер к выбранному элементу
						if (activeItemIndex > itemsPerView - 1) {
							currentPosition = activeItemIndex - (itemsPerView - 1);
							slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
						}
						
						return;
					}
				}
				
				// Если нет параметра section_id или не найден элемент, устанавливаем первый элемент активным
				if (sliderItems.length > 0) {
					sliderItems[0].querySelector('.item').classList.add('active');
				}
			}

			// Функция для обновления размеров элементов при изменении размера окна
			function updateSizes() {
				// Получаем ширину контейнера и определяем количество элементов в зависимости от ширины экрана
				const containerWidth = sliderContainer.offsetWidth;
				const windowWidth = window.innerWidth;
				
				if (windowWidth < 768) {
					itemsPerView = Math.max(1, Math.floor(containerWidth / 120)); // На мобильных устройствах
				} else if (windowWidth < 992) {
					itemsPerView = Math.max(2, Math.floor(containerWidth / 180)); // На планшетах
				} else {
					itemsPerView = Math.max(2, Math.floor(containerWidth / 260)); // На десктопах
				}

				// Ширина элемента с учетом пространства для стрелок навигации
				// Оставляем 40px с каждой стороны для стрелок, если кнопки показаны
				const navWidth = prevBtn && prevBtn.offsetWidth > 0 ? 80 : 0;
				itemWidth = (containerWidth - navWidth) / itemsPerView;
				
				// Устанавливаем ширину элементов
				sliderItems.forEach(item => {
					item.style.width = `${itemWidth}px`;
				});
				
				// Проверяем, не вышла ли текущая позиция за границы слайдера после изменения размеров
				const maxPosition = Math.max(0, totalItems - itemsPerView);
				currentPosition = Math.min(currentPosition, maxPosition);
				
				// Обновляем положение слайдера
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
				
				// Обновляем видимость кнопок навигации
				updateNavButtons();
			}

			// Функция для обновления состояния кнопок навигации
			function updateNavButtons() {
				if (prevBtn && nextBtn) {
					prevBtn.classList.toggle('disabled', currentPosition <= 0);
					nextBtn.classList.toggle('disabled', currentPosition >= totalItems - itemsPerView);
				}
			}

			// Функции для переключения слайдов
			function prevSlide() {
				if (isAnimating || currentPosition <= 0) return;
				
				isAnimating = true;
				currentPosition = Math.max(0, currentPosition - 1);
				
				slider.style.transition = 'transform 0.3s ease-out';
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
				
				setTimeout(() => {
					isAnimating = false;
				}, 300);
				
				updateNavButtons();
			}

			function nextSlide() {
				const maxPosition = Math.max(0, totalItems - itemsPerView);
				if (isAnimating || currentPosition >= maxPosition) return;
				
				isAnimating = true;
				currentPosition = Math.min(maxPosition, currentPosition + 1);
				
				slider.style.transition = 'transform 0.3s ease-out';
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
				
				setTimeout(() => {
					isAnimating = false;
				}, 300);
				
				updateNavButtons();
			}

			// Назначаем обработчики событий для кнопок навигации
			if (prevBtn && nextBtn) {
				prevBtn.addEventListener('click', prevSlide);
				nextBtn.addEventListener('click', nextSlide);
			}

			// Переменные для отслеживания свайпов
			let touchStartX = 0;
			let touchCurrentX = 0;
			let touchStartTime = 0;
			let isDragging = false;
			let startTransform = 0;
			let lastDelta = 0;
			let velocity = 0;
			let lastMoveTime = 0;
			let isTap = true; // Флаг для определения тапа

			// Функция для получения текущего смещения слайдера
			function getCurrentTransform() {
				const style = window.getComputedStyle(slider);
				const matrix = new WebKitCSSMatrix(style.transform);
				return matrix.e; // Горизонтальное смещение
			}

			// Начало касания
			slider.addEventListener('touchstart', function(e) {
				if (isAnimating) return;
				
				touchStartX = e.touches[0].clientX;
				touchCurrentX = touchStartX;
				touchStartTime = Date.now();
				lastMoveTime = touchStartTime;
				isDragging = true;
				isTap = true; // Предполагаем, что это может быть тап
				velocity = 0;
				
				// Запоминаем начальную позицию слайдера
				startTransform = getCurrentTransform();
				
				// Останавливаем анимацию во время свайпа
				slider.style.transition = 'none';
			}, {passive: true});

			// Перемещение пальца
			slider.addEventListener('touchmove', function(e) {
				if (!isDragging) return;
				
				const currentX = e.touches[0].clientX;
				const delta = currentX - touchStartX;
				
				// Если палец двигается больше чем на 10px, это не тап
				if (Math.abs(delta) > 10) {
					isTap = false;
				}
				
				const now = Date.now();
				const dt = now - lastMoveTime;
				
				// Расчет мгновенной скорости свайпа
				if (dt > 0) {
					velocity = (currentX - touchCurrentX) / dt;
				}
				
				touchCurrentX = currentX;
				lastMoveTime = now;
				lastDelta = delta;
				
				// Применяем перемещение только если это не тап
				if (!isTap) {
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
				}
			}, {passive: true});

			// Отпускание пальца
			slider.addEventListener('touchend', function(e) {
				if (!isDragging) return;
				isDragging = false;
				
				// Обрабатываем тап (короткое касание)
				if (isTap) {
					// Находим элемент, по которому был сделан тап
					const touch = e.changedTouches[0];
					const tappedElement = document.elementFromPoint(touch.clientX, touch.clientY);
					
					// Ищем родительский элемент .custom-slider-item
					const sliderItem = tappedElement.closest('.custom-slider-item');
					
					if (sliderItem) {
						// Находим ссылку внутри слайда
						const link = sliderItem.querySelector('a');
						if (link && link.href) {
							// Переходим по ссылке
							window.location.href = link.href;
							return;
						}
						
						// Симулируем клик на элемент, если нет прямой ссылки
						sliderItem.click();
					}
					
					return;
				}
				
				// Восстанавливаем анимацию для свайпа
				slider.style.transition = 'transform 0.3s ease-out';
				
				const touchDuration = Date.now() - touchStartTime;
				const delta = lastDelta;
				
				// Если это был свайп, а не тап
				if (!isTap && (Math.abs(delta) > 20 || Math.abs(velocity) > 0.5)) {
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

			// Функция для выравнивания по ближайшему слайду
			function snapToSlide() {
				const currentOffset = getCurrentTransform();
				const closestSlide = Math.round(-currentOffset / itemWidth);
				const maxPosition = Math.max(0, totalItems - itemsPerView);
				
				currentPosition = Math.max(0, Math.min(maxPosition, closestSlide));
				slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
			}
			
			// Обработчик для активации раздела при клике
			sliderItems.forEach((item, index) => {
				const sliderItem = item.querySelector('.item');
				
				item.addEventListener('click', function(e) {
					// Проверяем, не было ли движения пальцем (свайп)
					if (isDragging || isAnimating) {
						return;
					}
					
					// Находим ссылку внутри элемента
					const link = item.querySelector('a');
					
					// Если есть ссылка, переходим по ней
					if (link && link.href) {
						window.location.href = link.href;
						return;
					}
					
					// Если нет прямой ссылки, активируем раздел
					// Убираем активный класс со всех элементов
					sliderItems.forEach(el => {
						el.querySelector('.item').classList.remove('active');
					});
					
					// Добавляем активный класс текущему элементу
					sliderItem.classList.add('active');
					
					// Загружаем товары для выбранного раздела через AJAX
					const sectionId = item.getAttribute('data-sect');
					const sectionLevel = parseInt(item.getAttribute('data-level') || "1");
					
					if (sectionId) {
						loadProducts(sectionId, sectionLevel);
					}
					
					// Если элемент находится вне видимой области, делаем его видимым
					if (index < currentPosition) {
						// Если элемент находится левее видимой области
						currentPosition = index;
					} else if (index >= currentPosition + itemsPerView) {
						// Если элемент находится правее видимой области
						currentPosition = index - itemsPerView + 1;
					}
					
					slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
					
					// Обновляем состояние кнопок
					updateNavButtons();
				});
			});

			// Инициализация слайдера
			setInitialActiveItem();
			updateSizes();
			
			// Обработка изменений размера окна
			window.addEventListener('resize', updateSizes);
		});
	</script>
<? endif; ?>