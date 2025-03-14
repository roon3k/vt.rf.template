<?$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"main3",
	Array(
		"USE_REGION" => ($arRegion ? "Y" : "N"),
		"SECTION_TIZER"=>$arSection["UF_TIZERS"],
		"SHOW_UNABLE_SKU_PROPS"=>$arParams["SHOW_UNABLE_SKU_PROPS"],
		"ALT_TITLE_GET" => $arParams["ALT_TITLE_GET"],
		"GRUPPER_PROPS" => $arParams["GRUPPER_PROPS"],
		"SHOW_DISCOUNT_TIME_EACH_SKU" => $arParams["SHOW_DISCOUNT_TIME_EACH_SKU"],
		"SHOW_ARTICLE_SKU" => $arParams["SHOW_ARTICLE_SKU"],
		"SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
		"STORES_FILTER" => ($arParams["STORES_FILTER"] ? $arParams["STORES_FILTER"] : "TITLE"),
		"STORES_FILTER_ORDER" => ($arParams["STORES_FILTER_ORDER"] ? $arParams["STORES_FILTER_ORDER"] : "SORT_ASC"),
		"WIDE_BLOCK" => $isWideBlock,
		"HIDE_LEFT_BLOCK" => $hide_left_block,
		"DETAIL_DOCS_PROP"=>$arParams["DETAIL_DOCS_PROP"],
		"SHOW_DISCOUNT_TIME"=>$arParams["SHOW_DISCOUNT_TIME"],
		"TYPE_SKU" => ($typeSKU ? $typeSKU : $arTheme["TYPE_SKU"]["VALUE"]),
		"SEF_URL_TEMPLATES" => $arParams["SEF_URL_TEMPLATES"],
		"IBLOCK_REVIEWS_TYPE" => $arParams["IBLOCK_REVIEWS_TYPE"],
		"IBLOCK_REVIEWS_ID" => $arParams["IBLOCK_REVIEWS_ID"],
		"SHOW_ONE_CLICK_BUY" => $arParams["SHOW_ONE_CLICK_BUY"],
		"SEF_MODE_BRAND_SECTIONS" => $arParams["SEF_MODE_BRAND_SECTIONS"],
		"SEF_MODE_BRAND_ELEMENT" => $arParams["SEF_MODE_BRAND_ELEMENT"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"BLOCK_LANDINGS_NAME" => (isset($arParams["BLOCK_LANDINGS_NAME"]) ? $arParams["BLOCK_LANDINGS_NAME"] : "N" ),
		"DISPLAY_ELEMENT_SLIDER" => $arParams["DISPLAY_ELEMENT_SLIDER"],
		"TITLE_SLIDER" => $arParams["TITLE_SLIDER"],
		"DETAIL_ASSOCIATED_TITLE" => $arParams["DETAIL_ASSOCIATED_TITLE"],
		"DETAIL_EXPANDABLES_TITLE" => $arParams["DETAIL_EXPANDABLES_TITLE"],
		"RECOMEND_COUNT" => $arParams["RECOMEND_COUNT"],
		"MESSAGES_PER_PAGE" => $arParams["MESSAGES_PER_PAGE"],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"BUNDLE_ITEMS_COUNT" => $arParams["BUNDLE_ITEMS_COUNT"],
		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SHOW_CHEAPER_FORM" => $arParams["SHOW_CHEAPER_FORM"],
		"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
		"SET_LAST_MODIFIED" => "Y",
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"PRICE_CODE" => $arParams['PRICE_CODE'],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"USE_RATIO_IN_RANGES" => $arParams["USE_RATIO_IN_RANGES"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],
		"USE_ALSO_BUY" => $arParams["USE_ALSO_BUY"],
		'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
		'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"SKU_DISPLAY_LOCATION" => $arParams["SKU_DISPLAY_LOCATION"],
		"SKU_DETAIL_ID" => $arParams["SKU_DETAIL_ID"],
		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
		"ADD_ELEMENT_CHAIN" => $arParams["ADD_ELEMENT_CHAIN"],
		"USE_STORE" => $arParams["USE_STORE"],
		"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
		"USE_STORE_SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
		"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
		"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
		"STORE_PATH" => $arParams["STORE_PATH"],
		"MAIN_TITLE" => $arParams["MAIN_TITLE"],
		"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"IBLOCK_STOCK_ID" => $arParams["IBLOCK_STOCK_ID"],
		"SEF_MODE_STOCK_SECTIONS" => $arParams["SEF_MODE_STOCK_SECTIONS"],
		"SHOW_QUANTITY" => $arParams["SHOW_QUANTITY"],
		"SHOW_QUANTITY_COUNT" => $arParams["SHOW_QUANTITY_COUNT"],
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		'HIDE_NOT_AVAILABLE_LINKED' => $arParams['HIDE_NOT_AVAILABLE'],
		'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
		"USE_ELEMENT_COUNTER" => $arParams["USE_ELEMENT_COUNTER"],
		'STRICT_SECTION_CHECK' => (isset($arParams['DETAIL_STRICT_SECTION_CHECK']) ? $arParams['DETAIL_STRICT_SECTION_CHECK'] : ''),
		'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),

		"USE_RATING" => $arParams["USE_RATING"],
		"USE_REVIEW" => $arParams["USE_REVIEW"],
		"REVIEWS_VIEW" => $arTheme["REVIEWS_VIEW"]["VALUE"],
		"REVIEW_COMMENT_REQUIRED" => $arParams["REVIEW_COMMENT_REQUIRED"],
		"REVIEW_FILTER_BUTTONS" => $arParams["REVIEW_FILTER_BUTTONS"],
		"REAL_CUSTOMER_TEXT" => $arParams["REAL_CUSTOMER_TEXT"],
		"FORUM_ID" => $arParams["FORUM_ID"],
		"MAX_AMOUNT" => $arParams["MAX_AMOUNT"],
		"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
		"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
		"DEFAULT_COUNT" => $arParams["DEFAULT_COUNT"],
		"SHOW_BRAND_PICTURE" => $arParams["SHOW_BRAND_PICTURE"],
		"PROPERTIES_DISPLAY_LOCATION" => $arParams["PROPERTIES_DISPLAY_LOCATION"],
		"PROPERTIES_DISPLAY_TYPE" => $arParams["PROPERTIES_DISPLAY_TYPE"],
		"SHOW_ADDITIONAL_TAB" => $arParams["SHOW_ADDITIONAL_TAB"],
		"SHOW_ASK_BLOCK" => $arParams["SHOW_ASK_BLOCK"],
		"ASK_FORM_ID" => $arParams["ASK_FORM_ID"],
		"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
		"SHOW_HINTS" => $arParams["SHOW_HINTS"],
		"OFFER_HIDE_NAME_PROPS" => $arParams["OFFER_HIDE_NAME_PROPS"],
		"SHOW_KIT_PARTS" => $arParams["SHOW_KIT_PARTS"],
		"SHOW_KIT_PARTS_PRICES" => $arParams["SHOW_KIT_PARTS_PRICES"],
		"SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
		"SHOW_DISCOUNT_PERCENT" => $arParams["SHOW_DISCOUNT_PERCENT"],
		"SHOW_OLD_PRICE" => $arParams["SHOW_OLD_PRICE"],
		'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
		'OFFER_SHOW_PREVIEW_PICTURE_PROPS' => $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS'],
		'USE_PREDICTION' => $arParams['USE_DETAIL_PREDICTION'],
		'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
		"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
		"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
		"USER_FIELDS" => $arParams['USER_FIELDS'],
		"FIELDS" => $arParams['FIELDS'],
		"STORES" => $arParams['STORES'],
		"BIG_DATA_RCM_TYPE" => $arParams['BIG_DATA_RCM_TYPE'],
		"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"SALE_STIKER" => $arParams["SALE_STIKER"],
		"STIKERS_PROP" => $arParams["STIKERS_PROP"],
		"SHOW_RATING" => $arParams["SHOW_RATING"],

		"OFFERS_LIMIT" => $arParams["DETAIL_OFFERS_LIMIT"],
		"DISPLAY_ELEMENT_SLIDER" => $arParams["DISPLAY_ELEMENT_SLIDER"],
		"LIST_VIEW" => $arParams["LIST_VIEW"],

		'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
		"DETAIL_PICTURE_MODE" => (isset($arTheme["DETAIL_PICTURE_MODE"]["VALUE"]) ? $arTheme["DETAIL_PICTURE_MODE"]["VALUE"] : 'POPUP'),

		'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),
		'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
		'SET_VIEWED_IN_COMPONENT' => (isset($arParams['DETAIL_SET_VIEWED_IN_COMPONENT']) ? $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] : ''),

		'SHOW_SLIDER' => (isset($arParams['DETAIL_SHOW_SLIDER']) ? $arParams['DETAIL_SHOW_SLIDER'] : ''),
		'SLIDER_INTERVAL' => (isset($arParams['DETAIL_SLIDER_INTERVAL']) ? $arParams['DETAIL_SLIDER_INTERVAL'] : ''),
		'SLIDER_PROGRESS' => (isset($arParams['DETAIL_SLIDER_PROGRESS']) ? $arParams['DETAIL_SLIDER_PROGRESS'] : ''),
		'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
		'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),

		"CALCULATE_DELIVERY" => $arTheme["CALCULATE_DELIVERY"]["VALUE"],
		"EXPRESSION_FOR_CALCULATE_DELIVERY" => $arTheme["EXPRESSION_FOR_CALCULATE_DELIVERY"]["VALUE"],

		"USE_GIFTS_DETAIL" => $arParams['USE_GIFTS_DETAIL']?: 'Y',
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST']?: 'Y',
		"GIFTS_SHOW_DISCOUNT_PERCENT" => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
		"GIFTS_SHOW_OLD_PRICE" => $arParams['GIFTS_SHOW_OLD_PRICE'],
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
		"GIFTS_DETAIL_BLOCK_TITLE" => $arParams["GIFTS_DETAIL_BLOCK_TITLE"],
		"GIFTS_SHOW_NAME" => $arParams['GIFTS_SHOW_NAME'],
		"GIFTS_SHOW_IMAGE" => $arParams['GIFTS_SHOW_IMAGE'],
		"GIFTS_MESS_BTN_BUY" => $arParams['GIFTS_MESS_BTN_BUY'],

		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],

		"TAB_OFFERS_NAME" => $arParams["TAB_OFFERS_NAME"],
		"TAB_DESCR_NAME" => $arParams["TAB_DESCR_NAME"],
		"TAB_CHAR_NAME" => $arParams["TAB_CHAR_NAME"],
		"TAB_VIDEO_NAME" => $arParams["TAB_VIDEO_NAME"],
		"TAB_REVIEW_NAME" => ($arParams["TAB_REVIEW_NAME"] ? $arParams["TAB_REVIEW_NAME"] : GetMessage("REVIEW_TAB")),
		"TAB_FAQ_NAME" => $arParams["TAB_FAQ_NAME"],
		"TAB_STOCK_NAME" => $arParams["TAB_STOCK_NAME"],
		"TAB_DOPS_NAME" => $arParams["TAB_DOPS_NAME"],
		"SHOW_PAYMENT" => (isset($arParams["SHOW_PAYMENT"]) ? $arParams["SHOW_PAYMENT"] : "Y"),
		"SHOW_DELIVERY" => (isset($arParams["SHOW_DELIVERY"]) ? $arParams["SHOW_DELIVERY"] : "Y"),
		"SHOW_HOW_BUY" => (isset($arParams["SHOW_HOW_BUY"]) ? $arParams["SHOW_HOW_BUY"] : "Y"),
		"TITLE_HOW_BUY" => ($arParams["TITLE_HOW_BUY"] ? $arParams["TITLE_HOW_BUY"] : GetMessage("TITLE_HOW_BUY")),
		"TITLE_DELIVERY" => ($arParams["TITLE_DELIVERY"] ? $arParams["TITLE_DELIVERY"] : GetMessage("TITLE_DELIVERY")),
		"TITLE_PAYMENT" => ($arParams["TITLE_PAYMENT"] ? $arParams["TITLE_PAYMENT"] : GetMessage("TITLE_PAYMENT")),
		"BLOCK_SERVICES_NAME" => $arParams["BLOCK_SERVICES_NAME"],
		"BLOCK_DOCS_NAME" => $arParams["BLOCK_DOCS_NAME"],
		"CHEAPER_FORM_NAME" => $arParams["CHEAPER_FORM_NAME"],
		"USE_ADDITIONAL_GALLERY" => $arParams["USE_ADDITIONAL_GALLERY"],
		"ADDITIONAL_GALLERY_TYPE" => $arParams["ADDITIONAL_GALLERY_TYPE"],
		"ADDITIONAL_GALLERY_PROPERTY_CODE" => $arParams["ADDITIONAL_GALLERY_PROPERTY_CODE"],
		"ADDITIONAL_GALLERY_OFFERS_PROPERTY_CODE" => $arParams["ADDITIONAL_GALLERY_OFFERS_PROPERTY_CODE"],
		"BLOCK_ADDITIONAL_GALLERY_NAME" => $arParams["BLOCK_ADDITIONAL_GALLERY_NAME"],

		"BLOCK_BLOG_NAME" => (isset($arParams["BLOCK_BLOG_NAME"]) && $arParams["BLOCK_BLOG_NAME"] ? $arParams["BLOCK_BLOG_NAME"] : GetMessage("BLOCK_BLOG_NAME")),
		"BLOG_IBLOCK_ID" => (isset($arParams["BLOG_IBLOCK_ID"]) && $arParams["BLOG_IBLOCK_ID"] ? $arParams["BLOG_IBLOCK_ID"] : CNextCache::$arIBlocks[SITE_ID]['aspro_next_content']['aspro_next_articles'][0]),

		"VISIBLE_PROP_COUNT" => ($arParams['VISIBLE_PROP_COUNT'] ? $arParams['VISIBLE_PROP_COUNT'] : 4),
		"USE_BIG_DATA" => $arParams['USE_BIG_DATA'],
		"USE_SHARE" => $arParams["USE_SHARE"],

		"BLOG_URL" => $arParams["BLOG_URL"],
		"DETAIL_BLOG_EMAIL_NOTIFY" => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : 'Y'),
		"MAX_IMAGE_SIZE" => (isset($arParams['MAX_IMAGE_SIZE']) ? $arParams['MAX_IMAGE_SIZE'] : '0.5'),
		"MAX_IMAGE_COUNT" => $arParams['MAX_IMAGE_COUNT'] ? $arParams['MAX_IMAGE_COUNT'] : '10',
		
		"DETAIL_BLOCKS_ORDER" => ($arParams["DETAIL_BLOCKS_ORDER"] ? $arParams["DETAIL_BLOCKS_ORDER"] : 'tizers,complect,nabor,tabs,stores,char,galery,exp_goods,services,gifts,goods,podborki,blog,recomend_goods,assoc_goods'),
		"DETAIL_BLOCKS_TAB_ORDER" => ($arParams["DETAIL_BLOCKS_TAB_ORDER"] ? $arParams["DETAIL_BLOCKS_TAB_ORDER"] : 'offers,desc,char,buy,payment,delivery,video,reviews,ask,stores,custom_tab'),
		"DETAIL_BLOCKS_ALL_ORDER" => ($arParams["DETAIL_BLOCKS_ALL_ORDER"] ? $arParams["DETAIL_BLOCKS_ALL_ORDER"] : 'tizers,complect,nabor,offers,desc,char,galery,video,stores,exp_goods,reviews,gifts,ask,services,docs,custom_tab,goods,recomend_goods,podborki,blog,assoc_goods,buy,payment,delivery'),
		"TITLE_SLIDER" => $arParams["TITLE_SLIDER"],
		"DETAIL_ASSOCIATED_TITLE" => $arParams["DETAIL_ASSOCIATED_TITLE"],
		"DETAIL_EXPANDABLES_TITLE" => $arParams["DETAIL_EXPANDABLES_TITLE"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"VIEW_BLOCK_TYPE" => $arParams["VIEW_BLOCK_TYPE"],
		"FILTER_NAME" => $arParams["FILTER_NAME"], // !need to filter similar goods
		"BIG_DATA_SHOW_FROM_SECTION" => $arParams["BIG_DATA_SHOW_FROM_SECTION"],
		"ADDITIONAL_VIDEO_PROPERTY_CODE" => $arParams["ADDITIONAL_VIDEO_PROPERTY_CODE"],

		
		"SECTION_ID_FOR_BIG_DATA" => $arSection['ID'], 
		"BIGDATA_PATH_TEMPLATE" => $this->__folder.'/page_blocks/'.$sViewBigDataExtTemplate.'.php',
		"BIGDATA_TYPE_VIEW" => $arParams['BIGDATA_TYPE_VIEW'],
		"BIG_DATA_SHOW_FROM_SECTION" => $arParams['BIG_DATA_SHOW_FROM_SECTION'],
		"BIGDATA_SET_COUNT_BOTTOM" =>  $arParams["BIGDATA_SET_COUNT_BOTTOM"],
		"RCM_PROD_ID" => $arResult['ID'],
		"BIGDATA_COUNT" => $arParams["BIGDATA_COUNT"],
		"BIG_DATA_FILTER_IDS" => $arParams['BIG_DATA_FILTER_IDS'],		
	),
	$component
);?>

<?//bigdata?>
    <?if($arParams['USE_BIG_DATA'] == 'Y' && $arParams['BIGDATA_TYPE_VIEW'] === 'RIGHT' ):?>
        <?
        $GLOBALS['CATALOG_CURRENT_ELEMENT_ID'] = $ElementID;
        
        $GLOBALS['arrFilterBigData']['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
        CNext::makeElementFilterInRegion($GLOBALS['arrFilterBigData']);
        ?>
        <div class="bigdata-wrapper bigdata_recommended_products_items"><?include_once($_SERVER["DOCUMENT_ROOT"].$this->__folder.'/page_blocks/'.$sViewBigDataExtTemplate.'.php');?></div>
    <?endif;?>