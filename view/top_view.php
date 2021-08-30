<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>商品一覧ページ</title>
    <link type="text/css" rel="stylesheet" href="./css/common.css">
    <link type="text/css" rel="stylesheet" href="./css/top.css">
  </head>
  <body>
    <header>
      <div class="header-box">
        <a href="./top.php"><img class="logo" src="./images/logo.png" alt="ご当地レトルトオンライン"></a>
        <a class="hedder-left" href="./top.php">ご当地レトルトオンライン</a>
        <a class="hedder-right" href="./logout.php">ログアウト</a>
        <a href="./cart.php" class="cart"></a>
        <p class="hedder-right">ユーザー名：<?php print $_SESSION['user_name']; ?></p>
      </div>
    </header>
    <div class="search content">
      <form method="post">
        <div class="search_detail"><label>価格下限: <input type="text" size="10" name="price_min" placeholder="0~99999" value="<?php if (isset($price_min) && !isset($tmp_err_msg['price_min'])) {print($price_min);}?>"></label></div>
        <div class="search_detail"><label>価格上限: <input type="text" size="10" name="price_max" placeholder="0~99999" value="<?php if (isset($price_max) && !isset($tmp_err_msg['price_max'])) {print($price_max);}?>"></label></div>
        <div class="search_detail"><label>地方:
          <select name="area">
<?php       foreach ($area_list as $key => $value) {?>
<?php         if (isset($area) && ((int)$area === $key)) {?>
            <option value = "<?php print ($key); ?>" selected><?php print $value; ?></option>
<?php         } else {?>
            <option value = "<?php print ($key); ?>"><?php print $value; ?></option>
<?php         }?>
<?php       }?> 
          </select>
        </label></div>
        <div class="search_detail"><label>種類:
          <select name="type">
<?php       foreach ($type_list as $key => $value) {?>
<?php         if (isset($type) && ((int)$type === $key)) {?>
            <option value = "<?php print ($key); ?>" selected><?php print $value; ?></option>
<?php         } else {?>
            <option value = "<?php print ($key); ?>"><?php print $value; ?></option>
<?php         }?>      
<?php       }?> 
          </select>
        </label></div>
        <div class="search_detail"><input type="submit" value="検索"></div>
      </form>
    </div>
    <div class="content">
      <p>検索条件</p>
<?php if (isset($search)) {?>
<?php   foreach ($search as $value) { ?>
      <p><?php print $value; ?></p>
<?php   } ?>
<?php } else { ?>
      <p>指定なし</p>
<?php } ?>
    </div>
    <div class="content">
<?php if (empty($result_msg) !== TRUE) { ?>
      <p class="success-msg"><?php print $result_msg; ?></p>
<?php } ?>
<?php foreach ($err_msg as $value) { ?>
    <p class="err-msg"><?php print $value; ?></p>
<?php } ?>
      <ul class="item-list">
<?php foreach ($data as $value)  { ?>
        <li>
          <div class="item">
            <form action="./top.php" method="post">
              <div class="item-img">
                <img src="<?php print IMG_DIR . $value['img']; ?>" >
              </div>
              <div class="item-info">
                <div class="item-detail">商品名: <?php print $value['name']; ?></div>
                <div class="item-detail">名産地: <?php print $area_detail_list[$value['area_detail']]; ?></div>
                <div class="item-detail">種類: <?php print $type_list[$value['type']]; ?></div>
                <div class="item-detail">¥ <?php print (number_format($value['price'])); ?></div>
              </div>
<?php if ($value['stock'] > 0) { ?>
              <input class="cart-btn" type="submit" value="カートに入れる">
              <input type="hidden" name="price_min" value="<?php if (isset($price_min)) {print($price_min);}?>">
              <input type="hidden" name="price_max" value="<?php if (isset($price_max)) {print($price_max);}?>">
<?php   if (isset($area)) {?>
              <input type="hidden" name="area" value="<?php print ($area); ?>">
<?php   }?>
<?php   if (isset($type)) {?>
              <input type="hidden" name="type" value="<?php print ($type); ?>">
<?php   }?>
<?php } else { ?>
              <p class="sold-out" >売り切れ</p>
<?php } ?>
              <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
              <input type="hidden" name="sql_kind" value="insert_cart">
            </form>
          </div>
        </li>
<?php } ?>
      </ul>
    </div>
  </body>
</html>
