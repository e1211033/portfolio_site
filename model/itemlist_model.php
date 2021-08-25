<?php

/**
* 商品名・値段・個数が正しい形式で入力されていない場合の警告表示
*
* @param str        $subject      チェックする入力文字列
* @param str        $pattern      正規表現パターン
* @param str        $target_str   チェックする対象の文字列(例：ドリンク名であれば"ドリンク名"が格納される)
* @param boolean    $empty_check  $subjectの入力チェック結果(入力された文字($subject)が空でなければtrue, 空であればfalse)
* @return str(null) $tmp_err_msg  エラーメッセージ(エラーがない場合はnullを返す)
*/

function input_warning_check ($subject, $pattern, $target_str, $empty_check) {

  // 未入力の場合
  if ( !$empty_check || $subject === '' ) {
    return ($target_str."が未入力です。");
  }
  // 正しい形式で記入されていない場合
  if ( preg_match($pattern, $subject) !== 1 ) {
    return ($target_str."を正しい形式で入力してください。");
  }
  return (null);
}





/**
* 商品追加がなされた場合の画像確認およびアップロード処理
*
* @return str $err_msg  エラーメッセージ(エラーがなければ空が返る)
*/

function check_and_upload_the_image() {
  
  global $new_img_filename;   // アップロードされた画像のファイル名(サーバに保存時の名前)
  $tmp_err_msg = null;        // エラーメッセージ(エラーがない場合はnull)
  
  if ( isset($_POST['new']) ) {
    // 画像がサーバに一時保存されている場合
    if ( is_uploaded_file($_FILES['new_img']['tmp_name']) ) {
      // 画像の拡張子を取得
      $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
      // 画像の拡張子がjpg(jpeg)またはpngの場合
      if ( $extension === 'jpg' || $extension === 'jpeg' || $extension === 'png' ) {
        // 同一のファイル名がない場合
        if ( !is_file(IMG_DIR.$new_img_filename) ) {
          // 保存用画像ファイル名を新規に取得(重複を避けるためユニークなものを取得する)
          $new_img_filename = sha1(uniqid(mt_rand(), true)).'.'.$extension;
          // アップロードされた画像を指定のディレクトリに移動して保存(一時保存場所から移動)
          if ( !move_uploaded_file($_FILES['new_img']['tmp_name'], IMG_DIR.$new_img_filename) ) {
            $tmp_err_msg = 'ファイルのアップロードに失敗しました。';
          }
        // 同一のファイル名があった場合
        } else {
          $tmp_err_msg = 'ファイルのアップロードに失敗しました。再度お試しください。';
        }
      // 画像の拡張子が指定のものでない場合
      } else {
        $tmp_err_msg = 'ファイル形式が異なります。画像ファイルはJPG(JPEG),PNGのみ利用可能です。';
      }
    // 画像がサーバに一時保存されていない場合
    } else {
      $tmp_err_msg = 'ファイルを選択してください';
    }
  }
  return ($tmp_err_msg);
}





/**
* 商品追加・在庫数変更・公開設定変更がなされた場合の処理実行
*
* @param  obj $dbh DBハンドル
*/

function execution_of_add_change_processing($dbh) {
  
  // ドリンクの追加がなされた場合、アップロードした画像のファイル名を登録
  if ( isset($_POST['new']) ) {
    $area = area_check((int)$_POST['area_detail']);
    add_to_item_table($dbh, $_POST['name'], $_POST['price'], $_POST['publishing_setting'], $_POST['number'], $area, $_POST['area_detail'], $_POST['type']);
  }
  
  // 在庫数の変更がなされた場合、対象の在庫数を更新
  if ( isset($_POST['update']) ) {
    change_stock_quantity_table($dbh, $_POST['item_id'], $_POST['stock']);
  }
  
  // 削除ボタンが押された場合、対象の商品情報を削除
  if ( isset($_POST['delete']) ) {
    delete_item_table($dbh, $_POST['item_id']);
  }
 
  // 公開設定が変更された場合、対象の公開設定を更新
  if ( isset($_POST['change_status']) ) {
    change_status_table($dbh, $_POST['item_id'] ,$_POST['status']);
  } 
}





/**
* DBへの追加および更新成功時にsuccess_msgにメッセージを格納
*
* @param  int $area_detail      $list_area_detail(都道府県検索用配列)の内容に対応するキー
* @return str $area             $list_area_detailに対応する$list_areaのキー
*/
function area_check( $area_detail ) {

  // 北海道
  if ( $area_detail === 1) {
    $area = 1;
  // 東北地方
  } elseif ( 2 <= $area_detail && $area_detail <= 7  ) {
    $area = 2;
  // 関東地方
  } elseif ( 8 <= $area_detail && $area_detail <= 14 ) {
    $area = 3;
  // 中部地方
  } else if ( 15 <= $area_detail && $area_detail <= 23 ) {
    $area = 4;
  // 近畿地方
  } else if ( 24 <= $area_detail && $area_detail <= 30 ) {
    $area = 5;
  // 中国地方
  } else if ( 31 <= $area_detail && $area_detail <= 35 ) {
    $area = 6;
  // 四国地方
  } else if ( 36 <= $area_detail && $area_detail <= 39 ) {
    $area = 7;
  // 九州地方
  } else if ( 40 <= $area_detail && $area_detail <= 47 ) {
    $area = 8;
  }
  return ($area);
}





/**
* DBへの追加および更新成功時にsuccess_msgにメッセージを格納
*
* @param  str $err_msg          エラーメッセージ
* @return str $tmp_success_msg  実行された処理に対応するメッセージ
*/

function success_msg_set($err_msg) {

  $tmp_success_msg = null;

  // エラーメッセージがない場合
  if ( count($err_msg) === 0 ) {

    // ドリンクの追加が正常になされた場合
    if ( isset($_POST['new']) ) {
      $tmp_success_msg = '追加成功';
    }

    // 在庫数の変更が正常になされた場合
    if ( isset($_POST['update']) ) {
      $tmp_success_msg = '在庫変更成功';
    }
    
    // 商品の削除が正常になされた場合
    if ( isset($_POST['delete']) ) {
      $tmp_success_msg = '削除成功';
    }

    // 公開設定が変更された場合
    if ( isset($_POST['change_status']) ) {
      $tmp_success_msg = 'ステータス変更成功';
    }   
  }
  
  return ($tmp_success_msg);
}
?>