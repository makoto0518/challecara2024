<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}
print (printHeader("ConneCre", "募集を変更"));

// echo "<pre>POST:" . print_r($_POST, true) . "</pre>";


?>


<!-- 2024/10/28 新しいデータベースに合うよう修正 -->
 <div class="recruitFormBox">
<form class="recruitForm" action="confirmUpdateRecruit.php" method="post">
    <input type="hidden" name="recruitNum" value="<?php echo htmlspecialchars($_POST["recruitNum"]); ?>">

    <div class="formGroup">
    <label for="1">タイトル</label>
    <input type="text" name="recruitTitle" class="recruitTitle"
        value="<?php echo isset($_POST['recruitTitle']) ? htmlspecialchars($_POST['recruitTitle']) : ''; ?>">
    </input>
</div>

<div class="formGroup">
    <label for="2">内容</label>
    <textarea name="recruitContent" class="recruit_content" cols="50"
        rows="10"><?php echo isset($_POST['recruitContent']) ? htmlspecialchars($_POST['recruitContent']) : ''; ?></textarea>
</div>

<div class="formGroup">
    <label for="3">役割</label>
    <select name="recruitRole" class="select_role">
        <option value="0" <?php echo isset($_POST['role']) && $_POST['role'] == '0' ? 'selected' : ''; ?>>バックエンド</option>
        <option value="1" <?php echo isset($_POST['role']) && $_POST['role'] == '1' ? 'selected' : ''; ?>>フロントエンド</option>
        <option value="2" <?php echo isset($_POST['role']) && $_POST['role'] == '2' ? 'selected' : ''; ?>>フルスタック</option>
    </select>
</div>

<div class="formGroup">
    <label for="4">人数</label>
    <input type="number" name="recruitCapacity" min="1"
        value="<?php echo isset($_POST['recruitCapacity']) ? htmlspecialchars($_POST['recruitCapacity']) : ''; ?>">
    </input>
</div>

<div class="formGroup">
    <label for="5">場所</label>
    <select name="recruitLocation">
        <?php
        foreach ($location as $key => $loc) {
            echo "<option value='$key' " . (isset($_POST['recruitLocation']) && $_POST['recruitLocation'] == $key ? 'selected' : '') . ">$loc</option>";
        }
        ?>
    </select>
    </div>

    <div class="formButtons">
    <input type="reset" value="リセット" class="reset_btn">
    <input type="submit" value="編集する" class="apply_btn">
</form>
</div>
    </div>
</body>

</html>