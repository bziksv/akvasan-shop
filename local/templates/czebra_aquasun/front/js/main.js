$(function(){

    if($("#menu-stop").val() != "block") {
        $('.catalog-menu ul li').click(function () {
            $('.wrapp-menu').slideToggle();
        })
    }

    openFormCallback();

    dotdotdot();

    btnBuy();

    showAllPropElement();

    showAllPropFilter();

    complectProduct();

    showhideAllProps();
});

function openFormCallback() {
    $("#call-order").click(function(){
        $.get("/local/ajax/popup/?WEB_FORM_ID=1&URL_BACK=" + window.location.href, function (data) {
            $("body").append(data);

            $("#cz_form input[name='form_text_2']").mask("+7 (999) 999-99-99");
            $("[name='web_form_submit']").attr("id", "cz1_sibmit");

            $("#cz_form input[name='form_text_1']").attr("data-cz-validated-type","data");
            $("#cz_form input[name='form_text_1']").attr("data-cz-validated-group","FORM_CALLBACK_group");
            $("#cz_form input[name='form_text_1']").attr("data-cz-validated-msg","* Необходимо заполнить поле Имя");
            $("#cz_form input[name='form_text_1']").attr("placeholder","Имя");

            $("#cz_form input[name='form_text_2']").attr("data-cz-validated-type","data");
            $("#cz_form input[name='form_text_2']").attr("data-cz-validated-group","FORM_CALLBACK_group");
            $("#cz_form input[name='form_text_2']").attr("data-cz-validated-msg","* Необходимо заполнить поле Телефон");
            $("#cz_form input[name='form_text_2']").attr("placeholder","Телефон");

            cz_validated.runBtn('cz1_sibmit', 'FORM_CALLBACK_group');

            $("#cz_wrap_form").modal('show').on("hidden.bs.modal", function(){
                $("#cz_wrap_form").remove();
            });
        });
        $(this).blur();
        return false;
    });

    if($("#czebra_form_success").val() == "show")
    {
        $.get("/local/ajax/popup/result.php",function(data) {
            $("body").append(data);
            $("#cz_wrap_form").modal('show').on("hidden.bs.modal", function(){
                $("#cz_wrap_form").remove();
            });
        });
    }
}

function dotdotdot(){
    $(".dotdotdot").dotdotdot();
}

function afterInstertAjax() {
    dotdotdot();
    btnBuy();

    $("#wrap-pager").html($("#wrap-pager-ajax").html());
    $("#wrap-pager-ajax").remove();

    try {
        console.log($("#wrap-pager a.active-pagination").attr("cz-data-url"));
        history.pushState(null, null, $("#wrap-pager a.active-pagination").attr("cz-data-url"));
    } catch(e) {}
}

function btnBuy() {
    $("[cz-data='addtocart']").click(function(){

        if($(this).attr("cz-data-basket") == "yes") {
            window.location.href = "/personal/cart/";

        } else {

            var id = $(this).attr("cz-data-buy");

            var elem = $(this);

            $.ajax({
                url: "/local/ajax/basket/?action=add&id=" + id,
                cache: false,
                success: function (data) {
                    //$(".basket-line").load("/local/ajax/getBasket.php");
                    $(".cart-user").html(data);

                    $(elem).attr("cz-data-basket", "yes");
                    $(elem).text("уже в корзине");
                    $(elem).addClass("in-basket");
                }
            });
        }

        return false;
    });

    $.ajax({
        url: "/local/ajax/basket/?action=list",
        cache: false,
        success:function(data){
            data = JSON.parse(data);
            for(i in data.basket){
                var elem = $("[cz-data-buy='" + data.basket[i] + "']");
                elem.attr("cz-data-basket", "yes");
                elem.text("уже в корзине");
                elem.addClass("in-basket");
            }

        }
    });
}

function showAllPropElement() {
    $("#all-info, #all-info2").click(function(){
        if($("#props_all").css("display") == "none") {
            $("#all-info2").addClass("st-down");
            $("#props_all").show("slow");
        } else {
            $("#all-info2").removeClass("st-down");
            $("#props_all").hide("slow");
        }
        return false;
    });
}

function showAllPropFilter() {
    $("#show-all-prop").click(function () {
        if($(this).text() == "Расширенный поиск") {
            $(".hidden-filter").show("slow");
            $(this).text("Свернуть");
        } else {
            $(".hidden-filter").hide("slow");
            $(this).text("Расширенный поиск");
        }
        return false;
    });
}

function complectProduct() {
    var priceComplect = $("#price_complect").val();
    if(priceComplect !== undefined) {
        $("#binding_product h2 .bp-price").html(priceComplect + '<span class="rubl">i</span>');
    }

    $("[cz-data='addtocartarr']").click(function(){
        if($(this).attr("cz-data-basket") == "yes") {
            window.location.href = "/personal/cart/";
        } else {
            var id = JSON.parse($(this).attr("cz-data-buy"));
            var elem = $(this);

            $.each(id, function( key, value ) {
                $.ajax({
                    url: "/local/ajax/basket/?action=add&id=" + value,
                    cache: false,
                    success: function (data) {
                        $(".cart-user").html(data);
                    }
                });
            });

            $(elem).attr("cz-data-basket", "yes");
            $(elem).text("уже в корзине");
            $(elem).addClass("in-basket");
        }

        return false;
    });
}

function showhideAllProps() {
    $(".sh-all-prop").click(function(){
        if ($(this).attr("data-prop") == "stop") {
            return false;
        }

        if($(this).attr("data-prop") == "close") {
            $(this).next().show("slow");
            $(this).attr("data-prop", "open");
            $(this).find("svg use").attr("xlink:href", "#arrow_up");
        } else {
            $(this).next().hide("slow");
            $(this).attr("data-prop", "close");
            $(this).find("svg use").attr("xlink:href", "#arrow_down");
        }
        return false;
    });
}