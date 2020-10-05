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

var WishlistArray=new Array();
var CartArray=new Array();
var finalList=new Array();
var trtotalamount=0;
$(function()
{
	
});
$(document).ready(function()
{
	$("#aPayWishlist").click(function()
	{
		closeAll();
		WishlistArray=AjaxLoadWishlist();
		setWishlist();
		$("#divWishlist").css("display","block");
	});
	$("#aPayCart").click(function()
	{
		closeAll();
		CartArray=AjaxLoadCart();
		setCart();
		$("#divCart").css("display","block");
	});
	$("#divWishlist").on("click", "[name='btnDelWishlist']", function()
  	{
		var tf=AjaxDelWishlist($(this).attr("id"));
		if(tf==1)
			$("[name='trWishlist'][id='"+$(this).attr("id")+"']").remove();
	});
	
	$("#divCart").on("click", "[name='btnDelCart']", function()
  	{
		var tf=AjaxDelCart($(this).attr("id"));
		if(tf==1)
			$("[name='trCart'][id='"+$(this).attr("id")+"']").remove();
	});
	$("#divCart").on("click", "[name='btnCartPay']", function()
  	{
		closeAll();
		finalList=AjaxPayV1();
		setBuyList();
		$("#divShowBuyList").css("display","block");
		
	});
	
	$("#divShowBuyList").on("click", "[name='btnConfirmBuy']", function()
  	{
		closeAll();
		$("#trtotalamount").text("Rm"+trtotalamount);
		$("#divShowPay").css("display","block");
	});
	$("#divShowPay").on("click", "#btnPay", function()
  	{
		closeAll();
		AjaxPayV2();
	});
	
	$("#aPayInvoice").click(function()
	{
		closeAll();
		$("#DivInvoice").css("display","block");
		PaymentListArray=AjaxLoadPaymentList();
		setPaymentList();
	});
	$("#slcInvoiceList").change(function() 
	{
		PaymentDetailArray=AjaxLoadPaymentDetail($(this).val());
		setPaymentDetail($(this).val());
	});
	
	
});
function AjaxLoadPaymentList()
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{	post:"LoadPaymentList"
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
function AjaxLoadPaymentDetail(id)
{
	var rdata;
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{	post:"LoadPaymentDetail",paymentid:id
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
function setPaymentList()
{
	var slc=$("#slcInvoiceList");
	slc.html("");
	var opt = $("<option></option"); 
			opt.val("--"); 
			opt.text("Select Invoice");
			slc.append(opt);
	PaymentListArray.forEach(function(ele) 
	{
		if(ele.companyname!="Admin")
		{
			var opt = $("<option></option"); 
			opt.val(ele.paymentid); 
			opt.text(ele.paymentid);
			slc.append(opt);
		}
	});
}
function setPaymentDetail(id)
{
	var txt="";
	var div=$("#DivInvoiceTable");
		div.html("");
	var table=$("<table></table>");
		table.attr("border","1");
		div.append(table);
	
	var tr=$("<tr></tr>");
		table.append(tr);
		
	var td=$("<td></td>");
		td.text("Invoice Number : "+id);
		td.attr("colspan","99");
		tr.append(td);
	
	
	var tr0=$("<tr></tr>");
		table.append(tr0);
	
	var td00=$("<td></td>");
		td00.text("No");
		tr0.append(td00);
	
	var td01=$("<td></td>");
		td01.text("Receipt Number");
		tr0.append(td01);
	
	var td02=$("<td></td>");
		td02.text("Company Name");
		tr0.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Product Name");
		tr0.append(td03);
		
	var td04=$("<td></td>");
		td04.text("Promotion Name");
		tr0.append(td04);
		
	var td05=$("<td></td>");
		td05.text("Quantity");
		tr0.append(td05);	
	
	var td06=$("<td></td>");
		td06.text("Price(Final)");
		tr0.append(td06);
	
	var td07=$("<td></td>");
		td07.text("Shipping Location");
		tr0.append(td07);
	
	var td08=$("<td></td>");
		td08.text("Shipping Fee");
		tr0.append(td08);
	
	var td09=$("<td></td>");
		td09.text("Total");
		tr0.append(td09);
	
	
	var totalPrice=0;
	var totalShippingFee=0;
	var totalQuantity=0;
	var totalAmountOfRaw=0;
	var c=0;
	
	PaymentDetailArray.forEach(function(ele) 
	{
		c++;
		
		var tr1=$("<tr></tr>");
			table.append(tr1);
	
		var td10=$("<td></td>");
			td10.text(c);
			tr1.append(td10);
		
		var td11=$("<td></td>");
			td11.text(ele.receiptid);
			tr1.append(td11);
		
		var td12=$("<td></td>");
			td12.text(ele.companyname);
			tr1.append(td12);
		
		var td13=$("<td></td>");
			td13.text(ele.productname);
			tr1.append(td13);
			
		var td14=$("<td></td>");
			td14.text(ele.promotionname);
			tr1.append(td14);
			
		var td15=$("<td></td>");
			td15.text(ele.quantity);
			tr1.append(td15);	
		
		var td16=$("<td></td>");
			td16.text("Rm "+ele.price);
			tr1.append(td16);
		
		var td17=$("<td></td>");
			td17.text(ele.shippinginfo);
			tr1.append(td17);
		
		var td18=$("<td></td>");
			td18.text("Rm "+ele.shipppingfee);
			tr1.append(td18);
		
		
		
		totalAmountOfRaw=parseFloat(ele.shipppingfee)+(parseFloat(ele.price) * parseInt(ele.quantity));
		
		var td19=$("<td></td>");
			td19.text("Rm "+totalAmountOfRaw);
			tr1.append(td19);
			
		totalPrice+=parseFloat(ele.shipppingfee)+(parseFloat(ele.price) * parseInt(ele.quantity));
		totalShippingFee+=parseFloat(ele.shipppingfee);
		totalQuantity+=parseInt(ele.quantity);
		
	});
	var tr2=$("<tr></tr>");
		table.append(tr2);
	
	var td20=$("<td></td>");
		td20.text("Total : ");
		td20.attr({"colspan":"5","align":"right"});
		tr2.append(td20);
	
	var td21=$("<td></td>");
		td21.text(totalQuantity);
		tr2.append(td21);
	
	var td22=$("<td></td>");
		td22.text("");
		tr2.append(td22);
	
	var td23=$("<td></td>");
		td23.text("");
		tr2.append(td23);
		
	var td24=$("<td></td>");
		td24.text("Rm "+totalShippingFee);
		tr2.append(td24);
		
	var td25=$("<td></td>");
		td25.text("Rm "+totalPrice);
		tr2.append(td25);	
}

function setBuyList()
{
	var totalAmount=totalprice=totalshippingfee=0;
	
	var div=$("#divShowBuyList");	
		div.html("");
	var table=$("<table></table>");
		table.attr("border","1");
		div.append(table);	
		
	var tr1=$("<tr></tr>");	
	table.append(tr1);
	var td01=$("<td></td>");
		td01.text("Name");
	tr1.append(td01);
	
	var td02=$("<td></td>");
		td02.text("Quantity");
	tr1.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Total Price");
	tr1.append(td03);
	
	var td04=$("<td></td>");
		td04.text("Total Shipping Fee");
	tr1.append(td04);
	
	finalList.forEach(function(ele) 
  	{
		var tr=$("<tr></tr>");
		table.append(tr);
		
		var td1=$("<td></td>");
		tr.append(td1);
		var font1=$("<font></font>");
			font1.text(ele.productname);	
		td1.append(font1);
		
		var td2=$("<td></td>");
		tr.append(td2);
		var font2=$("<font></font>");
			font2.text(ele.quantity);	
		td2.append(font2);
		
		var td3=$("<td></td>");
		tr.append(td3);
		var font3=$("<font></font>");
			font3.text("Rm"+ele.totalamount);	
		td3.append(font3);
		
		var td4=$("<td></td>");
		tr.append(td4);
		var font4=$("<font></font>");
			font4.text("Rm"+ele.shippingfee);	
		td4.append(font4);
	

		totalprice+=ele.totalamount;
		totalshippingfee+=ele.shippingfee;
	});
	
	totalAmount= totalprice + totalshippingfee;
	trtotalamount=totalAmount;

	var tr2=$("<tr></tr>");
	table.append(tr2);
	
	
	var tdTotal=$("<td></td>");
	tdTotal.attr({"colspan":"2","align":"right"});
	tr2.append(tdTotal);
	var fontTotal=$("<font></font>");
		fontTotal.text("Total : ");	
	tdTotal.append(fontTotal);
		
	var tdTP=$("<td></td>");
	tr2.append(tdTP);
	var fontTP=$("<font></font>");
		fontTP.text("RM "+totalprice);	
	tdTP.append(fontTP);
	
	var tdTS=$("<td></td>");
	tr2.append(tdTS);
	var fontTS=$("<font></font>");
		fontTS.text("RM "+totalshippingfee);	
	tdTS.append(fontTS);
	
	
	var tr3=$("<tr></tr>");
	table.append(tr3);
		
	var tdT=$("<td></td>");
	tdT.attr({"colspan":"99","align":"right"});
	tr3.append(tdT);
	var fontT=$("<font></font>");
		fontT.text("Total : RM "+totalAmount);	
	tdT.append(fontT);
	

	var trLast=$("<tr></tr>");
	table.append(trLast);
		
	var tdBtn=$("<td></td>");
	tdBtn.attr({"colspan":"99","align":"right"});
	trLast.append(tdBtn);
	
	var btnP=$("<input>");
		btnP.attr({"type":"button","name":"btnConfirmBuy"});
		btnP.val("Pay");
	tdBtn.append(btnP);
}


function closeAll()
{
	$("#divWishlist,#divCart,#divShowBuyList,#divShowPay,#DivInvoice").css("display","none");
}
function setWishlist()
{
	var div=$("#divWishlist");	
		div.html("");
	var table=$("<table></table>");
		div.append(table);	
	WishlistArray.forEach(function(ele) 
  	{
		var tr=$("<tr></tr>");
			tr.attr({"id":ele.wishlistid,"name":"trWishlist"});
		table.append(tr);
		var td1=$("<td></td>");
		tr.append(td1);
		var font=$("<font></font>");
			font.text(AjaxGetProductName(ele.productid));	
		td1.append(font);
		var td2=$("<td></td>");
		tr.append(td2);
		var btn=$("<input>");
			btn.attr({"type":"button","id":ele.wishlistid,"name":"btnDelWishlist"});
			btn.val("Delete");
		td2.append(btn);
	});
}
function setCart()
{
	var totalAmount=0;
	
	var div=$("#divCart");	
		div.html("");
	var table=$("<table></table>");
		div.append(table);	
		
	var tr1=$("<tr></tr>");	
	table.append(tr1);
	var td01=$("<td></td>");
		td01.text("Name");
	tr1.append(td01);
	
	var td02=$("<td></td>");
		td02.text("Quantity");
	tr1.append(td02);
	
	var td03=$("<td></td>");
		td03.text("Price");
	tr1.append(td03);
	
	var td04=$("<td></td>");
		td04.text("Total Price");
	tr1.append(td04);
	
	var td05=$("<td></td>");
	tr1.append(td05);
	
	CartArray.forEach(function(ele) 
  	{
		var tr=$("<tr></tr>");
			tr.attr({"id":ele.cartid,"name":"trCart"});
		table.append(tr);
		var td1=$("<td></td>");
		tr.append(td1);
		var font=$("<font></font>");
			font.text(AjaxGetProductName(ele.productid));	
		td1.append(font);
		
		var td2=$("<td></td>");
		tr.append(td2);
		var fontquantity=$("<font></font>");
			fontquantity.text(ele.quantity);
		td2.append(fontquantity);
		
		var price=AjaxGetProductPrice(ele.productid);
		var promotionId=AjaxGetProductPromotionId(ele.productid);
		
		
		if(promotionId.length>2)
		{
			var promotion=AjaxGetPromotion(promotionId);
			var finalvalue=0;
			if(promotion.rate!=0)
			  finalvalue=price-(price/100*promotion.rate);
			else if(promotion.price!=0)
			{
			  finalvalue=price-promotion.price;
			  if(finalvalue<=0)
				finalvalue=0;
			}
		}
		else
			finalvalue=price;
		
		var td3=$("<td></td>");
		tr.append(td3);
		var fontprice=$("<font></font>");
			fontprice.text("Rm"+finalvalue);
		td3.append(fontprice);
		
		var td4=$("<td></td>");
		tr.append(td4);
		var fonttotal=$("<font></font>");
			fonttotal.text("Rm"+ (ele.quantity*finalvalue));
		td4.append(fonttotal);
		
		var td5=$("<td></td>");
		tr.append(td5);
		var btn=$("<input>");
			btn.attr({"type":"button","id":ele.cartid,"name":"btnDelCart"});
			btn.val("Delete");
		td5.append(btn);
		
		totalAmount+=ele.quantity*finalvalue;
	});
	
	var tr2=$("<tr></tr>");	
	table.append(tr2);
	var tdLast=$("<td></td>");
		tdLast.attr({"colspan":"99","align":"right"});
	tr2.append(tdLast);
	
	var fontP=$("<font></font>");
		fontP.text("Total pay Rm"+totalAmount);
	tdLast.append(fontP);
	
	var tr3=$("<tr></tr>");	
	table.append(tr3);
	var tdLast1=$("<td></td>");
		tdLast1.attr({"colspan":"99","align":"right"});
	tr3.append(tdLast1);
	
	var btnP=$("<input>");
		btnP.attr({"type":"button","name":"btnCartPay"});
		btnP.val("Next");
	tdLast1.append(btnP);
}
function AjaxDelWishlist(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"DelWishlist",id:id},
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
            alert("Error! delete Wishlist");
        },
    });
  return rdata;
}
function AjaxDelCart(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"DelCart",id:id},
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
            alert("Error! delete Cart");
        },
    });
  return rdata;
}

function AjaxLoadWishlist()
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"LoadWishlist"},
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
            alert("Error! Load Wishlist");
        },
    });
  return rdata;
}
function AjaxLoadCart()
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"LoadCart"},
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
            alert("Error! Load Cart");
        },
    });
  return rdata;
}
function AjaxGetProductName(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"GetProductName",id:id},
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
            alert("Error! Get product name");
        },
    });
  return rdata;
}
function AjaxGetProductPrice(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"GetProductPrice",id:id},
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
            alert("Error! get product price");
        },
    });
  return rdata;
}

function AjaxGetProductPromotionId(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"GetProductPromotionId",id:id},
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
            alert("Error! get promotion id");
        },
    });
  return rdata;
}
function AjaxGetPromotion(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"GetPromotion",id:id},
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
            alert("Error! get promotion discount");
        },
    });
  return rdata;
}
function AjaxPayV1()
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"PayV1"},
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
            alert("Error! PayV1");
        },
    });
  return rdata;
}
function AjaxPayV2()
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"payFunction.php", 
        data:{  post:"PayV2"},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==1)
				alert("Pay Success");
			else 
				alert("Pay Fail");
				
      		rdata=data;
        },
        error: function () 
        {
            alert("Error! PayV2");
        },
    });
  return rdata;
}
</script>
<body>
<div id="amainmenu">
	<ul id="mainmenu">
    	<li><a id="aPayWishlist">Wishlist</a></li>
        <li><a id="aPayCart">Cart</a></li>
        <li><a id="aPayInvoice">Invoice</a></li>
    </ul>
</div>
<div align="center">
	<div id="divWishlist" style="display:none;"></div>
    <div id="divCart" style="display:none;"></div>
    <div id="divShowBuyList" style="display:none;"></div>
    <div id="divShowPay" style="display:none;">
    <table>
    	<tr>
        	<td colspan="99" align="center">Payment</td>
        </tr>
        <tr>
        	<td>Bank ID : </td>
            <td>xxxx xxxx xxxx xxxx</td>
        </tr>
        <tr>
        	<td>Total Amount : </td>
            <td id="trtotalamount"></td>
        </tr>
        <tr>
        	<td align="right">
            <input id="btnPay" type="button" value="Pay"/>
            </td>
        </tr>
    </table>
    
    </div>
    <div id="DivInvoice" style="display:none;">
    	Choose Invoice <select id="slcInvoiceList"></select>
    	<div id="DivInvoiceTable"></div>
    </div>
    
    
</div>
</body>
</html>