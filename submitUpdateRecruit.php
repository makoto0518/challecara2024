<?php
include "mylib.php";
if(!isset($_SESSION)){
    session_start();
}

// echo "<pre>POST:" . print_r($_POST, true) . "</pre>";


//2024/10/28 ここの処理をうまくできるようにした
if(isset($_POST["recruitTitle"]) && isset($_POST["role"]) &&  isset($_POST["location"]) && isset($_POST["capacity"]) && isset($_POST["recruitContent"]) ){
    // ここで updateRecruit を呼び出す
    $success = updateRecruit($_POST['recruitNum'], $_POST["recruitTitle"], $_POST["role"], 
    $_POST["location"], $_POST["capacity"], $_POST["recruitContent"],);
    
    if ($success) {
        // 成功した場合
        header("Location: checkRecruit.php");
        exit();
    } else {
        // 失敗した場合の処理
        echo "更新に失敗しました。";
    }
} else {
    var_dump($_POST);
}
?>
