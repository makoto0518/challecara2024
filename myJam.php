<script>
function confirmMatching() {
    return confirm("ジャムマッチングの終了時刻になっていません。マッチングを開始しますか？");
}
</script>
<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}
print(printHeader("ConneCre", "ジャム確認"));

$html = "";

if (empty($_POST['jam_num'])) {
    echo "ジャム情報の取得に失敗しました";
} else {
    $backApplicant = getJamApplicantRole($_POST['jam_num'], 0);
    $frontApplicant = getJamApplicantRole($_POST['jam_num'], 1);
    $fullApplicant = getJamApplicantRole($_POST['jam_num'], 2);

    $jamInfo = getJam($_POST['jam_num']);
    if (empty($jamInfo)) {
        echo "ジャム情報の取得に失敗しました";
    } else {
        $row = $jamInfo[0];
        $tmp = getJamApplicantAmount($row['jam_num']);
        $applicantAmount = $tmp[0]['applicant_amount'];
        
        // ジャム情報のテーブル
        $html .= "<div class='confirmRecruitBox'>";
        $html .= "<table>";
        $html .= "<tr><th>タイトル</th><td>" . htmlspecialchars($row['hackason_name']) . "</td></tr>";
        $html .= "<tr><th>ハッカソン開催地</th><td>" . htmlspecialchars($location[$row['hackason_location']]) . "</td></tr>";
        $html .= "<tr><th>マッチング開始日時</th><td>" . htmlspecialchars($row['jam_start_time']) . "</td></tr>";
        $html .= "<tr><th>マッチング終了日時</th><td>" . htmlspecialchars($row['jam_period_time']) . "</td></tr>";
        $html .= "<tr><th>チーム人数</th><td>" . htmlspecialchars($row['team_min']) . " ~ " . htmlspecialchars($row['team_max']) . "名</td></tr>";
        $html .= "<tr><th>パスワード</th><td>" . htmlspecialchars($row['jam_password']) . "</td></tr>";
        $html .= "</table>";
        $html .= "<form action='checkJam.php' method='post' class='left-button' onsubmit='return confirmMatching();'>
                    <input type='hidden' name='jam_num' value='" . htmlspecialchars($row['jam_num']) . "'>
                    <input type='hidden' name='low_lim' value='" . htmlspecialchars($row['team_min']) . "'>
                    <input type='hidden' name='applicant_amount' value='" . $applicantAmount . "'>
                    <input type='hidden' name='end_jam' value='true'>
                    <div style=\"text-align:center;\"><button type='submit' class='apply_btn'>マッチングを開始する</button></div>
                  </form>";
        $html .= "</div>";
    }

    // 参加者情報のテーブル
    $html .= "<div class='confirmRecruitBox'>";
    $html .= "<table>";
    $html .= "<thead><tr><th>役割</th><th>参加者</th></tr></thead><tbody>";

    // バックエンド
    $html .= "<tr><th>バックエンド</th><td>";
    for ($i = 0; $i < count($backApplicant); $i++) {
        $row = $backApplicant[$i];
        $html .= "<a href='othersProfile.php?recruiter_id=" . htmlspecialchars($row['applicant_id']) . "'>" . htmlspecialchars($row['applicant_id']) . "</a>";
        if ($i < count($backApplicant) - 1) $html .= ", ";
    }
    $html .= "</td></tr>";

    // フロントエンド
    $html .= "<tr><th>フロントエンド</th><td>";
    for ($i = 0; $i < count($frontApplicant); $i++) {
        $row = $frontApplicant[$i];
        $html .= "<a href='othersProfile.php?recruiter_id=" . htmlspecialchars($row['applicant_id']) . "'>" . htmlspecialchars($row['applicant_id']) . "</a>";
        if ($i < count($frontApplicant) - 1) $html .= ", ";
    }
    $html .= "</td></tr>";

    // フルスタック
    $html .= "<tr><th>フルスタック</th><td>";
    for ($i = 0; $i < count($fullApplicant); $i++) {
        $row = $fullApplicant[$i];
        $html .= "<a href='othersProfile.php?recruiter_id=" . htmlspecialchars($row['applicant_id']) . "'>" . htmlspecialchars($row['applicant_id']) . "</a>";
        if ($i < count($fullApplicant) - 1) $html .= ", ";
    }
    $html .= "</td></tr>";

    $html .= "</tbody></table>";
    $html .= "</div>";
}

echo $html;
?>