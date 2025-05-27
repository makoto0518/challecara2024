<?php
include "mylib.php";
print (printHeader("ConneCre", "プロフィール"));

$debug_mode = false;


if (!isset($_SESSION)) {
    session_start();
}

$profile = "";

if (isset($_SESSION['userId'])) {
    $result = getUserInfoFromId($_SESSION['userId']);
    if ($result == null) {
        echo "ユーザー情報の取得に失敗しました。";
    } else {

        if ($debug_mode) { //デバッグモードがtrueのとき
            echo "<pre>" . print_r($result, true) . "</pre>";
        }

        for ($i = 0; $i < count($result); $i++) {    // $resultの内容を全て表示する
            $row = $result[$i];
            // $profile .= "<div class=\"Recruitment\">";

            // echo "<pre>result" . print_r($result, true) . "</pre>";

            $profile .= "<div class=\"confirmRecruitBox\" >";
            //もしアイコンが設定されている場合、表示する
            if (isset($row['user_icon'])) {
                $profile .= "<img src='" . $row['user_icon'] . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"><br>";
            }
            $profile .= "<table>
                            <tr>
                                <th>ユーザー名</th>
                                <td>" . $row['user_name'] . "</td>
                            </tr>
                            <tr>
                                <th>ユーザーID</th>
                                <td>" . $row['user_id'] . "</td>
                            </tr>
                            <tr>
                                <th>役割</th>
                                <td>" . $role[$row['user_role']] . "</td>
                            </tr>
                            <tr>
                                <th>自己紹介</th>
                                <td>" . $row['user_profile'] . "</td>
                            </tr>
                        </table>
                        </div>";

            $profile .= "<div class=\"twoBtnBox\" class=\"container\" style=\"border-bottom:5px white solid;\">";
            $profile .= "<button class=\"apply_btn\" onclick=\"location.href='updateProfile.php'\" style=\"margin-right:30px\">";
            $profile .= "ユーザー情報の編集";
            $profile .= "</button>";
            $profile .= "<button class=\"apply_btn\" onclick=\"location.href='submitHistory.php'\">";
            $profile .= "過去の作品を投稿する";
            $profile .= "</button>";
            $profile .= "</div>";
        }
        echo $profile;
    }
} else {
    echo "ユーザーが見つかりません。";
}


$html = "";
$html .= "<h2 style=\"text-align:center;\">あなたの過去の作品</h2>";
$result = getHistory($_SESSION['userId']);
if ($result == null) {
    $html .= "<div style=\"border-bottom:5px white solid;\">";
    $html .= "<h4 style=\"text-align:center;\">作品がありません。</h4>";
    $html .= "</div>";
} else if ($result == false) {
    echo "作品の取得に失敗しました。<br>";
} else {
    $html .= "<div class=\"container\" style=\"border-bottom:5px white solid;\">";
    $html .= "<div class=\"Box\">";
    for ($i = 0; $i < count($result); $i++) {
        $row = $result[$i];
        $html .= "<div class=\"Recruitment\">";
        $html .= "作品タイトル：" . $row['history_title'] . "<br>";
        $html .= "作品紹介　　：" . $row['history_discription'] . "<br>";
        $html .= "</div>";
    }
    $html .= "</div>";
    $html .= "</div>";
}

$html .= "<h2 style=\"text-align:center;\">募集中の募集</h2>";
$result = getRecruitFromUserId($_SESSION['userId'], 1);
// echo "<pre>募集中の募集" . print_r($result, true) . "</pre>";
if ($result == null) {
    $html .= "<div style=\"border-bottom:5px white solid;\">";
    $html .= "<h4 style=\"text-align:center;\">募集中の募集はありません。</h4>";
    $html .= "</div>";
} else {
    $i;
    $html .= "<div class=\"container\" style=\"border-bottom:5px white solid;\">";
    $html .= "<div class=\"Box\">";
    for ($i = 0; $i < count($result); $i++) {
        $modalId_1 = "modal1-" . $i;
        $row = $result[$i];
        $html .= "<div class=\"Recruitment\">";
        $html .= "<h3>" . $row['recruit_title'] . "</h3>";
        $html .= "ジャンル　　：" . $role[$row['recruit_role']] . "<br>";
        $html .= "募集本文　　：" . $row['recruit_content'] . "<br><br>";
        $html .= "<div style=\"text-align:center;\">";
        $html .= "<button onclick=\"openModal('$modalId_1')\" class=\"apply_btn\">詳細を見る</button>";
        $html .= "</div>";
        $html .= "</div>";

        //モーダル部分
        $html .= "
        <div id=\"$modalId_1\" class=\"modal\">
            <div class=\"modal-content\">
                <span class=\"close\" onclick=\"closeModal('$modalId_1')\">&times;</span>
                <h3>" . $row['recruit_title'] . "</h3>
                <p>募集者   :" . $row['recruiter_id'] . "</p>
                <p>開始日時     :" . $row['recruit_datetime'] . "</p>
                <p>募集役割 :" . $role[$row['recruit_role']] . "</p>
                <p>募集人数 :" . $row['recruit_capacity'] . "</p>
                <p>本文     :" . $row['recruit_content'] . "</p>";

        if ($row['edited'] == 1) {
            $html .= "<p>(修正済み)</p>";
        }

        $html .= "<div class=\"form_group\">";  //ボタン3つを囲み、それらにスタイルを適応させてボタンを横に並べる
        $html .= "<form action=\"applicant.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruit_num\" value=\"" . $row['recruit_num'] . "\">";
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"応募者確認\">";
        $html .= "</form>";

        $html .= "<form action=\"checkRecruit.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruit_num\" value=\"" . $row['recruit_num'] . "\">";
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"募集を終了\">";
        $html .= "</form>";

        // 2024/11/13 募集のチャット画面に遷移するための仮ボタン
        $html .= "<form action=\"recruitChat.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">";
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\">";
        $html .= "</form>";

        //2024 10/29 情報が全て表示されるようにした。
        $html .= "<form action=\"updateRecruit.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">"; //どの募集か一意に特定する
        $html .= "<input type=\"hidden\" name=\"recruitTitle\" value=\"" . htmlspecialchars($row['recruit_title']) . "\">"; //募集タイトル
        $html .= "<input type=\"hidden\" name=\"recruitRole\" value=\"" . htmlspecialchars($row['recruit_role']) . "\">";   //募集役割
        $html .= "<input type=\"hidden\" name=\"recruitCapacity\" value=\"" . htmlspecialchars($row['recruit_capacity']) . "\">"; //募集人数
        $html .= "<input type=\"hidden\" name=\"recruitLocation\" value=\"" . htmlspecialchars($row['recruit_location']) . "\">";//募集場所 
        $html .= "<input type=\"hidden\" name=\"recruitContent\" value=\"" . htmlspecialchars($row['recruit_content']) . "\">";//募集内容
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"編集\">";
        $html .= "</form>";

        $html .= "</div>"; //form_groupを閉じる

        $html .= "</div>"; //modal-contentを終わる
        $html .= "</div>"; //modalを終わる
    }
    $html .= "</div>";
    $html .= "</div>";
}


$html .= "<h2 style=\"text-align:center;\">あなたの過去の募集</h2>";
$result = getRecruitFromUserId($_SESSION['userId'], 0);
if ($result == null) {
    $html .= "<h4 style=\"text-align:center;\">過去の募集がありません。</h4>";
} else {
    $html .= "<div class=\"container\">";
    $html .= "<div class=\"Box\">";
    for ($i = 0; $i < count($result); $i++) {    // $resultの内容を全て表示する
        $modalId = "modal-" . $i;
        $row = $result[$i];
        // $html .= "<div style=\"border: 2px #658db449 solid; margin:10px; padding:10px;\">";
        $html .= "<div class=\"Recruitment\">";
        $html .= "<h3>" . $row['recruit_title'] . "</h3>";
        $html .= "募集本文　　：" . $row['recruit_content'] . "<br>";
        $html .= "募集役割　　：" . $role[$row['recruit_role']] . "<br>";
        $html .= "<div style=\"text-align:center;\">";
        $html .= "<button onclick=\"openModal('$modalId')\" class=\"apply_btn\">詳細を見る</button>";
        $html .= "</div>";
        $html .= "</div>";

        //モーダル部分
        $html .= "
                <div id=\"$modalId\" class=\"modal\">
                    <div class=\"modal-content\">
                        <span class=\"close\" onclick=\"closeModal('$modalId')\">&times;</span>
                        <h3>" . $row['recruit_title'] . "</h3>
                        <p>募集者   :" . $row['recruiter_id'] . "</p>
                        <p>開始日時     :" . $row['recruit_datetime'] . "</p>
                        <p>募集役割 :" . $role[$row['recruit_role']] . "</p>
                        <p>募集人数 :" . $row['recruit_capacity'] . "</p>
                        <p>本文     :" . $row['recruit_content'] . "</p>";

        if ($row['edited'] == 1) {
            $html .= "<p>(修正済み)</p>";
        }

        $html .= "</div>"; //modal-contentを終わる
        $html .= "</div>"; //modalを終わる


    }
    $html .= "</div>";
    $html .= "</div>";
}



echo $html;
?>
</body>

</html>