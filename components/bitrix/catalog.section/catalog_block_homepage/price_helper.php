<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $USER;
/**
 * Функция для получения цены товара
 * 
 * @param int $productId ID товара
 * @return array Массив с информацией о цене
 */
function getProductPrice($productId) {
    global $USER; 
    // Получаем базовую цену товара с помощью CPrice::GetBasePrice
    $basePrice = CPrice::GetBasePrice($productId);
    // $discountPrice = CCatalogDiscount::GetDiscountByProduct($productId,$USER->GetUserGroupArray(),
	// "N",
	// 2,
	// SITE_ID);
    // return $discountPrice;
    if (!empty($basePrice)) {
        return [
            'PRICE' => $basePrice["PRICE"],
            'CURRENCY' => $basePrice["CURRENCY"],
            'FORMATTED_PRICE' => CurrencyFormat($basePrice["PRICE"], $basePrice["CURRENCY"]),
            'DISCOUNT_PRICE' => $basePrice["DISCOUNT_PRICE"],
            'FORMATTED_DISCOUNT_PRICE' => !empty($basePrice["DISCOUNT_PRICE"]) ? 
                CurrencyFormat($basePrice["DISCOUNT_PRICE"], $basePrice["CURRENCY"]) : '',
            'HAS_DISCOUNT' => !empty($basePrice["DISCOUNT_PRICE"]) && 
                $basePrice["DISCOUNT_PRICE"] < $basePrice["PRICE"]
        ];
    }
    
    return null;
} 