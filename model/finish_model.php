<?php

/**
* カート情報の削除
* 
* @param  obj $dbh      DBハンドル
* @param  str $user_id  ユーザid
*/

function delete_cart($dbh, $user_id) {

  try {
    // SQL文を作成
    $sql = 'DELETE from ec_cart WHERE user_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
  } catch (PDOException $e) {
    throw $e;
  }

}





/**
* 在庫数を変更
* 
* @param obj $dbh       DBハンドル
* @param str $data()    商品に関するデータ
*/

function upadte_multiple_item_stock($dbh, $data) {

  $err_msg = "";

  foreach ($data as $key => $rec) {

    $stock = (int)$rec['stock'] - (int)$rec['amount'];

    try {
      // SQL文を作成
      $sql = 'UPDATE ec_item_stock SET stock = ?, update_datetime = NOW() WHERE item_id = ?';
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQL文のプレースホルダに値をバインド
      $stmt->bindValue(1, $stock, PDO::PARAM_INT);
      $stmt->bindValue(2, $rec['item_id'], PDO::PARAM_INT);
      // SQLを実行
      $stmt->execute();
    } catch (PDOException $e) {
      throw $e;
    }
  }
}

?>
