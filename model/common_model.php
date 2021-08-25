<?php

/**
* DBハンドルを取得
* 
* @return obj $dbh DBハンドル
*/

function get_db_connect() {
  
  try {
    $dbh = new PDO(DSN, DB_USER, DB_PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => DB_CHARSET));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }
  catch(PDOException $e) {
    throw $e;
  }
  
  return $dbh;
}





/**
* テーブルに商品を追加
*
* @param  obj $dbh                  DBハンドル
* @param  str $name                 登録する商品の名前
* @param  int $price                登録する商品の値段
* @param  int $publishing_setting   登録する商品の公開設定
* @param  int $number               登録する商品の個数
* @param  int $area                 登録する商品の地方
* @param  int $area_detail          登録する商品の都道府県
* @param  int $type                 登録する商品の種類
*/

function add_to_item_table($dbh, $name, $price, $publishing_setting, $number, $area, $area_detail, $type) {

  global $new_img_filename;
  
  try {
    
    // 管理テーブルに商品を追加
    $sql = 'INSERT INTO ec_item_master(name, price, img, status, create_datetime, update_datetime, area, area_detail, type) VALUES(?, ?, ?, ?, NOW(), NOW(), ?, ?, ?);';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $name,                PDO::PARAM_STR);
    $stmt->bindValue(2, $price,               PDO::PARAM_INT);
    $stmt->bindValue(3, $new_img_filename,    PDO::PARAM_STR);
    $stmt->bindValue(4, $publishing_setting,  PDO::PARAM_INT);
    $stmt->bindValue(5, $area,                PDO::PARAM_INT);
    $stmt->bindValue(6, $area_detail,         PDO::PARAM_INT);
    $stmt->bindValue(7, $type,                PDO::PARAM_INT);
    $stmt->execute();
    
    // 在庫テーブルに商品を追加
    $sql = 'INSERT INTO ec_item_stock(item_id, stock, create_datetime, update_datetime) VALUES(?, ?, NOW(), NOW());';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $dbh->lastInsertId(),   PDO::PARAM_INT);
    $stmt->bindValue(2, $number,                PDO::PARAM_INT);
    $stmt->execute();
  } catch (Exception $e) {
    throw $e;
  }
}





/**
* テーブルの在庫数を変更
*
* @param  obj $dbh        DBハンドル
* @param  int $item_id    購入した商品のid
* @param  int $stock      購入後の商品の在庫数
*/

function change_stock_quantity_table($dbh, $item_id, $stock) {

  try {
    $sql =  'UPDATE ec_item_stock SET stock=?, update_datetime=NOW() WHERE item_id=?;';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $stock,     PDO::PARAM_STR);
    $stmt->bindValue(2, $item_id,   PDO::PARAM_INT);
    $stmt->execute();
  } catch (Exception $e) {
    throw $e;
  }
}



/**
* テーブルから対象商品を削除
*
* @param  obj $dbh        DBハンドル
* @param  int $item_id    公開設定を変更する商品のid
*/
function delete_item_table($dbh, $item_id){
  try {
    $sql =  'DELETE FROM ec_item_master WHERE item_id=?;';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    $stmt->execute();
  } catch (Exception $e) {
    throw $e;
  }
}



/**
* テーブルの公開設定を変更
*
* @param  obj $dbh        DBハンドル
* @param  int $item_id    公開設定を変更する商品のid
* @param  int $status     変更後の公開設定
*/

function change_status_table($dbh, $item_id, $status) {

  try {
    $sql =  'UPDATE ec_item_master SET status=?, update_datetime=NOW() WHERE item_id=?;';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $status, PDO::PARAM_STR);
    $stmt->bindValue(2, $item_id,     PDO::PARAM_INT);
    $stmt->execute();
  } catch (Exception $e) {
    throw $e;
  }
}





/**
* テーブルの情報を取得を変更
*
* @param  obj $dbh    DBハンドル
* @return str $data[] dbに保存されているデータ
*/

function get_table($dbh) {

  $data     = array();  // 表示用配列

  // 追加・更新後の情報の取得
  try {
    $sql = 'SELECT  ec_item_master.item_id, ec_item_master.name, ec_item_master.price, ec_item_master.img, ec_item_master.status, ec_item_master.type, ec_item_master.area, ec_item_master.area_detail, ec_item_stock.stock
            FROM ec_item_master 
              INNER JOIN ec_item_stock 
              ON ec_item_master.item_id = ec_item_stock.item_id;';
    $stmt = $dbh->query($sql);
    $log = $stmt->fetchAll();
    foreach ($log as $key => $row) {
      $data[$key] = $row;
    }
  } catch (Exception $e) {
    throw $e;
  }
  return ($data);
}





/**
* 該当のPOSTデータを取得
*
* @param  str $key  取得したいPOSTデータが格納されている配列のキー
* @return str $str  該当のPOSTデータ(格納されていない場合は''を返す)
*/

function get_post_data($key) {

  $str = '';
  if (isset($_POST[$key]) === TRUE) {
   $str = $_POST[$key];
  }
  return $str;
}





/**
* ユーザログインチェック
* 
*/

function check_user_login() {
  if (isset($_SESSION['user_name']) !== TRUE) {
    // loginページへリダイレクト
    redirect_login_page();
  }
}





/**
* ログインページへリダイレクト
* 
*/

function redirect_login_page() {
  $url_root = dirname($_SERVER["REQUEST_URI"]).'/';
   header('Location: '.(empty($_SERVER["HTTPS"]) ? "http://" : "https://"). $_SERVER['HTTP_HOST'] . $url_root . 'login.php');
   exit();
}





/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
*
* @param  str $assoc_array  HTMLエンティティに変換したい2次元配列
* @return str $assoc_array  HTMLエンティティに変換後の2次元配列
*/

function entity_assoc_array($assoc_array) {

  foreach ($assoc_array as $key1 => $value) {
    foreach ($value as $key2 => $values) {
      // 特殊文字をHTMLエンティティに変換
      $assoc_array[$key1][$key2] = entity_str($values);
    }
  }

  return $assoc_array;
}





/**
* 特殊文字をHTMLエンティティに変換する
*
* @param  str $str    HTMLエンティティに変換したい変数
* @return str ($str)  HTMLエンティティに変換後の変数
*/

function entity_str($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}





/**
* カートにある商品情報の一覧を取得する
*
* @param  obj $dbh      DBハンドル
* @param  str $user_id  ユーザのid
*/

function get_cart_item_list($dbh, $user_id) {

  try {
    // SQL文を作成
    $sql = 'SELECT 
      ec_item_master.item_id, 
      ec_item_master.name, 
      ec_item_master.price,
      ec_item_master.img, 
      ec_cart.amount,
      ec_item_stock.stock
     FROM ec_cart 
     INNER JOIN ec_item_master ON ec_cart.item_id = ec_item_master.item_id
     INNER JOIN ec_item_stock ON ec_cart.item_id = ec_item_stock.item_id
     WHERE ec_item_master.status = 1 AND user_id = ' . $user_id; 
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();
  } catch (PDOException $e) {
    throw $e;
  }

  return $rows;
}





/**
* 購入の合計金額を取得
*
* @param    str $data()     カートに入っている商品の情報
* @return   str $sum_price  カートに入っている商品の合計金額
*/

function get_sum_price($data) {

  $sum_price = 0;
  foreach ($data as $value) {
    $sum_price = $sum_price + $value['price'] * $value['amount'];
  }

  return $sum_price;
}
?>