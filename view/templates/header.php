<header>
  <div class="header-box">
    <a href="./top.php"><img class="logo" src="./images/logo.png" alt="ご当地レトルトオンライン"></a>
    <a class="hedder-left" href="./top.php">ご当地レトルトオンライン</a>
    <?php if (isset($_SESSION['user_name']) === TRUE) {?> 
      <a class="hedder-right" href="./logout.php">ログアウト</a>
      <a href="./cart.php" class="cart"></a>
      <p class="hedder-right">ユーザー名：<?php print $_SESSION['user_name'];?></p>
    <?php } else {?>
      <a class="hedder-right" href="./register.php">サインアップ</a>
      <a class="hedder-right" href="./login.php">ログイン</a>
    <?php }?>
  </div>
</header>