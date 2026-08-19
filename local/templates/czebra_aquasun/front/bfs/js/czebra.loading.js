$(function() {
    ajaxLoading();
});

function ajaxLoading()
{
    var nameButton = ".more-items-text";
    var nameInsertPlace = $("#ajaxContener").val();

    var page = parseInt($("#ajaxNumberPage").val());
    var page_all = parseInt($("#ajaxCountPages").val());
    var callbackFunc = $("#ajaxCallback").val();

    if (page >= page_all) {
        $(nameButton).parent().hide();
    }
    else{
        $(nameButton).parent().show();
    }

    $(nameButton).click(function() {
        page = parseInt($("#ajaxNumberPage").val());
        page++;
        var path = "/local/ajax/loading/";
        path += "?PAGEN_1=" + page  + "&arParams=" + $("#ajaxParams").val()
            + "&arFilter=" + $("#ajaxFilter").val();
        $.get(path, function(data) {
            //$(nameInsertPlace).append(data);
            $(nameButton).parent().before(data);

            page_all = parseInt($("#ajaxCountPages").val());
            if (page >= page_all) {
                $(nameButton).parent().hide();
            }

            $(nameButton).blur();

            if (callbackFunc !== undefined && callbackFunc.length > 0) {
                eval(callbackFunc);
            }
        });
        $("#ajaxNumberPage").val(page);
        return false;
    });
}