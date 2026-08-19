$(function() {
    ajaxLoading();
});

function ajaxLoading()
{
    var nameButton = ".button-more-product";
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
			
			$('[text-js]').each(function(i, el){
			   let self = $(el);
			   self.html(self.attr('text-js'));
		   });

            page_all = parseInt($("#ajaxCountPages").val());
            if (page >= page_all) {
                //$(nameButton).parent().hide();
                $(this).parent().remove();
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

function afterInstertAjax() {

    btnBuy();

    $.ajax({
        url: "/local/ajax/compare/?action=list",
        cache: false,
        success:function(data){
            data = JSON.parse(data);
            var countElem = 0;
            for(i in data){
                var elem = $("[data-compare-id='" + data[i] + "']");
                $(elem).attr('data-compare-action', 'delete');
                $(elem).css('background-position', '-170px -222px');
                countElem++;
            }
            $(".comparison .counter-comparison").text(countElem);
        }
    });

    $("#container-sorting-bottom").html($("#container-sorting-top").html());

    $("#wrap-pager").html($("#wrap-pager-ajax").html());
    $("#wrap-pager-ajax").remove();

    try {
        history.pushState(null, null, $("#wrap-pager a.selected").attr("data-cz-url"));
    } catch(e) {}
}