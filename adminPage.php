<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Saler Page</title>
</head>
<link href="css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-3.1.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js.js" charset="utf-8"></script>
<?php 
	include"basic.html";
?>
<script>
var CategoryElementArray=new Array();
var CategoryArray=new Array();
var TableArray=new Array();
var ReportArray=new Array();
var ReportYearArray=new Array();
var ReportMonthArray=new Array();
var ReportDayArray=new Array();
var ReportSDTArray=new Array();
var CompanyArray=new Array();
var hv=0;

var currentType="";
var currentValue="";

$(function()
{
	CompanyArray=AjaxLoadCompany();
	setCompanyList();
});
$(document).ready(function()
{
	$("#aAdminMSName").click(function()
	{
		closeAll();
		$("#DivMSName").css("display","block");
	});
	$("#aAdminCategoryDesign").click(function()
	{
		closeAll();
		CategoryArray=AjaxLoadCategory();
		setUpSlc();
		$("#DivCategoryDesign").css("display","block");
	});
	$("#aAdminCategoryMenu").click(function()
	{
		closeAll();
		CategoryArray=AjaxLoadCategory();
		TableArray=AjaxLoadCategoryDesign();
		setMenu();
		var str=$("#divCode").clone().html();
		$("#txtCode").val(str);
		$("#DivCategoryMenu").css("display","block");
	});
	
	$("#txtCode").keyup( function()
	{
		$("#divCode").html($("#txtCode").val());
	});
	$("#btnMSName").click( function()
    {
		AjaxSubmitUpdateMSName($("#txtMSName").val());
	});
	$("#btnCategoryMenuSubmit").click( function()
    {
		AjaxSubmitUpdateCategoryMenu($("#txtCode").val());
	});
	
	
	$("#btnCategoryDesignSubmit").click( function()
    {
		AjaxSubmitCategoryDesign(showCurrentSlcSelect());
	});
	
	$("#divSlcCategoryDesign").on("keyup", "[name='inpTxt']", function()
	{
		var inptxt=$(this).val();
		var no=$(this).attr("no");
		var str="";
		if(inptxt.length>0)
		{
			$("#slc"+no+" >option").each(function() 
			{
				var txt =$(this).text()
				
				if(txt.search(inptxt)==-1 && $(this).attr("id")!=0)
					$(this).css("display","none");
				else
					$(this).css("display","");
			});
		}
		else
		{
			$("#slc"+no+" >option").css("display","");
		}
		var wslc=$("#slc"+no).outerWidth(true)-30;
		$("#inpTxt"+no).css("width",wslc+"px");
	});
	
	$("#divSlcCategoryDesign").on("change", "[name='slcC']", function()
	{
		var no=parseInt($(this).attr("no"));
		if(no<hv)
		{
			for(var c=hv;c>no;c--)
			{
				$("#div"+c).remove();
				hv=no;
			}	
		}
		showCurrentSlcSelect();
		removeSlcOption();
	});
	$("#btnAddSlcCategoryDesign").click( function()
	{
		setUpSlc();
		removeSlcOption();
	});
	$("#aAdminReportYear").click(function()
	{
		currentType="AdminReportYear";
		currentValue="--";
		closeAll();
		$("#DivCompanyNameList").css("display","");
		$("#DivReport").css("display","block");
		ReportArray=AjaxLoadReport();
		setReport();
	});
	$("#aAdminReportPickDateTime").click(function()
	{
		closeAll();
		$("#DivCompanyNameList").css("display","");
		$("#DivReportPickDateTime").css("display","block");
		
		var today=new Date();
		
		var tyear=today.getFullYear();
		var tmonth=today.getMonth();
		var tday=today.getDay();
		
		var syear=$("#StartYear");
		var optSY = $("<option></option").val("--").html("Select Year");
			syear.append(optSY);
		for(var c=tyear;2010<c;c--)
		{
			var optSY = $("<option></option").val(c).html(c);
			syear.append(optSY);
		}
		
		var smonth=$("#StartMonth");
		var optSM = $("<option></option").val("--").html("Select Month");
			smonth.append(optSM);
		for(var c=1;c<=12;c++)
		{
			var txt="";
			if(c.toString().length==1)
				txt="0"+c;
			else
				txt=c;
			var optSM = $("<option></option").val(txt).html(txt);
			smonth.append(optSM);
		}
		
		var sday=$("#StartDay");
		var optSD = $("<option></option").val("--").html("Select Day");
			sday.append(optSD);
		for(var c=1;c<=31;c++)
		{
			var txt="";
			if(c.toString().length==1)
				txt="0"+c;
			else
				txt=c;
			var optSD = $("<option></option").val(txt).html(txt);
			sday.append(optSD);
		}
		
		var shour=$("#StartHour");
		var optSH = $("<option></option").val("--").html("Select Hour");
			shour.append(optSH);
		for(var c=0;c<=23;c++)
		{
			var txt="";
			if(c.toString().length==1)
				txt="0"+c;
			else
				txt=c;
			var optSH = $("<option></option").val(txt).html(txt);
			shour.append(optSH);
		}
		
		var eyear=$("#EndYear");
		var optEY = $("<option></option").val("--").html("Select Year");
			eyear.append(optEY);
		for(var c=tyear;2010<c;c--)
		{
			var optEY = $("<option></option").val(c).html(c);
			eyear.append(optEY);
		}
		
		var emonth=$("#EndMonth");
		var optEM = $("<option></option").val("--").html("Select Month");
			emonth.append(optEM);
		for(var c=1;c<=12;c++)
		{
			var txt="";
			if(c.toString().length==1)
				txt="0"+c;
			else
				txt=c;
			var optEM = $("<option></option").val(txt).html(txt);
			emonth.append(optEM);
		}
		
		var eday=$("#EndDay");
		var optED = $("<option></option").val("--").html("Select Day");
			eday.append(optED);
		for(var c=1;c<=31;c++)
		{
			var txt="";
			if(c.toString().length==1)
				txt="0"+c;
			else
				txt=c;
			var optED = $("<option></option").val(txt).html(txt);
			eday.append(optED);
		}
		
		var ehour=$("#EndHour");
		var optEH = $("<option></option").val("--").html("Select Hour");
			ehour.append(optEH);
		for(var c=0;c<=23;c++)
		{
			var txt="";
			if(c.toString().length==1)
				txt="0"+c;
			else
				txt=c;
			var optEH = $("<option></option").val(txt).html(txt);
			ehour.append(optEH);
		}

	});
	$("#btnDivReportPickDateTime").click(function()
	{
		currentType="ReportPickDateTime"
		currentValue="--";
		ReportSDTArray=AjaxLoadReportSDT();
		setReportSDT();
	});

	$("#DivReport").on("click", "[name='ahrefYear']", function()
	{
		currentType="Report"
		currentValue=$(this).attr("year");
		closeAll();
		$("#DivCompanyNameList").css("display","");
		$("#DivReportYear").css("display","block");
		ReportYearArray=AjaxLoadReportYear($(this).attr("year"));
		setReportYear();
	});
	$("#DivReportYear").on("click", "[name='ahrefMonth']", function()
	{
		currentType="ReportYear";
		currentValue=$(this).attr("month");
		closeAll();
		$("#DivCompanyNameList").css("display","");
		$("#DivReportMonth").css("display","block");
		ReportMonthArray=AjaxLoadReportMonth($(this).attr("month"));
		setReportMonth();
	});
	$("#DivReportMonth").on("click", "[name='ahrefDay']", function()
	{
		currentType="ReportMonth";
		currentValue=$(this).attr("day");
		closeAll();
		$("#DivCompanyNameList").css("display","");
		$("#DivReportDay").css("display","block");
		ReportDayArray=AjaxLoadReportDay($(this).attr("day"));
		setReportDay();
	});
	
	$("#slcCompanyNameList").change(function() 
	{   
		if(currentType=="AdminReportYear")
		{
			ReportArray=AjaxLoadReport();
			setReport();
		}
		else if(currentType=="Report")
		{
			ReportYearArray=AjaxLoadReportYear(currentValue);
			setReportYear();
		}
		else if(currentType=="ReportYear")
		{
			ReportMonthArray=AjaxLoadReportMonth(currentValue);
			setReportMonth();
		}
		else if(currentType=="ReportMonth")
		{
			ReportDayArray=AjaxLoadReportDay(currentValue);
			setReportDay();
		}
		else if(currentType=="ReportPickDateTime")
		{
			ReportSDTArray=AjaxLoadReportSDT();
			setReportSDT();
		}
		
	});	

});
function closeAll()
{
	$("#DivCategoryDesign").css("display","none");
	$("#DivCategoryMenu").css("display","none");
	$("#DivReport").css("display","none");
	$("#DivReportYear").css("display","none");
	$("#DivReportMonth").css("display","none");
	$("#DivReportDay").css("display","none");
	$("#DivReportPickDateTime").css("display","none");
	$("#DivCompanyNameList").css("display","none");
	$("#DivMSName").css("display","none");
}

function AjaxSubmitUpdateMSName(txt)
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"UpdateMSName",name:txt},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==1)
				alert("Success Update System Name");
			else
				alert("Fail Update System Name");
        },
        error: function () 
        {
            alert("Error! Update System Name");
        },
    });
	return rdata;
}

function AjaxLoadCompany()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadCompanyList"},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {
        },
    });
	return rdata;
}
function setCompanyList()
{
	var slc=$("#slcCompanyNameList");
	slc.html("");
	var opt = $("<option></option"); 
			opt.val("--"); 
			opt.text("Select Company");
			slc.append(opt);
	CompanyArray.forEach(function(ele) 
	{
		if(ele.companyname!="Admin")
		{
			var opt = $("<option></option"); 
			opt.val(ele.companyid); 
			opt.text(ele.companyname);
			slc.append(opt);
		}
	});
}


function setName()
{
	$("[name='ctgBtn']").each(function() 
	{
		var id=$(this).attr("id");
		CategoryArray.forEach(function(ele) 
		{
			if(ele.id==id)
				$("#"+id).text(ele.name);
		});
	});	
	$("ul").each(function() 
	{
		if($(this).html().length==0)
			$(this).remove();
			
	});	
}
function setMenu()
{
	$("#mainmenu").html("");
	TableArray.forEach(function(ele) 
	{
		if(ele.parent=="Master")
		{
			var li=$("<li></li>");
			$("#mainmenu").append(li); 
			var ahref=$("<a></a>");
			ahref.attr({"id":ele.sub,"name":"ctgBtn"});
			li.append(ahref);
			setSubMenu(li,ele.sub);
		}
	});
	setName();
}

function setSubMenu(element,subId)
{

	var ul=$("<ul></ul>");
	
	TableArray.forEach(function(ele) 
	{
		if(ele.parent==subId)
		{
			var li=$("<li></li>");
			ul.append(li); 
			var ahref=$("<a></a>");
			ahref.attr({"id":ele.sub,"name":"ctgBtn"});
			li.append(ahref);
			setSubMenu(li,ele.sub);
		}
	});
	element.append(ul);
}

///////////
function setUpSlc()
{
	hv++;
	var select = $("<select></select>");
	select.attr({"id":"slc"+hv,"name":"slcC","no":hv}); 
	var opt = $("<option></option"); 
	$("#divSlcCategoryDesign").append(select);
	var div=$("<div></div>");
	div.attr({"id":"div"+hv,"name":"div","no":hv}); 
	div.css("float","left");
	
	var opt = $("<option></option"); 
		opt.val(0);
		opt.attr("id",0); 
		opt.html("Select...");
		select.append(opt);
		select.val(opt.val());
				
	for(var c=0;c<CategoryArray.length;c++)
	{
		var opt = $("<option></option"); 
		opt.val(CategoryArray[c].id);
		opt.html(CategoryArray[c].name);
		select.append(opt);
	}
	showCurrentSlcSelect();
}

function removeSlcOption()
{
	$("option").css("background","");
	$("[name='slcC'] option:selected").each(function() 
	{
		var val=$(this).val();
		if($(this).attr("id")!=0)
			$("[name='slcC']:last option[value='"+val+"']").css("background","#ff7c7c");
	});
}

function showCurrentSlcSelect()
{
	var categoryVal="",htmlcode="";
	$("[name='slcC'] option:selected").each(function() 
	{
		var val=$(this).val();
		
		CategoryArray.forEach(function(ele) 
		{
			if(ele.id==val)
			htmlcode+=ele.name+">";
		});
		
		if($(this).val()!=0)
			categoryVal+=val+"*.*.*.*";
	});
	$("#categoryDesign").text(htmlcode);
	return categoryVal;
}

function AjaxSubmitCategoryDesign(Category)
{
    $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"adminFunction.php", 
        data:{post:"InsertCategoryDesign",ctg:Category},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
            alert("Reg Success "+data);
        },
        error: function () 
        {
            alert("Error!");
        },
    });
}

function AjaxLoadCategory()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadCategory"},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {
            alert("Error! Load Category");
        },
    });
	return rdata;
}
function AjaxLoadCategoryDesign()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadCategoryDesign"},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {
            alert("Error! Load Category Design");
        },
    });
	return rdata;
}
function AjaxSubmitUpdateCategoryMenu(code)
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"UpdateCategoryMenu",code:code},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
			if(data==1)
				alert("Success");
			else
				alert("Fail");
        },
        error: function () 
        {
            alert("Error! Load Category Design");
        },
    });
	return rdata;
}




function setReport()
{
	$("#DivReport").html("");
	var table=$("<table></table>");
		table.attr("border","1");
		$("#DivReport").append(table);
		
	var tr0=$("<tr></tr>");
		table.append(tr0);
		
	var td01=$("<td></td>");
		td01.text("Year");
		tr0.append(td01);
				
	var td02=$("<td></td>");
		td02.text("Total Amount");
		tr0.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Total Shipping Fee");
		tr0.append(td03);
		
	var td04=$("<td></td>");
		td04.text("Total Quantity");
		tr0.append(td04);
		
	ReportArray.forEach(function(ele) 
	{
		var tr1=$("<tr></tr>");
			table.append(tr1);
			
		var td11=$("<td></td>");
			tr1.append(td11);
		
		var ahref11=$("<a></a>");
			ahref11.attr({"name":"ahrefYear","year":ele.year});
			ahref11.css({"cursor":"pointer","color":"blue"});
			ahref11.text(ele.year);
			td11.append(ahref11);
		
		var td12=$("<td></td>");
			td12.text("Sgd "+ele.amount);
			tr1.append(td12);
		
		var td13=$("<td></td>");
			td13.text("Sgd "+ele.shippingfee);
			tr1.append(td13);
			
		var td14=$("<td></td>");
			td14.text(+ele.quantity);
			tr1.append(td14);
		
	});
}
function setReportYear()
{

	$("#DivReportYear").html("");
	var table=$("<table></table>");
		table.attr("border","1");
		$("#DivReportYear").append(table);
		
	var tr0=$("<tr></tr>");
		table.append(tr0);
		
	var td01=$("<td></td>");
		td01.text("Month");
		tr0.append(td01);
				
	var td02=$("<td></td>");
		td02.text("Total Amount");
		tr0.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Total Shipping Fee");
		tr0.append(td03);
		
	var td04=$("<td></td>");
		td04.text("Total Quantity");
		tr0.append(td04);
		
	ReportYearArray.forEach(function(ele) 
	{
		var tr1=$("<tr></tr>");
			table.append(tr1);
			
		var td11=$("<td></td>");
			tr1.append(td11);
		
		var ahref11=$("<a></a>");
			ahref11.attr({"name":"ahrefMonth","month":ele.year+"-"+ele.Month});
			ahref11.css({"cursor":"pointer","color":"blue"});
			ahref11.text(ele.year+" "+ele.month);
			td11.append(ahref11);
		
		var td12=$("<td></td>");
			td12.text("Sgd "+ele.amount);
			tr1.append(td12);
		
		var td13=$("<td></td>");
			td13.text("Sgd "+ele.shippingfee);
			tr1.append(td13);
			
		var td14=$("<td></td>");
			td14.text(+ele.quantity);
			tr1.append(td14);
	});
}
function setReportMonth()
{

	$("#DivReportMonth").html("");
	var table=$("<table></table>");
		table.attr("border","1");
		$("#DivReportMonth").append(table);
		
	var tr0=$("<tr></tr>");
		table.append(tr0);
		
	var td01=$("<td></td>");
		td01.text("Day");
		tr0.append(td01);
				
	var td02=$("<td></td>");
		td02.text("Total Amount");
		tr0.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Total Shipping Fee");
		tr0.append(td03);
		
	var td04=$("<td></td>");
		td04.text("Total Quantity");
		tr0.append(td04);
		
	ReportMonthArray.forEach(function(ele) 
	{
		var tr1=$("<tr></tr>");
			table.append(tr1);
			
		var td11=$("<td></td>");
			tr1.append(td11);
		
		var ahref11=$("<a></a>");
			ahref11.attr({"name":"ahrefDay","day":ele.year+"-"+ele.Month+"-"+ele.day});
			ahref11.css({"cursor":"pointer","color":"blue"});
			ahref11.text(ele.year+"-"+ele.month+"-"+ele.day);
			td11.append(ahref11);
		
		var td12=$("<td></td>");
			td12.text("Sgd "+ele.amount);
			tr1.append(td12);
		
		var td13=$("<td></td>");
			td13.text("Sgd "+ele.shippingfee);
			tr1.append(td13);
			
		var td14=$("<td></td>");
			td14.text(+ele.quantity);
			tr1.append(td14);
	});
}
function setReportDay()
{

	$("#DivReportDay").html("");
	var table=$("<table></table>");
		table.attr("border","1");
		$("#DivReportDay").append(table);
		
	var tr0=$("<tr></tr>");
		table.append(tr0);
		
	var td01=$("<td></td>");
		td01.text("Time");
		tr0.append(td01);
				
	var td02=$("<td></td>");
		td02.text("Total Amount");
		tr0.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Total Shipping Fee");
		tr0.append(td03);
		
	var td04=$("<td></td>");
		td04.text("Total Quantity");
		tr0.append(td04);
		
	ReportDayArray.forEach(function(ele) 
	{
		var tr1=$("<tr></tr>");
			table.append(tr1);
			
		var td11=$("<td></td>");
			tr1.append(td11);
		
		var ahref11=$("<a></a>");
			ahref11.attr({"name":"ahrefDay","day":ele.year+"-"+ele.Month+"-"+ele.day+" "+ele.hour});
			ahref11.css({"cursor":"pointer","color":"blue"});
			ahref11.text(ele.year+"-"+ele.month+"-"+ele.day+" "+ele.hour+"00 ~"+ele.hour+"59");
			td11.append(ahref11);
		
		var td12=$("<td></td>");
			td12.text("Sgd "+ele.amount);
			tr1.append(td12);
		
		var td13=$("<td></td>");
			td13.text("Sgd "+ele.shippingfee);
			tr1.append(td13);
			
		var td14=$("<td></td>");
			td14.text(+ele.quantity);
			tr1.append(td14);
	});
}
function setReportSDT()
{

	$("#DivReportPickDateTimeList").html("");
	var table=$("<table></table>");
		table.attr("border","1");
		$("#DivReportPickDateTimeList").append(table);
		
	var tr0=$("<tr></tr>");
		table.append(tr0);
		
	var td01=$("<td></td>");
		td01.text("Time");
		tr0.append(td01);
				
	var td02=$("<td></td>");
		td02.text("Total Amount");
		tr0.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Total Shipping Fee");
		tr0.append(td03);
		
	var td04=$("<td></td>");
		td04.text("Total Quantity");
		tr0.append(td04);
		
	ReportSDTArray.forEach(function(ele) 
	{
		var tr1=$("<tr></tr>");
			table.append(tr1);
			
		var td11=$("<td></td>");
			tr1.append(td11);
		
		var ahref11=$("<a></a>");
			ahref11.attr({"name":"ahrefDay","day":ele.year+"-"+ele.Month+"-"+ele.day+" "+ele.hour});
			ahref11.css({"cursor":"pointer","color":"blue"});
			ahref11.text(ele.year+"-"+ele.month+"-"+ele.day+" "+ele.hour+"00 ~"+ele.hour+"59");
			td11.append(ahref11);
		
		var td12=$("<td></td>");
			td12.text("Sgd "+ele.amount);
			tr1.append(td12);
		
		var td13=$("<td></td>");
			td13.text("Sgd "+ele.shippingfee);
			tr1.append(td13);
			
		var td14=$("<td></td>");
			td14.text(+ele.quantity);
			tr1.append(td14);
	});
}
function AjaxLoadReport()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadReport",company:$("#slcCompanyNameList option:selected").val()},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {

        },
    });
	return rdata;
}
function AjaxLoadReportYear(y)
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadReportYear",year:y,company:$("#slcCompanyNameList option:selected").val()},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {

        },
    });
	return rdata;
}
function AjaxLoadReportMonth(m)
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadReportMonth",month:m,company:$("#slcCompanyNameList option:selected").val()},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {

        },
    });
	return rdata;
}
function AjaxLoadReportDay(d)
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadReportDay",day:d,company:$("#slcCompanyNameList option:selected").val()},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {

        },
    });
	return rdata;
}

function AjaxLoadReportSDT()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"AdminFunction.php", 
        data:{	post:"LoadReportSDT",
		sy:$("#StartYear").val(),
		sm:$("#StartMonth").val(),
		sd:$("#StartDay").val(),
		sh:$("#StartHour").val(),
		ey:$("#EndYear").val(),
		em:$("#EndMonth").val(),
		ed:$("#EndDay").val(),
		eh:$("#EndHour").val(),
		company:$("#slcCompanyNameList option:selected").val()
		},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			rdata=data;
        },
        error: function () 
        {

        },
    });
	return rdata;
}
</script>
<body>
<div id="amainmenu">
	<ul id="xmainmenu">
    	<li><a id="aAdminMSName">Edit System Name</a></li>
    	<li><a id="aAdminCategoryDesign">Edit Category Design</a></li>
        <li><a id="aAdminCategoryMenu">Edit Category Menu</a></li>
        <li><a id="aAdminReportYear">Report</a></li>
        <li><a id="aAdminReportPickDateTime">Report Of Specific Range of DateTime By Hourly</a></li>
    </ul>
</div>
<div align="center">
	<div id="DivCategoryMenu" style="display:none;">
    	<div id="divCode" style="padding-top:20px;">
            <ul id="mainmenu">
            </ul>
        </div>
        
        <textarea id="txtCode" name="" cols="" rows="20" style="width:80%;margin-top: 60px;"></textarea>
    	<br>
        <input id="btnCategoryMenuSubmit" type="button" value="Update"/>
    
    </div>
    <div id="DivCategoryDesign" style="display:none;">
        <table>
            <tr>
                <td align="left" colspan="99"><p id="categoryDesign"></p></td>
            </tr>    
            <tr>
                <td colspan="99">
                    <div id="divSlcCategoryDesign"></div>
                    <div style="clear:both;"></div>
                </td>
            </tr>    
            <tr>
                <td colspan="99">
                    <input id="btnAddSlcCategoryDesign" name="" type="button" value="Add Select"/>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <div style="display: inline-flex;">
                        <input id="btnCategoryDesignSubmit" type="button" value="Register"/>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div id="DivCompanyNameList" style="display:none;">
    	Choose Company <select id="slcCompanyNameList"></select>
    </div>
    <div id="DivReport" style="display:none;"></div>
    <div id="DivReportYear" style="display:none;"></div>
    <div id="DivReportMonth" style="display:none;"></div>
    <div id="DivReportDay" style="display:none;"></div>
    <div id="DivReportPickDateTime" style="display:none;">

        <select id="StartYear"></select>
        <select id="StartMonth"></select>
        <select id="StartDay"></select>
        <select id="StartHour"></select>
		~        
        <select id="EndYear"></select>
        <select id="EndMonth"></select>
        <select id="EndDay"></select>
        <select id="EndHour"></select>

        <input id="btnDivReportPickDateTime" type="button" value="Get Report"/>
        <div id="DivReportPickDateTimeList"></div>
    </div>
    <div id="DivMSName" style="display:none;">
    	<table>
        	<tr>
            	<td colspan="99" align="center">Update Company Name</td>
            </tr>
            <tr>
            	<td>Company Name</td>
                <td><input id="txtMSName" type="text" /></td>
            </tr>
            <tr>
            	<td align="center" colspan="99"><input id="btnMSName" type="button" value="Update"/></td>
            </tr>
        </table>
    </div>
    
</div>
</body>
</html>