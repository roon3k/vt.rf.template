<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//setting section
//***********************************
?>
<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
<?echo bitrix_sessid_post();?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="data-table top">
<thead><tr><td colspan="2"><h4><?echo GetMessage("subscr_title_settings")?></h4></td></tr></thead>
<tr valign="top">
	<td class="left_blocks">
		<div class="form-control">
			<label><?echo GetMessage("subscr_email")?> <span class="star">*</span></label>
			<input type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" size="30" maxlength="255" />
		</div>
		<div class="adaptive more_text">
			<div class="more_text_small">
				<?echo GetMessage("subscr_settings_note1")?><br/>
				<?echo GetMessage("subscr_settings_note2")?>
			</div>
		</div>
		<h5><?echo GetMessage("subscr_rub")?><span class="star">*</span></h5/>
		<div class="filter label_block">
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<input type="checkbox" name="RUB_ID[]" id="RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> />
				<label for="RUB_ID_<?=$itemValue["ID"]?>"><?=$itemValue["NAME"]?></label>
			<?endforeach;?>
		</div>
		<h5><?echo GetMessage("subscr_fmt")?></h5>
		<div class="filter label_block radio">
			<input type="radio" name="FORMAT" id="txt" value="text"<?if($arResult["SUBSCRIPTION"]["FORMAT"] == "text") echo " checked"?> /><label for="txt"><?echo GetMessage("subscr_text")?></label>&nbsp;/&nbsp;<input type="radio" name="FORMAT" id="html" value="html"<?if($arResult["SUBSCRIPTION"]["FORMAT"] == "html") echo " checked"?> /><label for="html">HTML</label>
		</div>
	</td>
	<td class="right_blocks">
		<div class="more_text_small">
			<?echo GetMessage("subscr_settings_note1")?><br/>
			<?echo GetMessage("subscr_settings_note2")?>
		</div>
	</td>
</tr>
<tfoot><tr><td colspan="2">
	<?global $arTheme;?>
	<?if($arTheme["SHOW_LICENCE"]["VALUE"] == "Y" && !$arResult["ID"] ):?>
		<div class="subscribe_licenses">
			<div class="licence_block filter label_block">
				<?if($arResult["ERROR"] && !$_POST["licenses_subscribe"]):?>
					<label id="licenses_subscribe-error" class="error" for="licenses_subscribe"><?=GetMessage("JS_REQUIRED_LICENSES");?></label>
				<?endif;?>
				<input type="checkbox" id="licenses_subscribe" <?=($_POST["licenses_subscribe"] ? "checked" : ($_POST ? "" : ($arTheme["SHOW_LICENCE"]["DEPENDENT_PARAMS"]["LICENCE_CHECKED"]["VALUE"] == "Y" ? "checked" : "")));?> name="licenses_subscribe" value="Y">
				<label for="licenses_subscribe">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
				</label>
			</div>
		</div>
	<?endif;?>
	<div class="form-control">
		<?$APPLICATION->IncludeFile(SITE_DIR."include/required_message.php", Array(), Array("MODE" => "html"));?>
	</div>
	<input type="submit" name="Save" class="btn btn-default" value="<?echo ($arResult["ID"] > 0? GetMessage("subscr_upd"):GetMessage("subscr_add"))?>" />
	<input type="reset" class="btn btn-default white" value="<?echo GetMessage("subscr_reset")?>" name="reset" />
</td></tr></tfoot>
</table>
<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
<?if($_REQUEST["register"] == "YES"):?>
	<input type="hidden" name="register" value="YES" />
<?endif;?>
<?if($_REQUEST["authorize"]=="YES"):?>
	<input type="hidden" name="authorize" value="YES" />
<?endif;?>
	<input type="hidden" name="check_condition" value="YES" />
</form>
<br />
