<?php 
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

print( printHeader("ジャムマッチング", ""));

$debug_mode = false;

// echo "<pre>location:" . print_r($location, true) . "</pre>";
?>


<div class="recruitFormBox">
    <form action="confirmJam.php" method="post" class="recruitForm">
        <div class="formGroup">
            <label for="1">タイトル</label>
            <input id = "1" type="text" name="hackasonName" class="hackasonName" required style="width:350px" value="<?php echo isset($_POST['hackasonName']) ? htmlspecialchars($_POST['hackasonName']) : ''; ?>">
        </div>

        <div class="formGroup">
            <label for="2">開催場所</label>
                <select id = "2" name="jamLocation" class="selectLocation">
                    <?php
                    foreach ($location as $key => $loc) {
                        echo "<option value='$key' " . (isset($_POST['location']) && $_POST['location'] == $key ? 'selected' : '') . ">$loc</option>";
                    }
                    ?>
                </select> 
            </div>
            
            <div class="formGroup">
                <label for="3">1チームの最少人数</label>
                <input type="number" name="minCapacity" min="2" required value="<?php echo isset($_POST['minCapacity']) ? htmlspecialchars($_POST['minCapacity']) : '';?>">
            </div>

            <div class="formGroup">
                <label for="4">1チームの最多人数</label>
                <input type="number" name="maxCapacity" min="2" required value="<?php echo isset($_POST['maxCapacity']) ? htmlspecialchars($_POST['maxCapacity']) : '';?>">
                </div>

            <div class="formGroup">
                <label for="5">終了時間</label>
                <input id="5" type="datetime-local" name="endDate" required value="<?php echo isset($_POST['endDate']) ? htmlspecialchars($_POST['endDate']) : ''; ?>">
                </div>

            <div class="formGroup">
                <label for="6">参加用パスワード</label>
                <input id="6" type="text" name="jamPassword" required value="<?php echo isset($_POST['jamPassword']) ? htmlspecialchars($_POST['jamPassword']) : ''; ?>">
            </div>

            <div class="formButtons">
                <input type="submit" value="確認する" class="apply_btn">
            </div>
    </form>
</div>