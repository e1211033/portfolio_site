<?php

// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/common_model.php';
require_once '../model/top_model.php';

// 変数の初期化
$sql     = '';
$data    = array();
$err_msg = array();
$sql_kind   = '';
$result_msg = '';
$dbh        = null; // DBハンドル

// セッション開始
session_start();

// DBに接続します
try {
  $dbh = get_db_connect();
} catch (PDOException $e) {
  $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
}

// DBを操作します
if ($dbh) {
  // 実行タイプを取得
  $sql_kind = get_post_data("sql_kind");
  // カートを入れる処理(表示内容は更新無し)
  if ($sql_kind === 'insert_cart') {
    // ユーザがログインしているかどうかチェック
    check_user_login();
    // user_idの取得
    $user_id = $_SESSION['user_id'];
    // パラメータの取得
    $item_id = get_post_data('item_id');
    // 「カートに入れる」ボタンをクリックした場合、指定の商品をカートに入れる
    try {
      execute_cart_click($dbh, $item_id, $user_id);
      $result_msg = 'カートに追加しました。';
    } catch  (PDOException $e) {
      $err_msg[] = 'カート更新に失敗'.$e->getMessage();
    }
  }
  // 商品情報の取得
  try {
    // 入力された下限価格の確認
    $price_min = get_post_data("price_min");
    $tmp_err_msg['price_min'] = input_price_check($price_min, SEARCH_PRICE_REGEX, '価格下限');
    if ( (!empty($price_min) || $price_min === '0') && (!isset($tmp_err_msg['price_min']))) {
      $search['price_min'] = "価格下限: ¥".$price_min." ~";
    // 下限価格に入力誤りがある場合は下限価格の条件をリセットする
    } else if (isset($tmp_err_msg['price_min'])) {
      $price_min = '';
    }
    // 入力された上限価格の確認
    $price_max = get_post_data("price_max");
    $tmp_err_msg['price_max'] = input_price_check($price_max, SEARCH_PRICE_REGEX, '価格上限');
    if ( (!empty($price_max) || $price_max === '0') && (!isset($tmp_err_msg['price_max']))) {
      $search['price_max'] = "価格上限: ~ ¥".$price_max;
    // 上限価格に入力誤りがある場合は上限価格の条件をリセットする
    } else if (isset($tmp_err_msg['price_max'])) {
      $price_max = '';
    }
    // 下限価格および上限価格を入力した場合、上限および下限価格が入れ替わっていないか確認
    if (  (!empty($price_min) && !empty($price_max)) &&
          (empty($tmp_err_msg['price_min']) && empty($tmp_err_msg['price_max'])) ) {
      if ( $price_min > $price_max) {
        $tmp_err_msg['price_min_max'] = '価格の上下限を入力する際は、価格下限は価格上限より小さい価格を入力してください。';
      }
    }
    // 地方が正しく選択されているか確認
    $area = get_post_data("area");
    if ( $area < 0 || 8 < $area ) {
      $tmp_err_msg['area'] = '地方を正しく設定してください。';
      $area = 0;
    } else if ( !empty($area) ) {
      $search['area'] = "地方: ".$area_list[$area];
    }
    // 種類が正しく選択されているか確認
    $type = get_post_data("type");
    if ( $type < 0 || 4 < $type ) {
      $tmp_err_msg['type'] = '食品の種類を正しく設定してください。';
      $type = 0;
    } else if ( !empty($type) ) {
      $search['type'] = "種類: ".$type_list[$type];
    }
    // 一時保存しているエラーメッセージを保存
    foreach ( $tmp_err_msg as $key => $value ) {
      if ( $value != null ) {
        $err_msg[$key] = $value;
      }
    }
    // 指定した条件に合う商品情報を取得する
    $result = get_item_list_all_by_status_search($dbh, $price_min, $price_max,  $area, $type);
    if ($result) {
      $data = entity_assoc_array($result);
    } else {
      $err_msg['empty'] = '商品はありません。';
    }
  } catch (PDOException $e) {
    $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
  }
}

// テンプレートファイル読み込み
include_once '../view/top_view.php';

?>
