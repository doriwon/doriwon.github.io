(function (window) {
    var web;
    web = (function () {
        var ajax;
        var _eventHandlers = {};
        var xml;
        // var xmlParser;

        return {           
            getParameter : function(str) {
                var strHref = location.href;
                var aryHref = strHref.split('#');
                aryHref = aryHref[0].split('?');
                if(!aryHref[1]) { aryHref[1] = ''; }
                var aryHrefParam = aryHref[1].split('&');
                var aryTemp = {};

                if(aryHref[1]) {
                    var intLength = aryHrefParam.length;
                    for(var i=0;i<intLength;i++) {
                        var temp = aryHrefParam[i].split('=');
                        aryTemp[temp[0]] = temp[1];
                    }
                }
                return aryTemp[str];
            },
            getParameterUrl : function(str, url) {
                var strHref = (url)? url : location.href;
                var aryHref = strHref.split('#');
                aryHref = aryHref[0].split('?');
                if(!aryHref[1]) { aryHref[1] = ''; }
                var aryHrefParam = aryHref[1].split('&');
                var aryTemp = {};

                if(aryHref[1]) {
                    var intLength = aryHrefParam.length;
                    for(var i=0;i<intLength;i++) {
                        var temp = aryHrefParam[i].split('=');
                        aryTemp[temp[0]] = temp[1];
                    }
                }
                return aryTemp[str];
            },
            pageMove : function(page, addStr, removeStr) {	
                var strHref = location.href;
                var aryHref = strHref.split('#');
                aryHref = aryHref[0].split('?');

                if(!aryHref[1]) { aryHref[1] = ''; }
                var aryHrefParam = aryHref[1].split('&');
                var aryTemp = {};
                var resultArr = [];
                var resultStr = "";

                if(aryHref[1]) {
                    var intLength = aryHrefParam.length;
                    for(var i=0;i<intLength;i++) {
                        var temp = aryHrefParam[i].split('=');
                        if(temp[1]) aryTemp[temp[0]] = temp[1];
                    }
                }
                if(addStr) {
                    var addStrArr = addStr.split(",");
                    for(var i=0; i<addStrArr.length; i++) {
                        var temp = addStrArr[i].split("=");
                        if(temp[1]) aryTemp[temp[0]] = temp[1];
                    }
                }
                if(removeStr) {
                    var removeStrArr = removeStr.split(",");
                    for(var i=0; i<removeStrArr.length; i++) {
                        var temp = removeStrArr[i].split("=");
                        delete aryTemp[temp[0]];
                    }
                }
                for( var key in aryTemp ) {
                    if(aryTemp[key]) resultArr.push(key+"="+aryTemp[key]);
                }
                if(resultArr.length > 0) resultStr = page + "?" + resultArr.join("&");
                else resultStr = page;

                location.href = resultStr;
            },
            isMobile: function () {
                return (/inapp|android|webos|iphone|ipod|ipad|blackberry|iemobile|opera mini/i
                    .test(navigator.userAgent.toLowerCase()));
            },
            parseJson: function (data) {
                var obj;
                try {
                    obj = !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(data
                        .replace(/\"(\\.|[^\"\\])*\"/g, ''))) && eval('(' + data + ')');
                    if (obj == false)
                        obj = {
                            error: "Parse Error",
                            text: data
                        };
                } catch (e) {
                    obj = {
                        error: "Parse Error",
                        text: data
                    };
                }
                return obj;
            },
            alert: function (arg1, arg2) {
                if (window.$.gritter) {
                    if (arguments.length == 2) {
                        $.gritter.add({
                            // (string | mandatory) the heading of the notification
                            title: arg1,
                            // (string | mandatory) the text inside the notification
                            text: arg2
                        });
                    } else {
                        $.gritter.add({
                            // (string | mandatory) the heading of the notification
                            title: "알림!",
                            // (string | mandatory) the text inside the notification
                            text: arg1
                        });

                    }
                } else {
                    if (arguments.length == 2) {
                        alert(arg1 + " : " + arg2);
                    } else {
                        alert(arg1);
                    }
                }

            },
            isEmail: function (str) {
                var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
                return regex.test(str);
            },
            isPhone: function (str) {
                str = str.replace(/[-]/g, "");
                var regex = /^((01[1|6|7|8|9])[1-9]+[0-9]{6,7})|(010[1-9][0-9]{7})$/;
                return regex.test(str);
            },
            isTel: function (str) {
                str = str.replace(/[-]/g, "");
                var regex = /^\d{2,3}\d{3,4}\d{4}$/;;
                return regex.test(str);
            },
            removeSC: function (str) {
                str = str.replace(/[^0-9]/g, "");
                return str;
            },
            trim: function (str) {
                return str.replace(/(^\s*)|(\s*$)/gi, "");
            },
            isNull: function (str) {
                return (typeof str == "undefined" || str == null || str == undefined || str == "null" || str == "undefined" || str == "");
            },
            isNumber: function (str) {
                return (str != "" && !isNaN(str));
            },
            isNumberScope: function (obj, min, max) {
                if (!this.isNull(obj.value)) {
                    if (!this.isNumber(obj.value)) {
                        this.alert("숫자를 입력해주세요.");
                        obj.value = "";
                        obj.focus();
                    } else if ((obj.value < min || obj.value > max) && min != max) {
                        this.alert(min + " ~ " + max + " 사이의 숫자를 입력해주세요.");
                        obj.value = min;
                        obj.focus();
                    } else if ((obj.value < min || obj.value > max) && min == max) {
                        this.alert(min + " 만 입력 가능합니다.");
                        obj.value = min;
                        obj.focus();
                    }
                }
            },
            numberFormat: function (number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            },
            addEventListener: function (obj, eventName, func) {
                if (!this.isNull(obj)) {
                    if(!(obj in _eventHandlers)) {
                        // _eventHandlers stores references to nodes
                        _eventHandlers[obj] = {};
                    }
                    if(!(eventName in _eventHandlers[obj])) {
                        // each entry contains another entry for each event type
                        _eventHandlers[obj][eventName] = [];
                    }
                    _eventHandlers[obj][eventName].push(func);

                    if (!obj.addEventListener) {
                        obj.attachEvent("on" + eventName, func);
                    } else {
                        obj.addEventListener(eventName, func, false);
                    }
                }
            },
            removeEventListener : function(obj, eventName, func) {
                if (!this.isNull(obj)) {
                    if (!obj.removeEventListener) {
                        obj.detachEvent("on" + eventName, func);
                    } else {
                        obj.removeEventListener(eventName, func, false);
                    }
                }
            },
            removeAllEventListener : function(obj, eventName) {
                if (!this.isNull(obj)) {
                    if(obj in _eventHandlers) {
                        var handlers = _eventHandlers[obj];
                        if(eventName in handlers) {
                            var eventHandlers = handlers[eventName];
                            for(var i = eventHandlers.length; i--;) {
                                var handler = eventHandlers[i];
                                obj.removeEventListener(eventName, handler, false);
                                if (!obj.removeEventListener) {
                                    obj.detachEvent("on" + eventName, handler);
                                } else {
                                    obj.removeEventListener(eventName, handler, false);
                                }
                            }
                        }
                    }

                }
            },
            setAjax: function () {
                if (!ajax) {
                    try {
                        // Opera 8.0+, Firefox, Chrome, Safari
                        ajax = new XMLHttpRequest();
                    } catch (e) {
                        // Internet Explorer Browsers
                        try {
                            ajax = new ActiveXObject("Msxml2.XMLHTTP");
                        } catch (e) {
                            try {
                                ajax = new ActiveXObject("Microsoft.XMLHTTP");
                            } catch (e) {
                                return false;
                            }
                        }
                    }
                }
            },
            getData: function (url, callback, async) {
                this.setAjax();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        if (ajax.status == 200 || ajax.status == 201) {
                            callback.call(this, ajax.responseText);
                        } else {
                            this.alert("다시 시도해주세요.("+ajax.status+")");
                        }
                    }
                }

                ajax.open("GET", url, ((async == true || async == false)? async : true));
                ajax.send(null);
            },
            postData: function (formObj, callback, async) {
                this.setAjax();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        if (ajax.status == 200 || ajax.status == 201) {
                            callback.call(this, ajax.responseText);
                        } else {
                        	this.alert("다시 시도해주세요.("+ajax.status+")");
                        }
                    }
                }
                ajax.open("POST", formObj.getAttribute("action"), ((async == true || async == false)? async : true));
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.setRequestHeader("isAjax", "Y");
                ajax.send(this.serialize(formObj));
            },
            postSimpleData: function (url, callback, async) {
                this.setAjax();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        if (ajax.status == 200 || ajax.status == 201) {
                            callback.call(this, ajax.responseText);
                        } else {
                        	this.alert("다시 시도해주세요.("+ajax.status+")");
                        }
                    }
                }
                ajax.open("POST", url.split("?")[0], ((async == true || async == false)? async : true));
                ajax.setRequestHeader("Content-type",
                    "application/x-www-form-urlencoded");
                ajax.send(url.split("?")[1]);
            },
            postJsonData: function (obj, callback, async) {
                if (typeof obj != "object") {
                	this.alert("데이터 형식이 올바르지 않습니다.");
                    return;
                }

                this.setAjax();
                ajax.onreadystatechange = function () {
                    if (ajax.readyState == 4) {
                        if (ajax.status == 200 || ajax.status == 201) {
                            callback.call(this, ajax.responseText);
                        } else {
                        	this.alert("다시 시도해주세요.("+ajax.status+")");
                        }
                    }
                }
                var queryString = [];
                for (key in obj) {
                    if(key == "url") continue;
                    queryString.push(key + "=" + obj[key]);
                }
                ajax.open("POST", obj.url, ((async == true || async == false)? async : true));
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send(queryString.join("&"));
            },
            ajax: function (obj) {
				try {
					/*
						var obj = {
							url : "url.jsp",
							method : "post",
							async : false(동기화할것인가. default = true),
							resultType : "json",(데이터를 받을 방식. default = text)
							data : {id : 'user', pwd : '1234'} 또는 form 객체,
							success : function(result) {
								성공시
							},
							error : function(result, status) {
								에러시
							}
						}
						web.ajax(obj);
					*/
					if (typeof obj != "object") {
						this.alert("데이터 형식이 올바르지 않습니다.");
						return;
					}

					this.setAjax();
					var queryString = "";
					var parseJson = this.parseJson;

					ajax.onreadystatechange = function () {
						if (ajax.readyState == 4) {
							if (ajax.status == 200 || ajax.status == 201) {
								if(obj.success) {	
									var result = (obj.resultType && obj.resultType.toLowerCase() === "json") ? parseJson(ajax.responseText) : ajax.responseText;
									obj.success.call(this, result);
								}                            
							} else {
								if(obj.error) {
									obj.error.call(this, ajax.responseText, ajax.status);
								} 
							}
						}
					}

					if(obj.data) {
						if (!obj.data.nodeName || obj.data.nodeName !== "FORM") {
							var tempArr = [];
							for (key in obj.data) {                							
								tempArr.push(key + "=" + obj.data[key]);
							}
							queryString = tempArr.join("&");
						} else {		
							queryString = this.serialize(obj.data);
						}
					}

					if(obj.method.toLowerCase() == "get") {
						ajax.open(obj.method, ((!queryString)? obj.url : obj.url+"?"+queryString) , ((obj.async == true || obj.async == false)? obj.async : true));
						ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						ajax.setRequestHeader("isAjax", "Y");
						ajax.send(null);
					} else if(obj.method.toLowerCase() == "post") {
						ajax.open(obj.method, obj.url, ((obj.async == true || obj.async == false)? obj.async : true));
						ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						ajax.setRequestHeader("isAjax", "Y");
						ajax.send(queryString);
					}					
				} catch (exception) {
					var output = "";
					for (var e in exception) {
						output += e + ' : ' + exception[e] + '\n';
					}
					this.alert("요청이 실패하였습니다.");
				}

            },
            getElementsByAttr : function(attr) {
                var tags = document.getElementsByTagName("*");
                var length = tags.length;
                var result = [];
                for(var i = 0; i < length; i++) {
                    if(tags[i].getAttribute("data-chain") == attr) result.push(tags[i]);
                }
                return result;
            },
            formValidation: function (form, isInvisibleCheck) {
                if (!form || form.nodeName !== "FORM") {
                    return;
                }
                var i, j, k, l, cnt = 0,
                    obj = [],
                    result = true,
                    length = form.elements.length;

                for (i = 0; i < length; i++) {
                    var isVisible = form.elements[i].offsetWidth > 0 || form.elements[i].offsetHeight > 0;
                    var validChain = form.elements[i].getAttribute("data-chain");
                    var validEquals = form.elements[i].getAttribute("data-equals");
                    var validAttr = form.elements[i].getAttribute("data-valid");
                    var validAlert = (form.elements[i].getAttribute("data-alert") ? form.elements[i].getAttribute("data-alert") : form.elements[i].name)
                    var value = form.elements[i].value;
                    var name = form.elements[i].name;

                    if (result == false)
                        break;
                    if (form.elements[i].name === "")
                        continue;
                    if (form.elements[i].disabled === true)
                        continue;
                    if (!validAttr)
                        continue;
                    if (isInvisibleCheck != true && !isVisible) continue;
                    switch (form.elements[i].nodeName) {
                        case 'TEXTAREA':
                            if (validAttr == "notnull") {
                                if (this.isNull(this.trim(value))) {
                                    this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                    form.elements[i].focus();
                                    result = false;
                                }
                            }
                            break;
						case 'SELECT':
                        case 'INPUT':
                            switch (form.elements[i].type) {
                                case 'text':
		                        case 'file':
                                case 'hidden':
                                case 'password':
                                case 'button':
                                case 'reset':
                                case 'submit':
								case 'select-one':
                                case 'select-multiple':
                                    if (validEquals) {
                                        var elements = document.getElementsByName(validEquals)[0];

                                        if (this.isNull(this.trim(value))) {
                                            this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                            form.elements[i].focus();
                                            result = false;
                                            break;
                                        } else if(value != elements.value) {
                                            this.alert(elements.getAttribute("data-alert") + " 항목과 값이 같아야합니다.");
                                            form.elements[i].focus();
                                            result = false;
                                            break;
                                        }

                                        if (result == false)
                                            break;
                                    }

                                    if (validChain) {
                                        var elements = this.getElementsByAttr(validChain);
                                        value = "";
                                        for (var l = 0; l < elements.length; l++) {
                                            var val = elements[l].value;
                                            if (this.isNull(this.trim(val))) {
                                                this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                                form.elements[i].focus();
                                                result = false;
                                                break;
                                            }

                                            if (validAttr == "email" && l == (elements.length-1)) value = value + "@";
                                            value = value + elements[l].value;
                                        }
                                        if (result == false)
                                            break;
                                    }

                                    if (validAttr == "notnull") {
                                        if (this.isNull(this.trim(value))) {
                                            this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                            form.elements[i].focus();
                                            result = false;
                                        }
                                    } else if (validAttr == "email") {
                                        if (this.isNull(this.trim(value))) {
                                            this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                            form.elements[i].focus();
                                            result = false;
                                        } else if (!this.isEmail(value)) {
                                            this.alert(validAlert + " 항목의 형식이 올바르지 않습니다.");
                                            form.elements[i].focus();
                                            result = false;
                                        }
                                    } else if (validAttr == "phone") {
                                        if (this.isNull(this.trim(value))) {
                                            this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                            form.elements[i].focus();
                                            result = false;
                                        } else if (!this.isPhone(value)) {
                                            this.alert(validAlert + " 항목의 형식이 올바르지 않습니다.");
                                            form.elements[i].focus();
                                            result = false;
                                        }
                                    } else if (validAttr == "tel") {
                                        if (this.isNull(this.trim(value))) {
                                            this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                            form.elements[i].focus();
                                            result = false;
                                        } else if (!this.isTel(value)) {
                                            this.alert(validAlert + " 항목의 형식이 올바르지 않습니다.");
                                            form.elements[i].focus();
                                            result = false;
                                        }
                                    }
                                    break;
                                case 'checkbox':
                                case 'radio':
                                    obj = document.getElementsByName(name);
                                    for (k = 0; k < obj.length; k++) {
                                        if (obj[k].checked && !this.isNull(obj[k].value)) {
                                            cnt++;
                                        }
                                    }
                                    if (cnt == 0) {
                                        this.alert(validAlert + " 항목은 필수 입력 값입니다.");
                                        form.elements[i].focus();
                                        result = false;
                                    }
                                    cnt = 0;
                                    break;
                            }
                    }
                }
                return result;
            },
            serialize: function (form) {
                if (!form || form.nodeName !== "FORM") {
                    return;
                }
                var i, j, q = [], length = form.elements.length;

                for (i = 0; i < length; i++) {
                    if (form.elements[i].name === "") {
                        continue;
                    }
                    if (form.elements[i].disabled === true) {
                        continue;
                    }
                    switch (form.elements[i].nodeName) {
                        case 'INPUT':
                            switch (form.elements[i].type) {
                                case 'text':
                                case 'hidden':
                                case 'password':
                                case 'button':
                                case 'reset':
                                case 'submit':
                                    q
                                        .push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                                    break;
                                case 'checkbox':
                                case 'radio':
                                    if (form.elements[i].checked) {
                                        q
                                            .push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                                    }
                                    break;
                            }
                            break;
                        case 'file':
                            break;
                        case 'TEXTAREA':
                            q.push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                            break;
                        case 'SELECT':
                            switch (form.elements[i].type) {
                                case 'select-one':
                                    q
                                        .push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                                    break;
                                case 'select-multiple':
                                    for (j = form.elements[i].options.length - 1; j >= 0; j = j - 1) {
                                        if (form.elements[i].options[j].selected) {
                                            q
                                                .push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].options[j].value));
                                        }
                                    }
                                    break;
                            }
                            break;
                        case 'BUTTON':
                            switch (form.elements[i].type) {
                                case 'reset':
                                case 'submit':
                                case 'button':
                                    q
                                        .push(form.elements[i].name + "=" + encodeURIComponent(form.elements[i].value));
                                    break;
                            }
                            break;
                    }
                }
                return q.join("&");
            },
            asyncFileUpload: function (fileObj, action, callback) {
                var cloneFile = fileObj.cloneNode(true);
                var target = 'iframe_upload';
                var form;
                var uploadFrame;

                if ((/MSIE (6|7|8)/).test(navigator.userAgent)) {
                    form = document.createElement("<form name='iframe_upload_form' enctype='multipart/form-data'>");
                } else {
                    form = document.createElement('form');
                    form.enctype = "multipart/form-data";
                }
                form.action = action;
                form.method = "post";
                form.style.display = "none";
                form.target = target;

                fileObj.parentNode.insertBefore(cloneFile, fileObj);
                document.body.appendChild(form);
                form.appendChild(fileObj);

                uploadFrame = (/MSIE (6|7|8)/).test(navigator.userAgent) ?
                    document.createElement('<iframe name="' + target + '">') :
                    document.createElement('iframe');
                uploadFrame.name = target;
                uploadFrame.style.display = "none";
                uploadFrame.id = target;
                document.body.appendChild(uploadFrame);
                window.frames[target].name = target;


                this.addEventListener(uploadFrame, "load", function () {
                    callback(uploadFrame.contentWindow.document.body.innerHTML);
                    window.document.body.removeChild(form);
                    window.document.body.removeChild(uploadFrame);
                });

                form.submit();
            },
            errorAlert: function (form, errorObj) {
                if (typeof errorObj != "object") {
                    errorObj = this.parseJson(errorObj);
                }
                if (form && form.nodeName === "FORM") {
                    for (key in errorObj) {
                        if (key === "loginRequired") {
                            this.alert("로그인 후 이용해주세요.");
                            return;
                        }
                        if (key === "tokenError") {
                            this.alert("요청이 올바르지 않습니다!");
                            return;
                        }
                        var errorType = errorObj[key];
                        var length = form.elements.length;
                        for (var i = 0; i < length; i++) {
                            var element = form.elements[i];
                            var alertAttr = element.getAttribute("data-alert") ? element.getAttribute("data-alert") : element.getAttribute("name");
                            if (element.getAttribute("name") === key) {
                                element.focus();
                                if (errorType === "required") {
                                    this.alert(alertAttr + " 항목은 필수 입력 값입니다.");
                                } else if (errorType === "digits") {
                                    this.alert(alertAttr + " 항목은 숫자만 입력 가능합니다.");
                                } else if (errorType === "minlength") {
                                    this.alert(alertAttr + " 항목의 글자 수가 너무 짧습니다.");
                                } else if (errorType === "maxlength") {
                                    this.alert(alertAttr + " 항목의 글자 수가 너무 깁니다.");
                                }
                            }
                        }

                    }
                }
            }
        }
    })();
    window.web = web;
})(window);

	
	// 조회기간 설정
	function goBoardPreiodModifyEvent(strType, intPreiod) {
		
		// 기본설정
		var objSchFrom = $("#sch_from");
		var objSchTo = $("#sch_to");
		var objDateFrom = new Date();
		var objDateTo = new Date();
		var strSchFrom = "";
		var strSchTo = "";

		// 체크
		if(!strType) { strType = ""; }
		if(!intPreiod) { intPreiod = 1; }

		if(strType == "today") {
			var objDate = new Date();
			strSchFrom = $.datepicker.formatDate('yymmdd', objDateFrom);
			strSchTo = $.datepicker.formatDate('yymmdd', objDateTo);	
		} else if(strType == "week") {
			objDateFrom.setDate(objDateFrom.getDate()-7);
			strSchFrom = $.datepicker.formatDate('yymmdd', objDateFrom);
			strSchTo = $.datepicker.formatDate('yymmdd', objDateTo);	
		} else if(strType == "month") {
			objDateFrom.setMonth(objDateFrom.getMonth()-intPreiod);
			strSchFrom = $.datepicker.formatDate('yymmdd', objDateFrom);
			strSchTo = $.datepicker.formatDate('yymmdd', objDateTo);	
		}


		objSchFrom.val(strSchFrom);
		objSchTo.val(strSchTo);

	}

	// 페이지 이동
	function goGlobalPageMoveEvent(intPage) {
		
		var data = new Object();
		data['page'] = intPage;

		goGlobalAddLocationUrl(data);

	}

	// 현재 주소에서 파라미터 추가
	function goGlobalAddLocationUrl(data, strUserHref, strTailTag, nodata) {
		// 기본설정
		var strHref = location.href;
		var aryHref = strHref.split('#'); 
		var aryHref = aryHref[0].split('?'); if(!aryHref[1]) { aryHref[1] = ''; }
		var aryHrefParam = aryHref[1].split('&');
		var aryParam = data;

		if(!strTailTag) { strTailTag =''; }
		if(!nodata) { nodata =''; }

		if(aryHref[1]) {
			var aryTemp = new Object();
			var intLength = aryHrefParam.length;
			for(var i=0;i<intLength;i++) {
				var temp = aryHrefParam[i].split('=');
				aryTemp[temp[0]] = temp[1];
			}
			
			aryParam = aryTemp;
			if(data) {
				for(var i in data) {					
					aryParam[i] = data[i];
				}
			}
		}

		// strUrlParam 만들기
		var strUrlParam = "";
		for(var i in aryParam) {
			
			if(!aryParam[i]) { continue; }
			if(aryParam[i] == "") { continue; }
			if(aryParam[i] == null) { continue; }

			//파라미터에서제거할 파라미터
			if(i == nodata) { continue; }		
			
			if(strUrlParam) { strUrlParam = strUrlParam + "&"; }
			strUrlParam = strUrlParam + i + '=' + aryParam[i];
		}

		// href 만들기
		if(!strUserHref) { strUserHref = aryHref[0]; }
		if(strUrlParam) { strUserHref = strUserHref + "?"; }
		strUserHref = strUserHref + strUrlParam;
		// 마무리
		location.href = strUserHref + strTailTag;
	}


	

	// 검색
	function goBoardSearchMoveEvent(url) {

		// 기본설정
		var objTarget = $('.pdc_form');
		var strSchFrom = objTarget.find('[name=sch_from]').val();
		var strSchTo = objTarget.find('[name=sch_to]').val();
		var strSearchType = objTarget.find('[name=searchType]').val();
		var strSearchWord = objTarget.find('[name=searchWord]').val();

		// 페이지 이동
		var data = new Object();
		data['searchFrom'] = strSchFrom;
		data['searchTo'] = strSchTo;
		data['searchType'] = strSearchType;
		data['searchWord'] = strSearchWord;

		goGlobalAddLocationUrl(data, url);
	}

	// 검색
	function goBoardParamMoveEvent(col, val) {

		// 기본설정
		var data = new Object();
		data[col] = val;

		goGlobalAddLocationUrl(data);
	}

	// 뷰페이지 이동
	function goBoardViewMoveEvent(intIdx, url) {
		// 기본설정
		var data = new Object();
		data['idx'] = intIdx;

		goGlobalAddLocationUrl(data, url);

	}


	// 목록 이동(4번째 seq는 파라미터에서 제외시킴)
	function goBoardListMoveEvent(url) {
		goGlobalAddLocationUrl('', url,'','seq');
	}	
	
	// 수정 이동
	function goBoardViewModifyMoveEvent(url) {
		goGlobalAddLocationUrl('', url);
	}

	// 페이지 이동시 컬럼추가
	function goBoardColumnMoveEvent(colm, val, url) {

		var data = new Object();
			data[colm] = val;

			goGlobalAddLocationUrl(data, url);
	}

	

	// 글작성하기
	function goBoardWriteMoveEvent(url) {

		goGlobalAddLocationUrl('', url);

	}

	function popFile(col) {
		var popupFile = window.open("/assets/file/fileUpload.jsp?col="+col,"popupFile","left=300px,top=200px,width=300,height=210");
		popupFile.focus();
	}

    function videoFile() {
        var videoFile = window.open("/assets/file/videoUpload.jsp","popupFile","left=300px,top=200px,width=300,height=265");
        videoFile.focus();
    }
    $(function() {
        $.ajaxSetup({
            dataType : "json",
            error : function(xhr, status, error) {
                alert("error : ["+status+" / " + error + "]")
            }
        });
    });