$(document).ready(function(){

    $('[data-fancybox="images"]').fancybox({
        margin : [44,0,22,0],
        //loop: false,
        //transitionEffect: "zoom-in-out",
        thumbs : {
          autoStart : true,
          axis      : 'x'
        }
    });

    var touchDevice = false;
    if (navigator.userAgent.match(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/)) {
        touchDevice = true;
    }

	$(".quick-link").mCustomScrollbar({
		axis:"x",
		theme:"light-3",
		autoExpandScrollbar:false,
		scrollbarPosition: "inside",
		alwaysShowScrollbar:0,
		advanced:{autoExpandHorizontalScroll:true}
	});

    $(".quick-link-button").click(function(){
        $(".quick-link").css('height', 'auto');
        $(this).remove();
    });

	$('.bx-catalog1 .bxslider').bxSlider({
        prevText:'',
        nextText:'',
        slideMargin: 20,
        minSlides: 1,
        maxSlides: 4,
        slideWidth:222,
        moveSlides:1,
        touchEnabled: touchDevice
    });

	$('a.catalog-panel').on('shown.bs.tab', function(e){

		$('a.catalog-panel').removeClass('active');
		$(this).addClass('active');

		let id = $(this).data('id');

        $('.bx-catalog' + id + ' .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 20,
            minSlides: 1,
            maxSlides: 4,
            slideWidth:222,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    })

    $('.bx1 .bxslider').bxSlider({
        prevText:'',
        nextText:'',
        slideMargin: 40,
        minSlides: 1,
        maxSlides: 3,
        slideWidth:210,
        moveSlides:1,
        touchEnabled: touchDevice
    });

    $('a[href="#panel2"]').on('shown.bs.tab', function(e){
        $('.bx2 .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 40,
            minSlides: 1,
            maxSlides: 3,
            slideWidth:210,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    })

    $('a[href="#panel3"]').on('shown.bs.tab', function(e){
        $('.bx3 .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 40,
            minSlides: 1,
            maxSlides: 3,
            slideWidth:210,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    })

    $('a[href="#panel4"]').on('shown.bs.tab', function(e){
        $('.bx4 .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 40,
            minSlides: 1,
            maxSlides: 3,
            slideWidth:210,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    })

    $('a[href="#panel5"]').on('shown.bs.tab', function(e){
        $('.bx5 .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 40,
            minSlides: 1,
            maxSlides: 3,
            slideWidth:210,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    })
    $('a[href="#panel6"]').on('shown.bs.tab', function(e){
        $('.bx6 .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 40,
            minSlides: 1,
            maxSlides: 3,
            slideWidth:210,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    });
    $('a[href="#panel7"]').on('shown.bs.tab', function(e){
        $('.bx7 .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 40,
            minSlides: 1,
            maxSlides: 3,
            slideWidth:210,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    });
    $('a[href="#panel8"]').on('shown.bs.tab', function(e){
        $('.bx8 .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            slideMargin: 40,
            minSlides: 1,
            maxSlides: 3,
            slideWidth:210,
            moveSlides:1,
            touchEnabled: touchDevice
        });
    });
    $('.novelty-slider .bxslider').bxSlider({
        prevText:'',
        nextText:'',
        slideMargin:20,
        minSlides:1,
        maxSlides:4,
        slideWidth: 222,
        moveSlides:1,
        touchEnabled: touchDevice
    });

    $('.slider-partners .bxslider').bxSlider({
        prevText:'',
        nextText:'',
        minSlides:1,
        maxSlides:7,
        slideWidth: 140,
        moveSlides:1
    });

    $('.mobile-icon-menu button').click(function(){
        $('.drop-menu').slideToggle();
        $('.menu-catalog').slideUp();
    });

    const menuElement = document.getElementById('test-menu-right');
    const menu = new SlideMenu(menuElement, {
        submenuLinkAfter: ' <i class="fas fa-angle-right"></i>',
        backLinkBefore: ' <i class="fas fa-angle-left"></i>',
    });

    $('.button-catalog-menu').click(function(){
        menu.open();
        console.log('open');
    });

    menuElement.addEventListener('sm.open-after', function () {
        let that = $(this);

        that.find('.btn.slide-menu__control[data-action="back"]').html('<i class="fas fa-angle-left"></i> Назад');
        that.find('.btn.slide-menu__control[data-action="close"]').html('Закрыть <i class="fas fa-times"></i>');
    });

    menuElement.addEventListener('sm.forward-after', function (e) {
        let that = $(this);

        let currSubmenu = that.find('.slide-menu__submenu--active');

        if(currSubmenu.find("> li:first-child > a").length === 1) {
            let main = currSubmenu.closest('li').find('> a').clone();

            main.find('span').remove();
            main.find('img').remove();
            main.css({
                'text-transform': 'uppercase',
            });

            let name = 'Открыть раздел ' + main.text();

            currSubmenu.find("> li:first-child").append(main.text(name));
        }
    });

    $('.slider-card .bxslider').bxSlider({
        pagerCustom: '.bx-pager-mini',
        prevText:'',
        nextText:'',
        pager:true,
        touchEnabled: false
    });

    $('.right-filter .body-filter').jScrollPane();

    $('.left-filter .body-filter').mCustomScrollbar({
        setHeight:true
    });

    $('.center-filter .body-filter').mCustomScrollbar({
        setHeight:true
    });

    setTimeout(function(){
        $('.container-filter').css('overflow', 'auto');
        $('.container-filter').css('max-height', 'none');
        $('.filter-preload').fadeOut('slow');
    }, 200);

    setTimeout(function(){
        // $('#auth').css('overflow', 'auto');
        $('#auth').css('max-height', 'none');
        $('.filter-preload').fadeOut('slow');
    }, 200);

    $('.menu-catalog-sticky').hover(
		function(){
			$('.container-menu-sticky').slideDown();
		},function() {
			$('.container-menu-sticky').slideUp();
		}
	);

    $('.preview-text-seo > a').click(function(){
        $(this).parent().toggleClass('active');
        return false;
    });

    $(window).scroll(function(){
        if($(window).scrollTop()>200){
            $('.sticky-menu').show();
        }

        else{
            $('.sticky-menu').hide();
        }
    });

    $(document).click(function(event){
        if($(event.target).closest('.hidden-body-menu').length)
        return;
        $('.hidden-body-menu').slideUp();
        event.stopPropagation();
    });


    $('.title-menu-border').click(function(){
        $(this).toggleClass('active-radius  no-radius');
        $('.hidden-body-menu').slideToggle();
        return false;

    });


    $("#myTab a").click(function(e){
        e.preventDefault();
        $(this).tab('show');
    });

    $("#all-info, #all-info2").click(function(){
        $("#props_all").slideToggle('slow');
        return false;
    });


    btnBuy();

    // BEGIN function openFormCallback
        $("#call-order, #call-order-footer").click(function(){
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
        };

    // END function openFormCallback


    // BEGIN function openFormToOrder

    // $("#to-order-not-available").click(function(){

    //     var name = $('#name_form_to_order').val();

    //     $.get("/local/ajax/popup/?WEB_FORM_ID=2&URL_BACK=" + window.location.href, function (data) {
    //         $("body").append(data);

    //         $('.name-item').append(name);

    //         $("#cz_form input[name='form_text_5']").mask("+7 (999) 999-99-99");
    //         $("[name='web_form_submit']").attr("id", "cz2_sibmit");

    //         $("#cz_form input[name='form_text_6']").attr("value", name);

    //         $("#cz_form input[name='form_text_3']").attr("data-cz-validated-type","data");
    //         $("#cz_form input[name='form_text_3']").attr("data-cz-validated-group","FORM_TO_ORDER_group");
    //         $("#cz_form input[name='form_text_3']").attr("data-cz-validated-msg","* Необходимо заполнить поле Имя");
    //         $("#cz_form input[name='form_text_3']").attr("placeholder","Имя");

    //         $("#cz_form input[name='form_text_5']").attr("data-cz-validated-type","data");
    //         $("#cz_form input[name='form_text_5']").attr("data-cz-validated-group","FORM_TO_ORDER_group");
    //         $("#cz_form input[name='form_text_5']").attr("data-cz-validated-msg","* Необходимо заполнить поле Телефон");
    //         $("#cz_form input[name='form_text_5']").attr("placeholder","Телефон");

    //         cz_validated.runBtn('cz2_sibmit', 'FORM_TO_ORDER_group');

    //         $("#cz_wrap_form").modal('show').on("hidden.bs.modal", function(){
    //             $("#cz_wrap_form").remove();
    //         });
    //     });
    //     $(this).blur();
    //     return false;
    // });

    // if($("#czebra_form_success").val() == "show")
    // {
    //     $.get("/local/ajax/popup/result.php",function(data) {
    //         $("body").append(data);
    //         $("#cz_wrap_form").modal('show').on("hidden.bs.modal", function(){
    //             $("#cz_wrap_form").remove();
    //         });
    //     });
    // };

    // END function openFormToOrder

    $('.slider-card .arrow-slide').appendTo('.slider-card .bx-wrapper')

    $(".slide-card a").click(function() {
        $("#myModal").modal('show');
    });

    $('#myModal').on('shown.bs.modal', function() {
        $('.slider-modal .bxslider').bxSlider({
            prevText:'',
            nextText:'',
            pagerCustom: '.bx-pager-mini-modal',
            adaptiveHeight: true,
            pager:true,
            keyboardEnabled: true,
            onSliderLoad: function(){

            }
        });
    });



    $('.sum input').TouchSpin({
        min: 1,
        max: 999,
        step: 1,
        decimals: 0,
        buttondown_class: "minus",
        buttonup_class: "plus"
    });

    (function($) {
        $.fn.setEqualHeight = function () {
            var $this = $(this);
    var tallestcolumn = 0;
    $this.each(function () {
                var currentHeight = $(this).height();
    if (currentHeight > tallestcolumn)
                {
                    tallestcolumn = currentHeight;
    }
            });
    $this.height(tallestcolumn);
    };
    })(jQuery);

    $(".container-catalog .row .container-product").setEqualHeight();


    slider = $('.compare-slider .bxslider').bxSlider({
        nextText: '',
        prevText: '',
        minSlides:2,
        maxSlides:3,
        slideWidth: 285,
        moveSlides:1,
        pager:false,
        keyboardEnabled: true,
        infiniteLoop: false,
        touchEnabled: false,
    });

    $('.deleted-product-compare .deleted').mouseover(function(){
        $(this).siblings('.hidden-deleted').show();
    });

    $('.deleted-product-compare .deleted').mouseout(function(){
        $(this).siblings('.hidden-deleted').hide();
    });

    var stopFlag = false;
    $(".body-menu li li a").click(function(){
        stopFlag = true;
    });

    $(".menu:not(.brand-menu) .body-menu li").click(function(){
        if(!stopFlag) {
            $(".body-menu li ul").slideUp();

            var element = $(this).find('ul');

            if (element.css('display') == "none") {
                element.slideDown();
            } else {
                element.slideUp();
            }
        }
    });


    compareEvents();

    filterItems();


    $('.advanced-search').click(function(){
        $('.drop-filter').slideToggle('slow');
        $('.drop-filter .workarea-filter .body-filter').jScrollPane({
            autoReinitialise: true,
            verticalGutter: 10
        });
        return false;
   });

   if($(".brand-img").length > 0) {
       console.log($("brand-img"));
        $(".brand-breadcrumb").append($(".brand-img").removeClass("hidden"));
   };

   tabsDelivery();

   $('[text-js]').each(function(i, el){
	   let self = $(el);
	   self.html(self.attr('text-js'));
   });

   $('#smartFilterModal').on('show.bs.modal', function (event) {
	  let modal = $(this);
	  let body = modal.find('.modal-body');

	  if(!body.find('.container-filter').length)
	  {
		let filter = $('.container-filter');

		filter.css({
			'opacity': 1,
			'height': 'auto',
		});

		body.html(filter);
	  }
	});
});

function filterItems(){

    $('.left-filter .title-filter').click(function(e){
        e.preventDefault();
        $(this).siblings('.body-filter').slideToggle();
        $(this).find('a').toggleClass('arrow-rotate');
   });

   $('.container-filter-price .title-filter').click(function(e){
       e.preventDefault();
       $(this).find('a').toggleClass('arrow-rotate');
       $('.filter-price-area').slideToggle();
   });


   $('.workarea-filter .title-filter').click(function(e){
        e.preventDefault();
        $(this).find('a').toggleClass('arrow-rotate');
        $(this).siblings('.body-filter').slideToggle();
   });

   $('a[ data-close="yes"]').click();

   $('.title-filter-mobile span').click(function(){
       $(this).toggleClass('arrow-rotate');
       $('.filter-area').slideToggle();
   });

}

function compareEvents()
{
    $('.arrow-slide').click(function(e){
        $.ajax({
            url: '/local/ajax/compare/?id=' + $(this).attr('data-compare-id') + "&action=" + $(this).attr('data-compare-action'),
            cache: false,
            success: function (data) {
                data = JSON.parse(data);
                $(".comparison .counter-comparison").text(data.COUNT);
            }
        });

        if($(this).attr('data-compare-action') == "add") {
            $(this).attr('data-compare-action', 'delete');
            $(this).css('background-position', '-170px -222px');
        } else {
            $(this).attr('data-compare-action', 'add');
            $(this).css('background-position', '-155px -51px');
        }
        return false;
    });

    $('.deleted-product-compare a').click(function(){
        $.ajax({
            url: '/local/ajax/compare/?id=' + $(this).attr('data-compare-id') + "&action=delete" ,
            cache: false,
            success: function (data) {
                //data = JSON.parse(data);
                //$(".comparison .counter-comparison").text(data.COUNT);
            }
        });

        //скрывать предка
        $(this).closest('.slide-compare').remove();

        slider.goToPrevSlide();

       if ($(".slide-compare").length == 0) {
            window.location.href = "/catalog/compare.php";
        }

        return false;
    });

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
}

function btnBuy()
{
    $("[data-cz='addtocart']").unbind('click');

    $("[data-cz='addtocart']").click(function(){

        if($(this).attr("data-cz-basket") == "yes") {
            window.location.href = "/personal/cart/";

        } else {
            $("#doptovar input[type='checkbox']:checked").each(function(){
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
                success: function (data) {
                    $("#basket-in-header").html(data);
                    $("#basket-in-panel").load("/local/ajax/basketline/");

                    $(elem).attr("data-cz-basket", "yes");
                    $(elem).text("уже в корзине");
                    $(elem).addClass("in-basket");
                    $(elem).removeClass('cart-icon');
                    $(elem).addClass('no-padding-cart');
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
                var elem = $("[data-cz-buy='" + data.basket[i] + "']");
                elem.attr("data-cz-basket", "yes");
                elem.text("уже в корзине");
                elem.addClass("in-basket");
                elem.removeClass('cart-icon');
                elem.addClass('no-padding-cart');
            }

        }
    });
}


function tabsDelivery(){
    $('.tabs-delivery a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
      })
}
