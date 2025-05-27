<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

print (printHeader("ConneCre", "募集確認"));
$html0 = "";
$html1 = "";
$resultRec0 = getRecruitFromUserId($_SESSION['userId'], 0);
$resultRec1 = getRecruitFromUserId($_SESSION['userId'], 1);

// echo "<pre>募集終了:" . print_r($resultRec0, true) . "</pre>";
// echo "<pre>募集中:" . print_r($resultRec1, true) . "</pre>";

// ボタンが押されたときの更新処理
if (isset($_POST['recruit_num'])) {
    endRecruit($_POST['recruit_num']);
    // 現在のページにリダイレクト
    header("Location: " . $_SERVER['PHP_SELF']);
    exit; // スクリプトの実行を終了してリダイレクトを確実にする
}

if ($resultRec0 == null) {
    $html0 .= "募集終了した募集はありません。";
} else {
    $i = 0;
    foreach ($resultRec0 as $row) {
        $modalId = "modal-" . $i;
        $icon = getIconFromId($row['recruiter_id']);
        $html0 .= "<div class=\"Recruitment\" style=\"text-align:left;\">";
                        //アイコンとタイトルを入れる箱を定義
                        $html0 .= "<div class='profile-container'>
                        <a href='" . ($row['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['recruiter_id']) . "'>
                            <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
                        </a>
                        <h3 class='profile-title'>" . $row['recruit_title'] . "</h3>
                      </div>"; //profile-containerの終了
        $html0 .= "募集本文：" . $row['recruit_content'] . "<br>";
        $html0 .= "募集役割：" . $role[$row['recruit_role']] . "<br>";
        $html0 .= "募集人数:"  . $row['recruit_capacity'] . "<br>";
        $html0 .= "<div style=\"text-align:center;\"><button onclick=\"openModal('$modalId')\" class=\"apply_btn\">詳細を見る</button></div>";
        $html0 .= "</div>";

        //モーダル部分
        $html0 .= "
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
            $html0 .= "<p>(修正済み)</p>";
        }

        $html0 .= "</div>"; //modal-contentを終わる
        $html0 .= "</div>"; //modalを終わる

        $i++;
    }
}

if ($resultRec1 == null) {
    $html1 .= "募集中の募集はありません。";
} else {
    $i = 0;
    foreach ($resultRec1 as $row) {
        $modalId_1 = "modal-1-" . $i;
        $icon = getIconFromId($row['recruiter_id']);
        $html1 .= "<div class=\"Recruitment\" style=\"text-align:left;\">";
                //アイコンとタイトルを入れる箱を定義
                $html1 .= "<div class='profile-container'>
                <a href='" . ($row['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['recruiter_id']) . "'>
                    <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
                </a>
                <h3 class='profile-title'>" . $row['recruit_title'] . "</h3>
              </div>"; //profile-containerの終了

        // $html1 .= "募集のタイトル：" . $row['recruit_title'] . "<br>";
        // $html1 .= "投稿時間:" . $row['recruit_datetime'] . "<br>";
        $html1 .= "募集本文：" . $row['recruit_content'] . "<br>";
        $html1 .= "募集役割：" . $role[$row['recruit_role']] . "<br>";
        $html1 .= "募集人数:"  . $row['recruit_capacity'] . "<br>";
        // $html1 .= "募集場所:" . $location[$row['recruit_location']] . "<br>";

        $html1 .= "<div style=\"text-align:center;\"><button onclick=\"openModal('$modalId_1')\" class=\"apply_btn\">詳細を見る</button> </div>";
        $html1 .= "</div>"; //recrutingを閉じる

                //モーダル部分
                $html1 .= "
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
                    $html1 .= "<p>(修正済み)</p>";
                }
                
        $html1 .= "<div class=\"form_group\">";  //ボタン3つを囲み、それらにスタイルを適応させてボタンを横に並べる
        $html1 .= "<form action=\"applicant.php\" method=\"post\">";
        $html1 .= "<input type=\"hidden\" name=\"recruit_num\" value=\"" . $row['recruit_num'] . "\">";
        $html1 .= "<input class=\"apply_btn\" type=\"submit\" value=\"応募者確認\">";
        $html1 .= "</form>";

        $html1 .= "<form action=\"checkRecruit.php\" method=\"post\">";
        $html1 .= "<input type=\"hidden\" name=\"recruit_num\" value=\"" . $row['recruit_num'] . "\">";
        $html1 .= "<input class=\"apply_btn\" type=\"submit\" value=\"募集を終了\">";
        $html1 .= "</form>";

        // 2024/11/13 募集のチャット画面に遷移するための仮ボタン
        $html1 .= "<form action=\"recruitChat.php\" method=\"post\">";
        $html1 .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">";
        $html1 .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\">";
        $html1 .= "</form>";
        
        //2024 10/29 情報が全て表示されるようにした。
        $html1 .= "<form action=\"updateRecruit.php\" method=\"post\">";
        $html1 .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">"; //どの募集か一意に特定する
        $html1 .= "<input type=\"hidden\" name=\"recruitTitle\" value=\"" . htmlspecialchars($row['recruit_title']) . "\">"; //募集タイトル
        $html1 .= "<input type=\"hidden\" name=\"recruitRole\" value=\"" . htmlspecialchars($row['recruit_role']) . "\">";   //募集役割
        $html1 .= "<input type=\"hidden\" name=\"recruitCapacity\" value=\"" . htmlspecialchars($row['recruit_capacity']) .  "\">"; //募集人数
        $html1 .= "<input type=\"hidden\" name=\"recruitLocation\" value=\"" . htmlspecialchars($row['recruit_location']) .   "\">";//募集場所 
        $html1 .= "<input type=\"hidden\" name=\"recruitContent\" value=\"" . htmlspecialchars($row['recruit_content']) . "\">";//募集内容
        $html1 .= "<input class=\"apply_btn\" type=\"submit\" value=\"編集\">";
        $html1 .= "</form>";

        $html1 .= "</div>"; //form_groupを閉じる

                $html1 .= "</div>"; //modal-contentを終わる
                $html1 .= "</div>"; //modalを終わる

        $i++;
    }
}
?>


<div class="checkRecruit">
    <div class="recruting_container">
        <h3 style="text-align:center;">募集中</h3>
        <div>
            <?php echo $html1; ?>
        </div>
    </div>
    <div class="end_recruit_container">
        <h3 style="text-align:center;">終了した募集</h3>
        <div>
            <?php echo $html0; ?>
        </div>
    </div>
</div>

</body>

</html>