$(document).ready(function() {
    function showValues() {
		var alias_cat =$('#alias-cat').val();	
		$('.sortPagiBar').hide();
		
		var mainarray = new Array();		
		var pricearray = new Array();		
		$('input[name="price"]:checked').each(function(){			
			pricearray.push($(this).val());		
			$('.spanbrandcls').css('visibility','visible');			
			//alert($(this).attr("checkboxname"));	
		});
		if(pricearray=='') $('.spanbrandcls').css('visibility','hidden');
		var price_checklist = "&price="+pricearray;
		
		var brandarray = new Array();		
		$('input[name="brand"]:checked').each(function(){			
			brandarray.push($(this).val());	
			$('.spansizecls').css('visibility','visible');	
		});
		if(brandarray=='') $('.spansizecls').css('visibility','hidden');
		var brand_checklist = "&brand="+brandarray;
		
		
		var xuatxuarray = new Array();		
		$('input[name="xuatxu"]:checked').each(function(){			
			xuatxuarray.push($(this).val());
			$('.spancolorcls').css('visibility','visible');		
		});
		if(xuatxuarray=='') $('.spancolorcls').css('visibility','hidden');
		var xuatxu_checklist = "&xuatxu="+xuatxuarray;
		
		
		var chatlieuarray = new Array();		
		$('input[name="chatlieu"]:checked').each(function(){			
			chatlieuarray.push($(this).val());
			$('.spanpricecls').css('visibility','visible');		
		});
		if(chatlieuarray=='') $('.spanpricecls').css('visibility','hidden');
		var chatlieu_checklist = "&chatlieu="+chatlieuarray;
		
		var main_string = price_checklist+brand_checklist+xuatxu_checklist+chatlieu_checklist+'&cat='+alias_cat;
		main_string = main_string.substring(1, main_string.length)
		//alert(main_string);
		
		//location.href='http://localhost/demo/filter-product?'+main_string;
		$.ajax({
			type: "POST",
			url: domain+"/sanpham/filter",
			data: main_string, 
			cache: false,
			success: function(html){
			//console.log(html);
				if(html == 102){
					location.reload();					
					}else{
					$('#list_product').html(html);
				}
			
			}
		});
		
		
	}
	
	$("input[type='checkbox'], input[type='radio']").on( "click", showValues );
    $("select").on( "change", showValues );
	
	
	$(".spanbrandcls").click(function(){
		$('.bcheck').removeAttr('checked');				
		showValues();
		$('.spanbrandcls').css('visibility','hidden');
	});
	$(".spansizecls").click(function(){
		$('.scheck').removeAttr('checked'); 
		showValues();
		$('.spansizecls').css('visibility','hidden');
	});
	$(".spancolorcls").click(function(){
		$('.ccheck').removeAttr('checked'); showValues();
		$('.spancolorcls').css('visibility','hidden');
	});
	$(".spanpricecls").click(function(){
		$('.price_range').removeAttr('checked'); showValues();
		$('.spanpricecls').css('visibility','hidden');
	});
	$(".clear_filters").click(function(){
		$('#productCategoryLeftPanel').find('input[type=checkbox]:checked').removeAttr('checked');
		$('#productCategoryLeftPanel').find('input[type=radio]:checked').removeAttr('checked');
		showValues();
	});
	
});	