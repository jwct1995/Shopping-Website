<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Page</title>
</head>
<link href="css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery-3.1.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="js.js" charset="utf-8"></script>
<?php 
	include"basic.html";
?>

<script>
var PaymentListArray=new Array();
var PaymentDetailArray=new Array();
$(function()
{
	var rtn=AjaxSubmitCheckLogin();
	if(rtn==2)
		window.location.replace("index.php");
});
$(document).ready(function()
{
	$("[name='txt']").keyup(function()
	{
		CheckTxt($(this));
	});
	
	$("#btnUserUpdPasswordSubmit").click(function()
	{
		AjaxSubmitUpdateUserAccountPassword();
	});
	$("#btnUserUpdPasswordReset").click(function()
	{
		$("[name='txt']").val("");
	});
	
	$("#btnUserUpdDetailSubmit").click(function()
	{
		AjaxSubmitUpdateUserAccountDetail();
	});
	$("#btnUserUpdDetailReset").click(function()
	{
		AjaxLoadUserAccountDetail();
	});	
	
	$("#aUserAccountEditDetail").click(function()
	{
		AjaxLoadUserAccountDetail();
		$("#DivUpdUserDetail").css("display","block");
		$("#DivUpdUserPassword").css("display","none");
	});
	$("#aUserAccountEditPassword").click(function()
	{
		$("#DivUpdUserDetail").css("display","none");
		$("#DivUpdUserPassword").css("display","block");
	});
});
function AjaxSubmitUpdateUserAccountPassword()
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{	post:"UpdateAccountPassword",
				oldpsw:$("#txtUserOldPassword").val(),
				newpsw:$("#txtUserNewPassword1").val(),
		},
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
            if(data==1)
			{
				alert("Update Password success");
				$("[name='txt']").val("");
			}
			else if(data==2)
			alert("Update Password Fail");
        },
        error: function () 
        {
            alert("Error! Update Password");
        },
    });
}
function AjaxLoadUserAccountDetail()
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{	post:"LoadAccountDetail"},//
        async:false,
        beforeSend: function() 
        {},
        complete:function()
        {},
        success: function (data) 
        {
			if(data==2)
				alert("Load Account Detail Fail");
            else
			{
				$("#txtUserUpdFirstName").val(data.fname);
				$("#txtUserUpdLastName").val(data.lname);
				$("#txtUserUpdEmail").val(data.email);
				$("#txtUserUpdEmail").prop( "disabled", true );
				$("[name='rdGender'][value='"+data.gender+"']").attr('checked', true);
				$("[name='rdGender']").prop( "disabled", true );
				$("#txtUserUpdDOB").val(data.dob);
				$("#txtUserUpdDOB").prop( "disabled", true );
				$("#slcUpdCountry").val(data.country);
				$("#txtUserUpdState").val(data.state);
				$("#txtUserUpdCity").val(data.city);
				$("#txtUserUpdPostCode").val(data.postcode);
				$("#txtUserUpdAddress").val(data.address);
				$("#txtUserUpdPhoneNumber").val(data.phonenumber);
			}
        },
        error: function () 
        {
            alert("Error! Load Account Detail");
        },
    });
}
function AjaxSubmitUpdateUserAccountDetail()
{
	$.ajax(
    {
        type:"POST",
        dataType:"json",
        url:"UserFunction.php", 
        data:{	post:"UpdateAccountDetail",
				firstname:$("#txtUserUpdFirstName").val(),
				lastname:$("#txtUserUpdLastName").val(),
				country:$("#slcUpdCountry option:selected").val(),
				state:$("#txtUserUpdState").val(),
				city:$("#txtUserUpdCity").val(),
				postcode:$("#txtUserUpdPostCode").val(),
				address:$("#txtUserUpdAddress").val(),
				phonenumber:$("#txtUserUpdPhoneNumber").val()
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
				alert("Update success");
			}
			else if(data==2)
			alert("Update Fail");
        },
        error: function () 
        {
            alert("Error! Update");
        },
    });
}

</script>
<body>
<div id="mainmenu">
	<ul id="mainmenu">
    	<li><a id="aUserAccountEditDetail">Edit Detail</a></li>
        <li><a id="aUserAccountEditPassword">Edit Password</a></li>
        
    </ul>
</div>
<div align="center">
    <div id="DivUpdUserDetail" style="display:none;">
        <table id="tableUpd">
            <tr>
                <td>FirstName : </td>
                <td><input id="txtUserUpdFirstName" name="txt" min="5" errorms="min 5 character" type="text"></td>
            </tr>
            <tr>
                <td>LastName : </td>
                <td><input id="txtUserUpdLastName" name="txt" min="5" errorms="min 5 character" type="text"></td>
            </tr>
            <tr>
                <td>Email : </td>
                <td><input id="txtUserUpdEmail" name="txt" min="8" errorms="min 8 character" type="text"></td>
            </tr>
            <tr>
                <td>Gender : </td>
                <td>
                    <label>Male<input id="rdmale" name="rdGender" type="radio" value="male" checked="checked"></label>
                    <label>Female<input id="rdfemale" name="rdGender" type="radio" value="female"></label>
                </td>
            </tr>
            <tr>
                <td>DateOfBirth : </td>
                <td><input id="txtUserUpdDOB" name="txt" min="10" errorms="exp:02/28/1990" type="date"></td>
            </tr>
            <tr>
                <td>Country : </td>
                <td>
                    <select id="slcUpdCountry" name="slc">
                        <script language="JavaScript" src="countries.js"></script>
                    </select>
                </td>
            </tr>
            <tr>
                <td>State : </td>
                <td><input id="txtUserUpdState" name="txt" min="3" errorms="min 3 character" type="text"></td>
            </tr>
            <tr>
                <td>City : </td>
                <td><input id="txtUserUpdCity" name="txt" min="3" errorms="min 3 character" type="text"></td>
            </tr>
            <tr>
                <td>PostCode : </td>
                <td><input id="txtUserUpdPostCode" name="txt" min="3" errorms="min 3 character" type="text"></td>
            </tr>
            <tr>
                <td>Address : </td>
                <td>
                    <textarea id="txtUserUpdAddress" name="txt" min="8" errorms="min 8 character"cols="" rows=""></textarea>
                </td>
            </tr>
            <tr>
                <td>PhoneNumber : </td>
                <td><input id="txtUserUpdPhoneNumber" name="txt" min="8" errorms="min 8 character" type="text"></td>
            </tr>
    
    
            <tr id="trBtn">
                <td colspan="99">
                    <input id="btnUserUpdDetailSubmit" name="btn" type="button" value="Update" style="padding: 5px;">
                    <input id="btnUserUpdDetailReset" name="btn" type="button" value="Reset" style="padding: 5px;">
                </td>
            </tr>
        </table>
    </div>
    <div id="DivUpdUserPassword" style="display:none;">
    <table id="tableUpd">
    	<tr>
        	<td>Old Password</td>
            <td><input id="txtUserOldPassword" name="txt" min="8" errorms="min 8 character" type="password"></td>
        </tr>
        <tr>
        	<td>New Password</td>
            <td><input id="txtUserNewPassword1" name="txt" min="8" errorms="min 8 character" type="password"></td>
        </tr>
        <tr>
        	<td>Retype New Password</td>
            <td><input id="txtUserNewPassword2" name="txt" min="8" errorms="min 8 character" type="password"></td>
        </tr>
        <tr id="trBtn">
                <td colspan="99">
                    <input id="btnUserUpdPasswordSubmit" name="btn" type="button" value="Update" style="padding: 5px;">
                    <input id="btnUserUpdPasswordReset" name="btn" type="button" value="Reset" style="padding: 5px;">
                </td>
            </tr>
    </table>
    </div>
    
</div>
</body>
</html>