<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)  die();
$this->setFrameMode(false);



if ($arParams['AJAX'] == 'Y') {
	require(realpath(dirname(__FILE__)).'/ajax.php');
} else {?>
	<input id="btnOpen<?=$arParams["FORM_ID"]?>" type="button"	value="Обратный звонок" />
	<input id="formId<?=$arParams["FORM_ID"]?>" type="hidden" value="<?=$arParams["FORM_ID"]?>" />
	<input id="placeParams<?=$arParams["FORM_ID"]?>" type="hidden" value="" />
	
	<div id="placeShowForm<?=$arParams["FORM_ID"]?>">
		<?/*Сюда загрузиться форма*/?>
	</div>

	<script>
		$(function(){
			$("#btnOpen<?=$arParams["FORM_ID"]?>").click(function(){
				var prefix = $(this).next().val();
				$("#placeShowForm" + prefix).get("/local/ajax/loading.php?arParams=".$("#placeParams" + prefix).val());
			});
		});
	</script>
<?
}
?>
