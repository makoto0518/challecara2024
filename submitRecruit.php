<?php
include "mylib.php";
if(!isset($_SESSION)){
    session_start();
}

if(isset($_POST["recruitTitle"]) && isset($_POST["role"]) && isset($_POST["location"]) && isset($_POST["capacity"]) && isset($_POST["recruitContent"])){
    setRecruiting($_SESSION['userId'], $_POST["recruitTitle"], $_POST["role"], $_POST["location"], 
    $_POST["capacity"], $_POST['recruitContent']);
    createChatRoom("recruit", getRecruitNum($_SESSION['userId']));
}

header("Location: index.php");
exit();
?>
