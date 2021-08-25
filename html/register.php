<?php

// 設定ファイル読み込み
require_once '../conf/const.php';
// 関数ファイル読み込み
require_once '../model/common_model.php';
require_once '../model/register_model.php';

// 変数の初期化
$result_msg = '';
$err_msg    = array();

$user_name = '';
$password = '';
$dbh        = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // ユーザー名の取得
  $user_name = get_post_data('user_name');

  // ユーザー名のチェック
  if (mb_strlen($user_name) < 6 || 20 < mb_strlen($user_name)){
    $err_msg[] = 'ユーザー名は6文字以上20文字以内の文字を入力してください';
  } else if (preg_match(USER_NAME_REGEX, $user_name) !== 1 ) {
    $err_msg[] = 'ユーザ名は半角英数字を入力してください';
  }

  // パスワードの取得
  $password = get_post_data('password');

  // パスワードのチェック
  if (mb_strlen($password) < 6 || 20 < mb_strlen($password)){
    $err_msg[] = 'パスワードは6文字以上20文字以内の文字を入力してください';
  } else if (preg_match(PASSWD_REGEX, $password) !== 1 ) {
    $err_msg[] = 'パスワードは半角英数字を入力してください';
  }

  if (count($err_msg) === 0) {
    try {
      // DBに接続します
      $dbh = get_db_connect();
      // DBを操作します
      if ($dbh) {
        // ユーザ情報の存在チェック
        $result = exist_user($dbh, $user_name);
        // 既に同じユーザ名が存在する場合
        if ($result === true) {
          $err_msg[] = '同じユーザー名が既に登録されています';
        } 
        // まだユーザ名が存在しない場合
        else {
          // ユーザ情報を登録する
          insert_user($dbh, $user_name, $password);
          $result_msg = 'アカウント作成を完了しました';
        }
      }
    } catch (PDOException $e) {
      $err_msg[] = 'エラーが発生しました。管理者へお問い合わせください。'.$e->getMessage();
    }
  }
}

// テンプレートファイル読み込み
include_once '../view/register_view.php';

?>
