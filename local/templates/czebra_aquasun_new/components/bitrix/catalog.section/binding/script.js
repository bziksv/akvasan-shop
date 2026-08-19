$(function(){
    calcPrice();
    changeCheckbox();
    btnBuyClick();
}); 

function calcPrice() {
    var price = parseInt($('#base_price_complect').val());

    $("#complect input[type='checkbox']:checked").each(function(){
        var id = $(this).val();
        price += parseInt($("[data-price-complect='"+id+"']").val());
    });

    $('#complect .megaprice span').text(" - " + price + " p.");
}

function changeCheckbox() {
    $("#complect input[type='checkbox']").change(function(){
        if ($("#complect input[type='checkbox']:checked").length == 0) {
            $('#complect .megaprice').slideUp();
        } else {
            $('#complect .megaprice').slideDown();
            calcPrice();
        }
    });
}

function btnBuyClick() {
    $('[data-cz="addtocartgroup"]').click(function(){
        if($(this).attr("data-cz-basket") == "yes") {
            window.location.href = "/personal/cart/";

        } else {
            $("#complect input[type='checkbox']:checked").each(function(){
                $.get("/local/ajax/basket/",{
                    action: "add",
                    id: $(this).val()
                });
            });
            var id = $(this).attr("data-cz-buy");
            var elem = $(this);
            $.ajax({
                url: "/local/ajax/basket/?action=add&id=" + id,
                cache: false,
                success: function () {
                    if (typeof window.refreshBasketFromList === 'function') {
                        window.refreshBasketFromList();
                    }
                }
            });
        }

        return false;
    });
}