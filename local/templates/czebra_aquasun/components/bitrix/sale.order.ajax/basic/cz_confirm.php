<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

		<?if (!empty($arResult["ORDER"])):?>

			<p><b><?=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></b></p>
			<div class="sale_order_full_table">
				<p><?=GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?></p>
				<p><?=GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"]))?></p>
			</div>
			<?/*
			if (!empty($arResult["PAY_SYSTEM"]))
			{
				?>
				<div class="sale_order_full_table">	
					<div class="ps_logo">
						<h5 class="pay_name"><?=GetMessage("SOA_TEMPL_PAY")?></h5>
						<?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
						<p class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></p>
					</div>
					<?//print_r($arResult);
					if($arResult["PAY_SYSTEM"]["ID"] == 8):?>
						<p>Оплата заказа будет доступна после проверки менеджером, ожидайте ответного письма.</p>
					
					<?else:?>
					<?
					if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
					{
						?>
						<p>
								<?
								$service = \Bitrix\Sale\PaySystem\Manager::getObjectById($arResult["ORDER"]['PAY_SYSTEM_ID']);

								if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
								{
									?>
									<script language="JavaScript">
										window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>&PAYMENT_ID=<?=$arResult['ORDER']["PAYMENT_ID"]?>');
									</script>
									<?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&PAYMENT_ID=".$arResult['ORDER']["PAYMENT_ID"]))?>
									<?
									if (CSalePdf::isPdfAvailable() && $service->isAffordPdf())
									{
										?><br />
										<?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&PAYMENT_ID=".$arResult['ORDER']["PAYMENT_ID"]."&pdf=1&DOWNLOAD=Y")) ?>
										<?
									}
								}
								else
								{
									if ($service)
									{

										$order = \Bitrix\Sale\Order::load($arResult["ORDER_ID"]);


										$paymentCollection = $order->getPaymentCollection();


										foreach ($paymentCollection as $payment)
										{
											if (!$payment->isInner())
											{
												$context = \Bitrix\Main\Application::getInstance()->getContext();
												$service->initiatePay($payment, $context->getRequest());
												break;
											}
										}
									}
									else
									{
										echo '<span style="color:red;">'.GetMessage("SOA_TEMPL_ORDER_PS_ERROR").'</span>';
									}
								}
								?>
							</p>
						<?
					
					}
					?>
					<?endif;?>
				</div>
				<?
			}*/
			?>
		<?else:?>
			<p><b><?=GetMessage("SOA_TEMPL_ERROR_ORDER")?></b></p>

			<div class="sale_order_full_table">
				<p><?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $arResult["ACCOUNT_NUMBER"]))?> <?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?></p>
			</div>
		<?endif?>

