<?php

/**
* ユーザ情報の有無を確認
* 
* @param  obj     $dbh        DBハンドル
* @param  str     $user_name  入力されたユーザネーム
* @return boolean $exist_flag ユーザ情報の有無(あればtrue)
*/
function exist_user($dbh, $user_name) {

  $exist_flag = false;  // ユーザが存在している場合はtrue

  try {
    // SQL文を作成
    $sql = 'SELECT user_id, user_name, password
        FROM ec_user
        WHERE user_name = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();

    if (count($rows) !== 0) {
     $exist_flag = true;
    }

  } catch (PDOException $e) {
    throw $e;
  }

  return $exist_flag;
}





/**
* ユーザ情報の登録
* 
* @param  obj     $dbh        DBハンドル
* @param  str     $user_name  入力されたユーザネーム
* @param  str     $password   入力されたパスワード
* @return boolean $exist_flag ユーザ情報の有無(あればtrue)
*/
function insert_user($dbh, $user_name, $password) {

  try {
    // SQL文を作成
    $sql = 'INSERT INTO ec_user (user_name, password, create_datetime, update_datetime) VALUES (?, ?, NOW(), NOW())';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $user_name, PDO::PARAM_STR);
    $stmt->bindValue(2, $password, PDO::PARAM_STR);
    // SQLを実行
    $stmt->execute();

  } catch (PDOException $e) {
    throw $e;
  }

}

?>