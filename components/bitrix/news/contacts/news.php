<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $arRegion, $dopClass;

// correction of the standard option tag for ios, is not normally hidden
$dopClass .= ' iks-on-ios';

$arItemFilter = CNext::GetIBlockAllElementsFilter($arParams);
$arItemSelect = array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'IBLOCK_SECTION_ID', 'PROPERTY_MAP', 'PROPERTY_PHONE', 'PROPERTY_SCHEDULE', 'PROPERTY_METRO', 'PROPERTY_EMAIL', 'PROPERTY_ADDRESS');
$arItems = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, false, false, $arItemSelect);

$arAllSections = array();
$arSchema = array();
if($arItems)
	$arAllSections = CNext::GetSections($arItems, $arParams);
?>
<?if($arParams['SHOW_TOP_MAP'] != 'Y'):?>
	<div class="contacts-page-top">
		<div class="contacts maxwidth-theme" itemscope itemtype="http://schema.org/Organization">
			<div class="row">
				<?$bHasSections = (isset($arAllSections['ALL_SECTIONS']) && $arAllSections['ALL_SECTIONS']);?>
				<?$bHasChildSections = (isset($arAllSections['CHILD_SECTIONS']) && $arAllSections['CHILD_SECTIONS']);?>
				<?if($bHasSections):?>
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-6 col-sm-4">
								<select class="<?=($bHasChildSections ? 'region' : 'city');?>">
									<option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => ($bHasChildSections ? Loc::getMessage('REGION') : Loc::getMessage('CITY'))))?></option>
									<?foreach($arAllSections['ALL_SECTIONS'] as $arSection):?>
										<option value="<?=$arSection['SECTION']['ID'];?>"><?=$arSection['SECTION']['NAME'];?></option>
									<?endforeach;?>
								</select>
							</div>
							<?if($bHasChildSections):?>
								<div class="col-md-6 col-sm-4">
									<select class="city">
										<option value="0" selected><?=Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CITY')))?></option>
										<?foreach($arAllSections['CHILD_SECTIONS'] as $arSection):?>
												<option style="display:none;" disabled="disabled" value="<?=$arSection['ID'];?>" data-parent_section="<?=$arSection['IBLOCK_SECTION_ID'];?>"><?=$arSection['NAME'];?></option>
										<?endforeach;?>
									</select>
								</div>
							<?endif;?>
						</div>
					</div>
				<?endif;?>
				<div class="col-md-<?=($bHasSections ? 6 : 12);?>">
					<div class="row">
						<div class="col-md-6 print-6">
							<?CNext::showContactPhones('', true, '', 'Phone_black2.svg', 'grey');?>
						</div>
						<div class="col-md-6 print-6">
							<?CNext::showContactEmail('E-mail', true, '', 'Email.svg', 'grey');?>
						</div>
					</div>

					<?//hidden text for validate microdata?>
					<div class="hidden">
						<?if($arRegion):?>
							<?if($arRegion["PROPERTY_ADDRESS_VALUE"]["TEXT"]):?>
								<span itemprop="address"><?=$arRegion["PROPERTY_ADDRESS_VALUE"]["TEXT"];?></span>
							<?endif;?>
						<?else:?>
							<span itemprop="address"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-address.php", Array(), Array("MODE" => "html", "NAME" => "Address"));?></span>
						<?endif;?>
						<?global $arSite;?>
						<span itemprop="name"><?=$arSite["NAME"];?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?endif;?>
<div class="ajax_items">
	<?if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y')){
		$APPLICATION->RestartBuffer();?>
	<?}?>
	<?if($arItems):?>

		<?$dbRes = CIBlock::GetProperties($arParams['IBLOCK_ID']);
		while($arRes = $dbRes->Fetch()){
			$arProperties[$arRes['CODE']] = $arRes;
		}?>

		<?$bPostSection = (isset($_POST['ID']) && $_POST['ID']);?>
		<?
		$bUseMap = CNext::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
		$mapLAT = $mapLON = $iCountShops =0;
		$arPlacemarks = array();
		if($bPostSection)
		{
			$arItems = CNextCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array_merge($arItemFilter, array('SECTION_ID' => $_POST['ID'])), false, false, $arItemSelect);
			$GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'] = $_POST['ID'];
		}

		$fullAdress = [];
		foreach($arItems as $i => $arItem)
		{
			if(!empty($arItem["PROPERTY_ADDRESS_VALUE"])){
				$fullAdress[] = $arItem["PROPERTY_ADDRESS_VALUE"];
			}

			if(!empty($arItem["PROPERTY_METRO_VALUE"]) && is_array($arItem["PROPERTY_METRO_VALUE"])) {
				$fullAdress[] = GetMessage('METRO').': '.implode(", ", $arItem["PROPERTY_METRO_VALUE"]);
			} elseif(!empty($arItem["PROPERTY_METRO_VALUE"])) {
				$fullAdress[] = GetMessage('METRO').': '.$arItem["PROPERTY_METRO_VALUE"];
			}

			$arSchema[] = array(
				"@context" => "http://schema.org",
				"@type" => "Store",
				"name" => $arItem["NAME"],
				"description" => $arItem['DESCRIPTION'],
				"openingHours" => (isset($arItem['PROPERTY_SCHEDULE_VALUE']['TEXT']) && strlen($arItem['PROPERTY_SCHEDULE_VALUE']['TEXT']) 
					? $arItem['PROPERTY_SCHEDULE_VALUE']['TEXT'] 
					: ""),
				"telephone" => is_array($arItem["PROPERTY_PHONE_VALUE"]) ? implode(", ", $arItem["PROPERTY_PHONE_VALUE"]) : $arItem["PROPERTY_PHONE_VALUE"],
				"image" => array($_SERVER['SERVER_NAME'].CFile::GetPath($arItem['PREVIEW_PICTURE'])),
				"address" => array(
					"@type" => "PostalAddress",
					"addressLocality" => $arItem["NAME"],
					"streetAddress" => (empty($fullAdress) ? '' : implode(', ', $fullAdress)),
				),
			);
			unset($fullAdress);

			if($arItem['PROPERTY_MAP_VALUE']){
				$arCoords = explode(',', $arItem['PROPERTY_MAP_VALUE']);
				$mapLAT += $arCoords[0];
				$mapLON += $arCoords[1];
				$str_phones = '';
				if($arItem['PHONE'])
				{
					foreach($arShop['PHONE'] as $phone)
					{
						$str_phones .= '<div class="phone"><a rel="nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $phone).'">'.$phone.'</a></div>';
					}
				}

				$html = '<div class="map_info_store scrollbar scrollbar--overflow">';

				$html .= '<div class="title font_mlg"><a href="'.$arItem["DETAIL_PAGE_URL"].'" class="dark_link">'.$arItem['NAME'].($arItem['PROPERTY_ADDRESS_VALUE'] ? ', '.$arItem['PROPERTY_ADDRESS_VALUE'] : '').'</a></div>';
				
				$bSchedule = (isset($arItem['~PROPERTY_SCHEDULE_VALUE']['TEXT']) && strlen($arItem['~PROPERTY_SCHEDULE_VALUE']['TEXT']));
				$bPhone = (isset($arItem['PROPERTY_PHONE_VALUE']) && !empty($arItem['PROPERTY_PHONE_VALUE']));
				$bMetro = (isset($arItem['PROPERTY_METRO_VALUE']) && !empty($arItem['PROPERTY_METRO_VALUE']));
				$bEmail = (isset($arItem['PROPERTY_EMAIL_VALUE']) && !empty($arItem['PROPERTY_EMAIL_VALUE']));

				if ($bSchedule || $bPhone || $bMetro || $bEmail) {
					$html .= '<div class="properties">';
						if ($bMetroValue) {
							$html .= '<div class="property schedule">'.
										'<div class="title-prop font_upper">'.($arProperties['METRO']['NAME'] ?? '').'</div>'.
										'<div class="value font_sm">'.(is_array($arItem['PROPERTY_METRO_VALUE']) ? implode(', ', $arItem['PROPERTY_METRO_VALUE']) : $arItem['PROPERTY_METRO_VALUE']).'</div>'.
									'</div>';
						}
						
						if ($bSchedule) {
							$html .= '<div class="property schedule">'.
										'<div class="title-prop font_upper">'.($arProperties['SCHEDULE']['NAME'] ?? '').'</div>'.
										'<div class="value font_sm">'.$arItem['~PROPERTY_SCHEDULE_VALUE']['TEXT'].'</div>'.
									'</div>';
						}
						
						if ($bPhone) {
							$phone = '';
							if (is_array($arItem['PROPERTY_PHONE_VALUE'])) {
								foreach ($arItem['PROPERTY_PHONE_VALUE'] as $value) {
									$phone .= '<div class="value"><a class="dark_link" rel= "nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $value).'">'.$value.'</a></div>';
								}
							}
							else{
								$phone = '<div class="value font_sm"><a class="dark_link" rel= "nofollow" href="tel:'.str_replace(array(' ', ',', '-', '(', ')'), '', $arItem['PROPERTY_PHONE_VALUE']).'">'.$arItem['PROPERTY_PHONE_VALUE'].'</a></div>';
							
								
							}
							$html .= '<div class="property phone"><div class="title-prop font_upper">'.$arProperties['PHONE']['NAME'].'</div>'.$phone.'</div>';
						}
	
						if ($bEmail) {
							$sEmail = '';
							if (is_array($arItem['PROPERTY_EMAIL_VALUE'])) {
								foreach ($arItem['PROPERTY_EMAIL_VALUE'] as $email) {
									$sEmail .= '<a class="dark_link" href="mailto:'.$email.'">'.$email.'</a>';
								}
							}
							else {
								$sEmail .= '<a class="dark_link" href="mailto:'.$arItem['PROPERTY_EMAIL_VALUE'].'">'.$arItem['PROPERTY_EMAIL_VALUE'].'</a>';
							}
	
							$html .= '<div class="property email">'.
										'<div class="title-prop font_upper">'.$arProperties['EMAIL']['NAME'].'</div>'.
										'<div class="value font_sm">'.$sEmail.'</div>'.
									'</div>';
						}
					$html .= '</div></div>';
				}
				$arPlacemarks[] = array(
					"ID" => $arItem["ID"],
					"LAT" => $arCoords[0],
					"LON" => $arCoords[1],
					"TEXT" => $html,
					"HTML" => '<div class="title">'.(strlen($arShop["URL"]) ? '<a href="'.$arShop["URL"].'">' : '').$arShop["ADDRESS"].(strlen($arShop["URL"]) ? '</a>' : '').'</div><div class="info-content">'.($arShop['METRO'] ? $arShop['METRO_PLACEMARK_HTML'] : '').(strlen($arShop['SCHEDULE']) ? '<div class="schedule">'.$arShop['SCHEDULE'].'</div>' : '').$str_phones.(strlen($arShop['EMAIL']) ? '<div class="email"><a rel="nofollow" href="mailto:'.$arShop['EMAIL'].'">'.$arShop['EMAIL'].'</a></div>' : '').'</div>'.(strlen($arShop['URL']) ? '<a rel="nofollow" class="button" href="'.$arShop["URL"].'"><span>'.GetMessage('DETAIL').'</span></a>' : '')
				);
				++$iCountShops;
				$arSchema[$i]["geo"] = array("@type" => "GeoCoordinates", "latitude" => floatval($mapLAT / $iCountShops), "longitude" => floatval($mapLON / $iCountShops));
			}
		}

		if($iCountShops && $bUseMap)
		{
			$mapLAT = floatval($mapLAT / $iCountShops);
			$mapLON = floatval($mapLON / $iCountShops);?>
			<?if($arParams['SHOW_TOP_MAP'] == 'Y'):?>
				<?$this->SetViewTarget('yandex_map');?>
			<?endif;?>
			<div class="contacts-page-map">
				<?if(CNext::GetFrontParametrValue('CONTACTS_TYPE_MAP') == 'YANDEX') { ?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:map.yandex.view",
					"map",
					array(
						"INIT_MAP_TYPE" => "MAP",
						"MAP_DATA" => serialize(array("yandex_lat" => $mapLAT, "yandex_lon" => $mapLON, "yandex_scale" => 19, "PLACEMARKS" => $arPlacemarks)),
						"MAP_WIDTH" => "100%",
						"MAP_HEIGHT" => "420",
						"CONTROLS" => array(
							0 => "ZOOM",
							1 => "TYPECONTROL",
							2 => "SCALELINE",
						),
						"OPTIONS" => array(
							0 => "ENABLE_DBLCLICK_ZOOM",
							1 => "ENABLE_DRAGGING",
						),
						"MAP_ID" => "MAP_v33",
						"COMPONENT_TEMPLATE" => "map"
					),
					false
				);
				}?>
				<?if(CNext::GetFrontParametrValue('CONTACTS_TYPE_MAP') == 'GOOGLE') { ?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:map.google.view",
					"map",
					array(
						"INIT_MAP_TYPE" => "MAP",
						"MAP_DATA" => serialize(array("google_lat" => $mapLAT, "google_lon" => $mapLON, "google_scale" => 19, "PLACEMARKS" => $arPlacemarks)),
						"MAP_WIDTH" => "100%",
						"MAP_HEIGHT" => "420",
						"CONTROLS" => array(
							0 => "SMALL_ZOOM_CONTROL",
							1 => "TYPECONTROL",
							2 => "SCALELINE",
						),
						"OPTIONS" => array(
							0 => "ENABLE_DBLCLICK_ZOOM",
							1 => "ENABLE_DRAGGING",
						),
						"MAP_ID" => "MAP_v33",
						"COMPONENT_TEMPLATE" => "map"
					),
					false
				);
				}?>
				
			</div>
			<?if($arParams['SHOW_TOP_MAP'] == 'Y'):?>
				<?$this->EndViewTarget();?>
			<?endif;?>
		<?}?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:news.list",
			"contacts",
			Array(
				"COUNT_IN_LINE" => $arParams["COUNT_IN_LINE"],
				"SHOW_SECTION_PREVIEW_DESCRIPTION" => $arParams["SHOW_SECTION_PREVIEW_DESCRIPTION"],
				"VIEW_TYPE" => $arParams["VIEW_TYPE"],
				"SHOW_TABS" => $arParams["SHOW_TABS"],
				"IMAGE_POSITION" => $arParams["IMAGE_POSITION"],
				"IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
				"IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
				"NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
				"SORT_BY1"	=>	$arParams["SORT_BY1"],
				"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
				"SORT_BY2"	=>	$arParams["SORT_BY2"],
				"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
				"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
				"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
				"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
				"SET_TITLE"	=>	$arParams["SET_TITLE"],
				"SET_STATUS_404" => $arParams["SET_STATUS_404"],
				"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
				"ADD_SECTIONS_CHAIN"	=>	$arParams["ADD_SECTIONS_CHAIN"],
				"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
				"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
				"CACHE_FILTER"	=>	"Y",
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
				"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
				"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
				"PAGER_TEMPLATE"	=>	($arParams["PAGER_TEMPLATE"] !== 'main' ? $arParams["PAGER_TEMPLATE"] : ".default" ), // template ajax 'main' temporarily not supported for contacts
				"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
				"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
				"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
				"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
				"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
				"DISPLAY_NAME"	=>	$arParams["DISPLAY_NAME"],
				"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
				"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
				"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
				"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
				"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
				"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
				"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
				"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
				"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
				"PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
				"PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
				"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
				"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
				"INCLUDE_SUBSECTIONS" => "Y",
				"SHOW_DETAIL_LINK" => $arParams["SHOW_DETAIL_LINK"],
			),
			$component
		);?>
		<script type="application/ld+json"><?=str_replace("'", "\"", CUtil::PhpToJSObject($arSchema, false, true));?></script>
		<?CNext::checkRestartBuffer();?>
	<?endif;?>
</div>