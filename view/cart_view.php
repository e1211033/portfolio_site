<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>ショッピングカートページ</title>
  <link type="text/css" rel="stylesheet" href="./css/common.css">
  <link type="text/css" rel="stylesheet" href="./css/cart.css">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header.php';?>
  <div class="content">
    <h1 class="title">ショッピングカート</h1>
<?php foreach ($err_msg as $value) { ?>
  <p><?php print $value; ?></p>
<?php } ?>

<?php if (empty($result_msg) !== TRUE) { ?>
    <p class="success-msg"><?php print $result_msg; ?></p>
<?php } ?>
<?php if (count($err_msg) === 0) { ?>
    <div class="cart-list-title">
      <span class="cart-list-img">商品画像</span>
      <span class="cart-list-name">商品名</span></span>
      <span class="cart-list-price">価格</span>
      <span class="cart-list-num">数量</span>
    </div>
    <ul class="cart-list">
<?php foreach ($data as $value)  { ?>
      <li>
        <div class="cart-item">
          <img class="cart-item-img" src="<?php print IMG_DIR . $value['img']; ?>">
          <span class="cart-item-name"><?php print $value['name']; ?></span>
          <form class="cart-item-del" action="./cart.php" method="post">
            <input type="submit" value="削除">
            <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
            <input type="hidden" name="sql_kind" value="delete_cart">
          </form>
          <span class="cart-item-price">¥ <?php print $value['price']; ?></span>
          <form class="form_select_amount" id="form_select_amount<?php print $value['item_id']; ?>" action="./cart.php" method="post">
            <input type="text" class="cart-item-num2" min="0" name="select_amount" value="<?php print $value['amount']; ?>">個&nbsp;<input type="submit" value="変更する">
            <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
            <input type="hidden" name="sql_kind" value="change_cart">
          </form>
        </div>
      </li>
<?php } ?>
    </ul>
    <div class="buy-sum-box">
      <span class="buy-sum-title">合計</span>
      <span class="buy-sum-price">¥<?php print $sum_price; ?></span>
    </div>
    <div>
      <form action="./finish.php" method="post">
        <input class="buy-btn" type="submit" value="購入する">
      </form>
    </div>
<?php } ?>
  </div>
</body>
</html>
