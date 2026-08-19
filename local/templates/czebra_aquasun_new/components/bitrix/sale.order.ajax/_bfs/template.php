
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	<div class="col-12">
        <h1 class="is-cart-header">Оформление заказа</h1>
    </div>
</div>
<?
if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			$APPLICATION->RestartBuffer();
			?>
			<script type="text/javascript">
				window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}

	}
}

?>

<a name="order_form"></a>

<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
if (!function_exists("getColumnName"))
{
	function getColumnName($arHeader)
	{
		return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
	}
}

if (!function_exists("cmpBySort"))
{
	function cmpBySort($array1, $array2)
	{
		if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
			return -1;

		if ($array1["SORT"] > $array2["SORT"])
			return 1;

		if ($array1["SORT"] < $array2["SORT"])
			return -1;

		if ($array1["SORT"] == $array2["SORT"])
			return 0;
	}
}
?>

<div class="bx_order_make">
	<?
	if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
	{
		if(!empty($arResult["ERROR"]))
		{
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);
		}
		elseif(!empty($arResult["OK_MESSAGE"]))
		{
			foreach($arResult["OK_MESSAGE"] as $v)
				echo ShowNote($v);
		}

		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	}
	else
	{
		if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
		{
			if(strlen($arResult["REDIRECT_URL"]) == 0)
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/cz_confirm.php");
			}
		}
		else
		{
			?>
			<script type="text/javascript">

			<?if(CSaleLocation::isLocationProEnabled()):?>

				<?
				// spike: for children of cities we place this prompt
				$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
				?>

				BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
					'source' => $this->__component->getPath().'/get.php',
					'cityTypeId' => intval($city['ID']),
					'messages' => array(
						'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
						'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
						'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
							'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
							'#ANCHOR_END#' => '</a>'
						)).'</div>'
					)
				))?>);

			<?endif?>

			var BXFormPosting = false;
			function submitForm(val)
			{
				$('#hidepanel').show();
				if (BXFormPosting === true)
					return true;

				BXFormPosting = true;
				if(val != 'Y')
					BX('confirmorder').value = 'N';

				var orderForm = BX('ORDER_FORM');
				BX.showWait();

				<?if(CSaleLocation::isLocationProEnabled()):?>
					BX.saleOrderAjax.cleanUp();
				<?endif?>

				BX.ajax.submit(orderForm, ajaxResult);

				return true;
			}

			function ajaxResult(res)
			{
				$('#hidepanel').hide();
				var orderForm = BX('ORDER_FORM');
				try
				{
					// if json came, it obviously a successfull order submit

					var json = JSON.parse(res);
					BX.closeWait();

					if (json.error)
					{
						BXFormPosting = false;
						return;
					}
					else if (json.redirect)
					{
						window.top.location.href = json.redirect;
					}
				}
				catch (e)
				{
					// json parse failed, so it is a simple chunk of html

					BXFormPosting = false;
					BX('order_form_content').innerHTML = res;

					<?if(CSaleLocation::isLocationProEnabled()):?>
						BX.saleOrderAjax.initDeferredControl();
					<?endif?>
				}

				BX.closeWait();
				BX.onCustomEvent(orderForm, 'onAjaxSuccess');
			}

			function SetContact(profileId)
			{
				BX("profile_change").value = "Y";
				submitForm();
			}
			</script>
			<?if($_POST["is_ajax_post"] != "Y")
			{
				?><form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
				<?=bitrix_sessid_post()?>
				<div id="order_form_content">
				<?
			}
			else
			{
				$APPLICATION->RestartBuffer();
			}

			if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
			{
				?>
				<input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
				<?
			}

			if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
			{
				foreach($arResult["ERROR"] as $v)
					echo ShowError($v);
				?>
				<script type="text/javascript">
					top.BX.scrollToNode(top.BX('ORDER_FORM'));
				</script>
				<?
			}

			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/cz_summary.php");?>
			<div class="cart-info">
				<div class="ci-title">Информация о получателе</div>
				<ul>
					
					<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/cz_personal_type.php");?>
					<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/cz_props.php");?>
					
				</ul>
				<?
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/cz_delivery.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/cz_delivery_prop.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/cz_paysystem.php");
					
				
				?>

			</div>
			<?
			
			
			


			
			if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
				echo $arResult["PREPAY_ADIT_FIELDS"];
			?>
			<div class="cart-all">
				<ul class="ca-list">
					<li><em>Товаров на</em> <span><?=$arResult["ORDER_PRICE_FORMATED"]?></span></li>
					<li><em>Доставка</em> <span><?=$arResult["DELIVERY_PRICE_FORMATED"]?></span></li>
					<li><em>Итого</em> <strong><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></strong></li>
				</ul>
				<a href="javascript:void();" id="ORDER_CONFIRM_BUTTON" class="is-more-btn"><span class="compence">Заказать</span></a>

			</div>
			

			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>
					</div>
					<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
					<input type="hidden" name="profile_change" id="profile_change" value="N">
					<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
					<input type="hidden" name="json" value="Y">
				</form>
				<?
				if($arParams["DELIVERY_NO_AJAX"] == "N")
				{
					?>
					<div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
					<?
				}
			}
			else
			{
				?>
				<script type="text/javascript">
					top.BX('confirmorder').value = 'Y';
					top.BX('profile_change').value = 'N';
				</script>
				<?
				die();
			}
		}
	}
	?>
	</div>
</div>

<?if(CSaleLocation::isLocationProEnabled()):?>

	<div style="display: none">
		<?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.steps", 
			".default", 
			array(
			),
			false
		);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.search", 
			".default", 
			array(
			),
			false
		);?>
	</div>

<?endif?>
<div id='hidepanel' style="display:none;"></div>
<script>
jQuery(function($) {initForm();});
BX.addCustomEvent('onAjaxSuccess', function(){ initForm();});
function initForm(){
    styleForm();
    actionFrom();
    inBasketEvent();
}
function styleForm(){
    $("input[data-cz-telefon='Y']").mask("+7(999)999-99-99");

    var locationPropID = $("#location_prop").val();
    if(locationPropID !== undefined)
    {
        var el = $(locationPropID);
        el.attr("data-cz-validated-type", "data");
        el.attr("data-cz-validated-group", "group_order_delivery");
        el.attr("data-cz-validated-msg", "* Необходимо заполнить поле Регион доставки");
    }
}
function actionFrom(){
    $("#ORDER_CONFIRM_BUTTON").click(function(){
        var validBaseFields = cz_validated.run('group_order');
        var validDeliveryFields = deliveryValidate();
        if (validBaseFields && validDeliveryFields) {
            submitForm('Y');
        }
        return false;
    });
}
function deliveryValidate(){
    var result = true;
    var validAdress = $("#delivery_adress_valid").val();
    if (validAdress == "Y") {
        result = cz_validated.run('group_order_delivery');
    }
    return result;
}

function inBasketEvent(){
    
    $('.table-cart .spinner .spin-down').click(function(){     
        var quentity = parseInt($(this).siblings('.table-cart .spinner input[type="text"]').val());
        if($(this).siblings('.table-cart .spinner input[type="text"]').val()>1){
            $(this).siblings('.table-cart .spinner input[type="text"]').val((quentity-1));
            changeQuanSpin(this);
        }
    });
    $('.table-cart .spinner .spin-up').click(function(){
        var quentity = parseInt($(this).siblings('.table-cart .spinner input[type="text"]').val());
        $(this).siblings('.table-cart .spinner input[type="text"]').val((quentity + 1));
        changeQuanSpin(this);
        
    });
    function changeQuanSpin(el){
        var id = $(el).parent().next().val();
         $.ajax({
            url: "/local/ajax/changeQuantity.php?ID=" +id+ "&COUNT="+$(el).siblings('.table-cart .spinner input[type="text"]').val()+"&TYPE=update",
               cache: false,
               success:function(){
                   //$(".cart-total span").load("/local/ajax/loading.php?arParams=" + $("#ajaxParams").val());
                   submitForm();
               }
         });
         var price = parseInt($("#price-" + id).val()) * parseInt($(el).siblings('.table-cart .spinner input[type="text"]').val());
         $("#sum-" + id).html(price.toString().replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g,"\$1 ") + ' <sup>╤</sup>');
    }
    
   $('.table-cart .spinner input[type="text"]').on('touchspin.on.startspin', changeQuan);
   $('.table-cart .spinner input[type="text"]').change(changeQuan);
   $('.table-cart .spinner input[type="text"]').keypress(function(event) {
      var keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == 13) {
          changeQuan.apply(this);
      }
   });
   
   function changeQuan(){
      var id = $(this).parent().next().val();
      $.ajax({
         url: "/local/ajax/changeQuantity.php?ID=" +id+ "&COUNT="+$(this).val()+"&TYPE=update",
			cache: false,
            success:function(){				
                //$(".cart-total span").load("/local/ajax/loading.php?arParams=" + $("#ajaxParams").val());
                submitForm();
			}
      });
      var price = parseInt($("#price-" + id).val()) * parseInt($(this).val());
      $("#sum-" + id).html(price.toString().replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g,"\$1 ") + ' <sup>╤</sup>');
   }
   
   $("a.ct-del").click(function(){
      var id = $(this).find("[id *= 'basket-id-']").val();
      $.ajax({
         url: "/local/ajax/changeBasket.php?ACTION=CHANGE_QUAN&basketID=" +id+ "&QUANTITY=-1",
			cache: false,
         success:function(){				
				//$("#panel-info-sum").load("/local/ajax/loading.php?arParams=" + $("#ajaxParams").val());
                $(".line-basket").load("/local/ajax/getBasket.php");
                submitForm();
			}
      });
      $(this).parents("tr").detach();
      return false;
   });
}
BX.ready(function(){
    loader = BX('ajax-preloader-wrap');
    BX.showWait = function(node, msg) {
        BX.style(loader, 'display', 'none');
    };
});
</script>