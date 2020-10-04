<?php

$Host = "localhost";
$User = "129751";
$Pass = "soo12345";
$dbName = "129751";

$conn=new mysqli($Host,$User,$Pass);
if($conn->connect_error)
	die("Connection Failed : ".$conn->connect_error."<br>");

$sql="CREATE DATABASE ".$dbName;
if($conn->query($sql)===TRUE)
	echo"Database Create Success<br>";
else
	echo"Database Create Fail".$conn->error."<br>";

$conn=new mysqli($Host,$User,$Pass,$dbName);
if($conn->connect_error)
	die("Connection Failed : ".$conn->connect_error."<br>");
	
	$sql=array(
	
	"CREATE TABLE tbluserloginacc(
	UserId VARCHAR(25) PRIMARY KEY,
	UserName VARCHAR(20),
	UserPsw VARCHAR(32),
	UserTF VARCHAR(2),
	UserType VARCHAR(2)
	)"
	,

	"CREATE TABLE tbluseraccinfo(
	UserId VARCHAR(25) PRIMARY KEY,
	UserFirstName VARCHAR(30),
	UserLastName VARCHAR(30),
	UserEmail VARCHAR(50),
	UserGender VARCHAR(10),
	UserDOB DATE,
	UserCountry VARCHAR(20),
	UserState VARCHAR(20),
	UserCity VARCHAR(20),
	UserPostCode VARCHAR(10),
	UserAddress VARCHAR(1000),
	UserPhoneNumber VARCHAR(20)
	)"
	,

	"CREATE TABLE tbluseraccinforecord(
	UserEditRecordId VARCHAR(25),
	UserId VARCHAR(25),
	UserFirstName VARCHAR(30),
	UserLastName VARCHAR(30),
	UserEmail VARCHAR(50),
	UserGender VARCHAR(10),
	UserDOB DATE,
	UserCountry VARCHAR(20),
	UserState VARCHAR(20),
	UserCity VARCHAR(20),
	UserPostCode VARCHAR(10),
	UserAddress VARCHAR(1000),
	UserPhoneNumber VARCHAR(20),
	UserPswAndTF VARCHAR(1000)
	)"
	,

	"CREATE TABLE tblcompanyinfo(
	CompanyId VARCHAR(25) PRIMARY KEY,
	CompanyName VARCHAR(100),
	CompanyCountry VARCHAR(20),
	CompanyState VARCHAR(20),
	CompanyCity VARCHAR(20),
	CompanyPostCode VARCHAR(10),
	CompanyAddress VARCHAR(1000),
	CompanyPhoneNumber VARCHAR(20),
	CompanyDesc VARCHAR(1000)
	)"
	,

	"CREATE TABLE tblcompanyinforecord(
	CompanyEditRecordId VARCHAR(25),
	CompanyId VARCHAR(25),
	CompanyName VARCHAR(100),
	CompanyCountry VARCHAR(20),
	CompanyState VARCHAR(20),
	CompanyCity VARCHAR(20),
	CompanyPostCode VARCHAR(10),
	CompanyAddress VARCHAR(1000),
	CompanyPhoneNumber VARCHAR(20),
	CompanyDesc VARCHAR(1000)
	)"
	,

	"CREATE TABLE tblcompanylinkinguser(
	CompanyLinkUserId VARCHAR(25) PRIMARY KEY,
	CompanyId VARCHAR(25),
	UserId VARCHAR(25),
	LinkType VARCHAR(2)
	)"
	,
	
	"CREATE TABLE tblproductinfo(
	ProductId VARCHAR(25) PRIMARY KEY,
	UserId VARCHAR(25),
	CompanyId VARCHAR(25),
	PromotionID VARCHAR(25),
	ProductName VARCHAR(1000),
	ProductDesc VARCHAR(1000),
	ProductImage VARCHAR(1000),
	ProductWeight DOUBLE,
	Category VARCHAR(1000)
	)"
	,

	"CREATE TABLE tblproductquantity( 
	ProductId VARCHAR(25) PRIMARY KEY,
	Quantity INT(10)
	)"
	,

	"CREATE TABLE tblproductprice(
	ProductId VARCHAR(25) PRIMARY KEY,
	Price DOUBLE
	)"
	,

	"CREATE TABLE tblcomment(
	CommentId VARCHAR(25) PRIMARY KEY,
	UserId VARCHAR(25),
	ProductId VARCHAR(25),
	CommentText VARCHAR(100)
	)"
	,

	"CREATE TABLE tblpromotion(
	PromotionId VARCHAR(25) PRIMARY KEY,
	UserId VARCHAR(25),
	CompanyId VARCHAR(25),
	PromotionName VARCHAR(100),
	PromotionDesc VARCHAR(1000),
	PromotionRate DOUBLE,
	PromotionPrice DOUBLE,
	PromotionStartDate DATE,
	PromotionEndDate DATE
	)"
	,

	"CREATE TABLE tblcart(
	CartId VARCHAR(25) PRIMARY KEY,
	UserId VARCHAR(25),
	ProductId VARCHAR(25),
	Quantity INT(10)
	)"
	,

	"CREATE TABLE tblwishlist(
	WishlistId VARCHAR(25) PRIMARY KEY,
	UserId VARCHAR(25),
	ProductId VARCHAR(25)
	)"
	,

	"CREATE TABLE tblcategoryelement
	(
		CategoryElementID VARCHAR(25) PRIMARY KEY,
		CategoryElementName VARCHAR(50),
		CategoryElementDescription VARCHAR(1000)
	)",
	"CREATE TABLE tblcategorydesign
	(
		ID VARCHAR(25) PRIMARY KEY,
		ParentID VARCHAR(25),
		SubID VARCHAR(25)
	)",
	"CREATE TABLE tblindex
	(
		ID VARCHAR(20) PRIMARY KEY,
		Description VARCHAR(1000),
		Info LONGTEXT
	)",
	
	"CREATE TABLE tblpayment
	(
		PaymentId VARCHAR(25) PRIMARY KEY,
		UserId VARCHAR(25),
		ReceiptId VARCHAR(1000),
		FinalAmount DOUBLE
	)",
	"CREATE TABLE tblreceipt
	(
		ReceiptId VARCHAR(25) PRIMARY KEY,
		UserId VARCHAR(25),
		CompanyId VARCHAR(25),
		ItemId VARCHAR(1000),
		TotalAmount DOUBLE,
		TotalShippingAmount DOUBLE,
		DateTime DATETIME
		
	)",
	"CREATE TABLE tblitem
	(
		ItemId VARCHAR(25) PRIMARY KEY,
		ProductId VARCHAR(25),
		Quantity INT(10),
		Prices DOUBLE,
		PromotionID VARCHAR(25),
		PromotionDesc VARCHAR(1000),
		ShippingInfo VARCHAR(1000),
		ShippingFee DOUBLE
	)"

	);
foreach($sql as $s)
if($conn->query($s)===TRUE)
	echo"Table Create Success<br>";
else
	echo"Table ".$s."Create Fail".$conn->error."<br>";
	
$mysqli=new mysqli($Host,$User,$Pass,$dbName);
if($mysqli->connect_error)
	die("Connection Failed : ".$mysqli->connect_error."<br>");


$query="INSERT INTO tbluserloginacc(UserId, UserName, UserPsw, UserTF, UserType) VALUES ('u000000000','admin','".md5("passwordadmin")."','T','0')";
mysqli_query($mysqli,$query);
$query="INSERT INTO tbluseraccinfo(UserId, UserFirstName, UserLastName, UserEmail, UserGender, UserDOB, UserCountry, UserState, UserCity, UserPostCode, UserAddress, UserPhoneNumber) VALUES ('u000000000','Admin','System','admin@admin.com','Male','2018-01-01','Malaysia','admin','admin','admin','admin','admin')";
mysqli_query($mysqli,$query);
	
$query="SELECT COUNT(ID) AS total FROM tblindex WHERE Description='MainMenu'";
$sql=mysqli_query($mysqli,$query)or die(mysql_error());
$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
if($row["total"]==0)
{
	$query="INSERT INTO tblindex(ID,Description,Info) VALUES('c'+NOW(),'MainMenu','<ul><li><a>MainMenuHere</a></li><li><a>li 1</a></li><li><a>li 2</a></li></ul>')";	
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		echo"Main Menu Create Success<br>";
	else
		echo"Main Menu Create Fail<br>";
}
else
	echo"Main Menu is Created ,cant create default<br>";

$query="SELECT COUNT(ID) AS total FROM tblindex WHERE Description='Title'";
$sql=mysqli_query($mysqli,$query)or die(mysql_error());
$row = mysqli_fetch_array($sql, MYSQLI_BOTH);
if($row["total"]==0)
{
	$query="INSERT INTO tblindex(ID, Description, Info) VALUES ('title','Title','Online Selling Management System')";	
	$result=mysqli_query($mysqli,$query);
	if($result==true)
		echo"Title Create Success<br>";
	else
		echo"Title Create Fail<br>";
}
else
	echo"Title is Created ,cant create default<br>";	
	

?>