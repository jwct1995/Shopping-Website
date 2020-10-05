<?php 
session_start();

include"db.php";
include"Function.php";
$post=$_POST["post"];

$rtn="";

if($post=="LoadWishlist")
{
	$rtn=array();
	$query="SELECT * FROM tblwishlist WHERE UserId='".$_SESSION["userid"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		$rtn[$c-1]=array("wishlistid"=>$row["WishlistId"],"productid"=>$row["ProductId"]);
	}
	echo json_encode($rtn);
}
else if($post=="LoadCart")
{
	$rtn=array();
	$query="SELECT * FROM tblcart WHERE UserId='".$_SESSION["userid"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		$rtn[$c-1]=array("cartid"=>$row["CartId"],"productid"=>$row["ProductId"],"quantity"=>$row["Quantity"]);
	}
	echo json_encode($rtn);
}

else if($post=="DelWishlist")
{
	$query="DELETE FROM tblwishlist WHERE WishlistId='".$_POST["id"]."' AND UserId='".$_SESSION["userid"]."'";
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$rtn=1;
	else
		$rtn=2;			

	echo json_encode($rtn);
}
else if($post=="DelCart")
{
	$query="DELETE FROM tblcart WHERE CartId='".$_POST["id"]."' AND UserId='".$_SESSION["userid"]."'";
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$rtn=1;
	else
		$rtn=2;			

	echo json_encode($rtn);
}
else if($post=="GetProductName")
{
	$query="SELECT * FROM tblproductinfo WHERE ProductId='".$_POST["id"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);			

	echo json_encode($row["ProductName"]);
}
else if($post=="GetProductPrice")
{
	$query="SELECT * FROM tblproductprice WHERE ProductId='".$_POST["id"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);			

	echo json_encode($row["Price"]);
}
else if($post=="GetProductPromotionId")
{
	$query="SELECT * FROM tblproductinfo WHERE ProductId='".$_POST["id"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);			

	echo json_encode($row["PromotionID"]);
}

else if($post=="GetPromotion")
{
	$query="SELECT * FROM tblpromotion WHERE PromotionId='".$_POST["id"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);			

	$rtn=array("rate"=>$row["PromotionRate"],"price"=>$row["PromotionPrice"]);

	echo json_encode($rtn);
}

else if($post=="PayV1")
{
$txtb="";
	$rtn=array();
	$query="SELECT * FROM tblcart WHERE UserId='".$_SESSION["userid"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		
		$query1="SELECT * FROM tblproductinfo WHERE ProductId='".$row["ProductId"]."'";
		$sql1=mysqli_query($mysqli,$query1)or die(mysql_error());
		$row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH);			

		
		$query2="SELECT * FROM tblpromotion WHERE PromotionId='".$row1["PromotionID"]."'";
		$sql2=mysqli_query($mysqli,$query2)or die(mysql_error());
		$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);			

		$query3="SELECT * FROM tblproductprice WHERE ProductId='".$row["ProductId"]."'";
		$sql3=mysqli_query($mysqli,$query3)or die(mysql_error());
		$row3 = mysqli_fetch_array($sql3, MYSQLI_BOTH);			

		
		$finalprice=0;
		if($row2["PromotionRate"]!=0)
			$finalprice=($row3["Price"]-(($row3["Price"]/100)*$row2["PromotionRate"]));
		else if($row2["PromotionPrice"]!=0)
		{
			$finalprice=$row3["Price"]-$row2["PromotionPrice"];
		}
		else
			$finalprice=$row3["Price"];
			
		if($finalprice<=0)
				$finalprice=0;
		
		$finalprice=round($finalprice, 2);
		
		$totalamount=$finalprice*$row["Quantity"];
		
		$totalamount=round($totalamount, 2);
		
		$productW=0;
		$productSP=0;
		if($row1["ProductWeight"]>=0.5)
		{
			$productW=$row1["ProductWeight"]-0.5;
			$productSP=$productSP+2;
		}
		else
		{
			$productW=0;
			$productSP=$productSP+2;
		}
		$productSP=$productSP+(ceil($productW/0.5)*1.5);
		$shippingfee=$productSP*$row["Quantity"];
	
		$shippingfee=round($shippingfee, 2);
		
		$rtn[$c-1]=array("productname"=>$row1["ProductName"],"shippingfee"=>$shippingfee,"quantity"=>$row["Quantity"],"orgprice"=>$row3["Price"],"finalprice"=>$finalprice,"totalamount"=>$totalamount);
	}
	echo json_encode($rtn);
}
else if($post=="PayV2")
{
	$txtb="";
	$txt="";
	$rtn=array();
	$query="SELECT * FROM tblcart WHERE UserId='".$_SESSION["userid"]."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$c=0;
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		
		
		$query1="SELECT * FROM tblproductinfo WHERE ProductId='".$row["ProductId"]."'";
		$sql1=mysqli_query($mysqli,$query1)or die(mysql_error());
		$row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH);			
		
		$query2="SELECT * FROM tblpromotion WHERE PromotionId='".$row1["PromotionID"]."'";
		$sql2=mysqli_query($mysqli,$query2)or die(mysql_error());
		$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);			
		
		$query3="SELECT * FROM tblproductprice WHERE ProductId='".$row["ProductId"]."'";
		$sql3=mysqli_query($mysqli,$query3)or die(mysql_error());
		$row3 = mysqli_fetch_array($sql3, MYSQLI_BOTH);	
		
		$query4="SELECT * FROM tblcompanyinfo WHERE CompanyId='".$row1["CompanyId"]."'";
		$sql4=mysqli_query($mysqli,$query4)or die(mysql_error());
		$row4 = mysqli_fetch_array($sql4, MYSQLI_BOTH);		
		
		$query5="SELECT * FROM tbluseraccinfo WHERE UserId='".$_SESSION["userid"]."'";
		$sql5=mysqli_query($mysqli,$query5)or die(mysql_error());
		$row5 = mysqli_fetch_array($sql5, MYSQLI_BOTH);		
	
		$finalprice=0;
		if($row2["PromotionRate"]!=0)
		{
			$finalprice=($row3["Price"]-(($row3["Price"]/100)*$row2["PromotionRate"]));
		}
		else if($row2["PromotionPrice"]!=0)
		{
			$finalprice=$row3["Price"]-$row2["PromotionPrice"];
		}
		else
			$finalprice=$row3["Price"];
		if($finalprice<=0)
				$finalprice=0;
		
		$finalprice=round($finalprice, 2);
		
		$totalamount=$finalprice*$row["Quantity"];
		
		$totalamount=round($totalamount, 2);

		$productW=0;
		$productSP=0;
		if($row1["ProductWeight"]>=0.5)
		{
			$productW=$row1["ProductWeight"]-0.5;
			$productSP=$productSP+2;
		}
		else
		{
			$productW=0;
			$productSP=$productSP+2;
		}
		
		$productSP=$productSP+(ceil($productW/0.5)*1.5);

		$shippingfee=$productSP*$row["Quantity"];
	
		$shippingfee=round($shippingfee, 2);
		
		$rtn[$c][0]=$row["ItemId"];
		$rtn[$c][1]=$row["ProductId"];
		$rtn[$c][2]=$row["Quantity"];
		$rtn[$c][3]=$finalprice;
		$rtn[$c][4]=$totalamount;
		$rtn[$c][5]=$row1["PromotionID"];
		$rtn[$c][6]=$row2["PromotionDesc"];
		$rtn[$c][7]=$shippingfee;
		$rtn[$c][8]=$row4["CompanyCountry"]." to ".$row5["UserCountry"];
		$rtn[$c][9]=$row1["CompanyId"];
		$c++;
	}

	$company=$rtn[0][9];
	$itemIdArray="";
	$totalproductamount=0;
	$totalshippingamount=0;
	$receiptArray="";
	$totalOfBoth=0;

	
	for($c=0;$c<sizeof($rtn);$c++)
	{
		$query="SELECT * FROM tblproductquantity WHERE ProductId='".$rtn[$c][1]."'";
		$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
		if($row["Quantity"]-$rtn[$c][2]>=0)
		{
			$query="UPDATE tblproductquantity SET Quantity='".($row["Quantity"]-$rtn[$c][2])."' WHERE ProductId='".$rtn[$c][1]."'";
			$sql=mysqli_query($mysqli,$query)or die(mysql_error());
			
			$id=DateTimeForId();
			$rtn[$c][0]="itm".$id;
			$query="INSERT INTO tblitem(ItemId, ProductId, Quantity, Prices, PromotionID, PromotionDesc, ShippingInfo,ShippingFee) 
					VALUES ('itm".$id."','".$rtn[$c][1]."','".$rtn[$c][2]."','".$rtn[$c][3]."','".$rtn[$c][5]."','".$rtn[$c][6]."','".$rtn[$c][8]."','".$rtn[$c][7]."')";
			$sql=mysqli_query($mysqli,$query)or die(mysql_error());

			if($company == $rtn[$c][9])
			{
				if(strlen($itemIdArray)>0)
					$itemIdArray=$itemIdArray.",".$rtn[$c][0];
				else
					$itemIdArray=$rtn[$c][0];
				$totalproductamount=$totalproductamount+$rtn[$c][4];
				$totalshippingamount=$totalshippingamount+$rtn[$c][7];
			}
			else
			{
				$itemIdArray=$rtn[$c][0];
				$totalproductamount=$rtn[$c][4];
				$totalshippingamount=$rtn[$c][7];
				$company = $rtn[$c][9];
			}
			
			if($company !=$rtn[$c+1][9])
			{
				$query="INSERT INTO tblreceipt(ReceiptId, UserId, CompanyId, ItemId, TotalAmount, TotalShippingAmount,DateTime) VALUES 
						('rcp".$id."','".$_SESSION["userid"]."','".$rtn[$c][9]."','".$itemIdArray."','".$totalproductamount."','".$totalshippingamount."',NOW())";
				$result=mysqli_query($mysqli,$query);
				
				if(strlen($receiptArray)>0)
					$receiptArray=$receiptArray.",rcp".$id;
				else
					$receiptArray="rcp".$id;
								
				$totalOfBoth=$totalOfBoth+$totalproductamount+$totalshippingamount;
			}
		}	
	}
	if($totalOfBoth>0)
	{
		$id=DateTimeForId();
		$query="INSERT INTO tblpayment(PaymentId, UserId, ReceiptId, FinalAmount) VALUES ('pym".$id."','".$_SESSION["userid"]."','".$receiptArray."','".$totalOfBoth."')";
		$result=mysqli_query($mysqli,$query);
		if($result==true)
		{
			$txt=1;
			$query="DELETE FROM tblcart WHERE UserId='".$_SESSION["userid"]."'";
			$result=mysqli_query($mysqli,$query);	
		}
		else
			$txt=2;
	}
	else
		$txt=2;
	echo json_encode($txt);
}
else if($post=="LoadPaymentList")
{
	$rtn=array();
	$c=0;
	$query="SELECT * FROM tblpayment WHERE UserId='".$_SESSION["userid"]."' ORDER BY PaymentId DESC";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$rtn[$c]=array("paymentid"=>$row["PaymentId"]);
		$c++;
	}
	echo json_encode($rtn);
}
else if($post=="LoadPaymentDetail")
{
	$paymentid=$_POST["paymentid"];
	$rtn=array();
	$c=0;
$txtb="";
	$query="SELECT * FROM tblpayment WHERE UserId='".$_SESSION["userid"]."' AND PaymentId='".$paymentid."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);

	
	$receiptid=explode(",", $row["ReceiptId"]);
	
	for($z=0;$z<sizeof($receiptid);$z++)
	{
		
		$query1="SELECT * FROM tblreceipt WHERE UserId='".$_SESSION["userid"]."' AND ReceiptId='".$receiptid[$z]."' ";
		$sql1=mysqli_query($mysqli,$query1)or die(mysql_error());
		$row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH);
		
		$itemid=explode(",", $row1["ItemId"]);
		
		$query3="SELECT * FROM tblcompanyinfo WHERE CompanyId='".$row1["CompanyId"]."' ";
		$sql3=mysqli_query($mysqli,$query3)or die(mysql_error());
		$row3 = mysqli_fetch_array($sql3, MYSQLI_BOTH);
		

		for($x=0;$x<sizeof($itemid);$x++)
		{
			
			$query2="SELECT * FROM tblitem WHERE ItemId='".$itemid[$x]."' ";
			$sql2=mysqli_query($mysqli,$query2)or die(mysql_error());
			$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);
			
			$query4="SELECT * FROM tblproductinfo WHERE ProductId='".$row2["ProductId"]."' ";
			$sql4=mysqli_query($mysqli,$query4)or die(mysql_error());
			$row4 = mysqli_fetch_array($sql4, MYSQLI_BOTH);
			
			$query5="SELECT * FROM tblpromotion WHERE PromotionId='".$row2["PromotionID"]."' ";
			$sql5=mysqli_query($mysqli,$query5)or die(mysql_error());
			$row5 = mysqli_fetch_array($sql5, MYSQLI_BOTH);
				
			
			$rtn[$c]=array(
			"payid"=>$row["PaymentId"],
			"finalAmount"=>$row["FinalAmount"],
			"receiptid"=>$row1["ReceiptId"],
			"companyname"=>$row3["CompanyName"],
			"ttamount"=>$row1["TotalAmount"],
			"ttshippingfee"=>$row1["TotalShippingAmount"],
			"productname"=>$row4["ProductName"],
			"quantity"=>$row2["Quantity"],
			"price"=>$row2["Prices"],
			"promotionname"=>$row5["PromotionName"],
			"shippinginfo"=>$row2["ShippingInfo"],
			"shipppingfee"=>$row2["ShippingFee"],
			);
			$c++;
		}
	}
	echo json_encode($rtn);
}









	
	










?>