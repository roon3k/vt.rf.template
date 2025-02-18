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
			<div class="row margin0 flexbox slick-slider" id="slider">
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
				
					<div class="col-md-3 col-sm-4 col-xs-<?= ($bCompactViewMobile ? 12 : 6) ?> new_section_tab" id="sect<?= $arParams['SECTION_VER'] ?>" data-sect="<?= $arSection['ID'] ?>">
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
			<div class="left_gif">
				<img src="/images/left.GIF" style="width:36px;">
			</div>
		</div>
	</div>
	
	<script>
		$(document).ready(function() {
			var $slider = $('.slick-slider');
			// $slider.on('init', function(event, slick){
			// 	setTimeout(function(){
			// 		scrollToElementById('sect' + <?= $arParams['SECTION_VER'] ?>);
			// 	}, 100);
			// });

			// Прокрутка до элемента с id sect130 после инициализации слайдера

			$slider.slick({
				dots: false,
				arrows: true,
				slidesToShow: 4,
				centerMode: true,
				prevArrow: `<button type="button" class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m15 6l-6 6l6 6"/></svg></button>`,
				nextArrow: `<button type="button" class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" d="m9 6l6 6l-6 6"/></svg></button>`,
				slidesToScroll: 1,
				responsive: [{	
						breakpoint: 768,
						settings: {
							arrows: true,
							centerMode: true,
							adaptiveHeight: true,
							slidesToShow: 3,
							slidesToScroll: 1,
						}
					},
					{
						breakpoint: 480,
						settings: {
							arrows: true,
							centerMode: true,
							slidesToShow: 2,
							adaptiveHeight: true,
							slidesToScroll: 1,
						}
					}
				]
			});

			// function scrollToElementById(elementId) {
			// 	var targetElement = $('#' + elementId);
			// 	if (targetElement.length) {
			// 		var slideIndex = targetElement.parent().children().index(targetElement);
			// 		$slider.slick('slickGoTo', slideIndex);
			// 	}
			// }
		});
	</script>
<? endif; ?>
<? endif; ?>