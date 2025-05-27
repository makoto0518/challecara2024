<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

print(printHeader("ジャムマッチングリスト","ジャムマッチングリスト"));

$systemMessage = "";
$result = "";
if (isset($_POST['title']) || isset($_POST['location'])) {
    $result = searchJam($_POST['title'], $_POST['location']);
} else {
    $result = getActiveJam(); // active状態のジャムを取り出す
    if ($result == null) {
        $systemMessage .= "現在開催中のジャムマッチングがありません。<br>";
    }
}
// $result = getActiveJam(); // active状態のジャムを取り出す
// echo "<pre>result:" . print_r($result, true) . "</pre>";

// ジャムを終了するボタンを押されたとき
if(isset($_POST['jamNum']) && isset($_POST['endJam'])){
    if(strcmp($_POST['endJam'], 'true') == 0){
        $result2 = jamMatching($_POST['jamNum']);
        endJam($_POST['jamNum']);
        // var_dump($result2);
        if(strcmp($result2, "APPLICANT_SHORTAGE_ERROR") == 0) {
            $systemMessage .= "参加者数が足りない為、マッチングに失敗しました。<br>";
        } else if( $result2 ){   // ジャムを終わらせる　成功したらtrue, 失敗したらfalse
            jamMatching($_POST['jamNum']);
            $systemMessage .= "ジャムを終了し、マッチングを完了しました。<br>";
        } else {
            $systemMessage .= "ジャムの終了に失敗しました。<br>";
        }
    }
}

//ジャムマッチングを探す検索フォーム
$html = "<div class=\"searchForm\">
            <form method=\"post\" action=\"displayjam.php\">
                <div class=\"keyword_input_form_jam\">
                    <label for=\"1\" class=\"keyword\">キーワード</label>
                    <input id=\"1\" type=\"text\" name=\"title\" placeholder=\"キーワードを入力\" class=\"keyword_input\" 
                    value=\"";
                    if (isset($_POST['title'])) {
                        $html .= $_POST['title'];
                    }
                $html .= "\">
                </div>
                
                <div class=\"searchMethod_select_form\">
                    <label for=\"2\" class=\"Search_Method\">場所</label>
                    <select id=\"2\" name=\"location\" class=\"select_genre\">";

                    foreach ($location as $key => $loc) {
                        $html .= "<option value='$key' " . (isset($_POST['location']) && $_POST['location'] == $key ? 'selected' : '') . ">$loc</option>";
                    }
                $html .= "</select>
                </div>

                <div class=\"kensakubtnBox\">
                    <button type=\"submit\" class=\"kensaku_btn\">検索</button>
                </div>
            </form>
            <h4 style=\"text-align:center;\">" . $systemMessage . "</p>
        </div>
        <div class=\"container\">
            <div class=\"Box\">";
            

executeJamMatching($_SESSION['userId']);




// ジャムがない場合
if($result == null){
    $systemMessage .= "現在開催中のジャムマッチングがありません。<br>";
}else{
    for($i = 0; $i < count($result); $i++) {
        $row = $result[$i];
        $icon = getIconFromId($row['host_id']);

        // $html .= "<div style=\"border: 2px #658db449 solid; margin:10px; padding:10px;\">";
        $html .= "<div class=\"Recruitment\">";
        // $html .= "<a href='" . ($row['host_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['host_id']) . "'>
        // <img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";

        // $html .= "<h2>" . $row['hackason_name'] . "</h2>";
        $html .= "<div class='profile-container'>
            <a href='" . ($row['host_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['host_id']) . "'>
                <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
            </a>
            <h2 class='profile-title'>" . $row['hackason_name'] . "</h2>
          </div>";

        $html .= "ハッカソン開催地　：" . $location[$row['hackason_location']] . "<br>";
        $html .= "マッチング開始日時：" . $row['jam_start_time'] . "<br>マッチング終了日時：" . $row['jam_period_time'];
        $html .= "<br>各チーム人数　　　：" . $row['team_min'] . " ~ " . $row['team_max'] . "名<br>";
        // 自分の作ったジャムでない場合、参加ボタンを表示する
        if(strcmp($row['host_id'], $_SESSION['userId']) !== 0) {
            // すでに参加している場合、参加ボタンを表示しない
            if(jamApplyExists($_SESSION['userId'], $row['jam_num'])) {
                $html .= "<button class=\"disabled_btn\" style=\"text-align:center;\">参加済み</button>";
            } else {
                $html .= "<form action=\"jamApply.php\" method=\"post\" style=\"text-align:center;\">
                            <input type=\"hidden\" name=\"jamNum\" value=\"" . $row['jam_num'] . "\">
                            <button type=\"submit\" class=\"apply_btn\">参加する</button>
                          </form>";
            }
        } else {
            // 自分の作ったジャムの場合、ジャムの終了ボタンを表示する
            $html .= "<form action='myJam.php' method='post' style=\"text-align:right;\">
                        <input type='hidden' name='jam_num' value=" . $row['jam_num'] . ">
                        <div style=\"text-align:center;\">
                        <button type='submit' class='apply_btn'>ジャムを管理</button>
                        </div>
                      </form>";
        }
        $html .= "</div>"; //Recruitを閉じる
    }
    $html .= " </div>";    //Boxを閉じる
    $html .= "</div>";//containerを閉じる
}
print($html);

?>