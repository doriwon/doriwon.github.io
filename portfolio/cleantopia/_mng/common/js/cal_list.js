	$(function() {
		// 조회기간 검색 달력 설정
		$( "#sch_from" ).datepicker({
		  showOn: "button",
		  buttonImage: "./images/btn_calendar.gif",
		  buttonImageOnly: true,
		  buttonText: "Select date",
		  dateFormat: "yymmdd"
		});		

		// 조회기간 검색 달력 설정
		$( "#sch_to" ).datepicker({
		  showOn: "button",
		  buttonImage: "./images/btn_calendar.gif",
		  buttonImageOnly: true,
		  buttonText: "Select date",
		  dateFormat: "yymmdd"
		});

		//버튼이미지CSS
		$("img.ui-datepicker-trigger").attr("style", "vertical-align: middle;");   
	});