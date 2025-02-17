<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
global $arTheme, $arRegion;
$arRegions = CNextRegionality::getRegions();
if($arRegion)
	$bPhone = ($arRegion['PHONES'] ? true : false);
else
	$bPhone = ((int)$arTheme['HEADER_PHONES'] ? true : false);
$logoClass = ($arTheme['COLORED_LOGO']['VALUE'] !== 'Y' ? '' : ' colored');
?>
<div class="header-v5 header-wrapper">
	<div class="logo_and_menu-row">
		<div class="phones-bar">
<div class="contacts__list container">
	<div class="contacts__item contacts__item--address">
		<div class="contacts__address-item">
			<span class="contacts__name">Адрес</span>
			<span class="contacts__link">Барклая 8, ТЦ Старая Горбушка, первый этаж, магазин 125</span>
		</div>
		<div class="contacts__address-item"> <span class="contacts__name">Ежедневно</span> <span class="contacts__link">10:00 – 20:00</span></div>
	</div>
	<div class="contacts__item contacts__item--phone">
		<div class="phone">
			<a class="elem-link contacts__link" href="tel:+79032222456">
				<span class="contacts__name">Заказ: </span>
				<span>+7 (903) 222 24-56</span>
			</a>
		</div>
		<div class="phone">
			<a class="elem-link contacts__link" href="tel:+79998343720">
				<span class="contacts__name">Техническая поддержка: </span>
				<span>+7 (999) 834 37-20</span>
			</a>
		</div>
	</div>
</div>
		</div>
		<div class="logo-row">
			<div class="maxwidth-theme update-header">
				<div class="row">
					<div class="logo-block col-md-2 col-sm-3">
						<div class="logo<?=$logoClass?> header-5-logo">
							<?=CNext::ShowLogo();?>
						</div>
					</div>
					<?if($arRegions):?>
						<div class="inline-block pull-left">
							<div class="top-description">
								<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
									array(
										"COMPONENT_TEMPLATE" => ".default",
										"PATH" => SITE_DIR."include/top_page/regionality.list.php",
										"AREA_FILE_SHOW" => "file",
										"AREA_FILE_SUFFIX" => "",
										"AREA_FILE_RECURSIVE" => "Y",
										"EDIT_TEMPLATE" => "include_area.php"
									),
									false
								);?>
							</div>
						</div>
					<?endif;?>
					<div class="col-md-<?=($arRegions ? 2 : 3);?> search_wrap">
						<div class="search-block inner-table-block">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/top_page/search.title.catalog.php",
									"EDIT_TEMPLATE" => "include_area.php"
								)
							);?>
						</div>
					</div>
					<div class="right-icons pull-left">
						<div class="pull-right">
							<?=CNext::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon inner-table-block baskets');?>
						</div>
						<div class="pull-right">
							<div class="wrap_icon inner-table-block">
								<?=CNext::showCabinetLink(true, false, 'big');?>
							</div>
						</div>
						<div class="pull-right">
							<div class="wrap_icon inner-table-block">
								<div class="phone-block" style="display:none;">
									<?if($bPhone):?>
										<?CNext::ShowHeaderPhones('lg');?>
									<?endif?>
									<?if($arTheme['SHOW_CALLBACK']['VALUE'] == 'Y'):?>
										<div class="inline-block"  style="margin-top:10px;">
											<!-- <span class="callback-block animate-load twosmallfont colored" data-event="jqm" data-param-form_id="CALLBACK" data-name="callback"><?=GetMessage("CALLBACK")?></span> -->
											<a href="https://wa.me/79032222456?text=Здравствуйте%2C+у+меня+есть+вопрос" class="callback-block animate-load twosmallfont colored">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2c-5.46 0-9.91 4.45-9.91 9.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21c5.46 0 9.91-4.45 9.91-9.91c0-2.65-1.03-5.14-2.9-7.01m-7.01 15.24c-1.48 0-2.93-.4-4.2-1.15l-.3-.18l-3.12.82l.83-3.04l-.2-.31a8.26 8.26 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24c2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c.02 4.54-3.68 8.23-8.22 8.23m4.52-6.16c-.25-.12-1.47-.72-1.69-.81c-.23-.08-.39-.12-.56.12c-.17.25-.64.81-.78.97c-.14.17-.29.19-.54.06c-.25-.12-1.05-.39-1.99-1.23c-.74-.66-1.23-1.47-1.38-1.72c-.14-.25-.02-.38.11-.51c.11-.11.25-.29.37-.43s.17-.25.25-.41c.08-.17.04-.31-.02-.43s-.56-1.34-.76-1.84c-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31c-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74c.59.26 1.05.41 1.41.52c.59.19 1.13.16 1.56.1c.48-.07 1.47-.6 1.67-1.18c.21-.58.21-1.07.14-1.18s-.22-.16-.47-.28"/></svg>
											</a>
										</div>
									<?endif;?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?// class=logo-row?>
	</div>
	<div class="menu-row middle-block bg<?=strtolower($arTheme["MENU_COLOR"]["VALUE"]);?>">
		<div class="maxwidth-theme">
			<div class="row">
				<div class="col-md-12">
					<div class="menu-only">
						<nav class="mega-menu sliced">
							<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default",
								array(
									"COMPONENT_TEMPLATE" => ".default",
									"PATH" => SITE_DIR."include/menu/menu.top.php",
									"AREA_FILE_SHOW" => "file",
									"AREA_FILE_SUFFIX" => "",
									"AREA_FILE_RECURSIVE" => "Y",
									"EDIT_TEMPLATE" => "include_area.php"
								),
								false, array("HIDE_ICONS" => "Y")
							);?>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="line-row visible-xs"></div>
</div>