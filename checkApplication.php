<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}
$html0 = "";
$html1 = "";
$html2 = "";

$deleteResult = false;
$systemMessage = "";
$deleteApplication = "";

if (isset($_POST['apply_num'])) {
    $deleteResult = deleteApplication($_POST['apply_num']);
}



//自分の今募集しているものを全て受け取る
$resultApp0 = getApplicationByStatus($_SESSION['userId'], 0);

if ($resultApp0 == null) {
    $html0 .= "拒否された応募はありません。";
} else {
    for ($i = 0; $i < count($resultApp0); $i++) {
        $resultRec0 = getRecruitFromNum($resultApp0[$i]['recruit_num']);
        // echo "<pre>" . print_r($resultRec0, true) . "</pre>";
        $rowApp = $resultApp0[$i];
        $rowRec = $resultRec0[0];

        // echo "<pre>rowApp:" . print_r($rowApp, true) . "</pre>";
        // echo "<pre>rowRec:" . print_r($rowRec, true) . "</pre>";
        $html0 .= "<div class=\"Recruitment\">";

        $modalId = "modal-rejected-" . $i; //ここは3通り全て被らないような名前にしておくこと！！！
        $icon = getIconFromId($rowRec['recruiter_id']); //アイコン情報取得

        //アイコンとタイトルを入れる箱を定義
        $html0 .= "<div class='profile-container'>
        <a href='" . ($rowRec['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $rowRec['recruiter_id']) . "'>
            <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
        </a>
        <h3 class='profile-title'>" . $rowRec['recruit_title'] . "</h3>
      </div>"; //profile-containerの終了
        $html0 .= "応募本文　　　：" . $rowApp['apply_content'] . "<br>";
        $html0 .= "<button onclick=\"openModal('$modalId')\" class=\"apply_btn\">詳細を見る</button>";
        $html0 .= "</div>"; //recruitmentの終了

        //モーダル部分
        $html0 .= "
        <div id=\"$modalId\" class=\"modal\">
            <div class=\"modal-content\">
                <span class=\"close\" onclick=\"closeModal('$modalId')\">&times;</span>
                <h3>" . $rowRec['recruit_title'] . "</h3>
                <p>募集者   :" . $rowRec['recruiter_id'] . "</p>
                <p>日時     :" . $rowRec['recruit_datetime'] . "</p>
                <p>募集役割 :" . $role[$rowRec['recruit_role']] . "</p>
                <p>募集人数 :" . $rowRec['recruit_capacity'] . "</p>
                <p>本文     :" . $rowRec['recruit_content'] . "</p>";

        if ($rowRec['edited'] == 1) {
            $html0 .= "<p>(修正済み)</p>";
        }
        $html0 .= "</div>"; //modal-contentを終わる
        $html0 .= "</div>"; //modalを終わる


    }
}


$resultApp1 = getApplicationByStatus($_SESSION['userId'], 1);
if ($resultApp1 == null) {
    $html1 .= "承認待ちの応募はありません。";
} else {
    for ($i = 0; $i < count($resultApp1); $i++) {
        $resultRec1 = getRecruitFromNum($resultApp1[$i]['recruit_num']);
        // echo "<pre>" . print_r($resultRec1, true) . "</pre>";

        $rowApp = $resultApp1[$i];
        $rowRec = $resultRec1[0];
        // echo "<pre>rowApp:" . print_r($rowApp, true) . "</pre>";
        // echo "<pre>rowRec:" . print_r($rowRec, true) . "</pre>";

        $html1 .= "<div class=\"Recruitment\">";

        $modalId_1 = "modal-pending-" . $i; //モーダル用変数
        $icon = getIconFromId($rowRec['recruiter_id']); //アイコン情報取得

        //アイコンとタイトルを入れる箱を定義
        $html1 .= "<div class='profile-container'>
                    <a href='" . ($rowRec['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $rowRec['recruiter_id']) . "'>
                        <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
                    </a>
                    <h3 class='profile-title'>" . $rowRec['recruit_title'] . "</h3>
                   </div>"; //profile-containerの終了

        $html1 .= "応募本文　　　：" . $rowApp['apply_content'] . "<br>";
        $html1 .= "<button onclick=\"openModal('$modalId_1')\" class=\"apply_btn\">詳細を見る</button>";
        $html1 .= "</div>"; //recruitmentの終了

                //モーダル部分
                $html1 .= "
                <div id=\"$modalId_1\" class=\"modal\">
                    <div class=\"modal-content\">
                        <span class=\"close\" onclick=\"closeModal('$modalId_1')\">&times;</span>
                        <h3>" . $rowRec['recruit_title'] . "</h3>
                        <p>募集者   :" . $rowRec['recruiter_id'] . "</p>
                        <p>日時     :" . $rowRec['recruit_datetime'] . "</p>
                        <p>募集役割 :" . $role[$rowRec['recruit_role']] . "</p>
                        <p>募集人数 :" . $rowRec['recruit_capacity'] . "</p>
                        <p>本文     :" . $rowRec['recruit_content'] . "</p>";
        
                if ($rowRec['edited'] == 1) {
                    $html1 .= "<p>(修正済み)</p>";
                }
                $html1 .= "<form action=\"checkApplication.php\" method=\"post\"><input type=\"hidden\" name=\"apply_num\" value=\"" . $rowApp['apply_num'] . "\">";
                $html1 .= "<input class=\"apply_btn\" type=\"submit\" value=\"応募を削除\">";
                $html1 .= "</form>";
                $html1 .= "</div>"; //modal-contentを終わる
                $html1 .= "</div>"; //modalを終わる
        
    }
}



$resultApp2 = getApplicationByStatus($_SESSION['userId'], 2);
if ($resultApp2 == null) {
    $html2 .= "承認された応募はありません。";
} else {
    for ($i = 0; $i < count($resultApp2); $i++) {
        $resultRec2 = getRecruitFromNum($resultApp2[$i]['recruit_num']);
        // echo "<pre>" . print_r($resultRec2, true) . "</pre>";
        $rowApp = $resultApp2[$i];
        $rowRec = $resultRec2[0];
        $html2 .= "<div class=\"Recruitment\">";

        $modalId_2 = "modal-approved-" . $i; //モーダル用変数
        $icon = getIconFromId($rowRec['recruiter_id']); //アイコン情報取得

        //アイコンとタイトルを入れる箱を定義
        $html2 .= "<div class='profile-container'>
                    <a href='" . ($rowRec['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $rowRec['recruiter_id']) . "'>
                        <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
                    </a>
                    <h3 class='profile-title'>" . $rowRec['recruit_title'] . "</h3>
                   </div>"; //profile-containerの終了

        $html2 .= "募集本文     ：" . $rowRec['recruit_title'] . "<br>";


        $html2 .= "<button onclick=\"openModal('$modalId_2')\" class=\"apply_btn\">詳細を見る</button>";
        $html2 .= "</div>"; //recruitmentを閉じる

                        //モーダル部分
                        $html2 .= "
                        <div id=\"$modalId_2\" class=\"modal\">
                            <div class=\"modal-content\">
                                <span class=\"close\" onclick=\"closeModal('$modalId_2')\">&times;</span>
                                <h3>" . $rowRec['recruit_title'] . "</h3>
                                <p>募集者   :" . $rowRec['recruiter_id'] . "</p>
                                <p>日時     :" . $rowRec['recruit_datetime'] . "</p>
                                <p>募集役割 :" . $role[$rowRec['recruit_role']] . "</p>
                                <p>募集人数 :" . $rowRec['recruit_capacity'] . "</p>
                                <p>本文     :" . $rowRec['recruit_content'] . "</p>";
                
                        if ($rowRec['edited'] == 1) {
                            $html2 .= "<p>(修正済み)</p>";
                        }

                        //2024 11/15  応募した人がチャットにアクセス
                        $html2 .= "<form action=\"recruitChat.php\" method=\"post\">";
                        $html2 .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $rowApp['recruit_num'] . "\">";
                        $html2 .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\">";
                        $html2 .= "</form>";
                        $html2 .= "</div>"; //modal-contentを終わる
                        $html2 .= "</div>"; //modalを終わる
    }
}
print (printHeader("ConneCre", "応募確認"));

?>
<div class="checkApplication">
    <div class="waiting_for_approval_container">
        <h3>承認待ちの応募</h3>
        <div>
            <?php echo $html1; ?>
        </div>
    </div>
    <div class="approval_container">
        <h3>承認された応募</h3>
        <div>
            <?php echo $html2; ?>
        </div>
    </div>
    <div class="rejection_container">
        <h3>拒否された応募</h3>
        <div>
            <?php echo $html0; ?>
        </div>
    </div>
</div>

</body>

</html>