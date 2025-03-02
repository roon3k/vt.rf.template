<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);?>

<!-- Блок "Хиты продаж" -->
<div class="hit_products_block">
    <div class="top_blocks">
        <div class="title_wrapper"><div class="title_block sm">Хиты продаж</div></div>
    </div>
    
    <?
    // Настраиваем фильтр для вывода товаров с свойством "Хит" (ID: 65, XML_ID: HIT)
    global $hitFilter;
    $hitFilter = array(
        "PROPERTY_HIT" => 65, // ID свойства "Хит"
        "ACTIVE" => "Y",
        "SECTION_GLOBAL_ACTIVE" => "Y"
    );
    
    // Выводим товары через компонент
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "catalog_block_homepage",
        array(
            "IBLOCK_TYPE" => "aspro_next_catalog",
            "IBLOCK_ID" => "18",
            "ELEMENT_SORT_FIELD" => "SORT",
            "ELEMENT_SORT_ORDER" => "ASC",
            "PAGE_ELEMENT_COUNT" => "10",
            "LINE_ELEMENT_COUNT" => "4",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "DISPLAY_COMPARE" => "Y",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER2" => "desc",
            "FILTER_NAME" => "hitFilter",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "/basket/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "ADD_PICT_PROP" => "MORE_PHOTO",
            "LABEL_PROP" => array(
                0 => "HIT",
                1 => "RECOMMEND",
                2 => "NEW",
                3 => "STOCK",
            ),
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "MESS_BTN_COMPARE" => "Сравнить",
            "OFFERS_FIELD_CODE" => array(
                0 => "ID",
                1 => "NAME",
                2 => "PREVIEW_PICTURE",
                3 => "",
            ),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "SIZES",
                1 => "COLOR_REF",
                2 => "",
            ),
            "OFFERS_SORT_FIELD" => "sort",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_FIELD2" => "id",
            "OFFERS_SORT_ORDER2" => "desc",
            "OFFERS_LIMIT" => "0",
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => "RUB",
            "HIDE_NOT_AVAILABLE" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
            "PROPERTY_CODE" => array(
                0 => "HIT",
                1 => "RECOMMEND",
                2 => "NEW",
                3 => "",
            ),
            "SHOW_MEASURE" => "Y",
            "DISPLAY_WISH_BUTTONS" => "Y",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "SHOW_OLD_PRICE" => "Y",
            "SHOW_RATING" => "Y",
            "TITLE" => "Хиты продаж",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ""
        ),
        false
    );
    ?>
</div>

<!-- Блок "Рекомендуем" -->
<div class="recommend_products_block">
    <div class="top_blocks">
        <div class="title_wrapper"><div class="title_block sm">Рекомендуем</div></div>
    </div>
    
    <?
    // Настраиваем фильтр для вывода товаров с свойством "Рекомендуем" (ID: 66, XML_ID: RECOMMEND)
    global $recommendFilter;
    $recommendFilter = array(
        "PROPERTY_HIT" => 66, // ID свойства "Рекомендуем"
        "ACTIVE" => "Y",
        "SECTION_GLOBAL_ACTIVE" => "Y"
    );
    
    // Выводим товары через компонент
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "catalog_block_homepage",
        array(
            "IBLOCK_TYPE" => "aspro_next_catalog",
            "IBLOCK_ID" => "18",
            "ELEMENT_SORT_FIELD" => "SORT",
            "ELEMENT_SORT_ORDER" => "ASC",
            "PAGE_ELEMENT_COUNT" => "10",
            "LINE_ELEMENT_COUNT" => "4",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "DISPLAY_COMPARE" => "Y",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER2" => "desc",
            "FILTER_NAME" => "recommendFilter",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "/basket/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "ADD_PICT_PROP" => "MORE_PHOTO",
            "LABEL_PROP" => array(
                0 => "HIT",
                1 => "RECOMMEND",
                2 => "NEW",
                3 => "STOCK",
            ),
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "MESS_BTN_COMPARE" => "Сравнить",
            "OFFERS_FIELD_CODE" => array(
                0 => "ID",
                1 => "NAME",
                2 => "PREVIEW_PICTURE",
                3 => "",
            ),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "SIZES",
                1 => "COLOR_REF",
                2 => "",
            ),
            "OFFERS_SORT_FIELD" => "sort",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_FIELD2" => "id",
            "OFFERS_SORT_ORDER2" => "desc",
            "OFFERS_LIMIT" => "0",
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => "RUB",
            "HIDE_NOT_AVAILABLE" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
            "PROPERTY_CODE" => array(
                0 => "HIT",
                1 => "RECOMMEND",
                2 => "NEW",
                3 => "",
            ),
            "SHOW_MEASURE" => "Y",
            "DISPLAY_WISH_BUTTONS" => "Y",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "SHOW_OLD_PRICE" => "Y",
            "SHOW_RATING" => "Y",
            "TITLE" => "Рекомендуем",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ""
        ),
        false
    );
    ?>
</div>

<!-- Блок "Новинки" -->
<div class="new_products_block">
    <div class="top_blocks">
        <div class="title_wrapper"><div class="title_block sm">Новинки</div></div>
    </div>
    
    <?
    // Настраиваем фильтр для вывода товаров с свойством "Новинка" (ID: 67, XML_ID: NEW)
    global $newFilter;
    $newFilter = array(
        "PROPERTY_HIT" => 67, // ID свойства "Новинка"
        "ACTIVE" => "Y",
        "SECTION_GLOBAL_ACTIVE" => "Y"
    );
    
    // Выводим товары через компонент
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "catalog_block_homepage",
        array(
            "IBLOCK_TYPE" => "aspro_next_catalog",
            "IBLOCK_ID" => "18",
            "ELEMENT_SORT_FIELD" => "SORT",
            "ELEMENT_SORT_ORDER" => "ASC",
            "PAGE_ELEMENT_COUNT" => "10",
            "LINE_ELEMENT_COUNT" => "4",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "36000000",
            "CACHE_GROUPS" => "N",
            "DISPLAY_COMPARE" => "Y",
            "ELEMENT_SORT_FIELD2" => "id",
            "ELEMENT_SORT_ORDER2" => "desc",
            "FILTER_NAME" => "newFilter",
            "INCLUDE_SUBSECTIONS" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
            "BASKET_URL" => "/basket/",
            "ACTION_VARIABLE" => "action",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "AJAX_MODE" => "N",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "ADD_PICT_PROP" => "MORE_PHOTO",
            "LABEL_PROP" => array(
                0 => "HIT",
                1 => "RECOMMEND",
                2 => "NEW",
                3 => "STOCK",
            ),
            "MESS_BTN_BUY" => "Купить",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
            "MESS_BTN_SUBSCRIBE" => "Подписаться",
            "MESS_BTN_DETAIL" => "Подробнее",
            "MESS_NOT_AVAILABLE" => "Нет в наличии",
            "MESS_BTN_COMPARE" => "Сравнить",
            "OFFERS_FIELD_CODE" => array(
                0 => "ID",
                1 => "NAME",
                2 => "PREVIEW_PICTURE",
                3 => "",
            ),
            "OFFERS_PROPERTY_CODE" => array(
                0 => "SIZES",
                1 => "COLOR_REF",
                2 => "",
            ),
            "OFFERS_SORT_FIELD" => "sort",
            "OFFERS_SORT_ORDER" => "asc",
            "OFFERS_SORT_FIELD2" => "id",
            "OFFERS_SORT_ORDER2" => "desc",
            "OFFERS_LIMIT" => "0",
            "SECTION_ID" => "",
            "SECTION_CODE" => "",
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => "RUB",
            "HIDE_NOT_AVAILABLE" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
            "PROPERTY_CODE" => array(
                0 => "HIT",
                1 => "RECOMMEND",
                2 => "NEW",
                3 => "",
            ),
            "SHOW_MEASURE" => "Y",
            "DISPLAY_WISH_BUTTONS" => "Y",
            "SHOW_DISCOUNT_PERCENT" => "Y",
            "SHOW_OLD_PRICE" => "Y",
            "SHOW_RATING" => "Y",
            "TITLE" => "Новинки",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "DISPLAY_TOP_PAGER" => "N",
            "DISPLAY_BOTTOM_PAGER" => "N",
            "PAGER_TITLE" => "",
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => ""
        ),
        false
    );
    ?>
</div>

<style>
.hit_products_block,
.recommend_products_block,
.new_products_block {
    margin-top: 40px;
    margin-bottom: 40px;
}

.hit_products_block .top_blocks,
.recommend_products_block .top_blocks,
.new_products_block .top_blocks {
    margin-bottom: 20px;
}
</style>