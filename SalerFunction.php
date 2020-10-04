<?php 
session_start();

include"db.php";
include"Function.php";
$post=$_POST["post"];

$rtn="";

if($post=="LoadPromotion")
{
	$rtn=array();
	$query="SELECT * FROM tblpromotion WHERE CompanyId='".$_SESSION["companyid"]."' AND DATE(PromotionEndDate) > CURDATE()  ORDER BY PromotionName ASC ";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		$rtn[$c-1]=array("id"=>$row["PromotionId"],"name"=>$row["PromotionName"],"desc"=>$row["PromotionDesc"],"rate"=>$row["PromotionRate"],"price"=>$row["PromotionPrice"],"sdate"=>$row["PromotionStartDate"],"edate"=>$row["PromotionEndDate"]);
	}
	echo json_encode($rtn);
}

else if($post=="LoadCategory")
{
	$rtn=array();
	$query="SELECT * FROM tblcategoryelement ORDER BY CategoryElementName ASC ";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		$rtn[$c-1]=array("id"=>$row["CategoryElementID"],"name"=>$row["CategoryElementName"],"desc"=>$row["	CategoryElementDescription"]);
	}
	echo json_encode($rtn);
}
else if($post=="LoadCompanyDetail")
{
	$query="SELECT * FROM tblcompanylinkinguser
	WHERE UserId='".$_SESSION["userid"]."' AND CompanyId='".$_SESSION["companyid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$result = mysqli_num_rows($sql);
	if($result==1)
	{
		$query="SELECT * FROM tblcompanyinfo
		WHERE CompanyId='".$_SESSION["companyid"]."'";	
		$sql=mysqli_query($mysqli,$query);
		$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
		
		$rtn=array("name"=>$row["CompanyName"],"country"=>$row["CompanyCountry"],"state"=>$row["CompanyState"],"city"=>$row["CompanyCity"],"postcode"=>$row["CompanyPostCode"],"address"=>$row["CompanyAddress"],"phonenumber"=>$row["CompanyPhoneNumber"],"desc"=>$row["CompanyDesc"]);
	}
	else
	$rtn==2;
	echo json_encode($rtn);
}
//edit
else if($post=="UpdateCompany")
{
	$country=mysqli_real_escape_string($mysqli,trim($_POST["country"]));
	$state=mysqli_real_escape_string($mysqli,trim($_POST["state"]));
	$city=mysqli_real_escape_string($mysqli,trim($_POST["city"]));
	$postcode=mysqli_real_escape_string($mysqli,trim($_POST["postcode"]));
	$address=mysqli_real_escape_string($mysqli,trim($_POST["address"]));
	$phonenumber=mysqli_real_escape_string($mysqli,trim($_POST["phonenumber"]));
	$desc=mysqli_real_escape_string($mysqli,trim($_POST["desc"]));
	
	$id=DateTimeForId();
	
	$query="SELECT * FROM tblcompanyinfo
			WHERE CompanyId='".$_SESSION["companyid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	$query="INSERT INTO tblcompanyinforecord (
			CompanyEditRecordId,CompanyId,CompanyName,CompanyCountry,CompanyState,CompanyCity,
			CompanyPostCode,CompanyAddress,CompanyPhoneNumber,CompanyDesc
			) 
			VALUES
			('cr".$id."','".$row["CompanyId"]."','".$row["CompanyName"]."','".$row["CompanyCountry"]."','".$row["CompanyState"]."','".$row["CompanyCity"]."','".$row["CompanyPostCode"]."','".$row["CompanyAddress"]."','".$row["CompanyPhoneNumber"]."','".$row["CompanyDesc"]."')";
		$result=mysqli_query($mysqli,$query);
		
		
	$query="UPDATE tblcompanyinfo SET 
			CompanyCountry='".$country."',CompanyState='".$state."',CompanyCity='".$city."',CompanyPostCode='".$postcode."',CompanyAddress='".$address."',CompanyPhoneNumber='".$phonenumber."',CompanyDesc='".$desc."'
			WHERE CompanyId='".$_SESSION["companyid"]."'";
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$rtn=1;
	else
	{
		$rtn=2;
		$query="DELETE FROM tblcompanyinforecord WHERE CompanyEditRecordId='cr".$id."'";
		$result=mysqli_query($mysqli,$query);
	}
		
	echo json_encode($rtn);
	
}
//insert
else if($post=="RegisterProduct")
{
	$ctg=mysqli_real_escape_string($mysqli,trim($_POST["ctg"]));
	$promotion=mysqli_real_escape_string($mysqli,trim($_POST["promotion"]));
	$name=mysqli_real_escape_string($mysqli,trim($_POST["name"]));
	$desc=mysqli_real_escape_string($mysqli,trim($_POST["desc"]));
	$weight=mysqli_real_escape_string($mysqli,trim($_POST["weight"]));
	$quantity=mysqli_real_escape_string($mysqli,trim($_POST["quantity"]));
	$price=mysqli_real_escape_string($mysqli,trim($_POST["price"]));
	$img=mysqli_real_escape_string($mysqli,trim($_POST["img"]));
	
	$id=DateTimeForId();
	
	$query=	"INSERT INTO tblproductinfo(ProductId, UserId, CompanyId, PromotionID, ProductName, ProductDesc, ProductImage, ProductWeight, Category) 
	VALUES (
	'p".$id."','".$_SESSION["userid"]."','".$_SESSION["companyid"]."','".$promotion."','".$name."','".$desc."','".$img."','".$weight."','".$ctg."')";
	
	$result=mysqli_query($mysqli,$query);
	if($result==true)
	{
		$query="INSERT INTO tblproductprice(ProductId, Price) VALUES ('p".$id."','".$price."')";
		$result=mysqli_query($mysqli,$query);
		$query="INSERT INTO tblproductquantity(ProductId, Quantity) VALUES ('p".$id."','".$quantity."')";
		$result=mysqli_query($mysqli,$query);
		
		$rtn=1;
	}
	else
		$rtn=2;
	echo json_encode($rtn);
}

else if($post=="RegisterPromotion")
{
	
	$name=mysqli_real_escape_string($mysqli,trim($_POST["name"]));
	$type=$_POST["type"];
	$value=mysqli_real_escape_string($mysqli,trim($_POST["value"]));
	$desc=mysqli_real_escape_string($mysqli,trim($_POST["desc"]));
	$sdate=mysqli_real_escape_string($mysqli,trim($_POST["sdate"]));
	$edate=mysqli_real_escape_string($mysqli,trim($_POST["edate"]));
	
	$Prate=$Pprice=0;
	if($type=="amount")
		$Pprice=$value;
	else if($type=="rate")
		$Prate=$value;
	
	$id=DateTimeForId();
	
	$query="INSERT INTO tblpromotion(
	PromotionId, UserId, CompanyId, PromotionName ,PromotionDesc, PromotionRate, PromotionPrice, PromotionStartDate, PromotionEndDate
	) VALUES ('pmt".$id."','".$_SESSION["userid"]."','".$_SESSION["companyid"]."','".$name."','".$desc."','".$Prate."','".$Pprice."','".$sdate."','".$edate."')";	
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$rtn=1;
	else
		$rtn=2;
	echo json_encode($rtn);
}

else if($post=="RegisterCategoryElement")
{
	
	$name=mysqli_real_escape_string($mysqli,trim($_POST["name"]));
	$desc=mysqli_real_escape_string($mysqli,trim($_POST["desc"]));
	$id=DateTimeForId();
	
	$query="INSERT INTO tblcategoryelement (CategoryElementID,CategoryElementName,CategoryElementDescription) 
			VALUES
			('ctg".$id."','".$name."','".$desc."')";	
	$result=mysqli_query($mysqli,$query);
	if($result==true)
	$rtn=1;
	else
	$rtn=2;
	
	echo json_encode($rtn);
}

else if($post=="RegisterCompany")
{
	$name=mysqli_real_escape_string($mysqli,trim($_POST["name"]));
	$country=mysqli_real_escape_string($mysqli,trim($_POST["country"]));
	$state=mysqli_real_escape_string($mysqli,trim($_POST["state"]));
	$city=mysqli_real_escape_string($mysqli,trim($_POST["city"]));
	$postcode=mysqli_real_escape_string($mysqli,trim($_POST["postcode"]));
	$address=mysqli_real_escape_string($mysqli,trim($_POST["address"]));
	$phonenumber=mysqli_real_escape_string($mysqli,trim($_POST["phonenumber"]));
	$desc=mysqli_real_escape_string($mysqli,trim($_POST["desc"]));
	
	$id=DateTimeForId();
	$query="INSERT INTO tblcompanylinkinguser (
			CompanyLinkUserId,CompanyId,UserId,LinkType
			) 
			VALUES
			('clu".$id."','c".$id."','".$_SESSION["userid"]."','A')";
	$result=mysqli_query($mysqli,$query);
	if($result==true)
	{
		$query="INSERT INTO tblcompanyinfo (
			CompanyId,CompanyName,CompanyCountry,CompanyState,CompanyCity,
			CompanyPostCode,CompanyAddress,CompanyPhoneNumber,CompanyDesc
			) 
			VALUES
			('c".$id."','".$name."','".$country."','".$state."','".$city."','".$postcode."','".$address."','".$phonenumber."','".$desc."')";
		$result=mysqli_query($mysqli,$query);
		if($result==true)
		{
			$rtn=1;
			$_SESSION["companyid"]=$row["CompanyId"];
		}
		else
		{
			$query="DELETE FROM tblcompanylinkinguser WHERE CompanyLinkUserId='clu".$id."'";
			$rtn=2;
		}	
	}
	else
		$rtn=2;
	echo json_encode($rtn);
}
else if($post=="LoadReport")
{
	$rtn=array();
	$strYear=0;
	$strTotalAmount=0;
	$strTotalShippingFee=0;
	$strTotalQuantity=0;
	
	$c=0;
	$query="SELECT * FROM tblcompanylinkinguser
			WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	$query1="SELECT * FROM tblreceipt
			WHERE CompanyId='".$row["CompanyId"]."'
			ORDER BY DateTime DESC";	
	$sql1=mysqli_query($mysqli,$query1);
	while ($row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH)) 
	{
		$strtime=strtotime($row1["DateTime"]);
		$year = date("Y", $strtime);
		
		$quantity=0;
		$itemid=explode(",", $row1["ItemId"]);
		for($z=0;$z<sizeof($itemid);$z++)
		{
			$query2="SELECT * FROM tblitem
				WHERE ItemId='".$itemid[$z]."'";	
				
			$sql2=mysqli_query($mysqli,$query2);
			$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);
			$quantity=$quantity+$row2["Quantity"];
		}
		if($c==0)
		{
			$strYear=$year;
			$c++;
		}
		
		if($strYear!=$year)
		{
			$rtn[$c-1]=array("year"=>$strYear,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
			$c++;
			$strYear=$year;
			$strTotalQuantity=$quantity;
			$strTotalAmount=$row1["TotalAmount"];
			$strTotalShippingFee=$row1["TotalShippingAmount"];
		}
		else
		{
			$strTotalQuantity=$strTotalQuantity+$quantity;
			$strTotalAmount=$strTotalAmount+$row1["TotalAmount"];
			$strTotalShippingFee=$strTotalShippingFee+$row1["TotalShippingAmount"];
		}
	}
	$rtn[$c-1]=array("year"=>$strYear,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
	echo json_encode($rtn);
}
else if($post=="LoadReportYear")
{
	$txtYear=$_POST["year"];
	$rtn=array();
	$strYear=0;
	$strMonth=0;
	$strmonth=0;
	$strTotalAmount=0;
	$strTotalShippingFee=0;
	$strTotalQuantity=0;
	
	$c=0;
	$query="SELECT * FROM tblcompanylinkinguser
			WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	$query1="SELECT * FROM tblreceipt
			WHERE 
			CompanyId='".$row["CompanyId"]."' AND
			DATE(DateTime) >= '".$txtYear."-01-01 00:00:00' AND
			DATE(DateTime) <= '".$txtYear."-12-31 23:59:59'
			ORDER BY DateTime DESC";
			
	$sql1=mysqli_query($mysqli,$query1);
	while ($row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH)) 
	{
		$strtime=strtotime($row1["DateTime"]);
		$year = date("Y", $strtime);
		$Month = date("m", $strtime);
		$month = date("F", $strtime);
		
		$quantity=0;
		$itemid=explode(",", $row1["ItemId"]);
		for($z=0;$z<sizeof($itemid);$z++)
		{
			$query2="SELECT * FROM tblitem
				WHERE ItemId='".$itemid[$z]."'";	
				
			$sql2=mysqli_query($mysqli,$query2);
			$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);
			$quantity=$quantity+$row2["Quantity"];
		}
		if($c==0)
		{
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$c++;
		}
		if($strMonth!=$Month)
		{
			$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
			$c++;
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$strTotalQuantity=$quantity;
			$strTotalAmount=$row1["TotalAmount"];
			$strTotalShippingFee=$row1["TotalShippingAmount"];
		}
		else
		{
			$strTotalQuantity=$strTotalQuantity+$quantity;
			$strTotalAmount=$strTotalAmount+$row1["TotalAmount"];
			$strTotalShippingFee=$strTotalShippingFee+$row1["TotalShippingAmount"];
		}
	}
	$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
	echo json_encode($rtn);
}
else if($post=="LoadReportMonth")
{
	$txtMonth=$_POST["month"];
	$rtn=array();
	$strYear=0;
	$strMonth=0;
	$strmonth=0;
	$strDay=0;
	$strTotalAmount=0;
	$strTotalShippingFee=0;
	$strTotalQuantity=0;
	
	$c=0;
	$query="SELECT * FROM tblcompanylinkinguser
			WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	$query1="SELECT * FROM tblreceipt
			WHERE 
			CompanyId='".$row["CompanyId"]."' AND
			DATE(DateTime) >= '".$txtMonth."-01 00:00:00' AND
			DATE(DateTime) <= '".$txtMonth."-31 23:59:59'
			ORDER BY DateTime DESC";
			
	$sql1=mysqli_query($mysqli,$query1);
	while ($row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH)) 
	{
		$strtime=strtotime($row1["DateTime"]);
		$year = date("Y", $strtime);
		$Month = date("m", $strtime);
		$month = date("F", $strtime);
		$day = date("d", $strtime);
		
		$quantity=0;
		$itemid=explode(",", $row1["ItemId"]);
		for($z=0;$z<sizeof($itemid);$z++)
		{
			$query2="SELECT * FROM tblitem
				WHERE ItemId='".$itemid[$z]."'";	
				
			$sql2=mysqli_query($mysqli,$query2);
			$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);
			$quantity=$quantity+$row2["Quantity"];
		}
		if($c==0)
		{
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$strDay=$day;
			$c++;
		}
		if($strDay!=$day)
		{
			$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"day"=>$strDay,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
			$c++;
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$strDay=$day;
			$strTotalQuantity=$quantity;
			$strTotalAmount=$row1["TotalAmount"];
			$strTotalShippingFee=$row1["TotalShippingAmount"];
		}
		else
		{
			$strTotalQuantity=$strTotalQuantity+$quantity;
			$strTotalAmount=$strTotalAmount+$row1["TotalAmount"];
			$strTotalShippingFee=$strTotalShippingFee+$row1["TotalShippingAmount"];
		}
	}
	$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"day"=>$strDay,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
	echo json_encode($rtn);
}


else if($post=="LoadReportDay")
{
	$txtDay=$_POST["day"];
	$rtn=array();
	$strYear=0;
	$strMonth=0;
	$strmonth=0;
	$strDay=0;
	$strHour=0;
	$strTotalAmount=0;
	$strTotalShippingFee=0;
	$strTotalQuantity=0;
	
	$c=0;
	$query="SELECT * FROM tblcompanylinkinguser
			WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	$query1="SELECT * FROM tblreceipt
			WHERE 
			CompanyId='".$row["CompanyId"]."' AND
			DATE(DateTime) >= '".$txtDay." 00:00:00' AND
			DATE(DateTime) <= '".$txtDay." 23:59:59'
			ORDER BY DateTime DESC";
			
	$sql1=mysqli_query($mysqli,$query1);
	while ($row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH)) 
	{
		$strtime=strtotime($row1["DateTime"]);
		$year = date("Y", $strtime);
		$Month = date("m", $strtime);
		$month = date("F", $strtime);
		$day = date("d", $strtime);
		$hour = date("H", $strtime);
		
		$quantity=0;
		$itemid=explode(",", $row1["ItemId"]);
		for($z=0;$z<sizeof($itemid);$z++)
		{
			$query2="SELECT * FROM tblitem
				WHERE ItemId='".$itemid[$z]."'";	
				
			$sql2=mysqli_query($mysqli,$query2);
			$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);
			$quantity=$quantity+$row2["Quantity"];
		}
		if($c==0)
		{
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$strDay=$day;
			$strHour=$hour;
			$c++;
		}
		if($strHour!=$hour)
		{
			$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"day"=>$strDay,"hour"=>$strHour,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
			$c++;
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$strDay=$day;
			$strHour=$hour;
			$strTotalQuantity=$quantity;
			$strTotalAmount=$row1["TotalAmount"];
			$strTotalShippingFee=$row1["TotalShippingAmount"];
		}
		else
		{
			$strTotalQuantity=$strTotalQuantity+$quantity;
			$strTotalAmount=$strTotalAmount+$row1["TotalAmount"];
			$strTotalShippingFee=$strTotalShippingFee+$row1["TotalShippingAmount"];
		}
	}
	$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"day"=>$strDay,"hour"=>$strHour,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
	echo json_encode($rtn);

}
else if($post=="LoadReportSDT")
{
	$txtSY=$_POST["sy"];
	$txtSM=$_POST["sm"];
	$txtSD=$_POST["sd"];
	$txtSH=$_POST["sh"];
	$txtEY=$_POST["ey"];
	$txtEM=$_POST["em"];
	$txtED=$_POST["ed"];
	$txtEH=$_POST["eh"];
	
	
	if($txtSY=="--")
		$txtSY='00';
	if($txtSM=="--")
		$txtSM='00';
	if($txtSD=="--")
		$txtSD='00';
	if($txtSH=="--")
		$txtSH='00';
	
	if($txtEY=="--")
		$txtEY='9999';
	if($txtEM=="--")
		$txtEM='12';
	if($txtED=="--")
		$txtED='31';
	if($txtEH=="--")
		$txtEH='23';
	
	$rtn=array();
	$strYear=0;
	$strMonth=0;
	$strmonth=0;
	$strDay=0;
	$strHour=0;
	$strTotalAmount=0;
	$strTotalShippingFee=0;
	$strTotalQuantity=0;
	
	$c=0;
	$query="SELECT * FROM tblcompanylinkinguser
			WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	$query1="SELECT * FROM tblreceipt
			WHERE 
			CompanyId='".$row["CompanyId"]."' AND
			DateTime >= '".$txtSY."-".$txtSM."-".$txtSD." ".$txtSH.":00:00"."' AND
			DateTime <= '".$txtEY."-".$txtEM."-".$txtED." ".$txtEH.":59:59"."'
			ORDER BY DateTime DESC";
			
	$sql1=mysqli_query($mysqli,$query1);
	while ($row1 = mysqli_fetch_array($sql1, MYSQLI_BOTH)) 
	{
		$strtime=strtotime($row1["DateTime"]);
		$year = date("Y", $strtime);
		$Month = date("m", $strtime);
		$month = date("F", $strtime);
		$day = date("d", $strtime);
		$hour = date("H", $strtime);
		
		$quantity=0;
		$itemid=explode(",", $row1["ItemId"]);
		for($z=0;$z<sizeof($itemid);$z++)
		{
			$query2="SELECT * FROM tblitem
				WHERE ItemId='".$itemid[$z]."'";	
				
			$sql2=mysqli_query($mysqli,$query2);
			$row2 = mysqli_fetch_array($sql2, MYSQLI_BOTH);
			$quantity=$quantity+$row2["Quantity"];
		}
		if($c==0)
		{
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$strDay=$day;
			$strHour=$hour;
			$c++;
		}
		if($strYear!=$year or $strMonth!=$Month or $strDay!=$day or $strHour!=$hour)
		{
			$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"day"=>$strDay,"hour"=>$strHour,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
			$c++;
			$strYear=$year;
			$strMonth=$Month;
			$strmonth=$month;
			$strDay=$day;
			$strHour=$hour;
			$strTotalQuantity=$quantity;
			$strTotalAmount=$row1["TotalAmount"];
			$strTotalShippingFee=$row1["TotalShippingAmount"];
		}
		else
		{
			$strTotalQuantity=$strTotalQuantity+$quantity;
			$strTotalAmount=$strTotalAmount+$row1["TotalAmount"];
			$strTotalShippingFee=$strTotalShippingFee+$row1["TotalShippingAmount"];
		}
	}
	$rtn[$c-1]=array("year"=>$strYear,"month"=>$strmonth,"Month"=>$strMonth,"day"=>$strDay,"hour"=>$strHour,"amount"=>$strTotalAmount,"shippingfee"=>$strTotalShippingFee,"quantity"=>$strTotalQuantity);
	echo json_encode($rtn);
}




?>