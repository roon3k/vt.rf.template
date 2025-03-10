<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? $bCompactViewMobile = $arParams['COMPACT_VIEW_MOBILE'] === 'Y'; ?>
<?php
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
				transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1);
				justify-content: center;
				width: 100%;
				/* Добавляем внутренние отступы вместо отступов контейнера */
				padding: 0 40px;
				will-change: transform; /* Оптимизация производительности */
				-webkit-backface-visibility: hidden; /* Предотвращение мерцания при анимации */
			}
			
			/* Улучшенная плавность для мобильных устройств */
			@media (max-width: 767px) {
				.custom-slider {
					transition: transform 0.6s cubic-bezier(0.1, 0.7, 0.1, 1);
					-webkit-overflow-scrolling: touch; /* Инерционный скролл для iOS */
					touch-action: pan-x; /* Оптимизация для сенсорных устройств */
				}
				
				/* В режиме перетаскивания - без transition для мгновенной реакции */
				.custom-slider.dragging {
					transition: none !important;
				}
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

			/* Удаляем ненужные элементы */
			.left_gif {
				display: none;
			}

			/* Стили для лоадера товаров */
			.products-loader {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: rgba(255, 255, 255, 0.7);
				display: flex;
				align-items: center;
				justify-content: center;
				z-index: 100;
			}

			.products-loader .spinner {
				width: 50px;
				height: 50px;
				border: 5px solid #f3f3f3;
				border-top: 5px solid #4086F1;
				border-radius: 50%;
				animation: spin 1s linear infinite;
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
					<?php
					// Возвращаем исходную логику с array_reverse, но меняем обработку активного элемента
					// Перемещаем элемент с ID, равным $arParams['SECTION_VER'], на первое место
					$activeKey = null;
					foreach ($arResult['SECTIONS'] as $key => $arSection) {
						if ($arSection['ID'] == $arParams['SECTION_VER']) {
							$activeKey = $key;
							break;
						}
					}

					// Сохраняем активный элемент в отдельной переменной
					$activeSectionData = null;
					if ($activeKey !== null) {
						$activeSectionData = $arResult['SECTIONS'][$activeKey];
					}

					// Теперь выводим секции, применяя array_reverse и особую обработку для активного элемента
					$reversedSections = array_reverse($arResult['SECTIONS']);

					// Если нашли активную секцию, выводим её отдельно первой
					if ($activeSectionData): ?>
						<div class="custom-slider-item item-section" data-sect="<?= $activeSectionData['ID'] ?>" data-level="<?= $activeSectionData['DEPTH_LEVEL'] ?? '1' ?>">
							<div class="item active" id="<?= $this->GetEditAreaId($activeSectionData['ID']); ?>">
								<div class="img shine">
									<?php if ($activeSectionData["PICTURE"]): ?>
										<?php $img = CFile::ResizeImageGet($activeSectionData["PICTURE"], array("width" => 120, "height" => 120), BX_RESIZE_IMAGE_EXACT, true); ?>
										<a href="<?= $activeSectionData["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= $img["src"] ?>" title="<?= $activeSectionData["NAME"] ?>" /></a>
									<?php elseif ($activeSectionData["~PICTURE"]): ?>
										<?php $img = CFile::ResizeImageGet($activeSectionData["~PICTURE"], array("width" => 120, "height" => 120), BX_RESIZE_IMAGE_EXACT, true); ?>
										<a href="<?= $activeSectionData["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= $img["src"] ?>" title="<?= $activeSectionData["NAME"] ?>" /></a>
									<?php else: ?>
										<a href="<?= $activeSectionData["SECTION_PAGE_URL"] ?>" class="thumb"><img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/catalog_category_noimage.svg" alt="<?= $activeSectionData["NAME"] ?>" title="<?= $activeSectionData["NAME"] ?>" /></a>
									<?php endif; ?>
								</div>
								<div class="name">
									<a href="<?= $activeSectionData['SECTION_PAGE_URL']; ?>" class="dark_link"><?= $activeSectionData['NAME']; ?></a>
								</div>
							</div>
						</div>
					<?php endif;

					// Затем выводим все остальные секции
					foreach ($reversedSections as $arSection):
						// Пропускаем активную секцию, т.к. она уже выведена выше
						if ($activeSectionData && $arSection['ID'] == $activeSectionData['ID']) continue;
					?>
						<div class="custom-slider-item item-section" data-sect="<?= $arSection['ID'] ?>" data-level="<?= $arSection['DEPTH_LEVEL'] ?? '1' ?>">
							<div class="item" id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
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
			// Получаем элементы лоадера
			const loader = document.getElementById('section-carousel-loader');

			// Функция для предзагрузки всех изображений
			function preloadAllImages() {
				return new Promise(function(resolve) {
					const images = [];
					let loadedCount = 0;
					const imgElements = document.querySelectorAll('#main-sections-carousel img');
					const totalImages = imgElements.length;

					// Если нет изображений, сразу возвращаем результат
					if (totalImages === 0) {
						resolve();
						return;
					}

					imgElements.forEach(function(imgEl) {
						const imgSrc = imgEl.getAttribute('src');
						if (imgSrc) {
							const img = new Image();

							img.onload = function() {
								loadedCount++;
								if (loadedCount === totalImages) {
									resolve();
								}
							};

							img.onerror = function() {
								loadedCount++;
								if (loadedCount === totalImages) {
									resolve();
								}
							};

							img.src = imgSrc;
							images.push(img);
						} else {
							loadedCount++;
							if (loadedCount === totalImages) {
								resolve();
							}
						}
					});

					// Страховка: если по какой-то причине не все изображения загрузились за 3 секунды
					setTimeout(function() {
						resolve();
					}, 3000);
				});
			}

			// Функция для загрузки товаров по AJAX - ВОССТАНАВЛИВАЕМ ИЗНАЧАЛЬНУЮ ВЕРСИЮ
			function loadProducts(sectionId, sectionLevel) {
				// Находим контейнер с товарами
				const productsContainer = document.querySelector('.catalog_block.items.block_list.grid-list');

				if (!productsContainer) {
					console.error('Контейнер для товаров не найден на странице');
					return;
				}

				// Запоминаем родительский контейнер для корректного обновления
				const parentContainer = productsContainer.parentNode;

				// Создаем и показываем индикатор загрузки
				const loader = document.createElement('div');
				loader.className = 'products-loader';
				loader.innerHTML = '<div class="spinner"></div>';

				// Позиционируем loader относительно контейнера товаров
				parentContainer.style.position = 'relative';
				parentContainer.appendChild(loader);

				// Формируем данные для запроса
				const formData = new FormData();
				formData.append('ajax', 'Y');
				formData.append('SECTION_ID', sectionId);
				if (sectionLevel) {
					formData.append('SECTION_LEVEL', sectionLevel);
				}

				// Сохраняем текущую позицию скролла
				const scrollPosition = window.scrollY;

				// Выполняем AJAX-запрос
				fetch('/ajax/load_products.php', {
						method: 'POST',
						body: formData,
						credentials: 'same-origin'
					})
					.then(response => {
						if (!response.ok) {
							throw new Error('Ошибка сети: ' + response.statusText);
						}
						return response.text();
					})
					.then(html => {
						try {
							// Пытаемся распарсить как JSON
							const response = JSON.parse(html);

							// Если успешно распарсили как JSON, обрабатываем JSON-ответ
							if (response.success) {
								if (response.productsHtml) {
									const tempDiv = document.createElement('div');
									tempDiv.innerHTML = response.productsHtml;

									const newProductsList = tempDiv.querySelector('.catalog_block.items.block_list.grid-list');
									if (newProductsList) {
										productsContainer.innerHTML = newProductsList.innerHTML;
										productsContainer.className = newProductsList.className;
									} else {
										productsContainer.innerHTML = response.productsHtml;
									}
								} else if (response.html) {
									productsContainer.innerHTML = response.html;
								}

								// Обновляем URL без перезагрузки страницы
								if (response.sectionUrl) {
									window.history.pushState({
										sectionId
									}, document.title, response.sectionUrl);
								}
							} else if (response.error) {
								productsContainer.innerHTML = '<div class="alert alert-danger">' + response.error + '</div>';
							} else {
								throw new Error('Некорректный формат JSON');
							}

							// Обновляем фильтр, если он есть в ответе
							if (response.filterHtml) {
								try {
									// Находим контейнер фильтра
									const filterContainer = document.querySelector('.bx_filter.bx_filter_vertical.swipeignore');

									if (filterContainer) {
										// Создаем временный div для парсинга HTML
										const tempDiv = document.createElement('div');
										tempDiv.innerHTML = response.filterHtml;

										// Находим фильтр в полученном HTML
										const newFilter = tempDiv.querySelector('.bx_filter.bx_filter_vertical.swipeignore') ||
											tempDiv.querySelector('.bx_filter_vertical') ||
											tempDiv.querySelector('.bx_filter');

										if (newFilter) {
											// Обновляем содержимое фильтра
											const filterInner = filterContainer.querySelector('.bx_filter_section');
											const newFilterInner = newFilter.querySelector('.bx_filter_section');

											if (filterInner && newFilterInner) {
												// Обновляем HTML фильтра
												filterInner.innerHTML = newFilterInner.innerHTML;

												// Инициализируем фильтр
												if (typeof JCSmartFilter !== 'undefined') {
													try {
														// Если есть существующий объект фильтра
														if (window.smartFilter && typeof window.smartFilter.bindPostEvents === 'function') {
															window.smartFilter.bindPostEvents();
														} else {
															// Иначе создаем новый
															const filterForm = document.querySelector('#smartfilter');
															if (filterForm) {
																window.smartFilter = new JCSmartFilter(filterForm.getAttribute('action'));
															}
														}

														// Инициализация элементов интерфейса
														if (typeof initSelectItem === 'function') {
															initSelectItem();
														}

														// Инициализация ползунков
														if (typeof initSlider === 'function') {
															const sliders = filterContainer.querySelectorAll('.bx_ui_slider_track');
															sliders.forEach(slider => {
																if (slider.id) {
																	initSlider(slider.id);
																}
															});
														}

														console.log('Фильтр успешно обновлен');
													} catch (e) {
														console.error('Ошибка при инициализации фильтра:', e);
													}
												}
											} else {
												console.error('Внутренние элементы фильтра не найдены');
											}
										} else {
											console.error('Новый фильтр не найден в ответе');
										}
									} else {
										console.error('Контейнер фильтра не найден на странице');
									}
								} catch (e) {
									console.error('Ошибка при обновлении фильтра:', e);
								}
							}
						} catch (e) {
							// Если не JSON, считаем ответ HTML-кодом
							console.log('Получен HTML-ответ вместо JSON:', e);

							// Парсим полученный HTML
							const tempDiv = document.createElement('div');
							tempDiv.innerHTML = html;

							// Находим в нем контейнер с товарами
							const newProductsList = tempDiv.querySelector('.catalog_block.items.block_list.grid-list');
							if (newProductsList) {
								productsContainer.innerHTML = newProductsList.innerHTML;
								productsContainer.className = newProductsList.className;
							} else {
								// Если не нашли контейнер, выводим весь HTML
								productsContainer.innerHTML = html;
							}
						}

						// Восстанавливаем позицию скролла
						window.scrollTo(0, scrollPosition);

						// Запускаем инициализацию скриптов
						if (typeof BX !== 'undefined' && BX.ajax && BX.ajax.processScripts) {
							BX.ajax.processScripts(BX.processHTML(html).SCRIPT);
						}

						// Запускаем событие для уведомления других скриптов
						document.dispatchEvent(new CustomEvent('products-loaded', {
							detail: {
								sectionId: sectionId,
								sectionLevel: sectionLevel
							}
						}));
					})
					.catch(error => {
						console.error('Ошибка загрузки товаров:', error);
						productsContainer.innerHTML = '<div class="alert alert-danger">Не удалось загрузить товары. Пожалуйста, попробуйте еще раз.</div>';
					})
					.finally(() => {
						// Удаляем индикатор загрузки
						if (loader && loader.parentNode) {
							loader.parentNode.removeChild(loader);
						}
					});
			}

			// Предзагрузка изображений перед инициализацией слайдера
			preloadAllImages().then(function() {
				// Скрываем лоадер
				if (loader) {
					loader.style.display = 'none';
				}

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
					
					// Определяем количество элементов в видимой области
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
					itemWidth = ((containerWidth - (itemGap * (itemsPerView - 1))) / itemsPerView) + 20;
					
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
					
					// Проверяем текущую позицию и корректируем при необходимости
					adjustPosition();

					// Обновляем видимость кнопок навигации
					updateNavButtons();

					// Проверка активного элемента - он всегда должен быть виден
					const activeItem = document.querySelector('.item-section .item.active');
					if (activeItem) {
						const activeSection = activeItem.closest('.item-section');
						if (activeSection) {
							// Обновляем текущую позицию, если активный элемент не виден
							const activeIndex = Array.from(sliderItems).indexOf(activeSection);

							// Если активный элемент находится за пределами видимой области
							if (activeIndex >= 0 && (activeIndex < currentPosition || activeIndex >= currentPosition + itemsPerView)) {
								// Устанавливаем позицию так, чтобы активный элемент был виден
								currentPosition = Math.min(Math.max(0, activeIndex), Math.max(0, totalItems - itemsPerView));

								// Применяем трансформацию
								slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;

								// Обновляем кнопки навигации после изменения позиции
								updateNavButtons();
							}
						}
					}
				}

				function adjustPosition() {
					// Получаем максимально возможную позицию слайдера
					const maxPosition = Math.max(0, totalItems - itemsPerView);

					// Ограничиваем текущую позицию
					currentPosition = Math.min(Math.max(0, currentPosition), maxPosition);

					// Если элементов меньше или равно количеству отображаемых, то центрируем их
					if (totalItems <= itemsPerView) {
						// Центрируем элементы
						slider.style.justifyContent = 'center';
						slider.style.transform = 'translateX(0)';
					} else {
						// Иначе сдвигаем слайдер
						slider.style.justifyContent = 'flex-start';
						slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
					}
				}

				// Функция обновления состояния кнопок навигации
				function updateNavButtons() {
					// Показываем/скрываем кнопки в зависимости от текущей позиции
					if (totalItems <= itemsPerView) {
						// Если все элементы помещаются, скрываем обе кнопки
						prevBtn.style.display = 'none';
						nextBtn.style.display = 'none';
					} else {
						// Если элементов больше чем может быть отображено
						const maxPosition = totalItems - itemsPerView;

						// Левая кнопка активна только если мы не в начале
						prevBtn.style.opacity = currentPosition <= 0 ? '0.5' : '1';
						prevBtn.style.pointerEvents = currentPosition <= 0 ? 'none' : 'auto';

						// Правая кнопка активна только если мы не в конце
						nextBtn.style.opacity = currentPosition >= maxPosition ? '0.5' : '1';
						nextBtn.style.pointerEvents = currentPosition >= maxPosition ? 'none' : 'auto';

						// Показываем обе кнопки
						prevBtn.style.display = 'flex';
						nextBtn.style.display = 'flex';
					}
				}

				// Поддержка свайпов для мобильных устройств
				let touchStartX = 0;
				let touchEndX = 0;
				let touchStartY = 0;
				let touchEndY = 0;
				let isDragging = false;
				let startTranslateX = 0;
				let currentDragPosition = 0;
				let lastMoveX = 0;
				let moveSpeed = 0;
				let moveTimestamp = 0;
				let animationId = null;

				slider.addEventListener('touchstart', function(e) {
					// Останавливаем любую текущую анимацию
					if (animationId) {
						cancelAnimationFrame(animationId);
						animationId = null;
					}
					
					// Сохраняем начальные точки касания
					touchStartX = e.changedTouches[0].clientX;
					touchStartY = e.changedTouches[0].clientY;
					
					// Сохраняем текущее положение слайдера
					startTranslateX = getCurrentTranslate();
					isDragging = true;
					moveSpeed = 0;
					lastMoveX = touchStartX;
					moveTimestamp = Date.now();
					
					// Добавляем класс dragging для отключения transition
					slider.classList.add('dragging');
				}, {
					passive: true
				});
				
				slider.addEventListener('touchmove', function(e) {
					if (!isDragging) return;
					
					const touchX = e.changedTouches[0].clientX;
					const touchY = e.changedTouches[0].clientY;
					
					// Проверяем, не вертикальный ли это скролл
					const diffX = Math.abs(touchX - touchStartX);
					const diffY = Math.abs(touchY - touchStartY);
					
					// Если движение больше вертикальное, чем горизонтальное - выходим
					if (diffY > diffX && diffY > 10) {
						isDragging = false;
						return;
					}
					
					// Предотвращаем скролл страницы при горизонтальном свайпе
					if (diffX > 10 && diffX > diffY) {
						e.preventDefault();
					}
					
					// Вычисляем новую позицию слайдера
					const deltaX = touchX - touchStartX;
					currentDragPosition = startTranslateX + deltaX;
					
					// Добавляем сопротивление при попытке проскроллить за границы
					const maxScroll = 0;
					const minScroll = -((totalItems - itemsPerView) * itemWidth);
					
					if (currentDragPosition > maxScroll) {
						// Сопротивление при скролле влево от начала
						currentDragPosition = maxScroll + (currentDragPosition - maxScroll) * 0.3;
					} else if (currentDragPosition < minScroll) {
						// Сопротивление при скролле вправо от конца
						currentDragPosition = minScroll + (currentDragPosition - minScroll) * 0.3;
					}
					
					// Применяем новую позицию
					slider.style.transform = `translateX(${currentDragPosition}px)`;
					
					// Вычисляем скорость движения для инерции
					const now = Date.now();
					const timeElapsed = now - moveTimestamp;
					if (timeElapsed > 0) {
						moveSpeed = (touchX - lastMoveX) / timeElapsed;
						moveTimestamp = now;
						lastMoveX = touchX;
					}
				}, {
					passive: false
				});
				
				slider.addEventListener('touchend', function(e) {
					if (!isDragging) return;
					
					touchEndX = e.changedTouches[0].clientX;
					touchEndY = e.changedTouches[0].clientY;
					isDragging = false;
					
					// Удаляем класс dragging для включения transition
					slider.classList.remove('dragging');
					
					// Рассчитываем инерцию
					let momentum = moveSpeed * 100; // Множитель инерции
					
					// Ограничиваем максимальную инерцию
					momentum = Math.min(Math.max(momentum, -20), 20);
					
					// Предполагаемая конечная позиция с учетом инерции
					let projectedPosition = currentDragPosition + momentum;
					
					// Определяем ближайшую позицию слайда для снаппинга
					let nearestSlidePosition = Math.round(projectedPosition / -itemWidth) * itemWidth * -1;
					
					// Проверяем границы
					const maxScroll = 0;
					const minScroll = -((totalItems - itemsPerView) * itemWidth);
					
					nearestSlidePosition = Math.min(Math.max(nearestSlidePosition, minScroll), maxScroll);
					
					// Вычисляем новую позицию в единицах элементов
					currentPosition = Math.round(Math.abs(nearestSlidePosition) / itemWidth);
					
					// Обеспечиваем, чтобы не выйти за границы
					currentPosition = Math.min(Math.max(currentPosition, 0), totalItems - itemsPerView);
					
					// Применяем анимированный переход к ближайшему слайду
					slider.style.transform = `translateX(${-currentPosition * itemWidth}px)`;
					
					// Обновляем состояние кнопок
					updateNavButtons();
				}, {
					passive: true
				});
				
				// Функция получения текущей позиции transform: translateX
				function getCurrentTranslate() {
					const style = window.getComputedStyle(slider);
					const matrix = new WebKitCSSMatrix(style.transform);
					return matrix.e;
				}

				// Обрабатываем клики на кнопки навигации
				function prevSlide() {
					if (isAnimating) return;
					isAnimating = true;

					// Проверяем, не в начале ли мы
					if (currentPosition > 0) {
						currentPosition = Math.max(0, currentPosition - 1);
						
						// Анимированное перемещение с плавным переходом
						slider.style.transform = `translateX(${-currentPosition * itemWidth}px)`;

						// Обновляем состояние кнопок
						updateNavButtons();
					}

					// Защита от быстрых множественных кликов
					setTimeout(() => {
						isAnimating = false;
					}, 600);
				}

				function nextSlide() {
					if (isAnimating) return;
					isAnimating = true;

					// Проверяем, не в конце ли мы
					const maxPosition = Math.max(0, totalItems - itemsPerView);
					if (currentPosition < maxPosition) {
						currentPosition = Math.min(currentPosition + 1, maxPosition);
						
						// Анимированное перемещение с плавным переходом
						slider.style.transform = `translateX(${-currentPosition * itemWidth}px)`;

						// Обновляем состояние кнопок
						updateNavButtons();
					}

					setTimeout(() => {
						isAnimating = false;
					}, 600);
				}
				
				// Функция определения мобильного устройства
				function isMobileDevice() {
					return window.innerWidth < 768;
				}

				// Привязываем обработчики событий
				prevBtn.addEventListener('click', prevSlide);
				nextBtn.addEventListener('click', nextSlide);

				// Обработчик для активации раздела при клике
				sliderItems.forEach((item, index) => {
					const sliderItem = item.querySelector('.item');

					item.addEventListener('click', function(e) {
						// Важно: предотвращаем стандартное действие для ссылок
						e.preventDefault();

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

				// Инициализация слайдера и обработка изменений размера окна
				updateSizes();
				window.addEventListener('resize', updateSizes);

				// Обработка навигации по истории браузера (кнопки Назад/Вперед)
				window.addEventListener('popstate', function(event) {
					if (event.state && event.state.sectionId) {
						// Находим элемент слайдера по ID секции
						const sectionItem = document.querySelector(`.item-section[data-sect="${event.state.sectionId}"]`);
						if (sectionItem) {
							// Программно запускаем клик по этому элементу
							sectionItem.click();
						}
					}
				});
			});
		});
	</script>
<? endif; ?>