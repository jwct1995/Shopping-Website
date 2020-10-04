// JavaScript Document

function AjaxLoadTitle()
{
	var rtn="";
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{post:"LoadTitle"},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			$("title").text(data);
        },
        error: function () 
        {
            
        },
    });
	return rtn;
}

function AjaxSubmitCheckLogin()
{
	var rtn="";
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{post:"CheckLogin"},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rtn=data;
        },
        error: function () 
        {
            
        },
    });
	return rtn;
}
function AjaxSubmitCheckCompany()
{
	var rtn="";
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{post:"CheckCompany"},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rtn=data;
        },
        error: function () 
        {
            
        },
    });
	return rtn;
}

function CheckTxt(ele)
{
	var val=ele.val()
	var minL=parseInt(ele.attr("min"));
	var errorms=ele.attr("errorms");
	var tf=0;
		
	if(val && val.length>=minL)
	{
		ele.css({"border-color":"","border-width":"","border-style":""});
		$("[id='"+ele.attr("id")+"'][name='error']").remove();
		tf=0;
	}
	else
	{
		ele.css({"border-color":"red","border-width":"2px","border-style":"solid"});
		
		var pleft=ele.position().left+ele.outerWidth();
		var ptop=ele.position().top;
		
		ele.after("<div style='float: right;padding-left: 5px;padding-right: 5px;background-color: red;margin-left: 10px;color: white; display:block;position: absolute;left:"+pleft+"px;top:"+ptop+"px;' id='"+ele.attr("id")+"' name='error'>"+errorms+"</div>");
		tf=1;
	}
	return tf;
}
function getTodayDate()
{
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10) 
	{
    	dd = '0'+dd
	} 
	
	if(mm<10) {
		mm = '0'+mm
	} 
	return yyyy+"-"+mm+"-"+dd;
}
function getNextMonthDate()
{
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+2; //January is 0!
	var yyyy = today.getFullYear();
	if(mm>12)
	{
		mm-=12;
		yyyy+=1;
	}
	
	if(dd<10) 
	{
    	dd = '0'+dd
	} 
	
	if(mm<10) {
		mm = '0'+mm
	} 
	return yyyy+"-"+mm+"-"+dd;
}
