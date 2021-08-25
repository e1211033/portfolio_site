<?php

// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/common_model.php';
require_once '../model/cart_model.php';

// 変数の初期化
$sql     = '';
$data    = array();
$err_msg = array();
$sql_kind   = '';
$result_msg = '';
$sum_price = 0;
$dbh        = null; // DBハンドル

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

  // 実行タイプを取得します
  $sql_kind = get_post_data('sql_kind');

  // user_idの取得
  $user_id = $_SESSION['user_id'];
  // パラメータの取得
  $item_id = get_post_data('item_id');

  // カートから商品を削除する
  if ($sql_kind === 'delete_cart') {
    // 指定の商品を削除することができる
    try {
      delete_cart_by_item_id($dbh, $user_id, $item_id);
      $result_msg = '削除しました。';
    } catch  (PDOException $e) {
      $err_msg[] = '削除に失敗'.$e->getMessage();
    }
  }

  // 数量を更新する
  else if ($sql_kind === 'change_cart'){
    $amount = get_post_data('select_amount');
    if (preg_match(AMOUNT_REGEX, $amount) !== 1 ) {
      $err_msg[] = '値段は半角数字を入力してください';
    } else if ($amount === '0') {
      $err_msg[] = '数量を0個に変更できません。 削除する場合は削除ボタンを押してください。';
    }
    if (count($err_msg) === 0 ) {
      // 指定の商品を数量を変更することができる。
      try {
        upadte_cart_amount($dbh, $user_id, $item_id, $amount);
        $result_msg = '更新しました。';
      } catch  (PDOException $e) {
        $err_msg[] = '更新に失敗'.$e->getMessage();
      }
    }
  }

  // カート情報の取得
  try {
    $result = get_cart_item_list($dbh, $user_id);
    if ($result) {
      $data = entity_assoc_array($result);
      // ショッピングカートにある商品の合計を表示する。
      $sum_price = get_sum_price($data);
    } else {
      $err_msg[] = '商品はありません。';
    }
  } catch (PDOException $e) {
    $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
  }
}

// テンプレートファイル読み込み
include_once '../view/cart_view.php';

?>
