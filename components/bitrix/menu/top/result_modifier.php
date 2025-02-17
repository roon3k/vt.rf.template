<?
$arResult = CNext::getChilds($arResult);
global $arRegion, $arTheme;

if($arResult){
	if($bUseMegaMenu = $arTheme['USE_MEGA_MENU']['VALUE'] === 'Y'){
		CNext::replaceMenuChilds($arResult, $arParams);
	}

	foreach($arResult as $key => $arItem)
	{
		if(isset($arItem["PARAMS"]["ONLY_MOBILE"]) && $arItem["PARAMS"]["ONLY_MOBILE"]=="Y") {
		    unset($arResult[$key]);
		    continue;
		}

		if(isset($arItem['CHILD']))
		{
			foreach($arItem['CHILD'] as $key2=>$arItemChild)
			{
				
				if($arParams["MAX_LEVEL"] > 3 && $arItemChild["CHILD"] )
				{
					foreach($arItemChild["CHILD"] as $key3=>$arItemSubChild)
					{
						if($arItemSubChild["CHILD"]){
							$arResult[$key]["CHILD"][$key2]["SUB_ITEMS_IS"] = true;
						}
					}
				}

				if(isset($arItemChild['PARAMS']) && $arRegion && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y')
				{
					// filter items by region
					if(isset($arItemChild['PARAMS']['LINK_REGION']))
					{
						if($arItemChild['PARAMS']['LINK_REGION'])
						{
							if(!in_array($arRegion['ID'], $arItemChild['PARAMS']['LINK_REGION']))
								unset($arResult[$key]['CHILD'][$key2]);
						}
						else
							unset($arResult[$key]['CHILD'][$key2]);
					}
				}
			}
		}
	}
}
?>