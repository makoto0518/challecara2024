<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}
print(printHeader("ConneCre", "ジャム確認"));

$html = "";
// var_dump($_POST);

if (empty($_POST['jam_num'])) {
    echo "ジャム情報の取得に失敗しました";
} else {
    $jamInfo = getJam($_POST['jam_num']);
    // echo "<pre>jamInfo" . print_r($jamInfo, true) . "</pre>";

    if (empty($jamInfo)) {
        echo "ジャム情報の取得に失敗しました";
    } else {
        $row = $jamInfo[0];
        
        // ジャム情報のテーブル
        $html .= "<div class='confirmRecruitBox'>";
        $html .= "<table>";
        $html .= "<tr><th>タイトル</th><td>" . htmlspecialchars($row['hackason_name']) . "</td></tr>";
        $html .= "<tr><th>ハッカソン開催地</th><td>" . htmlspecialchars($location[$row['hackason_location']]) . "</td></tr>";
        $html .= "<tr><th>マッチング開始日時</th><td>" . htmlspecialchars($row['jam_start_time']) . "</td></tr>";
        $html .= "<tr><th>マッチング終了日時</th><td>" . htmlspecialchars($row['jam_period_time']) . "</td></tr>";
        $html .= "<tr><th>チーム人数</th><td>" . htmlspecialchars($row['team_min']) . " ~ " . htmlspecialchars($row['team_max']) . "名</td></tr>";
        $html .= "</table>";
        $html .= "</div>";

        $tmp = getRoomNum("jam", $_POST['jam_num']);
        // echo "<pre>getRoomNum" . print_r($tmp, true) . "</pre>";
        $roomNum = [];
        $member = [];   // 二次元配列, 第一添え字：roomNum 第二添え字：userId
        // var_dump($tmp);
        for($i = 0; $i < count($tmp); $i++) {
            $roomNum[$i] = $tmp[$i]['room_num'];
            // $member[$i] = getJamMember($roomNum[$i]);
        }
        // var_dump($roomNum);
        // echo "<pre>roomNum:" . print_r($roomNum, true) . "</pre>";
        for($i = 0; $i < count($roomNum); $i++) {
            $member[$roomNum[$i]] = getJamMember($roomNum[$i]);
        }
        // var_dump($member);
        // echo "<pre>member:" . print_r($member, true) . "</pre>";
        sort($member);  //ここでmemberの第一添え字を0から始めさせる
        // echo "<pre>sorted_member:" . print_r($member, true) . "</pre>";

        $html .= "<div style=\"border-top:5px white solid;\">";
        $html .= "<h2 style=\"text-align:center;\">結成チーム</h2>";
        $html .= "<div class=\"Box\">";

        // echo "<pre>member:". print_r($member, true) . "</pre>";

        // echo "<pre>roomNum_i:" . print_r($roomNum[0], true) . "</pre>";
        // $lastKey = array_key_last($roomNum); //マッチングしたジャムの最後のroomNumを取得
        // echo "<pre>lastKey:" . print_r($roomNum[$lastKey], true) . "</pre>";

        $teamNum = 1;
        for($i = 0; $i < count($member); $i++, $teamNum++) {   //外側はグループ1つ1つを走査していくための値を変化させる
            $modalId = "modal-" .$i;
            // echo "<pre>i:" . print_r($i, true) . "</pre>";
            $html .= "<div class=\"Recruitment\">
                        <h3>チーム" . $teamNum . "</h3>
                        <button style=\"margin-left:28%;\"  class=\"apply_btn\" onclick=\"openModal('$modalId')\">チームメンバー詳細</button>
                      </div>
                      <div id=\"$modalId\" class=\"modal\">
                        <div class=\"modal-content\">
                            <span class=\"close\" onclick=\"closeModal('$modalId')\">&times;</span>
                            <h2 style=\"text-align:center;\">チーム$teamNum メンバーと役割<h2>   
                            <div class='modal-members'>";

            
            for($j = 0; $j < count($member[$i]); $j++) { //内側はあるグループのメンバー全員を走査していくための値を変化させる
                // echo "<pre>j:" . print_r($j, true) . "</pre>";
                // echo "<pre>" . $i . "roomの" . $j . "番目の人のuserId:" . print_r($member[$i][$j], true) . "</pre>";
                $memberRole = getRole($member[$i][$j]);
                // echo "<pre>"  . $i . "roomの"  . $j . "番目の人の役割:" . print_r($role[$memberRole], true) . "</pre>";
                $icon = getIconFromId($member[$i][$j]);
                $html .= "
                                <div class=\"modal-member\">
                                    <a href='" . (strcmp($member[$i][$j], $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $member[$i][$j]) . "'>
                                        <img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\">
                                     </a>
                                    <a href='" . (strcmp($member[$i][$j], $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $member[$i][$j]) . "'>" . $member[$i][$j] . "</a>："
                                    . $role[$memberRole] .
                                "</div>";

            }
            $html .= "</div>"; //modal-members
            $html .= "</div>"; //modal-content
            $html .= "</div>"; //memberModal
        }
    }
}
echo $html;

?>