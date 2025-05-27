<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

print(printHeader("ジャムマッチングリスト","ジャムマッチングリスト"));
$html = "";
$message = "";
$flag = false;

// ジャムへの参加が押された場合
if(isset($_POST['role'])){
    $result = applyJam($_SESSION['userId'], $_POST['jamNum'], $_POST['role'], $_POST['password']);
    if(strcmp($result, "SQL_ERROR") == 0) {
        $html .= "SQLの実行に失敗しました。";
    } else if($result == true) {
        $message .= "<h3>ジャムへの参加が完了しました</h3>";
        $flag = true;
    } else {
        $message .= "<h3>パスワードが違います<h3>";
    }

   // まだジャムへの参加が押されていない場合

}
    // 以下、ジャムマッチングの詳細表示と参加フォーム
    if(!isset($_POST['jamNum'])) {
        $html .= "ジャム情報の取得に失敗しました。";
    } else {
        $result = getJam($_POST['jamNum']); // active状態のジャムを取り出す
        if($result == null) {
            $html .= "ジャム情報の取得に失敗しました。";
        } else {
            $row = $result[0];
            // $html .= "<div style=\"border: 2px #658db449 solid; margin:10px; padding:10px;\">";
            $html .= "<div class=\"confirmRecruitBox\">";
            $html .=    "<table>";
            $html .=        "<tr>"; 
            $html .=            "<th>タイトル</th>";
            $html .=            "<td>" . $row['hackason_name'] . "</td>";
            $html .=        "</tr>";
            $html .=        "<tr>";
            $html .=            "<th>開催地</th>";
            $html .=            "<td>" . $location[$row['hackason_location']] . "</td>";
            $html .=        "</tr>";
            $html .=        "<tr>";
            $html .=            "<th>期限</th>";
            $html .=            "<td>" . $row['jam_start_time'] . "～" . $row['jam_period_time'] . "</td>";
            $html .=        "</tr>";
            $html .=        "<tr>";
            $html .=            "<th>各チーム人数</th>";
            $html .=            "<td>" . $row['team_min'] . "～" . $row['team_max'] . "人";
            $html .=        "</tr>";
            $html .=    "</table>";
            $html .= "</div>";
            // $html .= "タイトル：" . $row['hackason_name'] . "<br>";
            // $html .= "ハッカソン開催地：" . $row['hackason_location'] . "<br>";
            // $html .= "マッチング開始日時：" . $row['jam_start_time'] . "<br>マッチング終了日時：" . $row['jam_period_time'];
            // $html .= "<br>チーム人数：" . $row['team_min'] . " ~ " . $row['team_max'] . "名<br>";
            // $html .= "</div>";

            $html .= "<div class=\"recruitFormBox\">";
            // すでに参加している場合、参加ボタンは表示しない
            if ($flag) {
                $html .= "<h3 style=\"text-align:center;\">応募に成功しました！</h3>"; //成功した旨のメッセージを出す。入力フォームは出さない。
            }
            else if(jamApplyExists($_SESSION['userId'], $_POST['jamNum'])) {
                $html .= "<h3>このジャムマッチングには参加しています</h3>";
            } else {
                $html .= "<form class=\"jamForm\" method=\"post\">";
                $html .=      "<div class=\"jamFormGroup\">";
                $html .=         "<div>";
                $html .=            "<label for=\"1\">役割</label>";
                $html .=            "<select name=\"role\" id=\"1\">";
                $html .=                "<option value=\"0\">バックエンド</option>";
                $html .=                "<option value=\"1\">フロントエンド</option>";
                $html .=                "<option value=\"2\">フルスタック</option>";
                $html .=            "</select>";
                $html .=         "</div>";
                $html .=         "<div>";
                $html .=            "<label for=\"2\">パスワード</label>";
                $html .=             "<input type=\"text\" id=\"2\" name=\"password\">";
                $html .=         "</div>";
                $html .=       "</div>";
                $html .=       "<div class=\"formButtons\">";
                $html .=          "<input type=\"hidden\" name=\"jamNum\" value=\"" . $_POST['jamNum'] . "\">";
                $html .=          "<input type=\"submit\" value=\"参加する\" class=\"jamApplyBtn\">";
                $html .=       "</div>";
                $html .= "</form>";
                // $html .= "役割：";
                // $html .= "<form method='post' action='jamApply'>";
                // $html .= "<select name='role'>";
                // $html .= "<option value='0'>バックエンド</option>";
                // $html .= "<option value='1'>フロントエンド</option>";
                // $html .= "<option value='2'>フルスタック</option>";
                // $html .= "</select>";
                // $html .= "<br>パスワード:<input type='text' name='password'>";
                // $html .= "<input type='hidden' name='jamNum' value=" . $_POST['jamNum'] . ">";
                // $html .= "<input type='submit' class='btn' value='参加する'>";
                // $html .= "</form>";
            }
            $html .="</div>";

        }
    }


print($html);

?>