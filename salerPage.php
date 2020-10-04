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
var PromotionArray=new Array();

var ReportArray=new Array();
var ReportYearArray=new Array();
var ReportMonthArray=new Array();
var ReportDayArray=new Array();
var ReportSDTArray=new Array();

var numCategorySlc=0;

$(function()
{
	var rtn=AjaxSubmitCheckLogin();
	if(rtn==2)
		window.location.replace("~index.php");
	else if(rtn==1)
	{
		var rtnC=AjaxSubmitCheckCompany();
		if(rtnC==2)
		{
			$("#mainmenu").css("display","none");
			$("#DivRegCompany").css("display","block");
		}
	}
});
$(document).ready(function()
{
	$("[name='txt']").keyup(function()
	{
		CheckTxt($(this));
	});
	
	$("#aSalerRegPromotion").click(function()
	{
		closeAll();
		$("#DivRegPromotion").css("display","block");
		checkDiscountType();
		$("#txtRegPromotionStartDate").val(getTodayDate());
		$("#txtRegPromotionEndDate").val(getNextMonthDate());
	});
	$("#aSalerRegCategory").click(function()
	{
		closeAll();
		$("#DivRegCategory").css("display","block");
	});
	
	$("#aSalerCompanyEditDetail").click(function()
	{
		closeAll();
		$("#thTitle").text("Edit Company Detail");
		$("#txtCompanyRegName").prop( "disabled", true );
		$("#btnSalerRegSubmit").css("display","none");
		$("#btnSalerUdpSubmit").css("display","");
		$("#DivRegCompany").css("display","block");

		AjaxLoadCompanyDetail();
	});
	$("#aSalerRegProduct").click(function()
	{
		closeAll();
		$("#DivRegProduct").css("display","block");
		CategoryElementArray=AjaxLoadCategory();
		PromotionArray=AjaxLoadPromotion();
		setPromotionOption();
		setCategoryOption();
	});
	$("#aSalerReportYear").click(function()
	{
		closeAll();
		$("#DivReport").css("display","block");
		ReportArray=AjaxLoadReport();
		setReport();
	});
	$("#aSalerReportPickDateTime").click(function()
	{
		closeAll();
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
		ReportSDTArray=AjaxLoadReportSDT();
		setReportSDT();
	});

	$("#DivReport").on("click", "[name='ahrefYear']", function()
	{
		closeAll();
		$("#DivReportYear").css("display","block");
		ReportYearArray=AjaxLoadReportYear($(this).attr("year"));
		setReportYear();
	});
	$("#DivReportYear").on("click", "[name='ahrefMonth']", function()
	{
		closeAll();
		$("#DivReportMonth").css("display","block");
		ReportMonthArray=AjaxLoadReportMonth($(this).attr("month"));
		setReportMonth();
	});
	$("#DivReportMonth").on("click", "[name='ahrefDay']", function()
	{
		closeAll();
		$("#DivReportDay").css("display","block");
		ReportDayArray=AjaxLoadReportDay($(this).attr("day"));
		setReportDay();
	});
	

	$("#slcRegProductCategoryDiv").on("change", "[name='slcCategory']", function()
	{
		getSlcCategoryId();
	});
	
	$("#btnAddSlc").click(function()
	{
		setCategoryOption();
	});

	$("#slcRegProductPromotion").change(function(e) 
	{   
		$("[name='lbl']").css("display","none");
		PromotionArray.forEach(function(ele) 
		{
			var id =$("#slcRegProductPromotion option:selected").attr("id");
			
			if(ele.id==id)
			{
				if(ele.rate!=0)
					$("#lblrate").val(ele.rate+"%").css("display","block");
				else
					$("#lblprice").val("Sgd"+ele.price).css("display","block");
				$("#lbldate").val(ele.sdate+" - "+ele.edate).css("display","block");
				$("#lbldesc").val(ele.desc).css("display","block");
			}
		});	
	});
	
	$("[name='img_file']").change(function(e) 
	{   
		var no=$(this).attr("no");
		var upfile = $(this)[0]
		if (upfile) 
		{
			var reader = new FileReader();
			reader.onload = function(e) 
			{
				$("#Img[no='"+no+"']").attr('src', e.target.result);
			}
			reader.readAsDataURL(upfile.files[0]);
		}
	});	
	

	$("[name='rdPromotion']").click(function()
	{
		checkDiscountType();
	});
	
	$("#btnRegProductSubmit").click(function()
	{
		AjaxRegProduct();
	});
	
	$("#btnRegPromotionSubmit").click(function()
	{
		AjaxRegisterPromotion();
	});
	
	$("#btnRegCategorySubmit").click(function()
	{
		AjaxRegCategoryEle();
	});
	
	$("#btnSalerUdpSubmit").click(function()
	{
		AjaxSubmitUpdateCompany();
	});
	
	$("#btnSalerRegSubmit").click(function()
	{
		AjaxSubmitRegisterCompany();
	});
	$("#btnSalerClearSubmit").click(function()
	{
		$("[name='txt']").val("");
		$("#slcRegCompanyCountry").val("Malaysia");
	});
	
});

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

function closeAll()
{
	$("#DivRegCompany").css("display","none");
	$("#DivRegCategory").css("display","none");
	$("#DivRegPromotion").css("display","none");
	$("#DivRegProduct").css("display","none");
	$("#DivReport").css("display","none");
	$("#DivReportYear").css("display","none");
	$("#DivReportMonth").css("display","none");
	$("#DivReportDay").css("display","none");
	$("#DivReportPickDateTime").css("display","none");
}

function getSlcCategoryId()
{
	var txt="";
	$("[name='slcCategory']").each(function( index ) 
	{
  		var no=$(this).attr("no");
		var id =$("#slcRegProductCategory"+no+" option:selected").attr("id");
		if(id!=0)
		{
			if(txt.length==0)
				txt=id;
			else
				txt+=","+id;
		}
	});
	return txt;
}

function setCategoryOption()
{
	numCategorySlc++;
	var div=$("#slcRegProductCategoryDiv");
	
	var slc=$("<select></select>");
	slc.attr({id:"slcRegProductCategory"+numCategorySlc,name:"slcCategory",no:numCategorySlc});
	div.append(slc);
	
	var opt1=$("<option></option>");
	opt1.attr({"id":"0","name":"opt"});
	opt1.text("Select...");
	slc.append(opt1);
	
	CategoryElementArray.forEach(function(ele) 
	{
		var opt=$("<option></option>");
		opt.attr({"id":ele.id,"name":"opt"});
		opt.text(ele.name);
		slc.append(opt);
	});
}
function setPromotionOption()
{
	
	var slc=$("#slcRegProductPromotion");
	
	var opt1=$("<option></option>");
	opt1.attr({"id":"0","name":"opt"});
	opt1.text("Select...");
	slc.append(opt1);
	
	PromotionArray.forEach(function(ele) 
	{
		var opt=$("<option></option>");
		opt.attr({"id":ele.id,"name":"opt"});
		opt.text(ele.name);
		slc.append(opt);
	});	
}


function AjaxLoadPromotion()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"SalerFunction.php", 
        data:{	post:"LoadPromotion"},
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
            alert("Error! Load Promotion");
        },
    });
	return rdata;
}
function AjaxLoadCategory()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"SalerFunction.php", 
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
function AjaxLoadCompanyDetail()
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"SalerFunction.php", 
        data:{	post:"LoadCompanyDetail"},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==2)
				alert("Load Company Detail Fail");
            else
			{
				$("#txtCompanyRegName").val(data.name);
				$("#slcRegCompanyCountry").val(data.country);
				$("#txtCompanyRegState").val(data.state);
				$("#txtCompanyRegCity").val(data.city);
				$("#txtCompanyRegPostCode").val(data.postcode);
				$("#txtCompanyRegAddress").val(data.address);
				$("#txtCompanyRegPhoneNumber").val(data.phonenumber);
				$("#txtCompanyRegDesc").val(data.desc);
			}
        },
        error: function () 
        {
            alert("Error! Load Company Detail");
        },
    });
}


function AjaxRegProduct()
{
	var img="";
	var imgArray=new Array();
	imgArray[0]=uploadFile($("#Img_form1"));
	imgArray[1]=uploadFile($("#Img_form2"));
	imgArray[2]=uploadFile($("#Img_form3"));
	imgArray[3]=uploadFile($("#Img_form4"));
	imgArray[4]=uploadFile($("#Img_form5"));
	
	imgArray.forEach(function(ele) 
	{
		if(ele.length>0)
		{
			if(img.length==0)
				img=ele;
			else
				img+=","+ele;		
		}
	});	
	
	var ctg=getSlcCategoryId();
	var promotion=$("#slcRegProductPromotion option:selected").attr("id");
	var name=$("#txtRegProductName").val();
	var desc=$("#txtRegProductDesc").val();
	var weight=$("#txtRegProductWeight").val();
	var quantity=$("#txtRegProductQuantity").val();
	var quantityInt=parseInt($("#txtRegProductQuantity").val());
	var price=$("#txtRegProductPrice").val();
	
	if(ctg.length>0 && name.length>0 && desc.length>0 && $.isNumeric(price)==true && $.isNumeric(weight)==true && Number.isInteger(quantityInt)==true&& $.isNumeric(quantity)==true)
	{
		$.ajax(
		{
			type:"POST",
			dataType:"json",
			url:"SalerFunction.php", 
			data:{	post:"RegisterProduct",ctg:ctg,promotion:promotion,name:name,desc:desc,weight:weight,quantity:quantityInt,price:price,img:img},
			async:false,
			beforeSend: function() 
			{},
			complete:function()
			{},
			success: function (data) 
			{
				if(data==1)
					alert("Register Success");
				else
				{
					alert("Register Fail");
				}
			},
			error: function () 
			{
				alert("Error! Register");
			},
		});	
	}
	else
		alert("error");
	
	
}
function AjaxRegisterPromotion()
{
	var txtVal=$("#txtRegPromotionDigit").val();
	var tf=1;
	if($.isNumeric(txtVal)==true)
	{
		var rd=$("[name='rdPromotion']:checked").val();

		if(rd=="rate")
		{
			txtVal=parseFloat(txtVal);

			if(txtVal>=100)
			{
				alert("Cant Discount bigest then 100% ");
				tf=0;
			}	
		}	
	}
	else 
		alert("error");
		
	if(tf==1)
	{
		$.ajax(
		{
			type:"POST",
			dataType:"json",
			url:"SalerFunction.php", 
			data:{	post:"RegisterPromotion",
					name:$("#txtRegPromotionName").val(),
					type:rd,
					value:$("#txtRegPromotionDigit").val(),
					desc:$("#txtRegPromotionDesc").val(),
					sdate:$("#txtRegPromotionStartDate").val(),
					edate:$("#txtRegPromotionEndDate").val()
				},//
			async:false,
			beforeSend: function() 
			{},
			complete:function()
			{},
			success: function (data) 
			{
				if(data==1)
				{
					alert("register success");
				}
				else if(data==2)
					alert("register Fail");
			},
			error: function () 
			{
				alert("Error! register");
			},
		});
	}
}
function AjaxRegCategoryEle()
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"SalerFunction.php", 
        data:{	post:"RegisterCategoryElement",name:$("#txtRegCategoryElementName").val(),desc:$("#txtRegCategoryElementDesc").val()},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==1)
				alert("Register Success");
            else
			{
				alert("Register Fail");
			}
        },
        error: function () 
        {
            alert("Error! Register");
        },
    });
}
function AjaxSubmitUpdateCompany()
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"SalerFunction.php", 
        data:{	post:"UpdateCompany",
				country:$("#slcRegCompanyCountry option:selected").val(),
				state:$("#txtCompanyRegState").val(),
				city:$("#txtCompanyRegCity").val(),
				postcode:$("#txtCompanyRegPostCode").val(),
				address:$("#txtCompanyRegAddress").val(),
				phonenumber:$("#txtCompanyRegPhoneNumber").val(),
				desc:$("#txtCompanyRegDesc").val()
		},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
            if(data==1)
			{
				$("#tableRegCompany [name='txt']").val("");
				$("#slcRegCompanyCountry").val("Malaysia");
				$("#mainmenu").css("display","block");
				$("#DivRegCompany").css("display","none");
				
				alert("update success");
			}
			else if(data==2)
			alert("update Fail");
        },
        error: function () 
        {
            alert("Error! update");
        },
    });
	
}
function AjaxSubmitRegisterCompany()
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"SalerFunction.php", 
        data:{	post:"RegisterCompany",
				name:$("#txtCompanyRegName").val(),
				country:$("#slcRegCompanyCountry option:selected").val(),
				state:$("#txtCompanyRegState").val(),
				city:$("#txtCompanyRegCity").val(),
				postcode:$("#txtCompanyRegPostCode").val(),
				address:$("#txtCompanyRegAddress").val(),
				phonenumber:$("#txtCompanyRegPhoneNumber").val(),
				desc:$("#txtCompanyRegDesc").val()
		},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
            if(data==1)
			{
				$("#tableRegCompany [name='txt']").val("");
				$("#slcRegCompanyCountry").val("Malaysia");
				$("#mainmenu").css("display","block");
				$("#DivRegCompany").css("display","none");
				
				alert("register success");
			}
			else if(data==2)
			alert("register Fail");
        },
        error: function () 
        {
            alert("Error! register");
        },
    });
	
}
function checkDiscountType()
{
	var rd=$("[name='rdPromotion']:checked").val();
	$("[name='discountFont']").remove();
	var font=$("<font></font>");
	font.attr("name","discountFont");
	if(rd=="amount")
	{
		font.text("Sgd");
		$("#txtRegPromotionDigit").before(font);
	}
	else if(rd=="rate")
	{
		font.text("%");
		$("#txtRegPromotionDigit").after(font);
	}	
}
function uploadFile(ele)
{
    var rtn=0;
    $.ajax(
    {
        type: 'POST',
        cache: false,
        data: new FormData(ele[0]),
        processData: false,
        contentType: false,
        url: "uploadFunction.php", 
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function(data)
        {
            if(data==0)
            {
            }
            else if(data==2)
            {
                alert("fail upload");
            }
            data=data.replace(/\\\/|\/\\/g, "/");
            rtn= data.replace(/"/g, "");
        },
        error: function () 
        {
			rtn=2;
            alert("Error! Upload");
        },
    });

    return rtn;
}

function AjaxLoadReport()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"SalerFunction.php", 
        data:{	post:"LoadReport"},
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
        url:"SalerFunction.php", 
        data:{	post:"LoadReportYear",year:y},
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
        url:"SalerFunction.php", 
        data:{	post:"LoadReportMonth",month:m},
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
        url:"SalerFunction.php", 
        data:{	post:"LoadReportDay",day:d},
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
        url:"SalerFunction.php", 
        data:{	post:"LoadReportSDT",
		sy:$("#StartYear").val(),
		sm:$("#StartMonth").val(),
		sd:$("#StartDay").val(),
		sh:$("#StartHour").val(),
		ey:$("#EndYear").val(),
		em:$("#EndMonth").val(),
		ed:$("#EndDay").val(),
		eh:$("#EndHour").val()
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
<div id="mainmenu">
	<ul id="mainmenu">
    	<li><a id="aSalerCompanyEditDetail">Edit Company Detail</a></li>
        <li><a id="aSalerRegCategory">Register Category</a></li>
        <li><a id="aSalerRegPromotion">Register Promotion</a></li>
        <li><a id="aSalerRegProduct">Register Product</a></li>
        <li><a id="aSalerReportYear">Report</a></li>
        <li><a id="aSalerReportPickDateTime">Report Of Specific Range of DateTime By Hourly</a></li>
        
    </ul>
</div>
<div align="center">
	<div id="DivRegProduct" style="display:none;">
    <table>
    	<tr>
        	<td style="vertical-align: top;">Category</td>
            <td>
            <div id="slcRegProductCategoryDiv"></div>
            <input id="btnAddSlc" type="button" value="Add"/></td>
        </tr>
        <tr>
        	<td style="vertical-align: top;">Promotion</td>
            <td>
            	<select id="slcRegProductPromotion" name=""></select>
            	<input id="lblrate" name="lbl" type="text" disabled="disabled" style="display:none;">
                <input id="lblprice" name="lbl" type="text" disabled="disabled" style="display:none;">
                <input id="lbldate" name="lbl" type="text" disabled="disabled" style="display:none;">
            	<textarea id="lbldesc" name="lbl" cols="" rows="" disabled="disabled" style="display:none;"></textarea>
            </td>
        </tr>
    	<tr>
        	<td>Name</td>
            <td><input id="txtRegProductName" name="txt" min="5" errorms="min 5 character" type="text"></td>
        </tr>
        <tr>
        	<td>Desc</td>
            <td><textarea id="txtRegProductDesc" name="txt" min="15" errorms="min 15 character" cols="" rows=""></textarea></td>
        </tr>
        <tr>
        	<td id="tdimg" colspan="99" align="center">
            
            <img id="Img" no="1" src="" style="width:300px;">
			<form id="Img_form1" type="post" enctype="multipart/form-data">
				<input type="file" id="file" name="img_file" no="1">
			</form>
            <br>
            <img id="Img" no="2" src="" style="width:300px;">
			<form id="Img_form2" type="post" enctype="multipart/form-data">
				<input type="file" id="file" name="img_file" no="2">
			</form>
            <br>
            <img id="Img" no="3" src="" style="width:300px;">
			<form id="Img_form3" type="post" enctype="multipart/form-data">
				<input type="file" id="file" name="img_file" no="3">
			</form>
            <br>
            <img id="Img" no="4" src="" style="width:300px;">
			<form id="Img_form4" type="post" enctype="multipart/form-data">
				<input type="file" id="file" name="img_file" no="4">
			</form>
            <br>
            <img id="Img" no="5" src="" style="width:300px;">
			<form id="Img_form5" type="post" enctype="multipart/form-data">
				<input type="file" id="file" name="img_file" no="5">
			</form>
            
            </td>
        </tr>
        <tr>
        	<td>Weight</td>
            <td><input id="txtRegProductWeight" name="txt" min="1" errorms="min 1 character" type="text"></td>
        </tr>
        <tr>
        	<td>Quantity</td>
            <td><input id="txtRegProductQuantity" name="txt" min="1" errorms="min 1 character" type="text"></td>
        </tr>
        <tr>
        	<td>Price</td>
            <td><input id="txtRegProductPrice" name="txt" min="1" errorms="min 1 character" type="text"></td>
        </tr>
        <tr>
        	<td colspan="99" align="center">
            	<input id="btnRegProductSubmit" name="btn" type="button" value="Register Product" style="padding: 5px;margin-bottom: 70px;">
            </td>
        </tr>
    </table>
    </div>
	<div id="DivRegPromotion" style="display:none;">
    <table>
    	<tr>
        	<td>Name</td>
            <td><input id="txtRegPromotionName" name="txt" min="5" errorms="min 5 character" type="text"></td>
        </tr>
        <tr>
        	<td>Start Date</td>
            <td><input id="txtRegPromotionStartDate" name="txt" min="10" errorms="exp:02/28/1990" type="date"></td>
        </tr>
        <tr>
        	<td>End Date</td>
            <td><input id="txtRegPromotionEndDate" name="txt" min="10" errorms="exp:02/28/1990" type="date"></td>
        </tr>
        <tr>
        	<td colspan="99" align="center">
            <label>%<input id="rdrate" name="rdPromotion" type="radio" value="rate" checked="checked"></label>
            <label>Rm<input id="rdamount" name="rdPromotion" type="radio" value="amount"></label>
            </td>
        </tr>
        <tr>
        	<td>Discount</td>
            <td><input id="txtRegPromotionDigit" name="txt" min="1" errorms="min 1 character" type="text"></td>
        </tr>
        <tr>
        	<td>Desc</td>
            <td><textarea id="txtRegPromotionDesc" name="txt" min="15" errorms="min 15 character" cols="" rows=""></textarea></td>
        </tr>
        <tr>
        	<td colspan="99">
            	<input id="btnRegPromotionSubmit" name="btn" type="button" value="Register Promotion" style="padding: 5px;">
            </td>
        </tr>
       
    </table>
    </div>
	<div id="DivRegCategory" style="display:none;">
    	<table>
        	<tr>
            	<td>Name of new category : </td>
                <td><input id="txtRegCategoryElementName" name="txt" min="5" errorms="min 5 character" type="text"></td>
            </tr>
            <tr>
            	<td>Description : </td>
                <td>
                <textarea id="txtRegCategoryElementDesc" name="txt" min="15" errorms="min 15 character" cols="" rows=""></textarea></td>
            </tr>
            <tr>
            	<td colspan="99" align="center">
                	<input id="btnRegCategorySubmit" name="btn" type="button" value="Register" style="padding: 5px;">
                    <input id="btnRegCategoryClear" name="btn" type="button" value="Clear" style="padding: 5px;">
                </td>
            </tr>
        </table>
    </div>
    <div id="DivRegCompany" style="display:none;">
        <table id="tableRegCompany">
            <tr>
                <th id="thTitle" align="center" colspan="99">Register Company</th>
            </tr>
            <tr>
                <td>Company Name : </td>
                <td><input id="txtCompanyRegName" name="txt" min="5" errorms="min 5 character" type="text"></td>
            </tr>
            <tr>
                <td>Country : </td>
                <td>
                    
                    <select id="slcRegCompanyCountry" name="slc">
                        <script language="JavaScript" src="countries.js"></script>
                    </select>
                </td>
            </tr>
            <tr>
                <td>State : </td>
                <td><input id="txtCompanyRegState" name="txt" min="3" errorms="min 3 character" type="text"></td>
            </tr>
            <tr>
                <td>City : </td>
                <td><input id="txtCompanyRegCity" name="txt" min="3" errorms="min 3 character" type="text"></td>
            </tr>
            <tr>
                <td>PostCode : </td>
                <td><input id="txtCompanyRegPostCode" name="txt" min="3" errorms="min 3 character" type="text"></td>
            </tr>
            <tr>
                <td>Address : </td>
                <td>
                    <textarea id="txtCompanyRegAddress" name="txt" min="8" errorms="min 8 character"cols="" rows=""></textarea>
                </td>
            </tr>
            <tr>
                <td>PhoneNumber : </td>
                <td><input id="txtCompanyRegPhoneNumber" name="txt" min="8" errorms="min 8 character" type="text"></td>
            </tr>
            <tr>
                <td>Description : </td>
                <td><textarea id="txtCompanyRegDesc" name="txt" min="15" errorms="min 15 character" cols="" rows=""></textarea></td>
            </tr>
            <tr>
            	<td colspan="99" align="center">
                    <input id="btnSalerRegSubmit" name="btn" type="button" value="Register" style="padding: 5px;">
                    <input id="btnSalerUdpSubmit" name="btn" type="button" value="Update" style="padding: 5px; display:none;">
                    <input id="btnSalerClearSubmit" name="btn" type="button" value="Clear" style="padding: 5px;">
                </td>
            </tr>
        </table>
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
    
</div>
</body>
</html>