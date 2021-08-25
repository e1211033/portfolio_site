<?php

// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/common_model.php';
require_once '../model/user_manage_model.php';

// 変数の初期化
$data       = array();   // 表示データ
$err_msg    = array();   // エラーメッセージ
$dbh        = null; // DBハンドル


// セッション開始
session_start();

// ユーザがログインしているかどうかチェック
check_user_login();

// ログイン中かつusernameがadminでない場合、top画面に戻る
if ($_SESSION['user_name'] !== 'admin') {
  // top.phpを開く
  $url_root = dirname($_SERVER["REQUEST_URI"]).'/';
  header('Location: http://'. $_SERVER['HTTP_HOST'] . $url_root . 'top.php'); 
} 
  
// DBに接続します
try {
  $dbh = get_db_connect();
} catch (PDOException $e) {
  $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
}

// DBを操作します
if ($dbh) {

  try {
    // ユーザ情報を取得します
    $result = get_user_list($dbh);
    if ($result) {
      $data = entity_assoc_array($result);
    } else {
      $err_msg[] = 'ユーザ情報が登録されていません。';
    }
  } catch  (PDOException $e) {
    $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
  }

}

// テンプレートファイル読み込み
include_once '../view/user_manage_view.php';
?>
