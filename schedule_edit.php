<?php

$filename = "C:\\Program Files\\xampp\\htdocs\\samplefile.txt";

if (isset($_POST["regist"])) {
  // 登録の処理を開始する

  // 入力チェック
  $error_message = array();
  if (isset($_POST["year"]) && is_numeric($_POST["year"])
     && $_POST["year"] > 2000) {
    $year = $_POST["year"];
  } else {
    $error_message[] = "年を正しく入力してください。";
  }
  if (isset($_POST["month"]) && is_numeric($_POST["month"])
     && $_POST["month"] > 0 && $_POST["month"] < 13) {
    $month = $_POST["month"];
  } else {
    $error_message[] = "月を正しく入力してください。";
  }
  if (isset($_POST["day"]) && is_numeric($_POST["day"])
     && $_POST["day"] > 0 && $_POST["day"] < 32) {
    $day = $_POST["day"];
  } else {
    $error_message[] = "日を正しく入力してください。";
  }
  if (isset($_POST["title"]) && $_POST["title"]) {
    if (strstr($_POST["title"], "|")) {
      $error_message[] = "申し訳ありませんが、タイトルに|文字は使えません。";
    } else {
      $title = $_POST["title"];
    }
  } else {
    $error_message[] = "タイトルを入力してください。";
  }
  if (isset($_POST["body"]) && $_POST["body"]) {
    if (strstr($_POST["body"], "|")) {
      $error_message[] = "申し訳ありませんが、内容に|文字は使えません。";
    } else {
      $body = $_POST["body"];
    }
  } else {
    $error_message[] = "内容を入力してください。";
  }

  if (!count($error_message)) {
    // 内容の改行を<br>タグに変換する
    $body = str_replace(array("\r\n", "\r", "\n"), "<br>", $body);

    $schedule_date = sprintf("%04d%02d%02d", $year, $month, $day);
    $new_line = $schedule_date."|".$title."|".$body."\n";

    if (isset($_POST["lineno"])) {
        $schedule_list = file($filename);
        $fp = fopen($filename, "w");
        foreach ($schedule_list as $lineno => $original_line) {
            if ($lineno == $_POST["lineno"]) {
                fwrite($fp, $new_line);
            } else {
                fwrite($fp, $original_line);
            }
        }
        fclose($fp);
    } else {
        $fp = fopen($filename, "a");
        fwrite($fp, $new_line);
        fclose($fp);
    }

    // 一覧画面へリダイレクト
    header("Location: http://localhost/schedule_list.php");
    exit;
  }
} else {
    if (isset($_GET["lineno"])) {
      $lineno = intval($_GET["lineno"]);
      $schedule_list = file($filename);
      $line = $schedule_list[$lineno];

     if (!$line) {
        // スケジュールが見つからなかった場合
        print("指定されたスケジュールは見つかりません");
        exit;
     }

      list($schedule_date, $title, $body) = explode("|", $line);

     $year = intval(substr($schedule_date, 0, 4));
     $month = intval(substr($schedule_date, 4, 2));
     $day = intval(substr($schedule_date, 6, 2));
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<title>スケジュール登録</title>
<style type="text/css">
  h1 {color: #666666; font-size: 1.3em; font-weight: bold;
border-left: 10px solid #99CC99; border-bottom: 2px solid #99CC99;}
  form {color: #333333; background-color: #FFFFFF;}
  label {font-weight: bold; color: #333333;
background-color: transparent;}
  input, textarea {border: none; color: #333333;
background-color: #FFFFFF;}
  #schedule-year {width: 3em; border-bottom: 1px solid #000000;}
  #schedule-month {width: 2em; border-bottom: 1px solid #000000;}
  #schedule-day {width: 2em; border-bottom: 1px solid #000000;}
  #label-year, #label-month, #label-day {margin-right: 5px;}
  #schedule-title {margin-bottom: 10px; width: 20em;
border-bottom: 1px solid #000000;}
  #schedule-body {width: 20em; height: 15em;
border: 1px solid #000000;}
  #regist {font-weight: bold; padding: 3px;
border-top: 3px double #CCCCCC;
border-right: 3px double #333333;
border-bottom: 3px double
#333333; border-left: 3px double #CCCCCC; color: #333333;
background-color: #EDECEC;}
  #error-message {font-weight: bold; color: #DD5757;
background-color:
transparent;}
  #error-message li {list-style: circle; line-height: 1.5;}
</style>
</head>
<body>
<h1>スケジュール登録</h1>

<?php
// エラーメッセージを出力する
if (count($error_message)) {
    print("<div id=\"error-message\"><ul>");
    foreach ($error_message as $message) {
        print("<li>".$message."</li>");
    }
    print("</ul></div>");
}
?>
<form action="schedule_edit.php" method="post">
  <input type="text" name="year" id="schedule-year"
value="<?php print(htmlspecialchars($year, ENT_QUOTES)); ?>" />
<label for="schedule-year" id="label-year">年</label>
  <input type="text" name="month" id="schedule-month"
value="<?php print(htmlspecialchars($month, ENT_QUOTES)); ?>" />
<label for="schedule-month" id="label-month">月</label>
  <input type="text" name="day" id="schedule-day"
value="<?php print(htmlspecialchars($day, ENT_QUOTES)); ?>" />
<label for="schedule-day" id="label-day">日</label>
  <dl>
    <dt><label for="schedule-title" id="label-title">タイトル
</label></dt>
    <dd><input type="text" name="title" id="schedule-title"
value="<?php print(htmlspecialchars($title, ENT_QUOTES)); ?>" />
</dd>
    <dt><label for="schedule-body" id="labe-body">内容</label></dt>
    <dd><textarea name="body" id="schedule-body">
<?php print(htmlspecialchars($body, ENT_QUOTES)); ?></textarea>
</dd>
  </dl>
  <?php
   if (isset($_GET["lineno"])) {
      ?>
      <input type="hidden" name="lineno" id="lineno" value=
      "<?php print(htmlspecialchars($lineno, ENT_QUOTES)); ?>">
      <input type="submit" name="regist" id="regist"
      value="更新する" />
      <?php
   } else {
      ?>
      <input type="submit" name="regist" id="regist"
      value="登録する" />
      <?php
   }
   ?>
</form>

</body>
</html>
