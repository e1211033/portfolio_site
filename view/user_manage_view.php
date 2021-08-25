<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>ユーザ管理ページ</title>
    <link type="text/css" rel="stylesheet" href="../css/user_manage.css">
  </head>
  <body>
    <div class='container'>
<?php foreach ($err_msg as $value) { ?>
      <p class="err-msg"><?php print $value; ?></p>
<?php } ?>
      <h1>ユーザ管理ページ</h1>
      <p>ユーザー名：<?php print $_SESSION['user_name']; ?></p>
      <a href="./logout.php">ログアウト</a>
      <br><br>
      <a href="./itemlist.php" target="_blank">商品管理ページ</a>
    </div>
    <div class='container'>
      <h2>ユーザ情報一覧</h2>
      <table>
        <tr>
          <th>ユーザID</th>
          <th>登録日</th>
        </tr>
<?php   foreach ($data as $value)  { ?>
        <tr>
          <td class="name_width"><?php print $value['user_name']; ?></td>
          <td ><?php print $value['create_datetime']; ?></td>
        </tr>
<?php   } ?>
      </table>
      <br>
    </div>
  </body>
</html>
