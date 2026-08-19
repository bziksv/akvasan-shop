<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="container-main">
    <h1>Личные данные</h1>
    <form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
        <?=$arResult["BX_SESSION_CHECK"]?>
        <input type="hidden" name="lang" value="<?=LANG?>" />
        <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />


        <?ShowError($arResult["strProfileError"]);?>
        <?if ($arResult['DATA_SAVED'] == 'Y') ShowNote("Сохранено");?>
        <div class="my-info">
            <table>
                <ul>
                    <li>
                        <p>Логин</p>
                        <input type="text" name="LOGIN" maxlength="50" value="<?=$arResult["arUser"]["LOGIN"]?>" />
                    </li>
                    <li>
                        <p>Имя и Фамилия</p>
                        <input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" />
                    </li>
                    <li>
                        <p>Телефон</p>
                        <input type="text" name="PERSONAL_PHONE" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" />
                    </li>
                    <li>
                        <p>E-mail</p>
                        <input type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>" />
                    </li>

                </ul>
            </table>
            <input type="submit" id="data-base" name="save" class="my-info-change" value="Изменить данные"/>
        </div>
        <h2 class="change-pass-caption">Изменение пароля</h2>
        <div class="my-pass">
            <form action="/">
                <ul>
                    <li>
                        <p>Новый пароль</p>
                        <input type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" />
                    </li>
                    <li>
                        <p>Еще раз</p>
                        <input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" />
                    </li>
                    <li>
                        <input type="submit" name="save" class="my-pass-change" value="Изменить" />
                    </li>
                </ul>
            </form>
        </div>
</div>
</form>
<script>
    $(function(){

        $('[name="PERSONAL_PHONE"]').mask("+7(999) 999-99-99");

        $(document).on("keyup change","[name = 'LOGIN'], [name = 'NAME'], [name = 'EMAIL']",function(){ ValidClear(this); });

        $("#data-base").click(function(){
            var flagLogin = ValidName($("[name = 'LOGIN']"));
            var flagFIO = ValidName($("[name = 'NAME']"));
            var flagEmail = ValidEmail($("[name = 'EMAIL']"));

            if(!flagLogin){
                $("[name = 'LOGIN']").focus();
                showMessage($("[name = 'LOGIN']"), 'Введите корректно логин');
            }
            if(!flagFIO){
                $("[name = 'NAME']").focus();
                showMessage($("[name = 'NAME']"), 'Введите корректно ФИО');
            }
            if(!flagEmail){
                $("[name = 'EMAIL']").focus();
                showMessage($("[name = 'EMAIL']"), 'Введите корректно ваш Email');
            }




            if(flagLogin && flagFIO && flagEmail)
            {
                return true;
            }
            else
                return false;

        });
    });


    function ValidName(el){
        console.log("ValidName");
        var lenV=$(el).val();
        var lenVLngth=lenV.length;
        if(lenVLngth<=2) return false;
        else return true;
    }
    function showMessage(inps, msg){
        inps.next('div.cz-wrap-error').remove();
        inps.after('<div class="cz-wrap-error"><p class="cz-input-error">* '+ msg +'</p></div>');
        inps.focus();
    }
    function ValidEmail(el){
        $(el).next('p').remove();
        var emailV = $(el).val();
        var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
        if (filter.test(emailV)) return true;
        else return  false;
    }
    function ValidClear(el){
        $(el).next('div.cz-wrap-error').remove();
    }
</script>
