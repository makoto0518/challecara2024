<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

print (printHeader("ConneCre", "募集を作成"));

$debug_mode = false;
if ($debug_mode) {
    echo "<pre>POST:" . print_r($_POST, true) . "</pre>"; 
}

?>


<div class="recruitFormBox">
    <form class="recruitForm" action="confirmRecruit.php" method="post">
        <div class="formGroup">
            <label for="1">タイトル</label>
            <input id="1" type="text" name="recruitTitle" class="recruitTitle" required  value="<?php echo isset($_POST['recruitTitle']) ? htmlspecialchars($_POST['recruitTitle']) : ''; ?>">
        </div>

        <div class="formGroup">
            <label for="2">募集役割</label>
            <select id="2" name="role" class="selectRole">
                        <option value="0" <?php echo isset($_POST['role']) && $_POST['role'] == '0' ? 'selected' : ''; ?>>バックエンド</option>
                        <option value="1" <?php echo isset($_POST['role']) && $_POST['role'] == '1' ? 'selected' : ''; ?>>フロントエンド</option>
                        <option value="2" <?php echo isset($_POST['role']) && $_POST['role'] == '2' ? 'selected' : ''; ?>>フルスタック</option>
            </select>
        </div>

        <div class="formGroup">
            <label for="3">開催場所</label>
            <select id="3" name="loc" class="recruitLocation">
                <?php 
                    foreach ($location as $key => $loc) {
                    echo "<option value='$key' " . (isset($_POST['location']) && $_POST['location'] == $key ? 'selected' : '') . ">$loc</option>";
                    }
                ?>
            </select>
        </div>

        <div class="formGroup">
            <label for="4">募集人数</label>
            <input  id="4" type="number" name="capacity" min="1" required value="<?php echo isset($_POST['capacity']) ? htmlspecialchars($_POST['capacity']) : '';?>">
        </div>           
        
        
        <div class="formGroup">
            <label for="5">募集内容</label>
            <textarea id="5" name="content" class="recruitContent" rows="5" required ><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
        </div>
        

        <div class="formButtons">
            <input type="submit" value="確認する" class="apply_btn">
        </div>
    </form>
<div>

</body>

</html>