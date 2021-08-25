<?php

/**
* 指定のカート商品を削除
* 
* @param obj $dbh       DBハンドル
* @param str $user_id   ユーザid
* @param str $item_id   商品id
*/

function delete_cart_by_item_id($dbh, $user_id, $item_id) {

  try {
    // SQL文を作成
    $sql = 'DELETE from ec_cart WHERE item_id = ? AND user_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
  } catch (PDOException $e) {
    throw $e;
  }

}





/**
* 指定の商品を数量を変更
* 
* @param obj $dbh       DBハンドル
* @param str $user_id   ユーザid
* @param str $item_id   商品id
* @param int $amount    商品の総量
*/

function upadte_cart_amount($dbh, $user_id, $item_id, $amount) {
  try {
    // SQL文を作成
    $sql = 'UPDATE ec_cart SET amount = ? WHERE item_id = ? AND user_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $amount, PDO::PARAM_INT);
    $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
    $stmt->bindValue(3, $user_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
  } catch (PDOException $e) {
    throw $e;
  }

}

?>