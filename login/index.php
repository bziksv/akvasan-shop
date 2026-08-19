<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
<?
global $USER;
if ($USER->IsAuthorized()):?>
<div class="workarea-text col-lg-12 col-md-12 col-xs-12">
    <?
        if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
            LocalRedirect($backurl);
?>
    <h1>Авторизация</h1>
    <p>Вы зарегистрированы и успешно авторизовались.</p>
    <p><a href="<?=SITE_DIR?>">Вернуться на главную страницу</a></p>
</div>
<?else:?>
<div id="auth"><div class="preload"></div></div>
<?
    $url = explode("?", $APPLICATION->GetCurPageParam());
    $param = (count($url) == 2) ? $url[1]: "";
?>
<script>
    
    $(function(){
        var param = "<?=$param;?>";
        $.get("/login/login.php?" + param, function(data){
            $("#auth").html(data);
            <?if($_REQUEST['forgot_password'] == 'yes'):?>
                cz_validated.runBtn('send-passw', 'group_forgpassw');
            <?elseif($_REQUEST['register'] == 'yes'):?>

            <?else:?>
                cz_validated.runBtn('log', 'group_auth');
            <?endif?>
            aliveFomrs();
        });

    });

    function aliveFomrs(){
        $('#auth form[name="form_auth"]').submit(function(){
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize() + '&Login=ok',
                dataType: "html",
                success: function(data) {
                    if(data == 'ok') {
                        window.location.href = '<?=$_REQUEST['backurl']?>';
                    } else {
                        $('#auth').html(data).promise().done(function(){
                            aliveFomrs();
                        });
                    }
                }
            });
            return false;
        });
        $('#auth form[name="bform"]').submit(function(){
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize() + '&send_account_info=ok',
                dataType: "html",
                success: function(data) {
                    if(data == 'ok') {
                        window.location.href = '<?=$_REQUEST['backurl']?>';
                    } else {
                        $('#auth').html(data).promise().done(function(){
                            aliveFomrs();
                        });
                    }
                }
            });
            return false;
        });
        $("[name='REGISTER[PERSONAL_PHONE]']").mask("+7(999)999-99-99");

        $("[name='regform'] .wrap-btn-reg-form .form-btn").click(function(){
            $("[name='regform'] input[name='REGISTER[LOGIN]']").val($("[name='regform'] input[name='REGISTER[EMAIL]']").val());
            $("[name='regform'] input[name='REGISTER[LOGIN]']").remove('data-cz-validated-type data-cz-validated-group data-cz-validated-msg');
            var validBaseFields = cz_validated.run('REGISTRATION_group');
            if (validBaseFields == false) {
                return false;
            }
        });
    }

</script>
<?endif?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

<?/*

define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);

$APPLICATION->SetTitle("Авторизация");
?>
<div class="workarea-text col-lg-12 col-md-12 col-xs-12">
    <h1>Авторизация</h1>
    <p>Вы зарегистрированы и успешно авторизовались.</p>
    <p><a href="<?=SITE_DIR?>">Вернуться на главную страницу</a></p>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");*/?>