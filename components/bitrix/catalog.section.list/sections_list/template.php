<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?if($arResult["SECTIONS"]){?>
<style>
    /* Базовые стили для секций */
    .section_item {
        overflow: hidden;
        transition: all 0.3s ease;
        border-radius: 0; /* Явно сбрасываем border-radius для всех элементов */
    }
    
    /* Стили только для конкретных угловых элементов - применятся через JS */
    .section_item.top-left-corner {
        border-top-left-radius: 15px !important;
    }
    
    .section_item.top-right-corner {
        border-top-right-radius: 15px !important;
    }
    
    .section_item.bottom-left-corner {
        border-bottom-left-radius: 15px !important;
    }
    
    .section_item.bottom-right-corner {
        border-bottom-right-radius: 15px !important;
    }
</style>

<div class="catalog_section_list row items flexbox" id="section-list-container">
	<?foreach( $arResult["SECTIONS"] as $key => $arItems ){
		$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
	?>
		<div class="item_block col-lg-4 col-md-6 col-sm-6" data-index="<?=$key?>">
			<div class="section_item item" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
				<table class="section_item_inner mobile_url">
					<tr data-url="<?=$arItems["SECTION_PAGE_URL"]?>">
						<?if ($arParams["SHOW_SECTION_LIST_PICTURES"]=="Y"):?>
							<?$collspan = 2;?>
							<td class="image">
								<?if($arItems["PICTURE"]["SRC"]):?>
									<?$img = CFile::ResizeImageGet($arItems["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
									<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItems["PICTURE"]["ALT"] ? $arItems["PICTURE"]["ALT"] : $arItems["NAME"])?>" title="<?=($arItems["PICTURE"]["TITLE"] ? $arItems["PICTURE"]["TITLE"] : $arItems["NAME"])?>" /></a>
								<?elseif($arItems["~PICTURE"]):?>
									<?$img = CFile::ResizeImageGet($arItems["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
									<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=($arItems["PICTURE"]["ALT"] ? $arItems["PICTURE"]["ALT"] : $arItems["NAME"])?>" title="<?=($arItems["PICTURE"]["TITLE"] ? $arItems["PICTURE"]["TITLE"] : $arItems["NAME"])?>" /></a>
								<?else:?>
									<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/svg/catalog_category_noimage.svg" alt="<?=$arItems["NAME"]?>" title="<?=$arItems["NAME"]?>" /></a>
								<?endif;?>
							</td>
						<?endif;?>
						<td class="section_info">
							<ul>
								<li class="name">
									<a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="dark_link"><span><?=$arItems["NAME"]?></span></a>
								</li>
								<?if($arItems["SECTIONS"]){
									foreach( $arItems["SECTIONS"] as $arItem ){?>
										<li class="sect"><a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="dark_link"><?=$arItem["NAME"]?></a></li>
									<?}
								}?>
							</ul>
						</td>
					</tr>
					<?if($arParams["SECTIONS_LIST_PREVIEW_DESCRIPTION"]!="N"):?>
						<?$arSection = $section=CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arItems["ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]));?>
						<?if ($arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]):?>
							<tr><td class="desc" <?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]?></span></td></tr>
						<?else:?>
							<tr><td class="desc" <?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arItems["DESCRIPTION"]?></span></td></tr>
						<?endif;?>
					<?endif;?>
				</table>
			</div>
		</div>
	<?}?>
</div>

<script>
	$(document).ready(function() {
		// Обработчик для мобильных устройств
		function isMobile() {
			return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
		}

		if (isMobile()) {
			$('.mobile_url tr').on('click', function(e) {
				e.preventDefault();
				var url = $(this).data('url');
				if (url) {
					window.location.href = url;
				}
			});
		}
        
        // Функция для применения скруглений к угловым элементам
        function applyCornerRadius() {
            var $container = $('#section-list-container');
            var $items = $container.find('.item_block');
            var totalItems = $items.length;
            
            // Сначала удаляем все классы скругления
            $container.find('.section_item').removeClass('top-left-corner top-right-corner bottom-left-corner bottom-right-corner');
            
            if (totalItems === 0) return;
            
            // Определяем количество элементов в строке в зависимости от размера экрана
            var itemsPerRow = (window.innerWidth >= 992) ? 3 : 2;
            
            // Вычисляем количество строк
            var totalRows = Math.ceil(totalItems / itemsPerRow);
            
            // Верхний левый угол (всегда первый элемент)
            $items.eq(0).find('.section_item').addClass('top-left-corner');
            
            // Верхний правый угол 
            // (itemsPerRow-й элемент или последний в первой строке, если элементов меньше)
            var topRightIndex = Math.min(itemsPerRow - 1, totalItems - 1);
            $items.eq(topRightIndex).find('.section_item').addClass('top-right-corner');
            
            // Нижний левый угол (первый элемент в последней строке)
            var bottomLeftIndex = (totalRows - 1) * itemsPerRow;
            // Проверяем, что индекс не выходит за пределы
            if (bottomLeftIndex < totalItems) {
                $items.eq(bottomLeftIndex).find('.section_item').addClass('bottom-left-corner');
            }
            
            // Нижний правый угол (последний элемент)
            $items.eq(totalItems - 1).find('.section_item').addClass('bottom-right-corner');
        }
        
        // Применяем скругления при загрузке
        applyCornerRadius();
        
        // И при изменении размера окна
        $(window).on('resize', function() {
            applyCornerRadius();
        });
	});
</script>
<?}?>