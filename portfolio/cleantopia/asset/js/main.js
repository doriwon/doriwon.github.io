// popup
$(function(){
	$(".btn.btn01").click(function(){
		$(".popup_wrap").show();				
		$('html,body').css('overflow-y','hidden');
		return false;		
	});
	$(".btn.btn02").click(function(){
		$('.tab_cont').hide();
		$('.tab_cont').eq(1).show();
		$('.step_btn li').removeClass('active');
		$('.step_btn li:nth-child(2)').addClass('active');
		$(".popup_wrap").show();
		$('html,body').css('overflow-y','hidden');				
		$(".step_btn").css("background-image", "url(asset/images/tab02_bg.png)");
		return false;		
	});

	$(".popup_wrap .popup .close_btn, .popup_wrap .bg, .popup_wrap .end03").click(function(){
		$(".popup_wrap").hide();
		$('html,body').css('overflow-y','auto');
		window.location.reload();	
	});
});

// popup tab
$(document).ready(function(){
	$('.tab_cont').hide();
	$('.tab_cont').eq(0).show();
	$('.step_btn li a').click(function(e){
		e.preventDefault();
		var $this = $(this);
		var tindex = $(this).attr('rel');

		$('.tab_cont').hide();
		$('.step_btn li a').parent().removeClass('active');
		$(this).parent().addClass('active');
		$('#'+tindex).show();// $('#'+tindex).fadeIn('1500');
	});
	$('.step_btn li:nth-child(1) a').click(function(e){
		$(".step_btn").css("background-image", "url(asset/images/tab01_bg.png)")
	});
	$('.step_btn li:nth-child(2) a').click(function(e){
		$(".step_btn").css("background-image", "url(asset/images/tab02_bg.png)")
	});
	$('.step_btn li:nth-child(3) a').click(function(e){
		$(".step_btn").css("background-image", "url(asset/images/tab03_bg.png)")
	});
});
