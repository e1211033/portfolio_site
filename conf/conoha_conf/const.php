<?php

// データベースの接続情報
define('DB_HOST',       'localhost');           // MySQLのホスト名
define('DB_USER',       'root');       		      // MySQLのユーザ名（マイページのアカウント情報を参照）
define('DB_PASSWD',     'Sukekou-1120');       	// MySQLのパスワード（マイページのアカウント情報を参
define('DB_NAME',       'portfolio');       	  // MySQLのDB名(このコースではMySQLのユーザ名と同じで
define('DB_CHARSET',    'SET NAMES utf8mb4');   // MySQLのcharset
define('DSN', 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8');  // データベースのDSN情報

// 正規表現用定数
define('NAME_REGEX',          '/^[0-9a-zぁ-んァ-ヶ一-龠々\-_\+]+$/i');    // $nameの正規表現(itemlist.php)
define('USER_NAME_REGEX',     '/^[0-9a-z\-_\+]+$/i');                     // $user_nameの正規表現(login.php)
define('PASSWD_REGEX',        '/^[0-9a-z\-_\+]+$/i');                     // $user_nameの正規表現(login.php)
define('PRICE_REGEX',         '/^\d{1,5}+$/');                            // $priceの正規表現(itemlist.php)
define('NUMBER_REGEX',        '/^\d{1,5}+$/');                            // $numberの正規表現(itemlist.php)
define('STOCK_REGEX',         '/^\d{1,5}+$/');                            // $stockの正規表現(itemlist.php)
define('MONEY_REGEX',         '/^\d{1,5}+$/');                            // $_POST['money']の正規表現(result.php)
define('AMOUNT_REGEX',        '/^\d{1,5}+$/');                            // $amountの正規表現(cart.php)
define('SEARCH_PRICE_REGEX',  '/^\d{0,5}?$/');                            // $_POST['price_min'], $_POST['price_max']の正規表現(top.php)

// 画像保存・表示用
define('IMG_DIR',         './img/');      // 画像の保存ディレクトリ

// 地方検索用配列
$area_list = array(
  '0'=>'指定なし',
  '1'=>'北海道',
  '2'=>'東北',
  '3'=>'関東',
  '4'=>'中部',
  '5'=>'近畿',
  '6'=>'中国',
  '7'=>'四国',
  '8'=>'九州・沖縄'
);

// 都道府県検索用配列
$area_detail_list = array(
  '0'=>'指定なし',
  '1'=>'北海道',
  '2'=>'青森県',
  '3'=>'岩手県',
  '4'=>'宮城県',
  '5'=>'秋田県',
  '6'=>'山形県',
  '7'=>'福島県',
  '8'=>'茨城県',
  '9'=>'栃木県',
  '10'=>'群馬県',
  '11'=>'埼玉県',
  '12'=>'千葉県',
  '13'=>'東京都',
  '14'=>'神奈川県',
  '15'=>'新潟県',
  '16'=>'富山県',
  '17'=>'石川県',
  '18'=>'福井県',
  '19'=>'山梨県',
  '20'=>'長野県',
  '21'=>'岐阜県',
  '22'=>'静岡県',
  '23'=>'愛知県',
  '24'=>'三重県',
  '25'=>'滋賀県',
  '26'=>'京都府',
  '27'=>'大阪府',
  '28'=>'兵庫県',
  '29'=>'奈良県',
  '30'=>'和歌山県',
  '31'=>'鳥取県',
  '32'=>'島根県',
  '33'=>'岡山県',
  '34'=>'広島県',
  '35'=>'山口県',
  '36'=>'徳島県',
  '37'=>'香川県',
  '38'=>'愛媛県',
  '39'=>'高知県',
  '40'=>'福岡県',
  '41'=>'佐賀県',
  '42'=>'長崎県',
  '43'=>'熊本県',
  '44'=>'大分県',
  '45'=>'宮崎県',
  '46'=>'鹿児島県',
  '47'=>'沖縄県'
);

// 種類検索用配列
$type_list = array(
  '0'=>'指定なし',
  '1'=>'カレー',
  '2'=>'ハンバーグ',
  '3'=>'丼',
  '4'=>'その他'
);

define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'].'/../view/');

?>
