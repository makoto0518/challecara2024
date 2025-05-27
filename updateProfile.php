<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

$debug_mode = false;

print (printHeader("プロフィール編集", "プロフィール編集"));


$currentName = "";
$currentPass = "";
$currentRole = "";
$currentIntroduction = "";
$currentIcon = "";
$uploadFile = "";
//画像ファイルを選択しないまま保存をした場合、空のuploadFileで上書きしてしまうから、画像が表示されなくなる。
//2024.9.30 ファイルがアップロードされていない場合の処理を追加する

//もしuserIdが分かったとき
if (isset($_SESSION['userId'])) {

    //あらかじめ、現在のユーザー情報を入手しておく
    $currentStatus = getUserInfoFromId($_SESSION['userId']);

    if ($currentStatus == null) {
        echo "ユーザー情報の取得に失敗しました。";
    } else {
        //現在のユーザー情報を設定
        $currentName = $currentStatus[0]['user_name'];
        $currentPass = $currentStatus[0]['user_pw'];
        $currentRole = $role[$currentStatus[0]['user_role']];
        $currentIntroduction = $currentStatus[0]['user_profile'];
        $currentIcon = $currentStatus[0]['user_icon'];  // 既存のアイコンのパスを取得

        if ($debug_mode) {
            echo "デバッグが有効になっています。";
            echo "現在のユーザー情報";
            echo "<pre>名前:" . print_r($currentName, true);
            echo "<pre>Pass:" . print_r($currentPass, true);
            echo "<pre>ジャンル:" . print_r($currentrole, true);
            echo "<pre>自己紹介:" . print_r($currentIntroduction, true);
            echo "<pre>icon:" . print_r($currentIcon, true);
            echo "<br><br><br><br><br>";
        }
    }
}




if (isset($_SESSION['userId'])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { //もし、何かしらの更新データを受け取ったとき

        //2024.9.30 ファイルがアップロードされていない場合、もしくはアップロードに失敗した場合に、現在の画像を使用する
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $uploadFile = $currentIcon;
        } else if (isset($_FILES['image'])) {         // ファイルがアップロードされているとき
            // 画像ファイル情報を取得
            $file = $_FILES['image'];

            // ファイルのエラーチェック
            if ($file['error'] === UPLOAD_ERR_OK) {
                // 保存するディレクトリを設定
                $uploadDir = 'uploads/';

                // ディレクトリが存在しない場合は作成
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // ファイルの拡張子を取得
                $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);

                // ランダムな名前を生成する関数
                function generateRandomName($fileExt)
                {
                    return bin2hex(random_bytes(16)) . '.' . $fileExt;
                }

                // ファイル名が既存のものと被らないように生成する
                do {
                    $randomName = generateRandomName($fileExt);
                    $uploadFile = $uploadDir . $randomName;
                } while (file_exists($uploadFile));  // ファイル名が存在する場合は再生成

                // ファイルをディレクトリに保存
                if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                    // 以下、画像のリサイズ処理
                    $image = null;

                    // 画像の拡張子を判別
                    switch ($fileExt) {
                        case 'jpg':
                        case 'jpeg':
                            $image = imagecreatefromjpeg($uploadFile);
                            break;
                        case 'png':
                            $image = imagecreatefrompng($uploadFile);
                            break;
                        case 'gif':
                            $image = imagecreatefromgif($uploadFile);
                            break;
                        default:
                            echo 'サポートされていないファイル形式です。';
                            exit;
                    }
                    if($image === false) {
                        echo '画像の読み込みに失敗しました。';
                    }
                    // 画像サイズを取得
                    list($w_src, $h_src) = getimagesize($uploadFile);
                    if (!$w_src || !$h_src) {
                        echo '画像サイズの読み込みに失敗しました。';
                    } else {

                        $w = $h = 250;  // リサイズ後の画像のサイズ

                        $resizedImage = imagecreatetruecolor($w, $h);   // リサイズ後の画像のリソースを作成

                        $x_diff = $y_diff = 0;
                        $w_diff = $h_diff = 0;

                        // 画像の中心位置を求める
                        if ($w_src > $h_src) {
                            // 横長
                            $w_diff = $h_src;
                            $h_diff = $h_src;
                            $x_diff = ceil(($w_src - $h_src) * 0.5);    // int型にするためにceil()で小数点以下切り上げ
                            $y_diff = 0;
                        }
                        else if ($w_src < $h_src) {
                            // 縦長
                            $w_diff = $w_src;
                            $h_diff = $w_src;
                            $x_diff = 0;
                            $y_diff = ceil(($h_src - $w_src) * 0.5);    // int型にするためにceil()で小数点以下切り上げ
                        }
                        else if ($w_src == $h_src) {
                            // 正方形
                            $w_diff = $w_src;
                            $h_diff = $h_src;
                            $x_diff = 0;
                            $y_diff = 0;
                        }

                        // リサイズ処理
                        imagecopyresampled($resizedImage, $image, 0, 0, $x_diff, $y_diff, $w, $h, $w_diff, $h_diff);
                        // リサイズした画像を保存
                        switch ($fileExt) {
                            case 'jpg':
                            case 'jpeg':
                                imagejpeg($resizedImage, $uploadFile);
                                break;
                            case 'png':
                                imagepng($resizedImage, $uploadFile);
                                break;
                        }

                        echo "画像がアップロードされました。";
                    }
                } else {
                    echo "画像のアップロードに失敗しました。";
                }
            } else {
                echo "ファイルエラーが発生しました: " . $file['error'];
            }
        } else {
            echo "画像が選択されていません。";
        }


        $currentName = $_POST['new_Name'];
        $currentPass = $_POST['new_Pw'];
        $currentrole = $_POST['new_role'];
        $currentIntroduction = $_POST['new_Introduction'];

        $updateResult = updateUserInfo(
            $_POST['new_Pw'],
            $_POST['new_Name'],
            $_POST['new_role'],
            $_POST['new_Introduction'],
            $uploadFile, 
            $_SESSION['userId'],
        );


        if ($updateResult) {
            echo "更新に成功しました。";

            if ($debug_mode) {
                echo "更新後のユーザー情報";
                echo "<pre>名前:" . print_r($currentName, true);
                echo "<pre>Pass:" . print_r($currentPass, true);
                echo "<pre>ジャンル:" . print_r($currentrole, true);
                echo "<pre>自己紹介:" . print_r($currentIntroduction, true);
                echo "<pre>icon:" . print_r($currentIcon, true);
                echo "<pre>FILE:" . print_r($_FILES, true);
            }
        } else {
            echo "更新に失敗しました";
        }
    } else {  //更新データを受け取っていない場合
        echo "未更新です";
    }
} else {
    echo "SESSIONからユーザーIDを受け取れませんでした";
}
?>

<div id="updateMessage" style="display:none;">更新されました</div>

<div class="recruitFormBox">
    <form class="recruitForm" action="updateProfile.php" method="post" enctype="multipart/form-data">
        <div class="formGroup">
            <label for="name">名前</label>
            <input type="text" id="name" name="new_Name" value="<?php echo htmlspecialchars($currentName); ?>">
        </div>

        <div class="formGroup">
            <label for="password">パスワード</label>
            <input type="text" id="password" name="new_Pw" value="<?php echo htmlspecialchars($currentPass); ?>">
        </div>

        <div class="formGroup">
            <label for="role">ジャンル</label>
            <select id="role" name="new_role">
                <option value="0" <?php echo ($currentRole == '0') ? 'selected' : ''; ?>>フロントエンド</option>
                <option value="1" <?php echo ($currentRole == '1') ? 'selected' : ''; ?>>バックエンド</option>
                <option value="2" <?php echo ($currentRole == '2') ? 'selected' : ''; ?>>フルスタック</option>
            </select>
        </div>

        <div class="formGroup">
            <label for="introduction">自己紹介</label>
            <input type="text" id="introduction" name="new_Introduction"
                value="<?php echo htmlspecialchars($currentIntroduction); ?>">
        </div>

        <div class="formGroup">
            <label for="icon">アイコン</label>
            <!-- 2024.9.30 今のアイコン情報を入れておく -->
            <input type="file" id="icon" name="image" accept="image/*">
        </div>

        <div class="formButtons">
            <input class="apply_btn" type="submit" value="保存">
        </div>
    </form>
</div>