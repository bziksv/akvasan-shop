$(function() {
    $("[name='del-basket-item']").click(function () {
        if ($(".body-table-cart").length > 1) {
            $(this).parents('.body-table-cart').slideUp(800, function () {
                $(this).remove();

                if ($(".body-table-cart").length == 0) {
                    showEmptyBasket();
                }
            });
        } else {
            showEmptyBasket();
        }

        var id = $(this).attr('data-del-id');
        $.ajax({
            url: "/local/ajax/basket/?action=delete&id=" + id,
            cache: false,
            success: function (data) {
                var information = JSON.parse(data);
                var insertText = thousandSeparator(information.SUM_ORDER) + " р.";
                $('.total-price').html(insertText);
            }
        });

        if ($(".body-table-cart").length == 0) {
            window.location.href = "/personal/cart/";
        }

        return false;
    });

    $("[name='quantity']").change(function () {
        if ($(this).val().length > 0) {
            var id = $(this).attr('data-id-count');
            $.ajax({
                url: "/local/ajax/basket/?action=update&id=" + id + "&quantity=" + $(this).val(),
                cache: false,
                success: function (data) {
					console.log(data);
                    var information = JSON.parse(data);
                    var insertText = thousandSeparator(information.SUM_ORDER) + " руб.";
                    $('.total-price').html(insertText);
                    for (var i in information.ITEM) {
                        insertText = thousandSeparator(information.ITEM[i].SUM) + " р."
                        $('[data-sum="' + information.ITEM[i].ID + '"]').html(insertText);
                    }
                }
            });
        }
    });
});

function thousandSeparator(str) {
    var parts = (str + '').split('.'),
        main = parts[0],
        len = main.length,
        output = '',
        i = len - 1;

    while(i >= 0) {
        output = main.charAt(i) + output;
        if ((len - i) % 3 === 0 && i > 0) {
            output = ' ' + output;
        }
        --i;
    }

    if (parts.length > 1) {
        output += '.' + parts[1];
    }
    return output;
}
function showEmptyBasket(){
    $(".workarea-cart .container").html('<p>Ваша корзина пуста. Начните делать <a href="/catalog/">покупки прямо сейчас</a>.</p>');
}