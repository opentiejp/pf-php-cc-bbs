<?php
date_default_timezone_set('Asia/Tokyo');
$filename = './bbs_log.txt';
$user_name = '';
$comment = '';
$log = '';
$data = [];
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>10-1｜ひとこと掲示板</title>
    <style type="text/css">
      html {
        background-color: #ffffff;
      }
      html * {
        box-sizing: border-box;
        font-family: sans-serif;
      }
      h1 {
        margin: 20px 0 0 0;
      }
      p {        
        font-size: 16px;
        color: #000000;
        margin: 10px 0;
      }
      #form {
        float: left;
        text-align: left;
        width: 20em;
        margin: 0 auto 30px auto;
      }
      label, input, textarea {
        width: 100%;
      }
      h2 {
        clear: both;
      }
    </style>
  </head>
  <body>

<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_name']) === TRUE && $_POST['user_name'] !== '' && isset($_POST['comment']) === TRUE && $_POST['comment'] !== '') {
      if (mb_strlen($_POST['user_name']) > 20) {
        print '名前は、全角20文字以内で入力してください。' . '<br>';
      }
      if (mb_strlen($_POST['comment']) > 100) {
        print 'コメントは、全角100文字以内で入力してください。' . '<br>';
      }
      if ((mb_strlen($_POST['user_name']) <= 20) && (mb_strlen($_POST['comment']) <= 100)) {
          $user_name = $_POST['user_name'];
          $comment = $_POST['comment'];
      } else {
          $log = NULL;
      }
    } else {
        $log = NULL;  
        print '名前とコメントをいずれも入力してください。' . '<br>';
    }

    if (isset($log) === TRUE) {
      $log = $user_name . '： ' . "\t" . $comment . "\t" . '(' . date('m月d日 H:i:s') . ')' . "\n";
    }

    if (($fp = fopen($filename, 'a')) !== FALSE) {
      if (fwrite($fp, $log) === FALSE) {
        print 'ファイル書き込み失敗:　' . $filename;
      }
      fclose($fp);
    }
  }

  if (is_readable($filename) === TRUE) {
    if (($fp = fopen($filename, 'r')) !== FALSE) {
      while (($tmp = fgets($fp)) !== FALSE) {
        $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
      }
      fclose($fp);
    }
  } else {
    $data[0] = 'まだ書き込みがありません。';
  }
?>

    <h1>ひとこと掲示板</h1>

    <form method="post">
      <div id="form">
        <p>
          <label for="user_name">
            名前： <br><input type="text" id="user_name" name="user_name" placeholder="全角20文字以内で入力してください">
          </label>
        </p>
        <p>
          <label for="comment">
            コメント： <br><textarea id="comment" name="comment" rows="5" placeholder="全角100文字以内で入力してください"></textarea>
          </label><br>
        </p>
        <input type="submit" name="submit" value="送信">
      </div>
    </form>

    <h2>発言一覧</h2>
  <?php foreach ($data as $read) { ?>
    <p><?php print $read ?></p>
  <?php } ?>
  </body>
</html>