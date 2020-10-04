<?php 
session_start();

include"db.php";
include"Function.php";
$post=$_POST["post"];

$rtn="";

if($post=="UpdateMSName")
{
	$name=mysqli_real_escape_string($mysqli,$_POST["name"]);
	$query="UPDATE tblindex SET Info='".$name."' WHERE Description='Title'";	
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$txt=1;
	else
		$txt=0;
		
	echo json_encode($txt);
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

else if($post=="LoadCategoryDesign")
{
	$rtn=array();
	$query="SELECT * FROM tblcategorydesign ORDER BY ID ASC ";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());	
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		$rtn[$c-1]=array("id"=>$row["ID"],"parent"=>$row["ParentID"],"sub"=>$row["SubID"]);
	}
	echo json_encode($rtn);
}
else if($post=="InsertCategoryDesign")
{
	$txt="";
	$ctgarry=explode("*.*.*.*",$_POST["ctg"]);
	for($c=0;$c<sizeof($ctgarry)-1;$c++)
	{
		if($c==0)
		{
			$parent="Master";
			$sub=$ctgarry[$c];	
		}
		else
		{
			$parent=$ctgarry[$c-1];
			$sub=$ctgarry[$c];
		}
		$query="SELECT COUNT(ID) AS total FROM tblcategorydesign WHERE ParentID='".$parent."' AND SubID='".$sub."'";
		$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
		if($row["total"]==0)
		{
			$query1="INSERT INTO tblcategorydesign(ID,ParentID,SubID) 
			VALUES(
			'cd".DateTimeForId()."','".$parent."','".$sub."'
			)";	
			$result=mysqli_query($mysqli,$query1);
			if($result==true)
				$txt=1;
			else
				$txt=0;
		}
	}
	echo json_encode($txt);
}

else if($post=="UpdateCategoryMenu")
{
	
	$code=mysqli_real_escape_string($mysqli,$_POST["code"]);
	$rtn="";
	
	$query="UPDATE tblindex SET Info='".$code."' WHERE Description='MainMenu'";
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$rtn=1;
	else
		$rtn=2;
	
	echo json_encode($rtn);

}


else if($post=="LoadReport")
{
	$txt="";
	$rtn=array();
	$strYear=0;
	$strTotalAmount=0;
	$strTotalShippingFee=0;
	$strTotalQuantity=0;
	
	$c=0;
	
	$companyId=$_POST["company"];
	$SearchCompany="";
	if($companyId!="--")
	{
		$SearchCompany="WHERE CompanyId='".$companyId."' ";
	}		
	$query1="SELECT * FROM tblreceipt
			".$SearchCompany."
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
$txt=$txt."~".$strYear."~".$strTotalAmount."~".$strTotalShippingFee."~".$strTotalQuantity."\n";
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
	
	$companyId=$_POST["company"];
	$SearchCompany="";
	if($companyId!="--")
	{
		$SearchCompany="CompanyId='".$companyId."' AND";
	}
	$query1="SELECT * FROM tblreceipt
			WHERE 
			".$SearchCompany."
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
	
	$companyId=$_POST["company"];
	$SearchCompany="";
	if($companyId!="--")
	{
		$SearchCompany="CompanyId='".$companyId."' AND";
	}
	$query1="SELECT * FROM tblreceipt
			WHERE 
			".$SearchCompany."
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
	
	
	$companyId=$_POST["company"];
	$SearchCompany="";
	if($companyId!="--")
	{
		$SearchCompany="CompanyId='".$companyId."' AND";
	}
	$query1="SELECT * FROM tblreceipt
			WHERE 
			".$SearchCompany."
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
	
	$companyId=$_POST["company"];
	$SearchCompany="";
	if($companyId!="--")
	{
		$SearchCompany="CompanyId='".$companyId."' AND";
	}
	$query1="SELECT * FROM tblreceipt
			WHERE 
			".$SearchCompany."
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
else if($post=="LoadCompanyList")
{
	$rtn=array();
	$c=0;
	$query="SELECT * FROM tblcompanyinfo";
	$sql=mysqli_query($mysqli,$query);
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$rtn[$c]=array("companyid"=>$row["CompanyId"],"companyname"=>$row["CompanyName"]);
		$c++;
	}
	echo json_encode($rtn);
}




?>