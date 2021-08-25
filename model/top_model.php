<?php

/**
* カートに商品を追加
*
* @param  obj $dbh      DBハンドル
* @param  str $item_id  追加する商品のid
* @param  str $user_id  ユーザのid
*/

function execute_cart_click($dbh, $item_id, $user_id) {

  // カートに追加する対象商品がすでにカートに入っているかどうか確認する
  try {
    // SQL文を作成
    $sql = 'SELECT item_id, amount FROM ec_cart WHERE item_id = ? AND user_id = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // SQL文のプレースホルダに値をバインド
    $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
    // SQLを実行
    $stmt->execute();
    // レコードの取得
    $rows = $stmt->fetchAll();

    // カートに追加する対象商品がすでにカートに入っている場合
    if(count($rows) !== 0) {
      $amount = $rows[0]["amount"] + 1;
      $sql = 'UPDATE ec_cart SET amount = ? ,update_datetime = NOW() WHERE user_id = ? AND item_id = ?';
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQL文のプレースホルダに値をバインド
      $stmt->bindValue(1, $amount, PDO::PARAM_INT);
      $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
      $stmt->bindValue(3, $item_id, PDO::PARAM_INT);
      // SQLを実行
      $stmt->execute();

    // カートに追加の対象商品がまだカートに入っていない場合
    } else {
      // 追加するの対象商品がまだカートに入っていない場合
      $sql = 'INSERT INTO ec_cart (user_id, item_id, amount, create_datetime, update_datetime) VALUES (?, ?, 1, NOW(), NOW())';
      // SQL文を実行する準備
      $stmt = $dbh->prepare($sql);
      // SQL文のプレースホルダに値をバインド
      $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
      $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
      // SQLを実行
      $stmt->execute();
    }

  } catch (PDOException $e) {
    throw $e;
  }
}





/**
* 公開の商品情報の一覧を取得する
*
* @param  obj $dbh  DBハンドル
* @return str $row  公開設定になっている商品の情報
*/

function get_item_list_all_by_status($dbh) {

  try {
    // SQL文を作成
    $sql = 'SELECT
       ec_item_master.item_id,
       ec_item_master.name, ec_item_master.price,
       ec_item_master.img, ec_item_master.area,
       ec_item_master.area_detail, ec_item_master.type,
       ec_item_stock.stock
       FROM ec_item_master JOIN ec_item_stock
       ON  ec_item_master.item_id = ec_item_stock.item_id
       WHERE status = 1';
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
* 公開の商品情報の一覧を取得する(下限価格、上限価格、地方、種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $price_max    上限価格
* @param  str $area         名産地(地方)
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min_max_area_type($dbh, $price_min, $price_max, $area, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price BETWEEN ? AND ?
              AND area = ?
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
    $stmt->bindValue(2, $price_max, PDO::PARAM_INT);
    $stmt->bindValue(3, $area,      PDO::PARAM_INT);
    $stmt->bindValue(4, $type,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(下限価格、上限価格、地方の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $price_max    上限価格
* @param  str $area         名産地(地方)
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min_max_area($dbh, $price_min, $price_max, $area) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price BETWEEN ? AND ?
              AND area = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
    $stmt->bindValue(2, $price_max, PDO::PARAM_INT);
    $stmt->bindValue(3, $area,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(下限価格、上限価格、種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $price_max    上限価格
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min_max_type($dbh, $price_min, $price_max, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price BETWEEN ? AND ?
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
    $stmt->bindValue(2, $price_max, PDO::PARAM_INT);
    $stmt->bindValue(3, $type,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(下限価格、上限価格の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $price_max    上限価格
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min_max($dbh, $price_min, $price_max) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price BETWEEN ? AND ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
    $stmt->bindValue(2, $price_max, PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(下限価格、地方、種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $area         名産地(地方)
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min_area_type($dbh, $price_min, $area, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price >= ?
              AND area = ?
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
    $stmt->bindValue(2, $area,      PDO::PARAM_INT);
    $stmt->bindValue(3, $type,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(下限価格、地方の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $area         名産地(地方)
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min_area($dbh, $price_min, $area) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price >= ?
              AND area = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
    $stmt->bindValue(2, $area,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(下限価格、種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min_type($dbh, $price_min, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price >= ?
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
    $stmt->bindValue(2, $type,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(下限価格の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_min($dbh, $price_min) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price >= ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_min, PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(上限価格、地方、種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_max    上限価格
* @param  str $area         名産地(地方)
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_max_area_type($dbh, $price_max, $area, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price <= ?
              AND area = ?
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_max, PDO::PARAM_INT);
    $stmt->bindValue(2, $area,      PDO::PARAM_INT);
    $stmt->bindValue(3, $type,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(上限価格、地方の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_max    上限価格
* @param  str $area         名産地(地方)
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_max_area($dbh, $price_max, $area) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price <= ?
              AND area = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_max, PDO::PARAM_INT);
    $stmt->bindValue(2, $area,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(上限価格、種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_max    上限価格
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_max_type($dbh, $price_max, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price <= ?
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_max, PDO::PARAM_INT);
    $stmt->bindValue(2, $type,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(上限価格の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $price_max    上限価格
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_max($dbh, $price_max) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND price <= ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $price_max, PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(地方、種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $area         名産地(地方)
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_area_type($dbh, $area, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND area = ?
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $area,      PDO::PARAM_INT);
    $stmt->bindValue(2, $type,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(地方の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $area         名産地(地方)
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_area($dbh, $area) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND area = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $area,      PDO::PARAM_INT);
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
* 公開の商品情報の一覧を取得する(種類の検索ありver)
*
* @param  obj $dbh          DBハンドル
* @param  str $type         商品の種類
* @return str $rows         条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_type($dbh, $type) {

  try {
    // SQL文を作成
    $sql = 'SELECT
              ec_item_master.item_id, 
              ec_item_master.name, 
              ec_item_master.price, 
              ec_item_master.img, 
              ec_item_master.area,
              ec_item_master.area_detail, 
              ec_item_master.type,
              ec_item_stock.stock
            FROM 
              ec_item_master JOIN ec_item_stock
            ON
              ec_item_master.item_id = ec_item_stock.item_id
            WHERE 
              status = 1
              AND type = ?';
    // SQL文を実行する準備
    $stmt = $dbh->prepare($sql);
    // 変数をバインド
    $stmt->bindValue(1, $type,      PDO::PARAM_INT);
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
* 検索条件と合致する、公開設定の商品情報の一覧を取得する
*
* @param  obj $dbh          DBハンドル
* @param  str $price_min    下限価格
* @param  str $price_max    上限価格
* @param  str $area         名産地(地方)
* @param  str $type         商品の種類
* @return str $row          条件と合致していて公開設定になっている商品の情報
*/

function get_item_list_all_by_status_search($dbh, $price_min, $price_max, $area, $type) {

  try {
    // 何も記入および選択せずに検索ボタンを押下した場合
    if ((empty($price_min) && $price_min !== '0') && 
        (empty($price_max) && $price_max !== '0') && 
        empty($area) && 
        empty($type)) {
      $rows = get_item_list_all_by_status($dbh);
    // 記入および選択がなされている場合
    } else {
      // 下限価格の記入がある場合
      if (!empty($price_min) || $price_min === '0') {
        // 上限価格の記入がある場合
        if (!empty($price_max) || $price_max === '0') {
          // 地方の選択をしている場合
          if (!empty($area)) {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 下限・上限価格の記入および地方、種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min_max_area_type($dbh, $price_min, $price_max, $area, $type);
            // 種類の選択をしていない場合
            } else {
               // 下限・上限価格の記入および地方の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min_max_area($dbh, $price_min, $price_max, $area);
            }
          // 地方の選択をしていない場合
          } else {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 下限・上限価格の記入および種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min_max_type($dbh, $price_min, $price_max, $type);
            // 種類の選択をしていない場合
            } else {
              // 下限・上限価格の記入をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min_max($dbh, $price_min, $price_max);
            }        
          }
        // 上限価格の記入がない場合
        } else {
          // 地方の選択をしている場合
          if (!empty($area)) {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 下限価格の記入および地方、種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min_area_type($dbh, $price_min, $area, $type);
            // 種類の選択をしていない場合
            } else {
              // 下限価格の記入および地方の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min_area($dbh, $price_min, $area);
            }
          // 地方の選択をしていない場合
          } else {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 下限価格の記入および種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min_type($dbh, $price_min, $type);
            // 種類の選択をしていない場合
            } else {
              // 下限価格の記入をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_min($dbh, $price_min);
            }        
          }      
        }
      // 下限価格の記入がない場合
      } else {
        // 上限価格の記入がある場合
        if (!empty($price_max) || $price_max === '0') {
          // 地方の選択をしている場合
          if (!empty($area)) {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 上限価格の記入および地方、種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_max_area_type($dbh, $price_max, $area, $type);
            // 種類の選択をしていない場合
            } else {
              // 上限価格の記入および地方の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_max_area($dbh, $price_max, $area);
            }
          // 地方の選択をしていない場合
          } else {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 上限価格の記入および種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_max_type($dbh, $price_max, $type);
            // 種類の選択をしていない場合
            } else {
              // 上限価格の記入をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_max($dbh, $price_max);
            }        
          }
        // 上限価格の記入がない場合
        } else {
          // 地方の選択をしている場合
          if (!empty($area)) {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 地方、種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_area_type($dbh, $area, $type);
            // 種類の選択をしていない場合
            } else {
              // 地方の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_area($dbh, $area);
            }
          // 地方の選択をしていない場合
          } else {
            // 種類の選択をしている場合
            if (!empty($type)) {
              // 種類の選択をしている場合のSQL文を作成
              $rows = get_item_list_all_by_status_type($dbh, $type);
            // 種類の選択をしていない場合
            } else {
              // 何も記入していない場合のSQL文を作成
              $rows = get_item_list_all_by_status($dbh);
            }        
          }      
        }
      }
    }
  } catch (PDOException $e) {
    throw $e;
  }

  return $rows;
}





/**
* 上下限価格が正しい形式で入力されていない場合の警告表示
*
* @param str        $subject      チェックする入力文字列
* @param str        $pattern      正規表現パターン
* @param str        $target_str   チェックする対象の文字列(例：ドリンク名であれば"ドリンク名"が格納される)
* @return str(null) $tmp_err_msg  エラーメッセージ(エラーがない場合はnullを返す)
*/

function input_price_check ($subject, $pattern, $target_str) {

  // 正しい形式で記入されていない場合
  if ( preg_match($pattern, $subject) !== 1 ) {
    return ($target_str."を入力する場合、正しい形式(半角数字の0~99999)で入力してください。");
  }
  return (null);
}
?>
