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
				/* Делаем более непрозрачным */
				color: #333;
				width: 40px;
				height: 40px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
				transition: all 0.3s ease;
				pointer-events: auto;
				z-index: 101;
				/* Стрелки должны быть поверх всего */
				cursor: pointer;
				border: none;
			}

			.custom-slider-prev:hover,
			.custom-slider-next:hover {
				background: #fff;
				box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
			}

			/* Позиционируем стрелки на границах контейнера */
			.custom-slider-prev {
				left: 0;
			}

			.custom-slider-next {
				right: 0;
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
				.custom-slider {
					padding: 0 30px;
					/* Уменьшаем отступы на мобильных */
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

			// Предзагрузка изображений перед инициализацией слайдера
			preloadAllImages().then(function() {
				// Скрываем лоадер
				loader.style.display = 'none';

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
					const containerWidth = slider.parentElement.clientWidth - 80; // Учитываем размер места для стрелок

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

					// Проверяем текущую позицию и корректируем при необходимости
					adjustPosition();

					// Обновляем видимость кнопок навигации
					updateNavButtons();
				}

				function adjustPosition() {
					// Получаем максимально возможную позицию слайдера
					// Если элементов меньше или равно количеству отображаемых, то max = 0
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

				// Функция для обновления состояния кнопок навигации
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

				// Обработчики кнопок навигации
				function prevSlide() {
					if (isAnimating) return;
					isAnimating = true;

					// Проверяем, не в начале ли мы
					if (currentPosition > 0) {
						currentPosition = Math.max(0, currentPosition - 1);
						slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;

						// Обновляем состояние кнопок
						updateNavButtons();
					}

					// Защита от быстрых множественных кликов
					setTimeout(() => {
						isAnimating = false;
					}, 400); // Время анимации в стилях
				}

				function nextSlide() {
					if (isAnimating) return;
					isAnimating = true;

					// Проверяем, не в конце ли мы
					const maxPosition = Math.max(0, totalItems - itemsPerView);
					if (currentPosition < maxPosition) {
						currentPosition = Math.min(currentPosition + 1, maxPosition);
						slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;

						// Обновляем состояние кнопок
						updateNavButtons();
					}

					setTimeout(() => {
						isAnimating = false;
					}, 400);
				}

				// Привязываем обработчики событий
				prevBtn.addEventListener('click', prevSlide);
				nextBtn.addEventListener('click', nextSlide);

				// Обработчик для активации раздела при клике
				sliderItems.forEach((item, index) => {
					const sliderItem = item.querySelector('.item');

					item.addEventListener('click', function(e) {
						// Если клик был не по ссылке, а по самому элементу
						if (!e.target.closest('a')) {
							e.preventDefault();

							// Убираем активный класс со всех элементов
							sliderItems.forEach(el => {
								el.querySelector('.item').classList.remove('active');
							});

							// Добавляем активный класс текущему элементу
							sliderItem.classList.add('active');

							// Прокручиваем к этому элементу
							const maxPosition = Math.max(0, totalItems - itemsPerView);
							currentPosition = Math.max(0, Math.min(index, maxPosition));
							slider.style.transform = `translateX(-${currentPosition * itemWidth}px)`;

							// Обновляем состояние кнопок
							updateNavButtons();
						}
					});
				});

				// Инициализация слайдера и обработка изменений размера окна
				updateSizes();
				window.addEventListener('resize', updateSizes);

				// Поддержка свайпов для мобильных устройств
				let touchStartX = 0;
				let touchEndX = 0;

				slider.addEventListener('touchstart', function(e) {
					touchStartX = e.changedTouches[0].screenX;
				}, {
					passive: true
				});

				slider.addEventListener('touchend', function(e) {
					touchEndX = e.changedTouches[0].screenX;
					handleSwipe();
				}, {
					passive: true
				});

				function handleSwipe() {
					const swipeThreshold = 50; // минимальное расстояние для регистрации свайпа

					// Не обрабатываем свайпы, если все элементы помещаются на экране
					if (totalItems <= itemsPerView) return;

					if (touchEndX - touchStartX > swipeThreshold) {
						// Свайп вправо - только если не в начале
						if (currentPosition > 0) {
							prevSlide();
						}
					} else if (touchStartX - touchEndX > swipeThreshold) {
						// Свайп влево - только если не в конце
						const maxPosition = Math.max(0, totalItems - itemsPerView);
						if (currentPosition < maxPosition) {
							nextSlide();
						}
					}
				}
			});
		});
	</script>
<? endif; ?>