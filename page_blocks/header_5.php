<div class="header-v5 header-wrapper">
    <div class="logo_and_menu-row">
        <div class="phones-bar">
            <div class="contacts__list container">
                <div class="contacts__item contacts__item--address">
                    <div class="contacts__address-item">
                        <span class="contacts__name">Адрес</span>
                        <span class="contacts__link">Барклая 8, ТЦ Старая Горбушка, первый этаж, магазин 125</span>
                    </div>
                    <div class="contacts__address-item">
                        <span class="contacts__name">Ежедневно</span>
                        <span class="contacts__link">10:00 – 20:00</span>
                    </div>
                </div>
                <div class="contacts__item contacts__item--phone">
                    <div class="phone">
                        <a class="elem-link contacts__link new_phone" href="tel:+79032222456">
                            <span class="contacts__name">Заказ: </span>
                            <span>+7 (903) 222 24-56</span>
                        </a>
                    </div>
                    <div class="phone">
                        <a class="elem-link contacts__link new_phone" href="tel:+79998343720">
                            <span class="contacts__name">Техническая поддержка: </span>
                            <span>+7 (999) 834 37-20</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="logo-row">
            <div class="maxwidth-theme update-header">
                <div class="row flex-container">
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
                    <div class="right-icons pull-right">
                        <div class="pull-right">
                            <?=CNext::ShowBasketWithCompareLink('', 'big', '', 'wrap_icon inner-table-block baskets');?>
                        </div>
                        <div class="pull-right">
                            <div class="wrap_icon inner-table-block">
                                <?=CNext::showCabinetLink(true, false, 'big');?>
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