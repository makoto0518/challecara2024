<?php
include "mylib.php";
print(printHeader("ConneCre", "作品を投稿"));
if (!isset($_SESSION)) {
    session_start();
}

$html = "<div class=\"Recruitment\" style=\"width:60%; margin:auto; margin-top:50px;\">";

if(isset($_SESSION['userId']) && isset($_POST['history_title']) && isset($_POST['history_discription'])) {
    if(setHistory($_SESSION['userId'] , $_POST['history_title'], $_POST['history_discription'])){
        $html .=  "<h3 style=\"text-align:center;\">作品の投稿が完了しました。</h3>";
    } else {
        $html .=  "<h3 style=\"text-align:center;\">作品の投稿に失敗しました。</h3>";
    }
} else {
    $html .=  "<h3 style=\"text-align:center;\">情報が不十分です。</h3>";
}

$html .= "</div>";
print($html);

?>
