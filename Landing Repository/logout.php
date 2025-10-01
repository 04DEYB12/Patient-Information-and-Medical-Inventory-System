<?php 

session_start();

if(isset($_SESSION['User_ID']))
{
	unset($_SESSION['User_ID']);
	unset($_SESSION['role']);
}

header("Location: Loginpage.php");
die;	