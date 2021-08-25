<?php

// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/common_model.php';
require_once '../model/login_model.php';

// 変数の初期化
$err_msg    = array();
$dbh        = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user_name = '';
  $password = '';

  // パラメータの取得
  $user_name = get_post_data('user_name');
  $password = get_post_data('password');

  // パラメータのエラーチェックします
  if ($user_name === '') {
    $err_msg[] = 'ユーザー名を入力してください。';
  }
  if ($password === '') {
    $err_msg[] = 'パスワードを入力してください。';
  }

  // DBに接続します
  try {
    $dbh = get_db_connect();
  } catch (PDOException $e) {
    $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
  }

  // DBを操作します
  if ($dbh) {

    // 該当ユーザの取得
    $result = get_user($dbh, $user_name, $password);

    if ($result) {
      // セッション開始
      session_start();

      // セッション変数に値を保存
      $_SESSION['user_id'] = $result[0]['user_id'];
      $_SESSION['user_name'] = $user_name;
      
      // ページ移行の準備
      $url_root = dirname($_SERVER["REQUEST_URI"]).'/';

      // usernameがadminの場合
      if ($_SESSION['user_name'] === 'admin') {
        // itemlist.phpを開く
        header('Location: http://'. $_SERVER['HTTP_HOST'] . $url_root . 'itemlist.php'); 
      // それ以外の場合
      } else {
        // top.phpを開く
        header('Location: http://'. $_SERVER['HTTP_HOST'] . $url_root . 'top.php'); 
      }

    } else {
      $err_msg[] = 'ユーザー名あるいはパスワードが違います';
    }
  }
}

// テンプレートファイル読み込み
include_once '../view/login_view.php';

?>
