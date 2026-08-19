$(document).ready(function(){
   $('.filter-price').slider({
       min:0,
       max:100000,
       values:[0,100000],
       range:true,
       stop: function(event, ui) {
		$("input#minCost").val($(".filter-price").slider("values",0));
		$("input#maxCost").val($(".filter-price").slider("values",1));
    },
    slide: function(event, ui){
		$("input#minCost").val($(".filter-price").slider("values",0));
		$("input#maxCost").val($(".filter-price").slider("values",1));
    }  
   });
    
    
    $("input#minCost").change(function(){
          var value1=$("input#minCost").val();
          var value2=$("input#maxCost").val();


          if(parseInt(value1) > parseInt(value2)){
                value1 = value2;
                $("input#minCost").val(value1);
          }
          $(".filter-price").slider("values",0,value1);  
    });

    $("input#maxCost").change(function(){
        var value1=$("input#minCost").val();
        var value2=$("input#maxCost").val();

        if (value2 > 100000) {
            value2 = 100000;
            $("input#maxCost").val(100000);
        }

        if(parseInt(value1) > parseInt(value2)){
            value2 = value1;
            $("input#maxCost").val(value2);
        }
        $(".filter-price").slider("values",1,value2);
   });

});