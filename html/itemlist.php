<?php
  
  // 設定ファイル読み込み
  require_once '../conf/const.php';
  // 関数ファイル読み込み
  require_once '../model/common_model.php';
  require_once '../model/itemlist_model.php';
  
  // データ取得およびエラーメッセージ用配列
  $data         = array();          // 表示用配列
  $tmp_err_msg  = array();          // 一時保存用配列(エラーメッセージ)
  $err_msg      = array();          // 表示用配列(エラーメッセージ)
  $success_msg;                     // 表示用配列(DBへの書き換え成功時のメッセージ)

  // 画像保存用変数
  $new_img_filename   = '';         // リネーム後の画像の名前(保存用に特異な名前を付けるため)  

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

  // ドリンク追加・在庫数変更・公開非公開設定が変更された場合
  if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    // ドリンク追加・在庫数変更がなされた場合の内容確認(エラーがなければnull、エラーがあればエラーメッセージを一時保存用変数($tmp_err_msg)に格納)
    if ( isset($_POST['new']) ) {
      // 都道府県名と食品の種類を判別するため変数に選択された数字(キー)を格納(判定用にint型似キャスト)
      $area_detail = (int)$_POST['area_detail'];
      $type = (int)$_POST['type'];
      $tmp_err_msg['name'] = input_warning_check($_POST['name'], NAME_REGEX, '商品名', isset($_POST['name']));
      $tmp_err_msg['price'] = input_warning_check($_POST['price'], PRICE_REGEX, '値段', isset($_POST['price']));
      $tmp_err_msg['number'] = input_warning_check($_POST['number'], NUMBER_REGEX, '個数', isset($_POST['number']));
      if ( $_POST['publishing_setting'] !== '0' && $_POST['publishing_setting'] !== '1' ) {
        $tmp_err_msg['publishing_setting'] = 'ステータスを公開か非公開に設定してください。';
      }
      if ( $area_detail < 1 || 47 < $area_detail ) {
        $tmp_err_msg['area_detail'] = '都道府県を正しく設定してください。';
      }
      if ( $type < 1 || 4 < $type ) {
        $tmp_err_msg['type'] = '食品の種類を正しく設定してください。';
      }    
    } else if ( isset($_POST['update']) ) {
      $tmp_err_msg['stock'] = input_warning_check($_POST['stock'], STOCK_REGEX, '在庫数', isset($_POST['stock']));
    } else if ( isset($_POST['change_status']) ) {
      if ( $_POST['status'] !== '0' && $_POST['status'] !== '1' ) {
        $tmp_err_msg['status'] = 'ステータスを変更できませんでした。再度お試しください。';
      }
    }
    $tmp_err_msg['image'] = check_and_upload_the_image();
    foreach ( $tmp_err_msg as $key => $value ) {
      if ( $value != null ) {
        $err_msg[$key] = $value;
      }
    }
  }
  
  try {
    // データベースに接続
    $dbh = get_db_connect();
    // トランザクション開始
    $dbh->beginTransaction();
    
    // DBにデータを追加
    try {
      // エラーがなく何かしらのアクションがなされたときの処理
      if ( count($err_msg) === 0 && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        execution_of_add_change_processing($dbh);
      }
      $dbh->commit();
    } catch(Exception $e) {
      $dbh->rollback();
      throw $e;
    }
    
    // DBからデータを取得
    try {
      $data = get_table($dbh);
    } catch(Exception $e) {
      throw $e;
    }

  } catch (Exception $e) {
    $err_msg['db_connect'] = 'DBエラー:'.$e->getMessage();
  }
  
  // DBの変更が成功した場合のメッセージ挿入
  if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $success_msg = success_msg_set($err_msg);
  }

  // 画面表示ファイル読み込み
  include_once '../view/itemlist_view.php';
  ?>