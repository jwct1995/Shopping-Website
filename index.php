<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<link href="css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-3.1.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js.js" charset="utf-8"></script>

<?php 
include("basic.html");
?>

<script>
var ProductArray=new Array();
var CategoryArray=new Array();
var PromotionArray=new Array();
var CommentArray=new Array();
var TotalPage=0;
$(function()
{
  AjaxLoadTitle();
  $("#divmainmenu").append(LoadMainMenu());
  
  ProductArray=LoadProduct(1,"");
  CategoryArray=AjaxLoadCategory();
  PromotionArray=AjaxLoadPromotion();
  TotalPage=AjaxLoadPageNum("");
  setPageNumber();
  setProductList();

});

$(document).ready(function()
{
	
  $("[name='ctgBtn']").click( function()
  {
	  ProductArray=LoadProduct(1,$(this).attr("id"));
      TotalPage=AjaxLoadPageNum($(this).attr("id"));
      setPageNumber();
      setProductList();
	  $("#txtsearch").val($(this).attr("id"));
  });
  $("#btnCloseModalProduct").click( function()
  {
    $("#divModalProduct").css("display","none");
  });
  $("#divComment").on("click", "[name='btnCommentSubmit']", function()
  {
	  AjaxSubmitComment($(this).attr("id"),$("[name='txtComment']").val());
  });
  $("#divDetailProduct").on("click", "[name='btnAddToCart']", function()
  {
    AjaxSubmitCart($(this).attr("id"),$("[name='txtQ']").val());
  });
  $("#divDetailProduct").on("click", "[name='btnAddToWishlist']", function()
  {
    AjaxSubmitWishlist($(this).attr("id"));
  });
  
  $("#divDetailProduct").on("keyup", "[name='txtQ']", function()
  {
    checkValue($(this).attr("id"),"");
  });
  $("#divDetailProduct").on("click", "[name='btnD']", function()
  {
    checkValue($(this).attr("id"),"D");
  });
  $("#divDetailProduct").on("click", "[name='btnA']", function()
  {
    checkValue($(this).attr("id"),"A");
  });
  
  $("#ulProduct").on("click", "[id='imgProduct']", function()
  {
    var src=$(this).attr("src");
    $("#imgZoomProduct").attr("src",src);
  });
  
  $("#divproduct").on("click", "[name='divProductClick']", function()
  {
    setProductModal($(this).attr("id"));
  });
  $("#txtsearch,#btnsearch").on('keyup click', function(evt)
  {
    evt = (evt) ? evt : (window.event) ? event : null;
    var charCode = (evt.charCode) ? evt.charCode :((evt.keyCode) ? evt.keyCode :((evt.which) ? evt.which : 0)); 

    if(charCode==13 || $(this).attr("id")=="btnsearch" && $("#txtsearch").val()!="" && $("#txtsearch").val()!=" ")
    {
      
      ProductArray=LoadProduct(1,$("#txtsearch").val());
      TotalPage=AjaxLoadPageNum($("#txtsearch").val());
      setPageNumber();
      setProductList();
    }
  });
  $("#divpagenumber").on("click", "[name='btnPg']", function()
  {
    ProductArray=LoadProduct($(this).attr("numofpage"),$("#txtsearch").val());
    TotalPage=AjaxLoadPageNum($("#txtsearch").val());
    setPageNumber();
    setProductList();
  });
  
});
function checkValue(id,txt)
{
  var promotionid="";
  var price=0;
  var txtQ=0;
  ProductArray.forEach(function(ele) 
  { 
    if(ele.ProductId==id)
    {
      promotionid=ele.PromotionId;
	  
      price=ele.Price;
      var maxQ=ele.Quantity;
      
      txtQ=$("[name='txtQ']").val();
      txtQ=parseInt(txtQ);
      
      if(txt=="A")
        txtQ+=1;
      else if(txt=="D")
        txtQ-=1;
      
      if(txtQ>=maxQ)
        txtQ=maxQ;
      if(txtQ<=0)
        txtQ=0;
      $("[name='txtQ']").val(txtQ)
    }
  });
  if(promotionid.length>2)
  {
    var finalvalue=0;
    var discount="";  
    PromotionArray.forEach(function(promot) 
    {
      if(promot.id==promotionid)
      {
        if(promot.rate!=0)
        {
          discount="-"+promot.rate+"%";
          finalvalue=price-(price/100*promot.rate);
        }
        else if(promot.price!=0)
        {
          discount="-Sgd"+promot.price;
          finalvalue=price-promot.price;
          if(finalvalue<=0)
            finalvalue=0;
        }
      }         
    });
  }
  else
    finalvalue=price;

  $("#fontTotalAmount").text("Sgd"+finalvalue*txtQ);
  
}

function setProductModal(id)
{
  ProductArray.forEach(function(ele) 
  {
    if(ele.ProductId==id)
    {
      var category=ele.CategoryId.split(",");
      var imgE=ele.ProductImage.split(",");
      $("#imgZoomProduct").attr("src",imgE[0]);
      $("#divProductTitle").text(ele.ProductName);
      var ul=$("#ulProduct");
      ul.html("");
      for(var c=0;c<imgE.length;c++)
      {
        if(c<5)
        {
          var li=$("<li></li>");
          li.attr("id","liProduct");
          li.css({"margin-top": "10px"});
          ul.append(li);
            
          var img=$("<img>");
          img.attr({"id":"imgProduct","src":imgE[c]});
          img.css({"display": "block","width": "100%","height": "auto"});
          li.append(img);
        }
      }
      var divDetail=$("#divDetailProduct");
        divDetail.html("");
        
      var divCategoryColum=$("<div></div>");
        divCategoryColum.css("display","flex");
        divDetail.append(divCategoryColum);
        
        CategoryArray.forEach(function(element) 
        {
          for(var o=0;o<category.length;o++)
          {
            if(element.id==category[o])
            {
              var divCategoryShow=$("<div></div>");
                divCategoryShow.css({"border-style": "solid","width": "fit-content","padding": "5px","margin": "5px","background-color": "#ececec"});
                divCategoryShow.text(element.name);
                divCategoryColum.append(divCategoryShow);  
            } 
          }
        });
      var btnAddToWishlist=$("<input>");
        btnAddToWishlist.attr({"id":ele.ProductId,"name":"btnAddToWishlist","type":"button"});
        btnAddToWishlist.val("Add To Wishlist");
        divDetail.append(btnAddToWishlist);
      var p1=$("<p></p>");
        p1.text("Company : "+AjaxGetCompanyName(ele.CompanyId));
        divDetail.append(p1);   
          
      if(ele.PromotionId.length>2)
      {
        var finalvalue=0;
        var discount="";  
        PromotionArray.forEach(function(promot) 
        {
          if(promot.id==ele.PromotionId)
          {
            if(promot.rate!=0)
            {
              discount="-"+promot.rate+"%";
              finalvalue=ele.Price-(ele.Price/100*promot.rate);
            }
            else if(promot.price!=0)
            {
              discount="-Sgd"+promot.price;
              finalvalue=ele.Price-promot.price;
              if(finalvalue<=0)
                finalvalue=0;
            }
          }         
        });
        var p2=$("<p></p>");
          p2.text("Before Discount : Sgd"+ele.Price);
          divDetail.append(p2);
            
        var p3=$("<p></p>");
          p3.text("Discount : "+discount); 
          divDetail.append(p3);
            
        var p4=$("<p></p>");
          p4.text("After Discount : Sgd"+finalvalue);
          divDetail.append(p4);
      }
      else 
      {
        var p5=$("<p></p>");
          p5.text("Prices / item : Sgd"+ele.Price);  
          divDetail.append(p5);
      }
	  
	  
	  var p51=$("<p></p>");
        p51.text("Weight : "+ele.ProductWeight+"kg");
        divDetail.append(p51);
		
      var p6=$("<p></p>");
        p6.text("Stock left : "+ele.Quantity);
        divDetail.append(p6);
          
      var divC=$("<div></div>");
        divDetail.append(divC);
      var btnD=$("<input>");
        btnD.attr({"type":"button","name":"btnD","id":ele.ProductId});
        btnD.val(" - ");
        divC.append(btnD);
          
      var inpValue=$("<input>");
        inpValue.attr({"type":"text","name":"txtQ","id":ele.ProductId});
        inpValue.val(0);
        divC.append(inpValue);      
          
      var btnA=$("<input>");
        btnA.attr({"type":"button","name":"btnA","id":ele.ProductId});
        btnA.val(" + ");
        divC.append(btnA);
          
      var p7=$("<p></p>");
        p7.text("Total : ");
        divDetail.append(p7);
      var font=$("<font></font>");
        font.attr("id","fontTotalAmount");
        font.text("Sgd"+0);
        p7.append(font);
        
      var btnAddToCart=$("<input>");
        btnAddToCart.attr({"id":ele.ProductId,"name":"btnAddToCart","type":"button"});
        btnAddToCart.val("Add To Cart");
        divDetail.append(btnAddToCart);
      
      $("#divProductDesc").html("");
      var pDesc=$("<p></p>");
        pDesc.text(ele.ProductDesc);
      $("#divProductDesc").append(pDesc);
	  
	  $("#divComment").html("");
	  	var pComment=$("<p></p>");
	  	  pComment.text("Comment");
		  	$("#divComment").append(pComment);
	  	var br1=$("<br>");
	  		pComment.append(br1);
		var textarea=$("<textarea></textarea>");
			textarea.attr({"name":"txtComment","id":ele.ProductId,"cols":"50","rows":"3"});
			pComment.append(textarea);
	 	var br2=$("<br>");
	  		pComment.append(br2);
		var btnCommentSubmit=$("<input>");
			btnCommentSubmit.attr({"name":"btnCommentSubmit","id":ele.ProductId,"type":"button"});
			btnCommentSubmit.val("Submit");
	  		pComment.append(btnCommentSubmit);
	  	CommentArray=AjaxGetComment(ele.ProductId);
		CommentArray.forEach(function(comment) 
  		{ 
			var pC=$("<p></p>");
				pC.text(AjaxGetAccName(comment.userid) +" : "+ comment.comment);
				$("#divComment").append(pC);
		});	  
      $("#divModalProduct").css("display","block");
    }
  });
}

function setPageNumber()
{
  var div=$("#divpagenumber");
  div.html("");
  for(var c=1;c<=TotalPage;c++)
  {
    var btn=$("<input>");
      btn.attr({"type":"button","numofpage":c,"name":"btnPg"});
      btn.val(c);
      btn.css({"margin-left": "5px","margin-right": "5px"});  
    div.append(btn);
  }
}
function setProductList() 
{
  var divE=$("#divproduct");
  divE.html("");
  if(ProductArray.length==0)
  {
  	divE.html("<font color='red' size='+2'>No Result from : '"+$("#txtsearch").val()+"'</font><br>Auto Load Default page after 5 second."); 
	setTimeout(function()
	{
		ProductArray=LoadProduct(1,"");
		TotalPage=AjaxLoadPageNum("");
		setPageNumber();
		setProductList();
		$("#txtsearch").val("");
	}, 5000);
  }
  
  ProductArray.forEach(function(ele) 
  {
    var img=ele.ProductImage.split(",");
    var img0=img[0];
    var category=ele.CategoryId.split(",");
    var div=$("<div></div>");
      div.addClass("hvr-bounce-in")
      div.attr({"align":"center","name":"divProductClick","id":ele.ProductId});
    divE.append(div);
    
    var img=$("<img>");
      img.attr("src",img0);
      img.css({"width":"300px","height":"200px"});
    div.append(img);
    
    var b=$("<b></b>");
    div.append(b);
    
    var ahref=$("<a></a>");
      ahref.addClass("product");
      ahref.text(ele.ProductName);
    b.append(ahref);
    
    var div1=$("<div></div>");
      div1.attr("align","center");
      div1.css("line-height","35px");
    b.append(div1);
    
    var font1=$("<font></font>");
      font1.attr("id","fontStockLeft");
      font1.css({"background-color": "#aaa","padding": "4px","margin-left": "10px"});
      font1.text("Stock Left :");
    div1.append(font1);
    
    var font11=$("<font></font>");
      font11.attr("size","+1");
      font11.text(ele.Quantity);
    font1.append(font11);
    if(ele.PromotionId.length>2)
    {
      var div2=$("<div></div>");
        div2.attr("align","left");
      b.append(div2);
      
      var s=$("<s></s>");
        div2.append(s);
      
      var font2=$("<font></font>");
        font2.css({"color": "#7c7c7c"});
        font2.text("Sgd"+ele.Price);
      s.append(font2);
	  
      var finalvalue=0;
      var discount=" ";
      PromotionArray.forEach(function(promot) 
      {
        if(promot.id==ele.PromotionId)
        {
          if(promot.rate!=0)
          {
            discount="-"+promot.rate+"%";
            finalvalue=ele.Price-(ele.Price/100*promot.rate);
          }
          else if(promot.price!=0)
          {
            discount="-Sgd"+promot.price;
            finalvalue=ele.Price-promot.price;
            if(finalvalue<=0)
              finalvalue=0;
          }
        } 
          
      });
      var div3=$("<div></div>");
        div3.attr("align","left");
        div3.css("color","#F00");
        div3.text("Sgd"+finalvalue);
      b.append(div3);
      
      var font3=$("<font></font>");
        font3.css({"background-color": "#F00","color": "#FFF","margin-left": "10px","padding-left": "5px","padding-right": "5px"});
        font3.text(discount);
      div2.append(font3);
    }
    else
    {
      var div4=$("<div></div>");
        div4.attr("align","left");
        div4.css("color","#408a68");
        div4.text("Sgd"+ele.Price);
      b.append(div4);
    }
    var div5=$("<div></div>");  
      div5.attr("id","divBuyIt")
      div5.text("BuyIt");
    div.append(div5);
    
  });
}

function LoadMainMenu()
{
  var rdata;
  $.ajax(
  {
    type:"POST",
    dataType:"json",
    url:"UserFunction.php", 
    data:{post:"LoadMainMenu"},//
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
      alert("Error! Load "+Table);
    },
  });
  return rdata; 
}
function LoadProduct(no,searchkey)
{
  var rdata;
  $.ajax(
  {
    type:"POST",
    dataType:"json",
    url:"UserFunction.php", 
    data:{post:"LoadProduct",no:no,searchkey:searchkey},//
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
      alert("Error! Load ");
    },
  });
  return rdata; 
}
function AjaxLoadPromotion()
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"LoadPromotion"},//
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
        url:"UserFunction.php", 
        data:{  post:"LoadCategory"},//
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
function AjaxLoadPageNum(searchkey)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"LoadPageNum",searchkey:searchkey},//
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

function AjaxGetCompanyName(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"GetCompanyName",id:id},//
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
            alert("Error! Get Company Name");
        },
    });
  return rdata;
}
function AjaxSubmitWishlist(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"InsertWishlist",id:id},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==1)
				alert("Add Wishlist Success");
        },
        error: function () 
        {
            alert("Error! Insert WishList");
        },
    });
}
function AjaxSubmitCart(id,txtq)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"InsertCart",id:id,quantity:txtq},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==1)
				alert("Add Cart Success");
			else if(data==2)
				alert("Fail");
			else if(data==3)
				alert("Update Success");
        },
        error: function () 
        {
            alert("Error! Insert Cart");
        },
    });
}
function AjaxSubmitComment(id,txt)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"InsertComment",id:id,txt:txt},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==1)
				alert("Add Comment Success");
			else if(data==2)
				alert("Fail");
        },
        error: function () 
        {
            alert("Error! Add Comment");
        },
    });
}
function AjaxGetComment(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"GetComment",id:id},
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
            alert("Error! Get Comment");
        },
    });
  return rdata;
}
function AjaxGetAccName(id)
{
  var rdata;
  $.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{  post:"GetAccName",id:id},
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
            alert("Error! Get Account Name");
        },
    });
  return rdata;
}

</script>
<body>
<div align="right" style="margin-top: 20px;">
    <font style="font-weight:bold;" size="+2"> 
        <input id="txtsearch" class="txtsearch" type="text" placeholder="Search.."/>
      <input id="btnsearch" type="button" value="Search"/>
    </font>
</div>
<div id="divmainmenu" ></div>
<div id="divproduct" align="center"></div>
<div id="divpagenumber" align="center" style="margin-bottom: 70px;"></div>

<style>
/*modal start*/
.modal 
{
    display: none; 
    position: fixed; 
    z-index: 1; 
    padding-top: 100px; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%; 
    overflow: auto;
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.9); 
  -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom 
{
    from {-webkit-transform:scale(0)} 
    to {-webkit-transform:scale(1)}
}

@keyframes zoom 
{
    from {transform:scale(0)} 
    to {transform:scale(1)}
}

.close 
{
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}
.close:hover,
.close:focus 
{
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}
/*modal end*/
</style>

<div id="divModalProduct" class="modal">
  <span id="btnCloseModalProduct" class="close">&times;</span>
  <div align="center">

    <div style=" background-color:#CCC;width: 75%;margin-left: auto;margin-right: auto; padding:5px;">
      <h1 id="divProductTitle"align="left"> Title</h1>
      <div style="display: flex;align-items: center;">
        <ul id="ulProduct" style="margin: 0;padding: 0;list-style: none;width: 12%;flex: 1 1 auto; background:none;"></ul>
        <div style="padding-left: 10px;padding-right: 10px;width: 48%;flex: 1 1 auto;">
          <img id="imgZoomProduct"style="display: block;width: 100%;height: auto;margin-top: auto;margin-bottom: auto;" src="https://i.pinimg.com/originals/9e/7a/fd/9e7afda70cde1b6bd73da5dab17a7406.gif">
        </div>
        <div id="divDetailProduct" style="padding-left: 10px;padding-right: 10px;width: 40%;width: 48%;flex: 1 1 auto;align-self: stretch;" align="left">
        </div>
      </div>
      <div id="divProductDesc"></div>
      <div id="divComment" align="left" style="width:80%;padding-bottom: 100px;margin-bottom: 70px;"></div>
    </div>
    </div>
</div>
</body>
</html>