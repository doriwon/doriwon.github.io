//############### Form Script Start ####################
function Form_chkVal(Fid,msg)
{
	var frm=document.getElementById(Fid);
	if(!frm.value) {alert(msg);frm.focus();return false;}
	else {return true;}
}

function Form_chkVal2(Fid,msg)
{
	var frm=document.getElementById(Fid);
	if(!frm.value) {alert(msg);return false;}
	else {return true;}
}

function Form_chkCheckbox(form,name,msg)
{
	var chk;
	var gid;

	for(i=0;i<form.elements.length;i++)
	{
		if(form.elements[i].name==name)
		{
			if(!gid) gid=i;
			if(form.elements[i].checked) chk=true;
		}
	}

	if(chk==true)
	{
		return true;
	}
	else
	{
		if(msg) alert(msg);
		form.elements[gid].focus();
		return false;
	}
}

function Form_checkAll(form,name)
{
	var a;

	if(document.getElementById(name).checked==true) a=true;
	else a=false;

	for(i=0;i<form.elements.length;i++)
	{
		if(form.elements[i].name==name)
		{
			form.elements[i].checked=a;
		}
	}
}

//영문 숫자만 허용
function Lib_isCode(val) 
{ 
	for(var i=0;i<val.length;i++) 
	{ 
		var chr=val.substr(i,1); 
		if((chr<'0' || chr>'9') && (chr<'a' || chr>'z') && (chr<'A' || chr>'Z') && chr!='-' && chr!='_' && chr!='.') 
		{ 
			return false; 
		} 
	} 
	return true; 
}

function Lib_isKorean(val) 
{ 
	var code=0; 
	for(var i=0;i<val.length;i++) 
	{ 
		var code=val.charCodeAt(i); 
		var chr=val.substr(i,1).toUpperCase(); 
		code=parseInt(code); 
		if((chr<'0' || chr>'9') && (chr<'A' || chr>'Z') && (code>255 || code<0)) 
		{ 
			return false; 
		} 
	} 
	return true; 
}

function emailChg(v,id)
{
	if ( v != "" ) {		
		$("#"+id).attr("readonly", true);
	} else {
		$("#"+id).attr("readonly", false);
	}
	$("#"+id).val(v);
}

function viewAddr(zvar)
{
	var frm=document.nAform55;
	modalWindow('SelZipCode','layerOpenTarget2','tab-zip');
	frm.zipVar.value=zvar;
}

function Dstring(sdate)
{
	var year= sdate.getFullYear();
    var mon = (sdate.getMonth()+1)>9 ? ''+(sdate.getMonth()+1) : '0'+(sdate.getMonth()+1);
    var day = sdate.getDate()>9 ? ''+sdate.getDate() : '0'+sdate.getDate();
	var nDate=year+"-"+mon+"-"+day;

	return nDate;
}

// SNS 연동
function send_sns(dest, url)
{
	var text = encodeURIComponent($("#page_title").text());
	var summary = encodeURIComponent($("#page_summary").text());	
	var imgurl = $("#sns_img").attr("src");
	switch(dest)
	{
		case 1: // twitter
			href = "http://twitter.com/home?status=" + text + ' : ' + summary + " " + encodeURI(url);
			break;
		case 2: // facebook
			href = "http://www.facebook.com/sharer.php?s=100&p[title]="+text+"&p[summary]="+summary+"&p[images][0]="+imgurl+"&p[url]="+encodeURI(url);
			break;
	}

	window.open(href, 'sns', '');
}

//############### Form Script End ####################