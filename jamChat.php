<?php
// include "mylib.php";
// if(!isset($_SESSION)) {
//     session_start();
// }
// print (printHeader("チャット", "チャット"));

// $result;
// $table = "jam";

// executeJamMatching($_SESSION['userId']);

// if ( !isset($_POST["roomNum"]) ) {
//     echo "ジャム情報の取得に失敗しました。";
// } else {
//     $html = "";
//     $roomNum = $_POST["roomNum"];   // 募集番号からチャットルーム番号を取得

//     getJamApplicantRole($_POST['roomNum'], 0);

//     // チャットの送信処理
//     if( isset($_POST["chatContent"]) ) {
//         sendChat($table, $_SESSION["userId"], $_POST["chatContent"], $roomNum);
//     }

//     $result = getChat($table, $roomNum);

//     // チャット送信用フォーム
//     $html .= "<form action='jamChat.php' method='post'>
//         <input type='text' name='chatContent'>
//         <input type='hidden' name='roomNum' value=" . $roomNum . ">
//         <button type='submit'>送信する</button></form>";

//     for( $i = 0; $i < count($result); $i++ ) {
//         $row = $result[$i];
//         $icon = getIconFromId($row['sender_id']);
//         $html .= "<div style=\"border: 2px #658db449 solid; margin:10px; padding:10px;\">";

//         if( strcmp($row['sender_id'], $_SESSION['userId']) == 0){    // 自分のチャットであるとき
//             $html .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a><br>";
//         } else {    // 他人のチャットであるとき
//             $html .= "<a href=\"othersProfile.php?jamer_id=" . $row['sender_id'] . "\"><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a><br>";
//         }

//         if( strcmp($row['sender_id'], $_SESSION['userId']) == 0){    // 自分のチャットであるとき
//             $html .= "Name　　：<a href=\"myProfile.php\">" . $row['sender_id'] . "</a><br>";
//         } else {    // 他人のチャットであるとき
//             $html .= "Name　　：<a href=\"othersProfile.php?jamer_id=" . $row['sender_id'] . "\">" . $row['sender_id'] . "</a><br>"; 
//         }

//         $html .= "送信日時：" . $row["chat_datetime"] . "<br>";
//         $html .= "送信内容：" . $row["chat_content"] . "<br>";

//         $html .= "</div>";
//     }



//     echo $html;
// }
?>


<?php
include "mylib.php";
if(!isset($_SESSION)) {
    session_start();
}
print (printHeader("チャット", "チャット"));

$result;
$table = "jam";
$html = "";

executeJamMatching($_SESSION['userId']);

if ( !isset($_POST["roomNum"]) ) {
    echo "ジャム情報の取得に失敗しました。";
} else {
    $roomNum = $_POST["roomNum"];   // 募集番号からチャットルーム番号を取得

    $applicant = getJamMember($roomNum);
    // echo "<pre>applicant:" . print_r($applicant, true) . "</pre>";

    $result = getChat($table, $roomNum);
    // var_dump($result);

    // 参加者の一覧表示
    // $html = "<div class='applicantContainer'><h3>参加者一覧</h3>";
    // $html .= "<div class='rec'>";
    // $icon = getIconFromId($recruiter);
    // if( strcmp($recruiter, $_SESSION['userId']) == 0) {
    //     $html .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //     $html .= "<a href='myProfile.php'>" . $_SESSION['userId'] . "</a></div>";
    // } else {
    //     $html .= "<a href='othersProfile.php?recruiter_id=" . $recruiter . "' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //     $html .= "<a href='othersProfile.php?recruiter_id=" . $recruiter . "'>" . $recruiter . "</a></div>";
    // }

    // if($applicant != null) {
    //     for($i = 0; $i < count($applicant); $i++){
    //         $icon = getIconFromId($applicant[$i]);
    //         if( strcmp($applicant[$i], $_SESSION['userId']) == 0) {
    //             $html .= "<div class='app" . $i . "'>";
    //             $html .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //             $html .= "<a href='myProfile.php'>" . $_SESSION['userId'] . "</a></div>";
    //         } else {
    //             $html .= "<div class='app" . $i . "'>";
    //             $html .= "<a href='othersProfile.php?recruiter_id=" . $applicant[$i] . "' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //             $html .= "<a href='othersProfile.php?recruiter_id=" . $applicant[$i] . "'>" . $applicant[$i] . "</a></div>";
    //         }
    //     }
    // }
    // $html .= "</div>";
    $html .= "<div class=\"chatContainer\" id=\"scroll-inner\">"; //メッセージ、入力欄全体を囲む
    $html .= "  <div class=\"chatMessages\">"; //メッセージの塊だけを囲む

    // チャットの送信処理
    if( isset($_POST["chatContent"]) ) {
        sendChat($table, $_SESSION["userId"], $_POST["chatContent"], $roomNum);
    }

    $result = getChat($table, $roomNum);

    for( $i = count($result) - 1; $i >= 0; $i-- ) {
        $row = $result[$i];
        $icon = getIconFromId($row['sender_id']);

        if( strcmp($row['sender_id'], $_SESSION['userId']) == 0){    // 自分のチャットであるとき
            $html .= "<div class=\"chatMessage myChatMessage\">"; //1つのチャット(名前、時間、アイコン、送信内容すべてを含む)を入れる箱 これは縦並びを指定
            // $html .= "<div class=\"myMessage\">";
            $html .= "<div class=\"chatSubItem\">"; //ここに、ユーザー名、投稿時間を入れる箱を作る
            $html .= "<a href=\"myProfile.php\">" . $row['sender_id'] . "</a>　　" . $row["chat_datetime"]; //名前、送信時間をセット
            $html .= "</div>"; //chatSubItemを閉じる

            $html .= "<div class=\"IconAndContentBox\">"; //アイコンと送信内容を入れる箱を作る これは横並び
            //プロフィールアイコンをセット
            // $html .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";
        } else {    // 他人のチャットであるとき
            $html .= "<div class=\"chatMessage\">"; //1つのチャット(名前、時間、アイコン、送信内容すべてを含む)を入れる箱 これは縦並びを指定
            // $html .= "<div class=\"otherMessage\">";
            $html .= "<div class=\"chatSubItem\">"; //ここに、ユーザー名、投稿時間を入れる箱を作る
            $html .= "<a href=\"othersProfile.php?recruiter_id=" . $row['sender_id'] . "\">" . $row['sender_id'] . "</a>　　" . $row["chat_datetime"]; //名前、送信時間をセット
            $html .= "</div>";  //chatSubItemを閉じる

            $html .= "<div class=\"IconAndContentBox\">"; //アイコンと送信内容を入れる箱を作る これは横並び
            //プロフィールアイコンをセット
            $html .= "<a href=\"othersProfile.php?recruiter_id=" . $row['sender_id'] . "\"><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";
        }


        $html .= "<div class=\"chatContentBox\">" .  $row["chat_content"] . "</div>";
        if ( strcmp($row['sender_id'], $_SESSION['userId']) == 0) {
            $html  .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";
        }
        $html .= "         </div>"; //IconAndContentBoxを閉じる
        // $html .= "      </div>"; //my or other Messageを閉じる
        $html .= "     </div>";  //chatMessageを閉じる
    }
        $html .= "    </div>"; //Chatmessagesを閉じる



        $html .= "</div>"; //chatContainerを閉じる

        $html .= "
        <div class=\"fixedForm\">
            <form action='jamChat.php' method='post'>
                <button onclick=\"openModal('memberModal')\" class=\"apply_btn\">メンバー</button>
                <input type=\"text\" name='chatContent' class=\"fixedTextForm\" placeholder='メッセージを入力' required>
                <input type='hidden' name='roomNum' value='" . htmlspecialchars($_POST['roomNum']) . "'>
                <button type='submit' class=\"fixedButton\">送信</button>
            </form>
        </div>

        <div id=\"memberModal\" class=\"modal\">
            <div class=\"modal-content\">
                <span class=\"close\" onclick=\"closeModal('memberModal')\">&times;</span>
                <h2>メンバー<h2>   
                <div class='modal-members'>";

        
        // 参加者一覧を表示
        if ($applicant != null) {
            for ($i = 0; $i < count($applicant); $i++) {
                $icon = getIconFromId($applicant[$i]);
                $memberRole = getRole($applicant[$i]);
                $html .= "<div class='modal-member'>
                            <a href='" . (strcmp($applicant[$i], $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $applicant[$i]) . "'>
                            <img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\">
                            </a>
                            <a href='" . (strcmp($applicant[$i], $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $applicant[$i]) . "'>" . $applicant[$i] . "</a>："
                            . $role[$memberRole] .
                         "</div>";
            }
        }

        $html .= "
                        </div> <!-- modal-members -->
                </div> <!-- modal-content -->
            </div> <!-- memberModal -->
";

    echo $html;
}
?>