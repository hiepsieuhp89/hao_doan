(function($){
    "use strict"; // Start of use strict
    /* ---------------------------------------------
		Scripts initialization
	--------------------------------------------- */
    $(window).load(function() {
        // auto width megamenu
        auto_width_megamenu();
        resizeTopmenu();
	});
    /* ---------------------------------------------
		Scripts ready
	--------------------------------------------- */
    $(document).ready(function() {
	
        /* Resize top menu*/
        resizeTopmenu();
        /* Zoom image */
        if($('#product-zoom').length >0){
            $('#product-zoom').elevateZoom({
                //zoomType: "inner",
                //cursor: "crosshair",
                zoomWindowFadeIn: 500,
                zoomWindowFadeOut: 750,               
				scrollZoom : true,					
				containLensZoom: true,
				gallery:'gallery_01',
				cursor: 'pointer', 
				galleryActiveClass: "active_thumbnail",
				responsive:true,
			}); 
		}
        /* Popup sizechart */
        if($('#size_chart').length >0){
            $('#size_chart').fancybox();
		}
        /** OWL CAROUSEL**/
        $(".owl-carousel").each(function(index, el) {
			var config = $(this).data();
			config.navText = ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'];
			config.smartSpeed="300";
			if($(this).hasClass('owl-style2')){
				config.animateOut="fadeOut";
				config.animateIn="fadeIn";    
			}
			$(this).owlCarousel(config);
		});
        $(".owl-carousel-vertical").each(function(index, el) {
			var config = $(this).data();
			config.navText = ['<span class="icon-up"></spam>','<span class="icon-down"></span>'];
			config.smartSpeed="900";
			config.animateOut="";
            config.animateIn="fadeInUp";
			$(this).owlCarousel(config);
		});
        /** COUNT DOWN **/
        $('[data-countdown]').each(function() {
			var $this = $(this), finalDate = $(this).data('countdown');
			$this.countdown(finalDate, function(event) {
				var fomat ='<span>%H</span><b></b><span>%M</span><b></b><span>%S</span>';
				$this.html(event.strftime(fomat));
			});
		});
        if($('.countdown-lastest').length >0){
            var labels = ['Years', 'Months', 'Weeks', 'Days', 'Hrs', 'Mins', 'Secs'];
            var layout = '<span class="box-count"><span class="number">{dnn}</span> <span class="text">Days</span></span><span class="dot">:</span><span class="box-count"><span class="number">{hnn}</span> <span class="text">Hrs</span></span><span class="dot">:</span><span class="box-count"><span class="number">{mnn}</span> <span class="text">Mins</span></span><span class="dot">:</span><span class="box-count"><span class="number">{snn}</span> <span class="text">Secs</span></span>';
            $('.countdown-lastest').each(function() {
                var austDay = new Date($(this).data('y'),$(this).data('m') - 1,$(this).data('d'),$(this).data('h'),$(this).data('i'),$(this).data('s'));
                $(this).countdown({
                    until: austDay,
                    labels: labels, 
                    layout: layout
				});
			});
		}
        /* Close top banner*/
        $(document).on('click','.btn-close',function(){
            $(this).closest('.top-banner').animate({ height: 0, opacity: 0 },1000);
            return false;
		})
        /** SELECT CATEGORY **/
        $('.select-category').select2();
        /* Toggle nav menu*/
        $(document).on('click','.toggle-menu',function(){
            $(this).closest('.nav-menu').find('.navbar-collapse').toggle();
            return false;
		})
        /** HOME SLIDE**/
        if($('#home-slider').length >0 && $('#contenhomeslider').length >0){
            var slider = $('#contenhomeslider').bxSlider(
			{
				nextText:'<i class="fa fa-angle-right"></i>',
				prevText:'<i class="fa fa-angle-left"></i>',
				auto: true,
			}
			
            );
		}
        /** Custom page sider**/
        if($('#home-slider').length >0 && $('#contenhomeslider-customPage').length >0){
            var slider = $('#contenhomeslider-customPage').bxSlider(
			{
				nextText:'<i class="fa fa-angle-right"></i>',
				prevText:'<i class="fa fa-angle-left"></i>',
				auto: true,
				pagerCustom: '#bx-pager',
				nextSelector: '#bx-next',
				prevSelector: '#bx-prev',
			}
			
            );
		}
		
        if($('#home-slider').length >0 && $('#slide-background').length >0){
            var slider = $('#slide-background').bxSlider(
			{
				nextText:'<i class="fa fa-angle-right"></i>',
				prevText:'<i class="fa fa-angle-left"></i>',
				auto: true,
				onSlideNext: function ($slideElement, oldIndex, newIndex) {
					var corlor = $($slideElement).data('background');   
					$('#home-slider').css('background',corlor);     
				},
				onSlidePrev: function ($slideElement, oldIndex, newIndex) {
					var corlor = $($slideElement).data('background');   
					$('#home-slider').css('background',corlor);     
				}
			}
			
            );
            slider.goToNextSlide();
		}
        
        /* elevator click*/ 
        $(document).on('click','a.btn-elevator',function(e){
            e.preventDefault();
            var target = this.hash;
            if($(document).find(target).length <=0){
                return false;
			}
            var $target = $(target);
            $('html, body').stop().animate({
                'scrollTop': $target.offset().top-50
			}, 500);
            return false;
		})
        /* scroll top */ 
        $(document).on('click','.scroll_top',function(){
            $('body,html').animate({scrollTop:0},400);
            return false;
		})
        /** #brand-showcase */
        $(document).on('click','.brand-showcase-logo li',function(){
            var id = $(this).data('tab');
            $(this).closest('.brand-showcase-logo').find('li').each(function(){
                $(this).removeClass('active');
			});
            $(this).closest('li').addClass('active');
            $('.brand-showcase-content').find('.brand-showcase-content-tab').each(function(){
                $(this).removeClass('active');
			})
            $('#'+id).addClass('active');
            return false;
		})
        // CATEGORY FILTER 
        $('.slider-range-price').each(function(){
            var min             = $(this).data('min');
            var max             = $(this).data('max');
            var unit            = $(this).data('unit');
            var value_min       = $(this).data('value-min');
            var value_max       = $(this).data('value-max');
            var label_reasult   = $(this).data('label-reasult');
            var t               = $(this);
            $( this ).slider({
				range: true,
				min: min,
				max: max,
				values: [ value_min, value_max ],
				slide: function( event, ui ) {
					var result = label_reasult +" "+ unit + ui.values[ 0 ] +' - '+ unit +ui.values[ 1 ];
					console.log(t);
					t.closest('.slider-range').find('.amount-range-price').html(result);
				}
			});
		})
        /** ALL CAT **/
        $(document).on('click','.open-cate',function(){
            $(this).closest('.vertical-menu-content').find('li.cat-link-orther').each(function(){
                $(this).slideDown();
			});
            $(this).addClass('colse-cate').removeClass('open-cate').html('Close');
		})
        /* Close category */
        $(document).on('click','.colse-cate',function(){
            $(this).closest('.vertical-menu-content').find('li.cat-link-orther').each(function(){
                $(this).slideUp();
			});
            $(this).addClass('open-cate').removeClass('colse-cate').html('All Categories');
            return false;
		})
        // bar ontop click
        $(document).on('click','.vertical-megamenus-ontop-bar',function(){
            $('#vertical-megamenus-ontop').find('.box-vertical-megamenus').slideToggle();
            $('#vertical-megamenus-ontop').toggleClass('active');
            return false;
		})
        // View grid list product 
        $(document).on('click','.display-product-option .view-as-grid',function(){
            $(this).closest('.display-product-option').find('li').removeClass('selected');
            $(this).addClass('selected');
            $(this).closest('#view-product-list').find('.product-list').removeClass('list').addClass('grid');
            return false;
		})
        // View list list product 
        $(document).on('click','.display-product-option .view-as-list',function(){
            $(this).closest('.display-product-option').find('li').removeClass('selected');
            $(this).addClass('selected');
            $(this).closest('#view-product-list').find('.product-list').removeClass('grid').addClass('list');
            return false;
		})
        /// tre menu category
        $(document).on('click','.tree-menu li span',function(){
            $(this).closest('li').children('ul').slideToggle();
            if($(this).closest('li').haschildren('ul')){
                $(this).toggleClass('open');
			}
            return false;
		})
        /* Open menu on mobile */
        $(document).on('click','.btn-open-mobile',function(){
            var width = $(window).width();
            if(width >1024){
                if($('body').hasClass('home')){
                    if($('#nav-top-menu').hasClass('nav-ontop')){
						}else{
                        return false;
					}
				}
			}
            $(this).closest('.box-vertical-megamenus').find('.vertical-menu-content').slideToggle();
            $(this).closest('.title').toggleClass('active');
            return false;
		})
        /* Product qty */
        $(document).on('click','.btn-plus-down',function(){
            var value = parseInt($('#option-product-qty').val());
            value = value -1;
            if(value <=0) return false;
            $('#option-product-qty').val(value);
            return false;
		})
        $(document).on('click','.btn-plus-up',function(){
            var value = parseInt($('#option-product-qty').val());
            value = value +1;
            if(value <=0) return false;
            $('#option-product-qty').val(value);
            return false;
		})
        /* Close vertical */
        $(document).on('click','*',function(e){
            var container = $("#box-vertical-megamenus");
            if (!container.is(e.target) && container.has(e.target).length === 0){
                if($('body').hasClass('home')){
                    if($('#nav-top-menu').hasClass('nav-ontop')){
						}else{
                        return;
					}
				}
                container.find('.vertical-menu-content').hide();
                container.find('.title').removeClass('active');
			}
		})
        /* Send conttact*/
        $(document).on('click','#btn-send-contact',function(){
            var subject = $('#subject').val(),
			email   = $('#email').val(),
			order_reference = $('#order_reference').val(),
			message = $('#message').val();
            var data = {
                subject:subject,
                email:email,
                order_reference:order_reference,
                message:message
			}
            $.post('ajax_contact.php',data,function(result){
                if(result.trim()=="done"){
                    $('#email').val('');
                    $('#order_reference').val('');
                    $('#message').val('');
                    $('#message-box-conact').html('<div class="alert alert-info">Your message was sent successfully. Thanks</div>');
					}else{
                    $('#message-box-conact').html(result);
				}
			})
		})
	});
    /* ---------------------------------------------
		Scripts resize
	--------------------------------------------- */
    $(window).resize(function(){
        // auto width megamenu
        auto_width_megamenu();
        // Remove menu ontop
        remove_menu_ontop();
        // resize top menu
        resizeTopmenu();
	});
    /* ---------------------------------------------
		Scripts scroll
	--------------------------------------------- */
    $(window).scroll(function(){
        /* Show hide scrolltop button */
        if( $(window).scrollTop() == 0 ) {
            $('.scroll_top').stop(false,true).fadeOut(600);
			}else{
            $('.scroll_top').stop(false,true).fadeIn(600);
		}
        /* Main menu on top */
        var h = $(window).scrollTop();
        var max_h = $('#header').height() + $('#top-banner').height();
        var width = $(window).width();
        if(width > 767){
            if( h > (max_h + vertical_menu_height)-50){
                // fix top menu
                $('#nav-top-menu').addClass('nav-ontop');
                //$('#nav-top-menu').find('.vertical-menu-content').hide();
                //$('#nav-top-menu').find('.title').removeClass('active');
                // add cart box on top menu
                $('#cart-block .cart-block').appendTo('#shopping-cart-box-ontop .shopping-cart-box-ontop-content');
                $('#shopping-cart-box-ontop').fadeIn();
                $('#user-info-top').appendTo('#user-info-opntop');
                //$('#header .header-search-box form').appendTo('#form-search-opntop');
				}else{
                $('#nav-top-menu').removeClass('nav-ontop');
                if($('body').hasClass('home')){
                    $('#nav-top-menu').find('.vertical-menu-content').removeAttr('style');
                    if(width > 1024)
					$('#nav-top-menu').find('.vertical-menu-content').show();
                    else{
                        $('#nav-top-menu').find('.vertical-menu-content').hide();
					}
					$('#nav-top-menu').find('.vertical-menu-content').removeAttr('style');
				}
                ///
                $('#shopping-cart-box-ontop .cart-block').appendTo('#cart-block');
                $('#shopping-cart-box-ontop').fadeOut();
                $('#user-info-opntop #user-info-top').appendTo('.top-header .container');
                $('#form-search-opntop form').appendTo('#header .header-search-box');
			}
		}
	});
    var vertical_menu_height = $('#box-vertical-megamenus .box-vertical-megamenus').innerHeight();
    /**==============================
		***  Auto width megamenu
	===============================**/
    function auto_width_megamenu(){
        var full_width = parseInt($('.container').innerWidth());
        //full_width = $( document ).width();
        var menu_width = parseInt($('.vertical-menu-content').actual('width'));
        $('.vertical-menu-content').find('.vertical-dropdown-menu').each(function(){
            //$(this).width((full_width - menu_width)-2); mới bỏ
		});
	}
    /**==============================
		***  Remove menu on top
	===============================**/
    function remove_menu_ontop(){
        var width = parseInt($(window).width());
        if(width < 768){
            $('#nav-top-menu').removeClass('nav-ontop');
            if($('body').hasClass('home')){
                if(width > 1024)
				$('#nav-top-menu').find('.vertical-menu-content').show();
                else{
                    $('#nav-top-menu').find('.vertical-menu-content').hide();
				}
			}
            ///
            $('#shopping-cart-box-ontop .cart-block').appendTo('#cart-block');
            $('#shopping-cart-box-ontop').fadeOut();
            $('#user-info-opntop #user-info-top').appendTo('.top-header .container');
            $('#form-search-opntop form').appendTo('#header .header-search-box');
		}
	}
    /* Top menu*/
    function scrollCompensate(){
        var inner = document.createElement('p');
        inner.style.width = "100%";
        inner.style.height = "200px";
        var outer = document.createElement('div');
        outer.style.position = "absolute";
        outer.style.top = "0px";
        outer.style.left = "0px";
        outer.style.visibility = "hidden";
        outer.style.width = "200px";
        outer.style.height = "150px";
        outer.style.overflow = "hidden";
        outer.appendChild(inner);
        document.body.appendChild(outer);
        var w1 = parseInt(inner.offsetWidth);
        outer.style.overflow = 'scroll';
        var w2 = parseInt(inner.offsetWidth);
        if (w1 == w2) w2 = outer.clientWidth;
        document.body.removeChild(outer);
        return (w1 - w2);
	}
	
    function resizeTopmenu(){
        if($(window).width() + scrollCompensate() >= 768){
            var main_menu_w = $('#main-menu .navbar').innerWidth();
            $("#main-menu ul.mega_dropdown").each(function(){
                var menu_width = $(this).innerWidth();
                var offset_left = $(this).position().left;
				
                if(menu_width > main_menu_w){
                    $(this).css('width',main_menu_w+'px');
                    $(this).css('left','0');
					}else{
                    if((menu_width + offset_left) > main_menu_w){
                        var t = main_menu_w-menu_width;
                        var left = parseInt((t/2));
                        $(this).css('left',left);
					}
				}
			});
		}
		
        if($(window).width()+scrollCompensate() < 1025){
            $("#main-menu li.dropdown:not(.active) >a").attr('data-toggle','dropdown');
			}else{
            $("#main-menu li.dropdown >a").removeAttr('data-toggle');
		}
	}
	
	
	
	//btn đặt hàng detail product
	/*$('#add-cart').click(function(){
		var number_new = $('#option-product-qty').val();
		var number_by =parseInt(number_new);
		var number_old = parseInt($('#get_number').val());	
		if(number_by > number_old || number_by <= 0 || isNaN(number_new)){
		$('.body-error').html('Bạn chỉ được nhập số từ 0 -> 9 và số lượng không được lớn hơn '+ number_old+' và không được nhỏ hơn hoặc bằng 0');
		$('#show-error').modal('show');
		$('#option-product-qty').val('1');			
		}else{
		var product_id = $(this).attr('data-product');	
		location.href=domain+'/shoppingcart/add-cart.html?product='+product_id+'&qty='+number_new;
		}
	});*/
	$('.filter-site').prepend('<p class="show-mobi"><i class="fa fa-chevron-down"></i></p>');
	
	$('.show-mobi').click(function(){
		$('.filter-product').slideToggle(function() {    
			if($('.filter-product').is(":visible")==true){
				
				}else{				
				$('.filter-product').removeAttr("style");
			}
		});
	});
	
	$("#form-contact").validate({
        rules: {
            name: {
                required: true,               
			},
            email: {
                required: true,  
				email: true
			},
			phone: {
                required: true,               
			},
			address: {
                required: true,               
			},
            title: {
                required: true,               
			},
			content: {
                required: true, 
				
			},
		},
        messages: {
            name: {
                required: "Không được để trống họ tên",
				
			},
            email: {
                required: "Email không được để trống",	
				email:"Email không đúng định dạng"
				
			},
			phone: {
                required: "Điện thoại không được để trống",
				
			},
			address: {
                required: "Địa chỉ không được để trống",
				
			},
            title: {
                required: "Tiêu đề không được để trống",				
				
			},
			content: {
                required: "Nội dung không được để trống",				
				
			}
		}
		
	});// End Valide Form Checkout
	
	$("#register-form").validate({
        rules: {
			email: {
                required: true,
                minlength: 6
			},
            password: {
                required: true,
                minlength: 8
			},
            passconfirm: {
                required: true,
                minlength: 8,
                equalTo: "#password"
			},
			fullname: {
                required: true,               
			},
			phone: {
                required: true,               
			},
			address: {
                required: true,               
			},
			city: {
                required: true,               
			},
		},
        messages: {
		    email: {
                required: "Email không được để trống",
                email: "Email không dúng định dạng"
			},
            password: {
                required: "Mật khẩu không được để trống",
                minlength: "Mật khâu phải lớn hơn hoặc bằng 8 ký tự"
			},
            passconfirm: {
                required: "Không được để trống",
                minlength: "Mật khâu phải lớn hơn hoặc bằng 8 ký tự",
                equalTo: "Mật khẩu không khớp nhau"
			},
			fullname: {
                required: "Họ tên không được để trống",               
			},
			phone: {
                required: "Số điện thoại không được để trống",               
			},
			address: {
                required: "Địa chỉ không được để trống",               
			},
			city: {
				required: "Tỉnh thành phố không được để trống",               
			},
		}
	});
	
	//var pgurl = window.location.href.replace(window.location.href.lastIndexOf("/"),"1234");
	var pgurl = window.location.href;
	console.log(pgurl);
	$(".nav li").each(function(){
		var url_page = $(this).find('a').attr("href");		
		//console.log(url_page);
		if(url_page == pgurl || url_page == '' )
		$(this).addClass("active");
	})
	
	//Bỏ Zoom Img Trên Mobile
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};
	if(isMobile.any()) {
		$('#product-detail img').removeAttr( 'style' );
	
		$('.product-full').append('<div class="div-mb"></div>');
		var heightdiv = $('.product-full').height();
		var widhtdiv = $('.product-full').width();
		$('.div-mb').css({"width": widhtdiv ,"height":heightdiv, "position":"absolute", "top":"0px","z-index":"1000"});
		window.onresize = function (event) {
			applyOrientation();
		}
		
		$('.call-phone').css('display','block');
		
	}
	
	
	
    var get_heigth = $(".tab-container .tab-panel .box-right").height();
	localStorage.setItem('set_height', get_heigth);
     $('.product-featured .banner-featured .banner-img img').height(localStorage.getItem('set_height'));  
	
})(jQuery); // End of use strict

function applyOrientation() {
	if (window.innerHeight > window.innerWidth) {
		var heightdiv = $('.product-full').height();
		var widhtdiv = $('.product-full').width();
		$('.div-mb').css({"width": widhtdiv ,"height":heightdiv, "position":"absolute", "top":"0px","z-index":"1000"});
		} else {
		var heightdiv = $('.product-full').height();
		var widhtdiv = $('.product-full').width();
		
		$('.div-mb').css({"width": widhtdiv ,"height":heightdiv, "position":"absolute", "top":"0px","z-index":"1000"});
	} 
}
function ValidateEmail(email) {
	var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	return expr.test(email);
};
function Validatename(name) {
	var expr = /^((?![0-9\~\!\@\#\$\%\^\&\*\(\)\_\+\=\-\[\]\{\}\;\:\"\\\/\<\>\?]).)+$/;
	return expr.test(name);
}

function checknumber(){
	var number_new = $('#number').val();
	var number_by =parseInt(number_new);
	var number_old = parseInt($('#get_number').val());
	var price_old = parseInt($('#get_price').val());
	if(number_by > number_old || number_by <= 0 || isNaN(number_new)){
		$('.body-error').html('Bạn chỉ được nhập số từ 0 -> 9 và số lượng không được lớn hơn '+ number_old+' và không được nhỏ hơn hoặc bằng 0');
		$('#show-error').modal('show');
		$('#number').val('1');
		var rg_price =(price_old + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"); 
		$('.price_by').html('= '+rg_price+'đ');
		}else{
		var price_new = price_old*number_by;
		var rg_price =(price_new + "").replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,"); 
		$('.price_by').html('='+rg_price+'đ');
	}
}

//Update số lượng sản phẩm trong giỏ hàng
function updatecart(key){		
	var qty_old=parseInt($('#qty_old').val());
	var qty_new = parseInt($('#number-update').val());
	var qty_update = qty_new-qty_old;		
	location.href=domain+'/shoppingcart/add-cart.html?product='+key+'&qty='+qty_update;
}

function checkout_old(key){	
	var URL = $('#domain').val();
	var validate_getkt= /[^a-zA-Z]/;
	//var email= $('#email').val();
	var phone = $('#phone').val();
	//var get_kt = email.charAt(0); // lây ký tự đầu tiên của email
	//var text_email = email.substring(0,email.indexOf("@"));		
	var name = $('#name').val();
	var address = $('#address').val();
	if(!Validatename(name)){
		$('#name').focus();
		$('#name1-error1').html('Họ tên chỉ được nhập các chữ cái từ A-Z');
		return false;
	}
	else if(phone.length < 9 || isNaN(phone)){
		$('#phone').focus();
		$('#phone-error1').html('Số điện thoại không đúng định dạng');
		return false;
	}
	else if(address == '' || address.length <5 ){
		$('#address').focus();
		$('#address-error1').html('Địa chỉ không được để trống và không được quá ngắn');
		return false;
	}
	/*else if(text_email.length < 4 ||text_email.length >30 || validate_getkt.test(get_kt)){		
		$('#email').focus();
		$('#email-error1').html('Email không đúng định dạng');
		return false;
		
	}*/
	else if (!$("#dieukhoan").is(':checked')) {
		$('#dieukhoan').focus();
		$('#dieukhoan-error1').html('Để đặt hàng thành công bạn phải đồng ý với các điều khoản của giadung88.com');
		return false;
		}else{
		if(key==0){
			//form mua nhanh
			$('#process').html('<img src="'+domain+'/media/images/process.gif" class="img-responsive"/>');
			$.ajax({
				url: URL,
				type: "post",
				data: $('#form-by').serialize(),
				cache: false,
				success: function (data) {  
					//console.log(data);
					if(data =='0'){
						alert('Số lượng sản phẩm không được để trống');
						}else{
						location.href=domain+'/shoppingcart/checkoutsucess.html';
						$('#form-by')[0].reset();						
						//$('#process').html('');
					}
				},
				error: function () {
					alert("failure");
				}
			});
			}else{			
			$('.load').html('<img src="'+domain+'/media/images/load-checkout.gif" width="250;"/>');
			$('.check-out-mb').append('<center><img src="'+domain+'/media/images/load-checkout.gif" width="200;"/></center>');
			$('#content-checkout').fadeOut();
			$('.check-out-mb .box-border').fadeOut();
			var names='name=dac';
			//form checkout
			$.ajax({
				url: URL,
				type: "post",
				data: $('#form-by').serialize(),
				cache: false,
				success: function (data) {       
					//console.log(data);
					location.href=domain+'/shoppingcart/checkoutsucess.html';
					//$('#form-by')[0].reset();					
				},
				error: function () {
					alert("failure");
				}
			});
			
		}
		
	}
	
}

function checkout(key){	
	var URL = $('#domain').val();
	var validate_getkt= /[^a-zA-Z]/;
	//var email= $('#email').val();
	var phone = $('#phone').val();
	//var get_kt = email.charAt(0); // lây ký tự đầu tiên của email
	//var text_email = email.substring(0,email.indexOf("@"));		
	var name = $('#name').val();
	var address = $('#address').val();
	if(!Validatename(name)){
		$('#name').focus();
		$('#name1-error1').html('Họ tên chỉ được nhập các chữ cái từ A-Z');
		return false;
	}
	else if(phone.length < 9 || isNaN(phone)){
		$('#phone').focus();
		$('#phone-error1').html('Số điện thoại không đúng định dạng');
		return false;
	}
	else if(address == '' || address.length <5 ){
		$('#address').focus();
		$('#address-error1').html('Địa chỉ không được để trống và không được quá ngắn');
		return false;
	}
	/*else if(text_email.length < 4 ||text_email.length >30 || validate_getkt.test(get_kt)){		
		$('#email').focus();
		$('#email-error1').html('Email không đúng định dạng');
		return false;
		
	}*/
	else if (!$("#dieukhoan").is(':checked')) {
		$('#dieukhoan').focus();
		$('#dieukhoan-error1').html('Để đặt hàng thành công bạn phải đồng ý với các điều khoản của giadung88.com');
		return false;
		}else{		
			$('.load').html('<img src="'+domain+'/media/images/load-checkout.gif" width="250;"/>');
			$('.check-out-mb').append('<center><img src="'+domain+'/media/images/load-checkout.gif" width="200;"/></center>');
			$('#content-checkout').fadeOut();
			$('.check-out-mb .box-border').fadeOut();
			var names='name=dac';
			//form checkout
			$.ajax({
				url: URL,
				type: "post",
				data: $('#form-by').serialize(),
				cache: false,
				success: function (data) {       
					//console.log(data);
					location.href=domain+'/shoppingcart/checkoutsucess.html';
					//$('#form-by')[0].reset();					
				},
				error: function () {
					alert("failure");
				}
			});		
		
	}
	
}
function viewcart(){	
	$.ajax({
		url: domain+'/shoppingcart/view-cart.html',
		type: "get",
		//data:'id='+key,
		cache: false,
		success: function (data) {       
			//console.log(data);	
			if(data ==1){
				location.href=domain+"/shoppingcart/mb-view-cart.html"; //Nếu là mobile chuyển trang
				}else{
				$('.body-cart').html(data);
				$('#modal_cart').modal('show');
			}
		},
		error: function () {
			alert("failure");
		}
	});
}


function addcart(key, flag){	
	if(flag =='0'){
		$.ajax({
			url: domain+'/shoppingcart/add-cart.html',
			type: "post",
			data:'product='+key+'&qty=1',
			cache: false,
			success: function (data) {       
				if(data ==1){
					location.href=domain+"/shoppingcart/mb-view-cart.html"; //Nếu là mobile chuyển trang
					}else{
					$('.body-cart').html(data);
					$('#total-cart').html($('#count-cart').html());
					$('.notify-left').html($('#count-cart').html());
					$('#modal_cart').modal('show');
				}
			},
			error: function () {
				alert("failure");
			}
		});
		}else if(flag =='1'){		
		var number_new = $('#option-product-qty').val();
		var number_by =parseInt(number_new);
		//var number_old = parseInt($('#get_number').val());	
		if(number_by <= 0 || isNaN(number_new)){
			$('.body-error').html('Bạn chỉ được nhập số từ 0 -> 9 và số lượng không được nhỏ hơn hoặc bằng 0');
			$('#show-error').modal('show');
			$('#option-product-qty').val('1');			
			}else{
			$.ajax({
				url: domain+'/shoppingcart/add-cart.html',
				type: "post",
				data:'product='+key+'&qty='+number_by,
				cache: false,
				success: function (data) {       
					if(data ==1){
						location.href=domain+"/shoppingcart/mb-view-cart.html"; //Nếu là mobile chuyển trang
						}else{
						$('.body-cart').html(data);
						$('#total-cart').html($('#count-cart').html());
						$('.notify-left').html($('#count-cart').html());
						var number_new = $('#option-product-qty').val('1');
						$('#modal_cart').modal('show');
					}
					
				},
				error: function () {
					alert("failure");
				}
			});
		}
		
	}
}
function updatecart(key, flag){
	var qty_old=parseInt($('#qty_old'+key).val());
	if(flag =='1'){
		var qty_new = parseInt($('#number-update'+key).val())-1;
		}else{
		var qty_new = parseInt($('#number-update'+key).val())+1;
	}
	//Nếu số lượng mới lớn hơn 0 thì mới thực hiên update giỏ hàng
	if(qty_new > 0){
		var qty_update = qty_new-qty_old;	
		$('.body-cart').html('<center><img src="'+domain+'/media/images/load-checkout.gif" width="150"/></center>');
		$.ajax({
			url: domain+'/shoppingcart/add-cart.html',
			type: "post",
			data:'product='+key+'&qty='+qty_update,
			cache: false,
			success: function (data) {       
				//console.log(data);	
				if(data ==1){
					location.href=domain+"/shoppingcart/mb-view-cart.html"; //Nếu là mobile chuyển trang
					}else{
					$('.body-cart').html(data);
					$('#modal_cart').modal('show');
				}
			},
			error: function () {
				alert("failure");
			}
		});
		}else{
		$('#number-update'+key).val('1');
	}
}
function deletecart(key){
	$('.body-cart').html('<center><img src="'+domain+'/media/images/load-checkout.gif" width="150"/></center>');
	$.ajax({
		url: domain+'/shoppingcart/delete-items-cart',
		type: "post",
		data:'id='+key,
		cache: false,
		success: function (data) {       
			//console.log(data);			
			if(data ==1){
				location.href=domain+"/shoppingcart/mb-view-cart.html"; //Nếu là mobile chuyển trang
				}else{
				$('.body-cart').html(data);
				$('#total-cart').html($('#count-cart').html());
				$('.notify-left').html($('#count-cart').html());
				//$('#modal_cart').modal('show');
			}
		},
		error: function () {
			alert("failure");
		}
	});
}

var searchCache = {}; //cache all result
function searchproduct() {
	var keyword = $('#search-data1').val();
	//check the input != " " and != "  "
	if ((keyword != " ")) {
		//check the keyword != null
		keyword = keyword.trim();
		
		if (keyword != null) {
			if (keyword.length >= 2) {
				if (searchCache[keyword] != null) {
					//Get result from cache
					arrProducts = searchCache[keyword];
					ShowResult(arrProducts);						
                    } else {
					//Search
					if (keyword != "") {                          
						var arrProducts = [];
						$.ajax({
							type: "POST",
							url: domain+'/sanpham/searchkeyup',
							//contentType: "application/json; charset=utf-8",
							dataType: "json",
							data: 'key=' + keyword ,
							error: function (response) {
								
							},								
							complete: function (response) {
								
								var d = response.responseText;
								//d = d.substring(5, d.lastIndexOf("}"));
								arrProducts = JSON.parse(d);
								//console.log(arrProducts);
								//console.log(d);
								searchCache[keyword] = arrProducts; //cache results
								ShowResult(arrProducts);
							}
						});
                        } else {
						$('#list-search').html("");
						$('#list-search').hide();
					}
				}
                } else {
				$('#list-search').html("");
				$('#list-search').hide();
			}
		}
	}
}
function ShowResult(arrProducts) {
	
	if (arrProducts.length != 0) {
		$('#list-search').html("");
		// $("#SuggestPost").html("");           
		var newItem = "";
		$.each(arrProducts, function(i, index){
			newItem = "<li><a href='"+domain+"/san-pham/"+ index.alias + ".html'>"+
			"<img src='"+domain+"/media/images/"+index.thumbnail+"'  width='50' height='50' />"+index.product_name+ 
			"<span class='pull-right'>Giá: <strong>"+index.price+"đ</strong></span></a>"+
			"</li>";
			$('#list-search').append(newItem);
		})
		
		//var height = $j("#ctlSearch_ctl00_txtSearch").height();
		//$j("#SearchSmart").css("top", height+1);
		//$j("#SearchSmart").show();
		$('#list-search').fadeIn();
        } else {
		//$j("#SearchSmart").hide();
		$('#list-search').hide();
	}
}
