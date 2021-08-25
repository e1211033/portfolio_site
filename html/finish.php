<?php

// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/common_model.php';
require_once '../model/finish_model.php';

// 変数の初期化
$sql                  = '';
$cart_data            = array();
$item_data            = array();
$err_msg              = array();
$sum_price            = 0;
$dbh                  = null; // DBハンドル
$stock_check          = true; // 商品在庫がカート内の商品数より少ない場合false
$low_stock_item_name  = array();
$item_stock            = array();

// セッション開始
session_start();

// ユーザがログインしているかどうかチェック
check_user_login();

// DBに接続します
try {
  $dbh = get_db_connect();
} catch (PDOException $e) {
  $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
}

// DBを操作します
if ($dbh) {

  // user_idの取得
  $user_id = $_SESSION['user_id'];

  // カート内の商品情報の取得
  try {
    $result = get_cart_item_list($dbh, $user_id);
    if ($result) {
      $cart_data = entity_assoc_array($result);
      // ショッピングカートにある商品の合計を表示する。
      $sum_price = get_sum_price($cart_data);
    } else {
      $err_msg[] = '商品はありません。';
    }
  } catch  (PDOException $e) {
    $err_msg[] = 'カート情報の取得に失敗'.$e->getMessage();
  }
  
  // 商品の在庫情報を取得
  try {
    $item_data = get_table($dbh);
  } catch(Exception $e) {
    $err_msg[] = '商品情報の取得に失敗'.$e->getMessage();
  }
  
  // 商品の在庫数とカート内の商品数の比較
  foreach ($result as $cart_value) {
    foreach ($item_data as $item_value) {
      if ($cart_value['item_id'] === $item_value['item_id']) {
        if ($cart_value['amount'] > $item_value['stock']) {
          $low_stock_item_name[$item_value['item_id']] = $item_value['name'];
          $item_stock[$item_value['item_id']] = $item_value['stock'];
          $stock_check = false;
        }
        break;
      }
    }
  }

  // 在庫数がカート内の商品数を多い場合のみ購入処理を行う
  if ($stock_check === true) {
    
    // トランザクション開始
    $dbh->beginTransaction();
    // カート情報の削除
    try {
      delete_cart($dbh, $user_id);
      $dbh->commit();
    } catch  (PDOException $e) {
      $err_msg[] = 'カート削除に失敗'.$e->getMessage();
      $dbh->rollback();
    }
  
    // トランザクション開始
    $dbh->beginTransaction();
    // 在庫数の更新
    if (count($err_msg) === 0) {
      try {
        upadte_multiple_item_stock($dbh, $cart_data);
        $dbh->commit();
      } catch  (PDOException $e) {
        $err_msg[] = '在庫数の更新に失敗'.$e->getMessage();
        $dbh->rollback();
      }
    }
  
  // 在庫数がカート内の商品数より少ない場合
  } else {
    foreach ($low_stock_item_name as $key => $value) {
      $err_msg[$key] = '"' . $value . '"' . 'の在庫は残り'.$item_stock[$key].'個です。数量を修正してください。';
    }
  }
}

// テンプレートファイル読み込み
include_once '../view/finish_view.php';

?>
