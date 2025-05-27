<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

print (printHeader("チャット一覧", "チャット一覧"));

executeJamMatching($_SESSION['userId']);

//チャット可能な募集やチームと最新のチャット履歴を載せる
//自分が開催している募集、承認された(参加中)募集に分ける
//まず、開催している募集を全て入手し、それら1つずつの最新のチャット投稿を載せる
//参加中の募集に対しても同様


$recruitList = getRecruitFromUserId($_SESSION['userId'], 1); //今自分が募集しているもの一覧
// var_dump($recruitList);
$latestRecruitChat = ""; //最後のチャット内容
$recruitHtml = "";
// echo "<pre>recruitList" . print_r($recruitList, true) . "</pre>";

//今自分が承認されているもの一覧
$approvalList = getApplicationByStatus($_SESSION['userId'], 2);
$latestApprovalChat = ""; //最後のチャット内容
$approvalHtml = "";
// echo "<pre>approvalList:" . print_r($approvalList, true) . "</pre>";

if ($recruitList == null) {
    $recruitHtml = "<p style=\"text-align:center;\">募集中のものはありません</p>";
} else { //募集があった場合、その募集の最新のチャットを載せる
    $table = "recruit";  //recruitテーブルを指定
    $recruitNum = $recruitList[0]['recruit_num']; //自分の募集の固有番号を入手
    $recruitTitle = $recruitList[0]['recruit_title']; //募集タイトル

    // echo "<pre:recruitNum>" . print_r($recruitNum, true) . "</pre>";
    // echo "<pre:recruittitle>" . print_r($recruitTitle, true) . "</pre>";  

    $roomNum = getRoomNum($table, $recruitNum); //recruitテーブルの中の募集番号に一致するチャットルームの番号を取得
    // echo "<pre>roomNum:" . print_r($roomNum, true) . "</pre>";
    $RecruitChat = getChat($table, $roomNum); //チャットルーム番号に一致するチャット内容を全て取得
    // echo "<pre>RecruitChat:" . print_r($RecruitChat, true) . "</pre>";

    $latestRecruitChat = getLastChatDetail($RecruitChat);   //得たチャット内容の最後に投稿した人の名前と投稿内容、時間を取得
    // echo "<pre>latestRecruitChat:" . print_r($latestRecruitChat, true) . "</pre>";


    $recruitHtml .= "<div class=\"Recruitment\">"; 
    $recruitHtml .= "<h4 style=\"margin: 5px 0; padding: 0;\">" . $recruitTitle . "</h4>";
    if ($RecruitChat == null) {
        $recruitHtml .= "<p style=\"margin: 5px 0; padding: 0;\">まだチャットがありません</p>";
    } else {
        $recruitHtml .= "<p style=\"margin: 5px 0; padding: 0;\">".$latestRecruitChat['senderId'] . ":" . $latestRecruitChat['chatContent'] . "</p><p style=\"margin: 5px 0; padding: 0;\">" . $latestRecruitChat['time'] . "</p>";
    }
    $recruitHtml .= "<form action=\"recruitChat.php\" method=\"post\">";
    $recruitHtml .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $recruitNum . "\">";
    $recruitHtml .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\">";
    $recruitHtml .= "</form>";
    $recruitHtml .= "</div>";
}


if ($approvalList == null) {
    $approvalHtml = "<p style=\"text-align:center;\">承認されたものはありません</p>";
} else {
    $table = 'recruit'; //リクルートテーブルを指定
    for ($i = 0; $i < count($approvalList); $i++) { //自分が応募した全ての募集に対して最新チャットを得る

        $recruitNum = $approvalList[$i]['recruit_num'];  //応募した募集の固有番号を入手
        // echo "<pre>approvalNum:" . print_r($recruitNum, true) . "</pre>";

        //応募した募集の詳細を入手
        $approvalDitail = getRecruitFromNum($recruitNum);
        // echo "<pre>approvalDitail:" . print_r($approvalDitail, true) . "</pre>";

        //応募した募集の名前を入手
        $approvalTitle = $approvalDitail[0]['recruit_title'];
        // echo "<pre>approvalTitle:" . print_r($approvalTitle, true) . "</pre>";

        //recruitテーブルの中の募集番号に一致するチャットルームの番号を取得
        $roomNum = getRoomNum($table, $recruitNum); 

        //チャット内容を入手
        $approvalChat = getChat($table, $roomNum);
        // echo "<pre>approvalChat:" . print_r($approvalChat, true) . "</pre>";

        //最後に投稿された人の名前、内容、時間を入手
        $latestApprovalChat = getLastChatDetail($approvalChat);
        // echo "<pre>latestApprovalChat:" . print_r($latestApprovalChat, true) . "</pre>";
        
        
        $approvalHtml .= "<div class=\"Recruitment\">";
        $approvalHtml .= "<h4 style=\"margin: 5px 0; padding: 0;\">" . $approvalTitle . "</h4>";
        if ($approvalChat == null) {
            $approvalHtml .= "<p style=\"margin: 5px 0; padding: 0;\">まだチャットがありません</p>";
        } else {
            $approvalHtml .= "<p style=\"margin: 5px 0; padding: 0;\">" . $latestApprovalChat['senderId'] . ":" . $latestApprovalChat['chatContent'] . "</p><p style=\"margin: 5px 0; padding: 0;\">" . $latestApprovalChat['time'] . "</p>";
        }
        $approvalHtml .= "<form action=\"recruitChat.php\" method=\"post\">";
        $approvalHtml .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $recruitNum . "\">";
        $approvalHtml .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\">";
        $approvalHtml .= "</form>";
        $approvalHtml .= "</div>";
    }
}

// ジャム
$jamHtml = "";

if(getJamRoomNumFromUserId($_SESSION['userId']) == null) {
    $jamHtml .= "<p style=\"text-align:center;\">マッチしたチームがありません</p>";
} else {
    $tmp = getJamRoomNumFromUserId($_SESSION['userId']);    // 参加しているジャムチームの部屋番号をユーザーIDから取得する

    for($i = 0; $i < count($tmp); $i++) {
        $jamRoomNum = $tmp[$i]['room_num'];
        // var_dump($jamRoomNum);
        
        $tmpJamNum = getJamNum($jamRoomNum);          // 参加しているジャムの番号をジャムチームの部屋番号から取得する
        // var_dump($tmpJamNum);
        $jamNum = $tmpJamNum[0]['jam_num'];
        
        $jamList = getJam($jamNum);             // ジャムの情報をジャムの番号から取得する

        $latestJamChat = "";    // 最後のチャット内容

        $table = 'jam';
        $jamTitle = $jamList[0]['hackason_name'];   // ジャムのタイトル（ハッカソン名）
        // var_dump($jamRoomNum);
        $jamChat = getChat($table, $jamRoomNum);    // チャットの情報を取得
        // var_dump($jamChat);
        $latestJamChat = getLastChatDetail($jamChat);   // 最新のチャットの情報を取得

        $jamHtml .= "<div class=\"Recruitment\">";
        $jamHtml .= "<h4 style=\"margin: 5px 0; padding: 0;\">" . $jamTitle . "</h4>";
        if ($latestJamChat == null) {
            $jamHtml .= "<p style=\"margin: 5px 0; padding: 0;\">まだチャットがありません</p>";
        } else {
            $jamHtml .= "<p style=\"margin: 5px 0; padding: 0;\">" . $latestJamChat['senderId'] . ":" . $latestJamChat['chatContent'] . "</p><p style=\"margin: 5px 0; padding: 0;\">" . $latestJamChat['time'] . "</p>";
        }
        $jamHtml .= "<form action=\"jamChat.php\" method=\"post\">";
        $jamHtml .= "<input type=\"hidden\" name=\"roomNum\" value=\"" . $jamRoomNum . "\">";
        $jamHtml .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\">";
        $jamHtml .= "</form>";
        $jamHtml .= "</div>";
    }
}


?>

<div class="displayChatContent">   
    <div class="displayChatBox" style="border-bottom: white solid 5px;">
        <h4>あなたの募集のチャット</h4>
        <?php echo $recruitHtml; ?>
    </div>

    <div class="displayChatBox" style="border-bottom: white solid 5px;">
        <h4>承認された応募のチャット</h4>
        <?php echo $approvalHtml; ?>
    </div>
    <div class="displayChatBox">
        <h4>マッチングしたジャムのチャット</h4>
        <?php echo $jamHtml; ?>
    </div>
</div>