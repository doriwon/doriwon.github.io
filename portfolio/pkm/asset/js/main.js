var speed = 600;

function scrolling(obj){
	if (!obj){
		$('html, body').animate({scrollTop:0},speed);
	}else{
		var windowWidth = $( window ).width();
		var posTop = $(obj).offset().top - $("#header").outerHeight();
		console.log($("#header").outerHeight())
		$('html, body').animate({scrollTop:posTop}, speed)
	}
}

$(document).ready(function(){
	$(".gnb a").click(function(){
		var direction = $(this).attr("href");
		scrolling( direction );
		return false;
	});

	// header
	$(window).scroll(function(){
		var scroll = $(window).scrollTop();
		var $header = $(".header");
		var headerH = $(".header").height() + 150;
		var mainH = $(".visual").height() - 150;

		if (scroll >= headerH){
			$header.addClass("scroll");
		}else{
			$header.removeClass("scroll");
		}
		if (scroll >= mainH){
			$header.addClass("move");
		}else{
			$header.removeClass("move");
		}
	});


	// visual_slider
	/*$('.visual_slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
		$('.visual').removeClass('in');
		//$('.visual.slick-active').addClass('in');
	});
	$('.visual_slider').on('afterChange', function(event, slick, currentSlide, nextSlide) {
		//$('.visual').removeClass('in');
		$('.visual.slick-active').addClass('in');
	});*/
	$('.visual_slider').slick({
		autoplay: true,
		autoplaySpeed: 3000,
		speed:800,
		dots: true,
		arrows: false,
		infinite: true,
		fade: true,
		cssEase: 'linear',
		pauseOnHover: false,
		pauseOnFocus: false,
	});
	$('.gallery_slider').slick({
		autoplay: false,
		slidesToShow: 3,
		slidesToScroll: 3,
		rows:2,
		speed:800,
		dots: true,
		arrows: true,
		infinite: true,
		fade: false,
		pauseOnHover: false,
		pauseOnFocus: false,
		responsive: [
			{
			  breakpoint:1024,
			  settings: {
				rows: 1,
				slidesToShow: 1,
				slidesToScroll: 1
			  }
			}
		]
	});

	$('.gallery_popup').magnificPopup({
		type: 'inline',
		tClose: '팝업닫기',
		callbacks:{
			open: function(){
				$('html').css('overflow-y','hidden');

				if($(window).width()>=1280){
					$('body').css({'overflow-y': 'hidden', 'padding-right': '17px', 'box-sizing': 'border-box'});
				}
			},
			close: function(){
				$('html').css('overflow-y','visible');

				if($(window).width()>=1280){
					$('body').css({'overflow-y': 'visible', 'padding-right': '0', 'box-sizing': 'content-box'});
				}
			}
		},
		gallery:{
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1],
			tPrev: '이전',
			tNext: '다음'
		}
	});
	$('.menu_popup').magnificPopup({
		type: 'inline',
		tClose: '팝업닫기',
		callbacks:{
			open: function(){
				$('html').css('overflow-y','hidden');

				if($(window).width()>=1280){
					$('body').css({'overflow-y': 'hidden', 'padding-right': '17px', 'box-sizing': 'border-box'});
				}
			},
			close: function(){
				$('html').css('overflow-y','visible');

				if($(window).width()>=1280){
					$('body').css({'overflow-y': 'visible', 'padding-right': '0', 'box-sizing': 'content-box'});
				}
			}
		},
		menu:{
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1],
			tPrev: '이전',
			tNext: '다음'
		}
	});

	//btn_top
	$(".btn_top").on("click", function() {
		$("html, body").stop().animate({
			scrollTop : 0
		}, 400);
		return false;
	});
    $(window).scroll(function(){
        if($(this).scrollTop() > 500){
            $(".btn_top").fadeIn(300);
        }else{
            $(".btn_top").fadeOut(300);
        }

        if($(".footer").size() > 0 && $(this).scrollTop() + $(window).height() > $(".footer").offset().top){
            $(".btn_top").css("position", "absolute");
        }else{
            $(".btn_top").css("position", "fixed");
        }
    });


	 /* header fix menu */
	var headertop = $(".header").height();
	$(window).scroll(function(){
		var scroll = $(this).scrollTop();
		var content = $(".main > section");
		var headertop = $(".header").height();
		if(scroll >= content.eq(1).offset().top - headertop){
			$(".header .gnb li").removeClass("on");
			$(".header .gnb li").eq(0).addClass("on");
		}
		if(scroll >= content.eq(2).offset().top - headertop){
			$(".header .gnb li").removeClass("on");
			$(".header .gnb li").eq(1).addClass("on");
		}
		if(scroll >= content.eq(3).offset().top - headertop){
			$(".header .gnb li").removeClass("on");
			$(".header .gnb li").eq(2).addClass("on");
		}
		if(scroll >= content.eq(4).offset().top - headertop){
			$(".header .gnb li").removeClass("on");
			$(".header .gnb li").eq(3).addClass("on");
		}		
		if(scroll >= content.eq(5).offset().top - headertop - 250){
			$(".header .gnb li").removeClass("on");
			$(".header .gnb li").eq(4).addClass("on");
		}		
   }); //scroll

    $(".header .gnb li").click(function(e){
		e.preventDefault();
		var ht = $(".main > section").height();
		var i = $(this).index();
		var headertop = $(".header").height() ;
		var target = i*ht;
		$("html,body").stop().animate({"scrollTop":target - headertop},500);
	});
});