<?php

/**
* ユーザ情報の一覧を取得する
*
* @param  obj $dbh  DBハンドル
* @return str $row  ユーザ情報
*/

function get_user_list($dbh) {

  try {
    // SQL文を作成
    $sql = 'SELECT user_name, create_datetime
     FROM ec_user ORDER BY create_datetime';

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

?>