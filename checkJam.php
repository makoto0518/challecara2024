<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}
print(printHeader("ConneCre", "ジャム確認"));

$html0 = "";
$html1 = "";
$message = "";
$resultJam0 = getMyJamByStatus($_SESSION['userId'], 0);
$resultJam1 = getMyJamByStatus($_SESSION['userId'], 1);

if (isset($_POST['jam_num']) && isset($_POST['end_jam'])) {
    if ($_POST['end_jam'] === 'true') {
        $jamNum = $_POST['jam_num'];
        $lowLim = (int)$_POST['low_lim']; // 最少人数
        $applicantAmount = (int)$_POST['applicant_amount']; // 参加者数

        if ($lowLim > $applicantAmount) {
            $message .= "参加人数が足りないためマッチングの開始に失敗しました。";
        } else {
            $result2 = jamMatching($jamNum);
            if ($result2 === "APPLICANT_SHORTAGE_ERROR") {
                $message .= "参加者数が足りない為、マッチングに失敗しました。";
            } elseif ($result2) {
                endJam($jamNum);
                $message .= "ジャムを終了し、マッチングを完了しました。";

                // 更新して処理結果を反映させる
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $message .= "ジャムの終了に失敗しました。";
            }
        }
    }
}


if ($resultJam0 == null) {
    $html0 .= "<h3 style=\"padding-left: 60px;\">募集が終了したジャムはありません。</h3>";
} else {
    foreach ($resultJam0 as $row) {;
        $icon = getIconFromId($row['host_id']);
        $html0 .= "<div class=\"Recruitment\">";
        $html0 .= "<div class='profile-container'>
            <a href='" . ($row['host_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['host_id']) . "'>
                <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
            </a>
            <h2 class='profile-title'>" . $row['hackason_name'] . "</h2>
          </div>";
        $html0 .= "開催地　　　　：" . $location[$row['hackason_location']] . "<br>";
        $html0 .= "日時　　　　　：" . $row['jam_period_time'] . "　まで<br>";
        $html0 .= "各チーム人数　：" . $row['team_min'] . " ~ " . $row['team_max'] . "人<br>";
        $html0 .= "<form method='post' action='checkJamTeam.php'>";

        $html0 .= "<input type='hidden' name='jam_num' value='" . $row['jam_num'] . "'>";
        $html0 .= "<input class='apply_btn' type='submit' value='結果を見る' style=\"margin-left:180px;\">";
        $html0 .= "</form>";
        $html0 .= "</div>";
    }
}

if ($resultJam1 == null) {
    $html1 .= "<h3 style=\"padding-left: 60px;\">あなたが現在開催しているジャムはありません。</h3>";
} else {
    foreach ($resultJam1 as $row) {
        $icon = getIconFromId($row['host_id']);
        $lowLim = $row['team_min'];
        $tmp = getJamApplicantAmount($row['jam_num']);
        $applicantAmount = $tmp[0]['applicant_amount'];

        $html1 .= "<div class=\"Recruitment\">";
        $html1 .= "<div class='profile-container'>
            <a href='" . ($row['host_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['host_id']) . "'>
                <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
            </a>
            <h2 class='profile-title'>" . $row['hackason_name'] . "</h2>
          </div>";

        $html1 .= "開催地　　　　：" . $location[$row['hackason_location']] . "<br>";
        $html1 .= "日時　　　　　：" . $row['jam_period_time'] . "　まで<br>";
        $html1 .= "各チーム人数　：" . $row['team_min'] . " ~ " . $row['team_max'] . "人<br>";
        $html1 .= "<div class=\"form_group\" style=\"\">"; //ボタンを整列させるためのdiv 2つのボタンの並び方を揃える
        $html1 .= "
        <form method='POST' action='myJam.php' class='left-button'>
            <input type='hidden' name='jam_num' value='" . $row['jam_num'] . "'>
            <input class='apply_btn' type='submit' value='参加者確認' style=\"\">
        </form>
        <form method='POST' action='" . $_SERVER['PHP_SELF'] . "' class='right-button' onsubmit='return confirmMatching();'>
            <input type='hidden' name='jam_num' value='" . $row['jam_num'] . "'>
            <input type='hidden' name='low_lim' value='" . $lowLim . "'>
            <input type='hidden' name='applicant_amount' value='" . $applicantAmount . "'>
            <input type='hidden' name='end_jam' value='true'>
            <input class='apply_btn' type='submit' value='マッチングを開始' style=\"\">
        </form>";
        $html1 .= "</div>";
        $html1 .= "</div>";
    }
}


// マッチングが成立した、自分の所属しているジャムを一覧表示
$html2 = "";
$tmp = getJamRoomNumFromUserId($_SESSION['userId']); // 自分の所属しているルーム番号を得る
$jamRoomNum = [];
$jamList = [];

if ($tmp == false) {
    $html2 .= "<h3 style=\"padding-left:60px;\">マッチングしたジャムはありません。</h3>";
} else {
    for($i = 0; $i < count($tmp); $i++) {
        $jamRoomNum[$i] = $tmp[$i]['room_num'];
    }

    for($i = 0; $i < count($jamRoomNum); $i++) {
        $tmpJamList = getJamNum($jamRoomNum[$i]);
        $jamList[$i] = $tmpJamList[0]['jam_num'];
    }
    $table = "jam";
    
    for($i = 0; $i < count($jamList); $i++) {
        $jamChat = getChat($table, $jamRoomNum[$i]);    // チャットの情報を取得
        // var_dump($jamChat);
        $latestJamChat = getLastChatDetail($jamChat);   // 最新のチャットの情報を取得
        
        $jamInfo = getJam($jamList[$i]);
        $html2 .= "<div class=\"Recruitment\">";
        $html2 .= "<h4 style=\"margin: 5px 0; padding: 0;\">" . $jamInfo[0]['hackason_name'] . "</h4>";
        if ($latestJamChat == null) {
            $html2 .= "<p style=\"margin: 5px 0; padding: 0;\">まだチャットがありません</p>";
        } else {
            $html2 .= "<p style=\"margin: 5px 0; padding: 0;\">" . $latestJamChat['senderId'] . ":" . $latestJamChat['chatContent'] . "</p><p style=\"margin: 5px 0; padding: 0;\">" . $latestJamChat['time'] . "</p>";
        }
        $html2 .= "<div style=\"text-align:center;\">";
        $html2 .= "<form action=\"jamChat.php\" method=\"post\">";
        $html2 .= "<input type=\"hidden\" name=\"roomNum\" value=\"" . $jamRoomNum[$i] . "\">";
        // $html2 .= $jamRoomNum[$i];
        $html2 .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\" >";
        $html2 .= "</form>";
        $html2 .= "</div>";
        $html2 .= "</div>";
    }
}



?>

<script>
function confirmMatching() {
    return confirm("ジャムマッチングの終了時刻になっていません。マッチングを開始しますか？");
}
</script>

<div class="checkJamContent">
    <h2 style="padding-left:30px;">マッチングが完了したジャム</h2>
    <?php if(!empty($message)) { ?>
        <div class="Recruitment">
            <?php echo $message; ?>
        </div>
    <?php } ?>
    <div style="border-bottom: 5px white solid;"  class="Box">
        <?php echo $html2; ?>
    </div >

    <h2 style="padding-left:30px;">あなたが開催しているジャム</h2>
    <div style="border-bottom: 5px white solid;" class="Box">
        <?php echo $html1; ?>
    </div>

    <h2 style="padding-left:30px;">募集が終了したジャム</h2>
    <div class="Box">
        <?php echo $html0; ?>
    </div>
</div>

<!-- <div class="checkJamContent">
    <div class="checkJamHeading">
        <h2 style="padding-left:50px">マッチング完了した参加中のジャム</h2>
    </div>
    <?php if(!empty($message)) { ?>
        <div class="Recruitment">
            <?php echo $message; ?>
        </div>
    <?php } ?>
    <div class="checkJamBox" style="border-bottom: 5px white solid;">
        <?php echo $html2; ?>
    </div>

    <div class="checkJamBox" style="border-bottom: 5px white solid;">
        <?php echo $html1; ?>
    </div>

    <div class="checkJamFooter">
        <div class="checkJamHeading">
            <h2 style="padding-left:50px">募集が終了したジャム</h2>
        </div>

        <div class="checkJamBox">
            <?php echo $html0; ?>
        </div>
    </div>
</div> -->
</body>
</html>