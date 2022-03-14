(function() {
	$(document).ready(function() {
		if (localStorage.getItem("content") !== null) {
			$("#color-settings-body-color").attr("href", localStorage.getItem("content"));
		}
		if ((localStorage.getItem("contrast") !== null) && !$("body").hasClass("contrast-background")) {
			$("body")[0].className = $("body")[0].className.replace(/(^|\s)contrast.*?(\s|$)/g, " ").replace(/\s\s+/g, " ").replace(/(^\s|\s$)/g, "");
			$("body").addClass(localStorage.getItem("contrast"));
		}
		$(".color-settings-body-color > a").hover(function() {
			$("#color-settings-body-color").attr("href", $(this).data("change-to"));
			return localStorage.setItem("content", $(this).data("change-to"));
		});
		return $(".color-settings-contrast-color > a").hover(function() {
			$('body')[0].className = $('body')[0].className.replace(/(^|\s)contrast.*?(\s|$)/g, ' ').replace(/\s\s+/g, ' ').replace(/(^\s|\s$)/g, '');
			$('body').addClass($(this).data("change-to"));
			return localStorage.setItem("contrast", $(this).data("change-to"));
		});
		
	});
	////////////////////////////////////////////////////////////////////////////
	$('.box-header button').click(function(){
		$('.content-tab').fadeOut();
		$('.box-header button').removeClass('background-new');
		$(this).addClass('background-new');
		var tab=$(this).attr('rel');
		$(tab).fadeIn();
	});
}).call(this);




function deleteaction(key){
	var geturl=$('#url').val();
	$('#cat_id').val(key);
	var URL =geturl+key+'.html';
	$('#btn-del').html('<a href='+URL+'> <button type="button" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button></a>');
	$('#del').modal('show');
}
function trashaction(key){
	var geturl=$('#url'+key).val();
	$('#btn-del').html('<a href='+geturl+'> <button type="button" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button></a>');
	$('#del').modal('show');
}
function delUser(key){
	var URL ='delete-user-'+key+'.html';
	$('#btn-del').html('<a href='+URL+'> <button type="button" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button></a>');
	$('#del').modal('show');
}
function permission(){
	
	$('#pm').modal('show');
}
function delqs(key){
	var URL ='delete-question-'+key+'.html';
	$('#btn-del').html('<a href='+URL+'> <button type="button" class="btn btn-success" ><span class="glyphicon glyphicon-ok-sign"></span> Yes</button></a>');
	$('#del').modal('show');
}
function ressetpass(key){
	
	var pass='123456';
	var iduser=key;
	var values='id='+iduser+'&password='+pass;
	
	$.ajax({
        url: domain+"/system/ajaxreset/resetpass",
        type: "post",
        data: values,
        cache: false,
        success: function (data) {
            console.log(data);
            if (data == 'Sucsses') {
                $('#repass').modal('show');
				
			}
		},
        error: function () {
            alert("failure");
			
		}
	});
}

function submitform(){
	$('#form-admin').submit(); 
}
function resetform(){
	$('#form-admin')[0].reset();
}
function get_codeproduct(){
	var code_id=randomStringkt(3)+randomString(6);
	$('#code-product').val(code_id);
}
function randomString(len) {
    charSet ='000001112223334445555666777888999';
    var randomString = '';
    for (var i = 0; i < len; i++) {
    	var randomPoz = Math.floor(Math.random() * charSet.length);
    	randomString += charSet.substring(randomPoz,randomPoz+1);
	}
    return randomString;
}
function randomStringkt(len) {
    charSet ='QWERTYUIOPLKJHGFDSAZXCVBNM';
    var randomString = '';
    for (var i = 0; i < len; i++) {
    	var randomPoz = Math.floor(Math.random() * charSet.length);
    	randomString += charSet.substring(randomPoz,randomPoz+1);
	}
    return randomString;
}
function uploadimage(el){
	$('#img').attr("src", "");
	$('#img').hide();
	$("#img-load").show();
	$.ajax({
		url: domain+"/system/product/uploadimg",
		type: "POST",
		data: new FormData(el),
		contentType: false,
		cache: false,
		processData:false,
		success: function(data){
			$('#asd').val(''); // xóa dữ liệu trong input
			$('#img').show();
			$("#img-load").hide();
			//console.log(data);	
			if(data=='error'){
				$(".error-upload").html('<p class="bg-warning">Không có hình ảnh nào được chọn để tải lên.</p>');
				}else{
				$(".error-upload").html('<p class="bg-success">Tải lên thành công thành công</p>');
				$('#add-img').append(data);
			}
			
		}
	});
}// 
function removeimg(key){
	var idimg='id_img='+key;
	$('#tr-img'+key).remove();
	$.ajax({
        url: domain+"/system/product/removeimg",
        type: "post",
        data: idimg,
        cache: false,
        success: function (data) {		
            console.log(data);
			
		},
        error: function () {
            alert("failure");
			
		}
	});
}

// Thay đổi trạng thái ảnh
function changstatus(key){
	var values='id_img='+key;	
	$.ajax({
        url: domain+"/system/product/changestatusimg",
        type: "post",
        data: values,
        cache: false,
        success: function (data) {		
            console.log(data);
			
		},
        error: function () {
            alert("failure");
			
		}
	});
}

// Thay đổi trạng thái còn hàng hay hết hàng
function productstatus(key){	
	var status_pro=$('#status-pro').val();
	var datas='id='+key+'&status_pro='+status_pro;
	$.ajax({
        url: domain+"/system/product/productstatus",
        type: "post",
        data: datas,
        cache: false,
        success: function (data) {		
            console.log(data);
			
		},
        error: function () {
            alert("failure");
			
		}
	});
}
function changestatus_oder(key){   
	var status=$('#status-oder').val();  
	var values='id_oder='+ key +'&status='+status;
	
    $.ajax({
        url: domain+'/system/order/status',
        type: "post",
        data: values,
        cache: false,
        success: function (data) {
            console.log(data);           
            $('#capnhat').html('<div class="alert alert-success" id="chang_status" role="alert">'+data+'</div>');
			
		},
        error: function () {
            alert("failure");
		}
	});
}
function changer_location(key){   
	var location=$('#location'+key).val();  
	var values='id='+ key +'&location='+location;
	
    $.ajax({
        url: domain+'/system/acticre/changelocation',
        type: "post",
        data: values,
        cache: false,
        success: function (data) {
            console.log(data); 
			if(data !=''){
				$('#capnhat').html('<div class="alert alert-success" id="chang_status" role="alert"> Cập nhật thành công</div>');
			}
		},
        error: function () {
            alert("failure");
		}
	});
}

function get_valueradio(){
	var value_radio=$('input[name="type"]:checked').val();
	if(value_radio =='0'){	
	$('.select-cat').fadeIn();
	}else{
	$('.select-cat').fadeOut();
	}
}

function getsales(){
	var value_radio=$('input[name="sales"]:checked').val();
	if(value_radio =='0'){	
	$('#validation_sales').prop( "disabled", true );
	}else{
	$('#validation_sales').prop( "disabled", false );
	}
}

$(function(){
	$('#file_wrap').click(function(){
		if($('.MultiFile-label').length ==5){
			$( ".MultiFile-applied" ).prop( "disabled", true );
			alert('Chỉ được chọn tối đa 5 ảnh 1 lần');
			};
		 
	})
});
