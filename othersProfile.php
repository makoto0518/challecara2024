<?php
include "mylib.php";
print (printHeader("プロフィール", "プロフィール"));
if (!isset($_SESSION)) {
    session_start();
}
// $creatorTag = ["作曲", "イラスト", "動画制作", "ボーカル"];

$profile = "";
if (isset($_GET['recruiter_id'])) {
    $result = getUserInfoFromId($_GET['recruiter_id']);
    if ($result == null) {
        $profile .= "<h2 style=\"text-align:center;\">ユーザー情報の取得に失敗しました。</h2>";
    } else {
        for ($i = 0; $i < count($result); $i++) {    // $resultの内容を全て表示する
            $row = $result[$i];

            $profile .= "<div class=\"confirmRecruitBox\">";
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

            // $profile .= "<div class=\"twoBtnBox\" class=\"container\" style=\"border-bottom:5px white solid;\">";
            // $profile .= "<button class=\"btn\" onclick=\"location.href='updateProfile.php'\" style=\"margin-right:30px\">";
            // $profile .= "ユーザー情報の編集";
            // $profile .= "</button>";
            // $profile .= "<button class=\"btn\" onclick=\"location.href='submitHistory.php'\">";
            // $profile .= "過去の作品を投稿する";
            // $profile .= "</button>";
            // $profile .= "</div>";
        }
        echo $profile;
    }
} else {
    echo "ユーザーが見つかりません。";
}
$html = "";

$html .= "<h2 style=\"text-align:center;\">このユーザーの過去の作品</h2>";
$result = getHistory($_GET['recruiter_id']);
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
$html .= "<h2 style=\"text-align:center;\">このユーザーの募集中の募集</h2>";
$result = getRecruitFromUserId($_GET['recruiter_id'], 1);
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

        $html .= "</div>"; //modal-contentを終わる
        $html .= "</div>"; //modalを終わる
    }


    $html .= "</div>"; //Boxを終わる
    $html .= "</div>"; //containerを終わる
}



$html .= "<h2 style=\"text-align:center;\">このユーザーの過去の募集</h2>";
$result = getRecruitFromUserId($_GET['recruiter_id'], 0);
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
        $html .= "<button onclick=\"openModal('$modalId')\" class=\"btn\">詳細を見る</button>";
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