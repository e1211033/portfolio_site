<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>商品管理ページ</title>
    <link type="text/css" rel="stylesheet" href="./css/itemlist.css">
  </head>
  <body>
    <div class='container'>
<?php if ( isset($success_msg) ) {
        print "<p>$success_msg</p>";
      } else {
        foreach ( (array)$err_msg as $value ) {
          print "<p>$value</p>";
        }
      } 
?>
      <h1>商品管理ページ</h1>
      <p>ユーザー名：<?php print $_SESSION['user_name']; ?></p>
      <a href="./logout.php">ログアウト</a>
      <br><br>
      <a href="./user_manage.php" target="_blank">ユーザ管理ページ</a>
    </div>
    <div class='container'>
      <h2>商品の登録</h2>
      <form method="post" enctype="multipart/form-data">
        <div><label>名前:<input type="text" size="20" name="name"></label></div>
        <div><label>値段:<input type="text" size="20" name="price"></label></div>
        <div><label>個数:<input type="text" size="20" name="number"></label></div>
        <div><label>商品画像:<input type="file" name="new_img"></label></div>
        <div><label>ステータス:
          <select name="publishing_setting">
            <option value = "1">公開</option>
            <option value = "0">非公開</option>
          </select>
        </label></div>
        <div><label>都道府県:
          <select name="area_detail">
<?php       foreach ($area_detail_list as $key => $value) {?>
              <option value = <?php print $key; ?>><?php print $value; ?></option>
<?php       }?>
          </select>
        </label></div>
        <div><label>種類:
          <select name="type">
<?php       foreach ($type_list as $key => $value) {?>
              <option value = <?php print $key; ?>><?php print $value; ?></option>
<?php       }?>
          </select>
        </label></div>
        <div><input type="submit" name="new" value="■□■□ 商品追加 ■□■□"></div>
      </form>
      <p></p>
    </div>
    <div class='container'>
      <h2>商品情報変更</h2>
      <p>商品一覧</p>
      <table>
        <tr>
          <th>商品画像</th>
          <th>商品名</th>
          <th>価格</th>
          <th>在庫数</th>
          <th>商品削除</th>
          <th>ステータス</th>
          <th>都道府県</th>
          <th>地方</th>
          <th>種類</th>
        </tr>
    <?php   foreach ( $data as $value ) { ?>
        <tr <?php if ( $value['status'] === 0 ) {?> id = 'table_gray' <?php }?>>
          <td id='img'><img src="<?php print IMG_DIR.$value['img'];?>"></td>
          <td id='name'><?php print (htmlspecialchars($value['name'], ENT_QUOTES, "UTF-8"));?></td>
          <td id='price'><?php print $value['price'];?>円</td>
          <td id='stock'>
            <form method="post">
              <input type="text" size="10" name="stock" value="<?php print $value['stock'];?>" style="text-align:right">個
              <input type="submit" name="update" value="変更">
              <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
            </form>
          </td>
          <td id='delete'>
            <form method="post">
              <input type="submit" name="delete" value="削除">
              <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
            </form>
          </td>
          <td id='status'>
            <form method="post">
    <?php       if ( $value['status'] === 0 ) {    // 公開設定でない場合、公開設定に変更するボタンを表示 ?>
                <input type="submit" name="change_status" value="非公開→公開">
          　　    <input type="hidden" name="status" value="1">
    <?php       } else {                          // 公開設定の場合、非公開設定に変更するボタンを表示  ?>
                <input type="submit" name="change_status" value="公開→非公開">
          　　    <input type="hidden" name="status" value="0">
    <?php       } ?>
              <input type="hidden" name="item_id" value="<?php print $value['item_id']; ?>">
            </form>
          </td>
          <td id='area'><?php print $area_detail_list[$value['area_detail']];?></td>
          <td id='area'><?php print $area_list[$value['area']];?></td>
          <td id='type'><?php print $type_list[$value['type']];?></td>
        </tr>
    <?php   } ?>
      </table>
      <br>
    </div>
  </body>
</html>