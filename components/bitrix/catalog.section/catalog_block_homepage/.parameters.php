<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"SHOW_RATING" => Array(
		"NAME" => GetMessage("SHOW_RATING"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"CUSTOM_FILTER" => Array(
		"NAME" => GetMessage("CUSTOM_FILTER"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),
);
?>
