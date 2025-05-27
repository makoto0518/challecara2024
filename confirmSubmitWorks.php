<?php
include "mylib.php";
print(printHeader("ConneCre", "作品を投稿"));
if (!isset($_SESSION)) {
    session_start();
}

$debug_mode = false;
if ($debug_mode) {
    echo "<pre>POST: " . print_r($_POST, true) . " </pre>";
    echo "<pre>SESSION: " . print_r($_SESSION, true) . "</pre>";
}

if(isset($_SESSION['userId']) && isset($_POST['work_title']) && isset($_POST['work_url']) && isset($_POST['work_discription'])) {
    if(setWork($_SESSION['userId'] , $_POST['work_title'], $_POST['work_url'], $_POST['work_discription'])){
        echo "作品の投稿が完了しました。";
    } else {
        echo "作品の投稿に失敗しました。";
    }
} else {
    echo "情報が不十分";
}
?>