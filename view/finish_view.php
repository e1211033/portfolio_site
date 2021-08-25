<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>購入完了ページ</title>
  <link type="text/css" rel="stylesheet" href="../css/common.css">
  <link type="text/css" rel="stylesheet" href="../css/finish.css">
</head>
<body>
  <header>
    <div class="header-box">
      <a href="./top.php"><img class="logo" src="../images/logo.png" alt="ご当地レトルトオンライン"></a>
      <a class="hedder-left" href="./top.php">ご当地レトルトオンライン</a>
      <a class="hedder-right" href="./logout.php">ログアウト</a>
      <a href="./cart.php" class="cart"></a>
      <p class="hedder-right">ユーザー名：<?php print $_SESSION['user_name']; ?></p>
    </div>
  </header>
  <div class="content">
<?php foreach ($err_msg as $value) { ?>
  <p class="err-msg"><?php print $value; ?></p>
<?php } ?>
<?php if (empty($err_msg)) {?>
    <div class="finish-msg">ご購入ありがとうございました。</div>
<?php } else {?>
    <h2>エラーのため注文は完了していません。</h2>
    <p>注文を修正して再度購入処理を行ってください。</p>
    <a href="./cart.php">カートに戻る</a>
<?php }?>
    <div class="cart-list-title">
      <span class="cart-list-img">商品画像</span>
      <span class="cart-list-name">商品名</span></span>
      <span class="cart-list-price">価格</span>
      <span class="cart-list-num">数量</span>
    </div>
      <ul class="cart-list">
<?php foreach ($cart_data as $value)  { ?>
        <li>
          <div class="cart-item">
            <img class="cart-item-img" src="<?php print IMG_DIR . $value['img']; ?>">
            <span class="cart-item-name"><?php print $value['name']; ?></span>
            <span class="cart-item-price">¥<?php print $value['price']; ?></span>
            <span class="finish-item-price"><?php print $value['amount']; ?></span>
          </div>
        </li>
<?php } ?>
      </ul>
    <div class="buy-sum-box">
      <span class="buy-sum-title">合計</span>
      <span class="buy-sum-price">¥<?php print $sum_price; ?></span>
    </div>
  </div>
</body>
</html>