<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="ci-title">Способ оплаты</div>
<p>Оплата в магазине наличными или банковской картой.</p>
<ul class="is-rlist payment" style="display: none">
	<?foreach($arResult["PAY_SYSTEM"] as $arPaySystem):?>
	<li class="is-radio">
		<p>
			<input type="radio" name="PAY_SYSTEM_ID" id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>" value="<?=$arPaySystem["ID"]?>" class="css-checkbox" onclick="submitForm();" <?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?> />
			<label for="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"><?=$arPaySystem["NAME"]?></label>
			<?if($arPaySystem["CHECKED"]=="Y"):?>
				<?if($arPaySystem["DESCRIPTION"]):?>
					<span class="abs_cloud"><span class="cloud"><?=$arPaySystem["DESCRIPTION"]?></span></span>
				<?endif?>
			<?endif;?>
			<input type="hidden" value="<?=$arPaySystem["ID"]?>">
		</p>
	</li>
	<?endforeach?>
</ul>