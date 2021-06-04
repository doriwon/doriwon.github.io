$(document).ready(function () {
  //$(window).on("load", function(){
  TweenMax.to($(".tit_wrap .tit"), 1, {
    opacity: 1,
    y: 0,
    delay: 0,
    ease: Power1.ease,
  });
  TweenMax.to($(".tit_wrap .txt"), 1, {
    opacity: 1,
    y: 0,
    delay: 0.5,
    ease: Power1.ease,
  });
});

$(window).scroll(function () {
  var winTop = $(this).scrollTop();
  var cont01 = $(".cont01").offset().top - 600;
  var cont02 = $(".cont02").offset().top - 600;
  var cont02Img = $(".cont02 .img").offset().top - 600;
  var cont03 = $(".cont03").offset().top - 600;
  var cont04 = $(".cont04").offset().top - 600;
  var cont05 = $(".cont05").offset().top - 600;

  if (cont01 < winTop && winTop < cont02) {
    TweenMax.to($(".cont01 .txt"), 1, {
      opacity: 1,
      y: 0,
      delay: 0,
      ease: Power1.ease,
    });
  }
  if (cont02 < winTop && winTop < cont04) {
    TweenMax.to($(".cont02 .tit .f_left"), 1, {
      opacity: 1,
      x: 0,
      delay: 0,
      ease: Power1.ease,
    });
    TweenMax.to($(".cont02 .tit .f_right"), 1, {
      opacity: 1,
      x: 0,
      delay: 0.5,
      ease: Power1.ease,
    });
    TweenMax.to($(".cont02 .img .b1"), 1, {
      opacity: 1,
      y: 0,
      delay: 1,
      ease: Power1.ease,
    });
    TweenMax.to($(".cont02 .img .b2"), 1, {
      opacity: 1,
      y: 0,
      delay: 1.5,
      ease: Power1.ease,
    });
    TweenMax.to($(".cont02 .img .b3"), 1, {
      opacity: 1,
      y: 0,
      delay: 2,
      ease: Power1.ease,
    });
  }
  if (cont04 < winTop && winTop < cont03) {
    TweenMax.to($(".cont04 .tit"), 1, {
      opacity: 1,
      y: 0,
      delay: 0,
      ease: Power1.ease,
    });
    TweenMax.to($(".cont04 .txt"), 1, {
      opacity: 1,
      y: 0,
      delay: 0.5,
      ease: Power1.ease,
    });
  }
});

// popup
$(".btn_pop").click(function () {
  pop_rel = $(this).attr("rel");
  $("#" + pop_rel).fadeIn();
  $(".pop_bg").fadeIn();
});

$(".btn_close").click(function () {
  $(".pop_bg").fadeOut();
  $(this).parents(".pop").fadeOut();
});

$(".pop_bg").click(function () {
  $(".pop_bg").fadeOut();
  $(".pop").fadeOut();
});

// tab
$(document).ready(function () {
  var tabBtn = $(".tab_btn > li");
  var tabCont = $(".tab_cont > div");

  tabCont.hide().eq(0).show();

  tabBtn.click(function (e) {
    e.preventDefault();
    var target = $(this);
    var index = target.index();
    tabBtn.removeClass("on");
    target.addClass("on");
    tabCont.css("display", "none");
    tabCont.eq(index).css("display", "block");
  });
});

$(window).scroll(function () {
  // animation
  var tree = new TimelineMax();
  var winTop = $(this).scrollTop();
  var tabCont = $(".tab_cont").offset().top - 600;
  var tab1_box1 = $(".tab01 .txt_box.tb01").offset().top - 600;
  var tab1_box2 = $(".tab01 .txt_box.tb02").offset().top - 600;
  var tab1_box3 = $(".tab01 .txt_box.tb03").offset().top - 600;
  var tab1_box4 = $(".tab01 .txt_box.tb04").offset().top - 600;
  var cont03 = $(".cont03").offset().top - 600;
  var cont04 = $(".cont04").offset().top - 600;
  var cont05 = $(".cont05").offset().top - 600;

  if (cont03 < winTop && winTop < tab1_box1) {
    TweenMax.to($(".tab01 .graph"), 1, { opacity: 1, delay: 0 });
    TweenMax.to($(".tab01 .word01"), 1, { opacity: 1, delay: 0.3 });
    TweenMax.to($(".tab01 .word02"), 1, { opacity: 1, delay: 0.6 });
    TweenMax.to($(".tab01 .word03"), 1, { opacity: 1, delay: 0.9 });
    TweenMax.to($(".tab01 .word04"), 1, { opacity: 1, delay: 1.2 });
    TweenMax.to($(".tab01 .word05"), 1, { opacity: 1, delay: 1.5 });
  }
  if (tab1_box1 < winTop && winTop < tab1_box3) {
    tree.add(
      TweenMax.to(function () {}, 0, {
        onComplete: function () {
          $(".tab01 .txt_box.tb01").addClass("active");
        },
        delay: 0,
      })
    );
    TweenMax.to($(".tab01 .txt_box.tb01 .txt"), 1, {
      opacity: 1,
      delay: 0.5,
    });
    tree.add(
      TweenMax.to(function () {}, 0, {
        onComplete: function () {
          $(".tab01 .txt_box.tb02").addClass("active");
        },
        delay: 1,
      })
    );
    TweenMax.to($(".tab01 .txt_box.tb02 .txt"), 1, {
      opacity: 1,
      delay: 1.5,
    });
  }
  if (tab1_box3 < winTop && winTop < tab1_box4) {
    tree.add(
      TweenMax.to(function () {}, 0, {
        onComplete: function () {
          $(".tab01 .txt_box.tb03").addClass("active");
        },
        delay: 0,
      })
    );
    TweenMax.to($(".tab01 .txt_box.tb03 .txt"), 1, {
      opacity: 1,
      delay: 0.5,
    });
    tree.add(
      TweenMax.to(function () {}, 0, {
        onComplete: function () {
          $(".tab01 .txt_box.tb04").addClass("active");
        },
        delay: 1,
      })
    );
    TweenMax.to($(".tab01 .txt_box.tb04 .txt"), 1, {
      opacity: 1,
      delay: 1.5,
    });
  }
});

$(".cont02 .img a").on("click", function (e) {
  e.preventDefault();
  var offset = $(".tab_btn").offset();
  $("html, body").animate({ scrollTop: offset.top }, 400);
  return false;
});
$(".cont02 .img .b1 a").on("click", function (e) {
  var tabBtn = $(".tab_btn > li");
  var tabCont = $(".tab_cont > div");
  var tabBtnThis = $(".tab_btn .tbt01");
  var tabContThis = $(".tab_cont .tab01");

  e.preventDefault();
  tabBtn.removeClass("on");
  tabBtnThis.addClass("on");
  tabCont.css("display", "none");
  tabContThis.css("display", "block");
});
$(".cont02 .img .b2 a").on("click", function (e) {
  var tabBtn = $(".tab_btn > li");
  var tabCont = $(".tab_cont > div");
  var tabBtnThis = $(".tab_btn .tbt02");
  var tabContThis = $(".tab_cont .tab02");

  e.preventDefault();
  tabBtn.removeClass("on");
  tabBtnThis.addClass("on");
  tabCont.css("display", "none");
  tabContThis.css("display", "block");
});
$(".cont02 .img .b3 a").on("click", function (e) {
  var tabBtn = $(".tab_btn > li");
  var tabCont = $(".tab_cont > div");
  var tabBtnThis = $(".tab_btn .tbt03");
  var tabContThis = $(".tab_cont .tab03");

  e.preventDefault();
  tabBtn.removeClass("on");
  tabBtnThis.addClass("on");
  tabCont.css("display", "none");
  tabContThis.css("display", "block");
});

$(".tab_btn > li.tbt01, .cont02 .img .b1 a").on("click", function (e) {
  e.preventDefault();
  $(".graph_wrap img,.txt_box .txt").css("opacity", "0");
  e.preventDefault();
  var tree = new TimelineMax();
  var tab1_box1 = $(".tab01 .txt_box.tb01").offset().top - 600;
  var tab1_box2 = $(".tab01 .txt_box.tb02").offset().top - 600;
  var tab1_box3 = $(".tab01 .txt_box.tb03").offset().top - 600;
  var tab1_box4 = $(".tab01 .txt_box.tb04").offset().top - 600;

  TweenMax.to($(".tab01 .graph"), 1, { opacity: 1, delay: 0 });
  TweenMax.to($(".tab01 .word01"), 1, { opacity: 1, delay: 0.3 });
  TweenMax.to($(".tab01 .word02"), 1, { opacity: 1, delay: 0.6 });
  TweenMax.to($(".tab01 .word03"), 1, { opacity: 1, delay: 0.9 });
  TweenMax.to($(".tab01 .word04"), 1, { opacity: 1, delay: 1.2 });
  TweenMax.to($(".tab01 .word05"), 1, { opacity: 1, delay: 1.5 });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab01 .txt_box.tb01").addClass("active");
      },
      delay: 1.7,
    })
  );
  TweenMax.to($(".tab01 .txt_box.tb01 .txt"), 1, {
    opacity: 1,
    delay: 2,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab01 .txt_box.tb02").addClass("active");
      },
      delay: 0.5,
    })
  );
  TweenMax.to($(".tab01 .txt_box.tb02 .txt"), 1, {
    opacity: 1,
    delay: 2,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab01 .txt_box.tb03").addClass("active");
      },
      delay: 0.4,
    })
  );
  TweenMax.to($(".tab01 .txt_box.tb03 .txt"), 1, {
    opacity: 1,
    delay: 2.9,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab01 .txt_box.tb04").addClass("active");
      },
      delay: 0.2,
    })
  );
  TweenMax.to($(".tab01 .txt_box.tb04 .txt"), 1, {
    opacity: 1,
    delay: 3.6,
  });
  return false;
});
$(".tab_btn > li.tbt02, .cont02 .img .b2 a").on("click", function (e) {
  e.preventDefault();
  $(".graph_wrap img,.txt_box .txt").css("opacity", "0");
  var tree = new TimelineMax();
  var tab2_box1 = $(".tab02 .txt_box.tb01").offset().top - 600;
  var tab2_box2 = $(".tab02 .txt_box.tb02").offset().top - 600;
  var tab2_box3 = $(".tab02 .txt_box.tb03").offset().top - 600;
  var tab2_box4 = $(".tab02 .txt_box.tb04").offset().top - 600;
  var tab2_box5 = $(".tab02 .txt_box.tb05").offset().top - 600;
  var tab2_box6 = $(".tab02 .txt_box.tb06").offset().top - 600;

  TweenMax.to($(".tab02 .graph"), 1, { opacity: 1, delay: 0 });
  TweenMax.to($(".tab02 .word01"), 1, { opacity: 1, delay: 0.3 });
  TweenMax.to($(".tab02 .word02"), 1, { opacity: 1, delay: 0.6 });
  TweenMax.to($(".tab02 .word03"), 1, { opacity: 1, delay: 0.9 });
  TweenMax.to($(".tab02 .word04"), 1, { opacity: 1, delay: 1.2 });
  TweenMax.to($(".tab02 .word05"), 1, { opacity: 1, delay: 1.5 });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab02 .txt_box.tb01").addClass("active");
      },
      delay: 1.7,
    })
  );
  TweenMax.to($(".tab02 .txt_box.tb01 .txt"), 1, {
    opacity: 1,
    delay: 2,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab02 .txt_box.tb02").addClass("active");
      },
      delay: 0.5,
    })
  );
  TweenMax.to($(".tab02 .txt_box.tb02 .txt"), 1, {
    opacity: 1,
    delay: 2.5,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab02 .txt_box.tb03").addClass("active");
      },
      delay: 0.9,
    })
  );
  TweenMax.to($(".tab02 .txt_box.tb03 .txt"), 1, {
    opacity: 1,
    delay: 2.9,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab02 .txt_box.tb04").addClass("active");
      },
      delay: 0.4,
    })
  );
  TweenMax.to($(".tab02 .txt_box.tb04 .txt"), 1, {
    opacity: 1,
    delay: 3.6,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab02 .txt_box.tb05").addClass("active");
      },
      delay: 0.8,
    })
  );
  TweenMax.to($(".tab02 .txt_box.tb05 .txt"), 1, {
    opacity: 1,
    delay: 4.5,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab02 .txt_box.tb06").addClass("active");
      },
      delay: 0.9,
    })
  );
  TweenMax.to($(".tab02 .txt_box.tb06 .txt"), 1, {
    opacity: 1,
    delay: 5.5,
  });

  return false;
});
$(".tab_btn > li.tbt03, .cont02 .img .b3 a").on("click", function (e) {
  e.preventDefault();
  $(".graph_wrap img,.txt_box .txt").css("opacity", "0");
  var tree = new TimelineMax();
  var tab3_box1 = $(".tab03 .txt_box.tb01").offset().top - 600;
  var tab3_box2 = $(".tab03 .txt_box.tb02").offset().top - 600;
  var tab3_box3 = $(".tab03 .txt_box.tb03").offset().top - 600;
  var tab3_box4 = $(".tab03 .txt_box.tb04").offset().top - 600;
  var tab3_box5 = $(".tab03 .txt_box.tb05").offset().top - 600;
  var tab3_box6 = $(".tab03 .txt_box.tb06").offset().top - 600;

  TweenMax.to($(".tab03 .graph"), 1, { opacity: 1, delay: 0 });
  TweenMax.to($(".tab03 .word01"), 1, { opacity: 1, delay: 0.3 });
  TweenMax.to($(".tab03 .word02"), 1, { opacity: 1, delay: 0.6 });
  TweenMax.to($(".tab03 .word03"), 1, { opacity: 1, delay: 0.9 });
  TweenMax.to($(".tab03 .word04"), 1, { opacity: 1, delay: 1.2 });
  TweenMax.to($(".tab03 .word05"), 1, { opacity: 1, delay: 1.5 });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab03 .txt_box.tb01").addClass("active");
      },
      delay: 1.7,
    })
  );
  TweenMax.to($(".tab03 .txt_box.tb01 .txt"), 1, {
    opacity: 1,
    delay: 2,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab03 .txt_box.tb02").addClass("active");
      },
      delay: 0.5,
    })
  );
  TweenMax.to($(".tab03 .txt_box.tb02 .txt"), 1, {
    opacity: 1,
    delay: 2.5,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab03 .txt_box.tb04").addClass("active");
      },
      delay: 0.9,
    })
  );
  TweenMax.to($(".tab03 .txt_box.tb04 .txt"), 1, {
    opacity: 1,
    delay: 2.9,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab03 .txt_box.tb05").addClass("active");
      },
      delay: 0.4,
    })
  );
  TweenMax.to($(".tab03 .txt_box.tb05 .txt"), 1, {
    opacity: 1,
    delay: 3.6,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab03 .txt_box.tb03").addClass("active");
      },
      delay: 0.8,
    })
  );
  TweenMax.to($(".tab03 .txt_box.tb03 .txt"), 1, {
    opacity: 1,
    delay: 4.5,
  });

  tree.add(
    TweenMax.to(function () {}, 0, {
      onComplete: function () {
        $(".tab03 .txt_box.tb06").addClass("active");
      },
      delay: 1,
    })
  );
  TweenMax.to($(".tab03 .txt_box.tb06 .txt"), 1, {
    opacity: 1,
    delay: 5.5,
  });

  return false;
});
