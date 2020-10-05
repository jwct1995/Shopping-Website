<?php 
session_start();

include"db.php";
include"Function.php";
$post=$_POST["post"];
$rowofpage=8;
$rtn="";


$queryP="SELECT * FROM tblpromotion WHERE PromotionEndDate<NOW()";	
$sqlP=mysqli_query($mysqli,$queryP)or die(mysql_error());
while ($rowP = mysqli_fetch_array($sqlP, MYSQLI_BOTH)) 
{
	$queryX="UPDATE tblproductinfo SET PromotionID='' WHERE PromotionID LIKE'%".$rowP["PromotionId"]."%'";	
	$sqlX=mysqli_query($mysqli,$queryX)or die(mysql_error());
}




if($post=="LoadTitle")
{
	$query="SELECT * FROM tblindex WHERE Description='Title'";	
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	echo json_encode($row["Info"]);
}
else if($post=="CheckType")
{
	if($_SESSION["userid"]=="u000000000")
		$rtn=1;
	else
		$rtn=2;
	echo json_encode($rtn);
}
else if($post=="CheckLogin")
{
	$query="SELECT * FROM tbluserloginacc
	WHERE UserId='".$_SESSION["userid"]."' AND UserTF='T'";	
	$sql=mysqli_query($mysqli,$query);
	$result = mysqli_num_rows($sql);
	if($result==1)
		$rtn=1;
	else
		$rtn=2;
	echo json_encode($rtn);
}

else if($post=="CheckCompany")
{
	$query="SELECT * FROM tblcompanylinkinguser
	WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	$result = mysqli_num_rows($sql);
	if($result==1)
	{
		$rtn=1;
		$_SESSION["companyid"] = $row["CompanyId"];
	}
	else
		$rtn=2;
	echo json_encode($rtn);
}

else if($post=="Login")
{
	$username=mysqli_real_escape_string($mysqli,trim($_POST["username"]));
	$password=md5(mysqli_real_escape_string($mysqli,trim($_POST["password"])));

	$query="SELECT * FROM tbluserloginacc
	WHERE UserName='".$username."' AND UserPsw='".$password."' AND UserTF='T'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	$result = mysqli_num_rows($sql);
	if($result==1)
	{
		$_SESSION["userid"] = $row["UserId"];
		$rtn=1;
	}
	else
		$rtn=2;
	
	echo json_encode($rtn);
}
else if($post=="Logout")
{  
	session_destroy(); 
	$rtn=1;
	echo json_encode($rtn);
}

//menu 
else if($post=="LoadMainMenu")
{
	$query="SELECT * FROM tblindex WHERE Description='MainMenu'";
		
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	echo json_encode($row["Info"]);
}
//reg Account
else if($post=="RegisterAccount")
{
	$username=mysqli_real_escape_string($mysqli,trim($_POST["username"]));
	$password=md5(mysqli_real_escape_string($mysqli,trim($_POST["password"])));
	$firstname=mysqli_real_escape_string($mysqli,trim($_POST["firstname"]));
	$lastname=mysqli_real_escape_string($mysqli,trim($_POST["lastname"]));
	$email=mysqli_real_escape_string($mysqli,trim($_POST["email"]));
	$gender=mysqli_real_escape_string($mysqli,trim($_POST["gender"]));
	$dob=mysqli_real_escape_string($mysqli,trim($_POST["dob"]));
	$country=mysqli_real_escape_string($mysqli,trim($_POST["country"]));
	$state=mysqli_real_escape_string($mysqli,trim($_POST["state"]));
	$city=mysqli_real_escape_string($mysqli,trim($_POST["city"]));
	$postcode=mysqli_real_escape_string($mysqli,trim($_POST["postcode"]));
	$address=mysqli_real_escape_string($mysqli,trim($_POST["address"]));
	$phonenumber=mysqli_real_escape_string($mysqli,trim($_POST["phonenumber"]));
	
	$id=DateTimeForId();
	
	
	$query="SELECT * FROM tbluserloginacc WHERE UserName='".$username."'";	
	$sql=mysqli_query($mysqli,$query);
	$result = mysqli_num_rows($sql);
	if($result==0)
	{
		$query="SELECT * FROM tbluseraccinfo WHERE UserEmail='".$email."'";	
		$sql=mysqli_query($mysqli,$query);
		$result = mysqli_num_rows($sql);
		if($result==0)
		{
			$query="INSERT INTO tbluserloginacc (UserId,UserName,UserPsw,UserTF,UserType) 
			VALUES
			('u".$id."','".$username."','".$password."','T','1')";	
			$result=mysqli_query($mysqli,$query);
			if($result==true)
			{
				$query="INSERT INTO tbluseraccinfo (
					UserId,UserFirstName,UserLastName,UserEmail,UserGender,UserDOB,
					UserCountry,UserState,UserCity,UserPostCode,UserAddress,UserPhoneNumber) 
				VALUES
				('u".$id."','".$firstname."','".$lastname."','".$email."','".$gender."','".$dob."','".$country."','".$state."','".$city."','".$postcode."','".$address."','".$phonenumber."')";	
				$result=mysqli_query($mysqli,$query);
				if($result==true)
					$rtn=1;
				else
					$rtn=2;
			}
			else
				$rtn=2;
		}
		else
			$rtn=2;
	}
	else
		$rtn=2;
		
	echo json_encode($rtn);
}
//load account detail
else if($post=="LoadAccountDetail")
{
	$query="SELECT * FROM tbluserloginacc
	WHERE UserId='".$_SESSION["userid"]."' AND UserTF='T'";	
	$sql=mysqli_query($mysqli,$query);
	$result = mysqli_num_rows($sql);
	if($result==1)
	{
		$query="SELECT * FROM tbluseraccinfo
		WHERE UserId='".$_SESSION["userid"]."'";	
		$sql=mysqli_query($mysqli,$query);
		$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
		$rtn=array("fname"=>$row["UserFirstName"],"lname"=>$row["UserLastName"],"email"=>$row["UserEmail"],"gender"=>$row["UserGender"],"dob"=>$row["UserDOB"],"country"=>$row["UserCountry"],"state"=>$row["UserState"],"city"=>$row["UserCity"],"postcode"=>$row["UserPostCode"],"address"=>$row["UserAddress"],"phonenumber"=>$row["UserPhoneNumber"]);
	}
	else
	$rtn=2;
	echo json_encode($rtn);
}
//update account 
else if($post=="UpdateAccountDetail")
{
	$firstname=mysqli_real_escape_string($mysqli,trim($_POST["firstname"]));
	$lastname=mysqli_real_escape_string($mysqli,trim($_POST["lastname"]));
	$country=mysqli_real_escape_string($mysqli,trim($_POST["country"]));
	$state=mysqli_real_escape_string($mysqli,trim($_POST["state"]));
	$city=mysqli_real_escape_string($mysqli,trim($_POST["city"]));
	$postcode=mysqli_real_escape_string($mysqli,trim($_POST["postcode"]));
	$address=mysqli_real_escape_string($mysqli,trim($_POST["address"]));
	$phonenumber=mysqli_real_escape_string($mysqli,trim($_POST["phonenumber"]));
	
	$id=DateTimeForId();
	
	$query="SELECT * FROM tbluserloginacc
	WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	$tf=$row["UserTF"];
	
	$query="SELECT * FROM tbluseraccinfo
			WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	$query="INSERT INTO tbluseraccinforecord (
			UserEditRecordId,UserId,UserFirstName,UserLastName,UserEmail,UserGender,UserDOB,
			UserCountry,UserState,UserCity,UserPostCode,UserAddress,UserPhoneNumber,UserPswAndTF) 
			VALUES
			('ur".$id."','".$row["UserId"]."','".$row["UserFirstName"]."','".$row["UserLastName"]."','".$row["UserEmail"]."','".$row["UserGender"]."','".$row["UserDOB"]."','".$row["UserCountry"]."','".$row["UserState"]."','".$row["UserCity"]."','".$row["UserPostCode"]."','".$row["UserAddress"]."','".$row["UserPhoneNumber"]."','PasswordNoChange-".$tf."')";	
	$result=mysqli_query($mysqli,$query);
	
	$query="UPDATE tbluseraccinfo SET 
	
	UserFirstName='".$firstname."',UserLastName='".$lastname."',UserCountry='".$country."',UserState='".$state."',UserCity='".$city."',UserPostCode='".$postcode."',UserAddress='".$address."',UserPhoneNumber='".$phonenumber."' WHERE UserId='".$_SESSION["userid"]."'";
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$rtn=1;
	else
	{
		$rtn=2;
		$query="DELETE FROM tbluseraccinforecord WHERE UserEditRecordId='ur".$id."'";
		$result=mysqli_query($mysqli,$query);
	}
	
	echo json_encode($rtn);
}

else if($post=="UpdateAccountPassword")
{
	$oldpsw=md5(mysqli_real_escape_string($mysqli,trim($_POST["oldpsw"])));
	$newpsw=md5(mysqli_real_escape_string($mysqli,trim($_POST["newpsw"])));
	
	$id=DateTimeForId();
	
	$query="SELECT * FROM tbluserloginacc
	WHERE UserId='".$_SESSION["userid"]."'";	
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	$tf=$row["UserTF"];
	
	if($row["UserPsw"]==$oldpsw)
	{
		$query="SELECT * FROM tbluseraccinfo
				WHERE UserId='".$_SESSION["userid"]."'";	
		$sql=mysqli_query($mysqli,$query);
		$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
		
		$query="INSERT INTO tbluseraccinforecord (
				UserEditRecordId,UserId,UserFirstName,UserLastName,UserEmail,UserGender,UserDOB,
				UserCountry,UserState,UserCity,UserPostCode,UserAddress,UserPhoneNumber,UserPswAndTF) 
				VALUES
				('ur".$id."','".$row["UserId"]."','".$row["UserFirstName"]."','".$row["UserLastName"]."','".$row["UserEmail"]."','".$row["UserGender"]."','".$row["UserDOB"]."','".$row["UserCountry"]."','".$row["UserState"]."','".$row["UserCity"]."','".$row["UserPostCode"]."','".$row["UserAddress"]."','".$row["UserPhoneNumber"]."','PasswordChange-".$tf."')";	
		$result=mysqli_query($mysqli,$query);
		
		$query="UPDATE tbluserloginacc SET UserPsw='".$newpsw."' WHERE UserId='".$_SESSION["userid"]."'";
		$result=mysqli_query($mysqli,$query);
		if($result==true)
			$rtn=1;
		else
		{
			$rtn=2;
			$query="DELETE FROM tbluseraccinforecord WHERE UserEditRecordId='ur".$id."'";
			$result=mysqli_query($mysqli,$query);
		}
	}
	echo json_encode($rtn);
}
// product

else if($post=="LoadProduct")
{
	$rtn=array();
	
	$count=0;
	$numberOfRow=0;
	$search="";
	
	if(trim($_POST["searchkey"])!= null)
	{
		
		$key=trim(strtolower($_POST["searchkey"]));
		$rtnarray=array();
		$c=0;
		$txt="";
		
		$query="SELECT CategoryElementID FROM tblcategoryelement WHERE CategoryElementName LIKE'%".$key."%'";
		$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
		{
			$rtnarray[$c]=$row["CategoryElementID"];
			$c++;
		}
		for($z=0;$z<sizeof($rtnarray);$z++)
		{
			$txt="LOWER(tblproductinfo.Category) LIKE'%".$rtnarray[$z]."%' OR";
		}
		
		
		
		$search="AND ( 
		".$txt."
		LOWER(tblproductinfo.Category) LIKE'%".$key."%' OR
		LOWER(tblproductinfo.ProductId) LIKE'%".$key."%' OR 
		LOWER(ProductName) LIKE'%".$key."%'OR 
		LOWER(ProductDesc) LIKE'%".$key."%' 
		)";
	}
	
	$pageNumber=($_POST["no"]-1)*$rowofpage;
	
	$query="SELECT 	
	tblproductinfo.ProductId as productid, 
	tblproductinfo.UserId as userid, 
	tblproductinfo.CompanyId as companyid, 
	tblproductinfo.PromotionID as promotionid, 
	tblproductinfo.ProductName as pname, 
	tblproductinfo.ProductDesc as pdesc, 
	tblproductinfo.ProductImage as pimg, 
	tblproductinfo.ProductWeight as pweight, 
	tblproductinfo.Category as pcategory,
	tblproductquantity.Quantity as quantity, 
	tblproductprice.Price as price
	
	FROM tblproductinfo , tblproductquantity , tblproductprice
	WHERE 
	tblproductinfo.ProductId = tblproductquantity.ProductId AND
	tblproductinfo.ProductId = tblproductprice.ProductId AND
	Quantity <> 0 
	".$search."  
	LIMIT ".$rowofpage." OFFSET ".$pageNumber;
	
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$count++;
		$rtn[$count-1]=array(
		"ProductId"=>$row["productid"],
		"CompanyId"=>$row["companyid"],
		"PromotionId"=>$row["promotionid"],
		"ProductName"=>$row["pname"],
		"ProductDesc"=>$row["pdesc"],
		"ProductImage"=>$row["pimg"],
		"ProductWeight"=>$row["pweight"],
		"CategoryId"=>$row["pcategory"],
		"Quantity"=>$row["quantity"],
		"Price"=>$row["price"]);
	}
	
	echo json_encode($rtn);
	//echo json_encode($query);	
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

else if($post=="LoadPromotion")
{
	$rtn=array();
	$query="SELECT * FROM tblpromotion WHERE DATE(PromotionEndDate) > CURDATE()  ORDER BY PromotionName ASC ";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		$rtn[$c-1]=array("id"=>$row["PromotionId"],"name"=>$row["PromotionName"],"desc"=>$row["PromotionDesc"],"rate"=>$row["PromotionRate"],"price"=>$row["PromotionPrice"],"sdate"=>$row["PromotionStartDate"],"edate"=>$row["PromotionEndDate"]);
	}
	echo json_encode($rtn);
}
else if($post=="LoadPageNum")
{
	$search="";
	
	if(trim($_POST["searchkey"])!= null)
	{
		
		$key=trim(strtolower($_POST["searchkey"]));
		$rtnarray=array();
		$c=0;
		$txt="";
		
		$query="SELECT CategoryElementID FROM tblcategoryelement WHERE CategoryElementName LIKE'%".$key."%'";
		$sql=mysqli_query($mysqli,$query)or die(mysql_error());
		while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
		{
			$rtnarray[$c]=$row["CategoryElementID"];
			$c++;
		}
		for($z=0;$z<sizeof($rtnarray);$z++)
		{
			$txt="LOWER(tblproductinfo.Category) LIKE'%".$rtnarray[$z]."%' OR";
		}

		$search="AND ( 
		".$txt."
		LOWER(tblproductinfo.Category) LIKE'%".$key."%' OR
		LOWER(tblproductinfo.ProductId) LIKE'%".$key."%' OR 
		LOWER(ProductName) LIKE'%".$key."%'OR 
		LOWER(ProductDesc) LIKE'%".$key."%' 
		)";
	}
	
	$pageNumber=($_POST["no"]-1)*$rowofpage;
	
	$query="SELECT COUNT(tblproductinfo.ProductId) AS total		
	FROM tblproductinfo , tblproductquantity , tblproductprice
	WHERE 
	tblproductinfo.ProductId = tblproductquantity.ProductId AND
	tblproductinfo.ProductId = tblproductprice.ProductId AND
	Quantity <> 0 
	".$search;
	
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	echo json_encode(ceil($row["total"]/$rowofpage));
}

else if($post=="GetCompanyName")
{
	$id=$_POST["id"];
	$query="SELECT * FROM tblcompanyinfo WHERE CompanyId='".$id."'";
	
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	
	echo json_encode($row["CompanyName"]);
	
}
else if($post=="InsertCart" && $_SESSION["userid"])
{
	$pid=$_POST["id"];
	$quantity=$_POST["quantity"];
	$id=DateTimeForId();

	$query="SELECT * FROM tblcart WHERE UserId='".$_SESSION["userid"]."' AND ProductId='".$pid."'";
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	$result = mysqli_num_rows($sql);
	if($result==1)
	{
		$query="UPDATE tblcart SET Quantity='".$quantity."' WHERE CartId='".$row["CartId"]."'";
		$result=mysqli_query($mysqli,$query);
		if($result==true)
			$rtn=3;
		else
			$rtn=2;		
	}
	else
	{	
		$query="INSERT INTO tblcart(CartId, UserId, ProductId, Quantity) VALUES ('cart".$id."','".$_SESSION["userid"]."','".$pid."','".$quantity."')";	
		$result=mysqli_query($mysqli,$query);
		if($result==true)
			$rtn=1;
		else
			$rtn=2;
	}
	echo json_encode($rtn);
	
}
else if($post=="InsertWishlist" && $_SESSION["userid"])
{
	$pid=$_POST["id"];
	$id=DateTimeForId();
	
	
	$query="SELECT * FROM tblwishlist WHERE UserId='".$_SESSION["userid"]."' AND ProductId='".$pid."'";
	$sql=mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
	$result = mysqli_num_rows($sql);
	if($result==1)
	{
		$rtn=2;		
	}
	else
	{
		$query="INSERT INTO tblwishlist(WishlistId, UserId, ProductId) VALUES ('w".$id."','".$_SESSION["userid"]."','".$pid."')";	
		$result=mysqli_query($mysqli,$query);
		if($result==true)
			$rtn=1;
		else
			$rtn=2;
	}
	echo json_encode($rtn);
}

else if($post=="InsertComment" && $_SESSION["userid"])
{
	$pid=$_POST["id"];
	$txt=mysqli_real_escape_string($mysqli,trim($_POST["txt"]));
	
	$id=DateTimeForId();
	
	$query="INSERT INTO tblcomment(CommentId, UserId, ProductId, CommentText) VALUES ('cmt".$id."','".$_SESSION["userid"]."','".$pid."','".$txt."')";	
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		$rtn=1;
	else
		$rtn=2;
	echo json_encode($rtn);
}

else if($post=="GetComment")
{
	$rtn=array();
	$c=0;
	$id=$_POST["id"];
	$query="SELECT * FROM tblcomment WHERE ProductId='".$id."' ORDER BY CommentId DESC LIMIT 10";
	
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) 
	{
		$c++;
		$rtn[$c-1]=array("userid"=>$row["UserId"],"comment"=>$row["CommentText"]);
	}
	echo json_encode($rtn);
}
else if($post=="GetAccName")
{
	$rtn=array();
	$c=0;
	$id=$_POST["id"];
	$query="SELECT * FROM tbluseraccinfo WHERE UserId='".$id."'";
	$sql=mysqli_query($mysqli,$query)or die(mysql_error());
	$row = mysqli_fetch_array($sql, MYSQLI_BOTH);

	echo json_encode($row["UserFirstName"]);
}

?>