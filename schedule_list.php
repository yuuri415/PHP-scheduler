<?php
$filename = "C:\\Program Files\\xampp\\htdocs\\samplefile.txt";
$schedule_list = file($filename);

foreach ($schedule_list as $lineno => $line) {
    list($schedule_date, $title, $body) = explode("|", $line);
    print("日付：$schedule_date タイトル：$title 内容：$body");
    print("<a href=\"schedule_edit.php?lineno=$lineno\">
          編集する</a>");
    print("<br>");
}
?> 
