<?php 
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

echo "<pre>" . print_r($_POST, true) . "</pre>";

if (isset($_POST["hackasonName"]) && isset($_POST["jamLocation"]) && isset($_POST["maxCapacity"]) && isset($_POST["minCapacity"]) && isset($_POST["endDate"]) && isset($_POST["jamPassword"])) {
    if (setJam($_POST["hackasonName"], $_POST["jamLocation"], $_SESSION['userId'], 
    $_POST["maxCapacity"], $_POST["minCapacity"], $_POST["endDate"], $_POST["jamPassword"])) {
        echo "ジャム投稿成功";
        header("Location:displayJam.php");
        exit();
    } else {
        echo "ジャム投稿失敗";
    }
}

?>