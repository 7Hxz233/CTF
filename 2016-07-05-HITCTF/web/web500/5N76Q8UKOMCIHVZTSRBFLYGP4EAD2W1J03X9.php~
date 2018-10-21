<html>
  <title>此功能也在开发中</title>
  <body>
    <form method="post" enctype="multipart/form-data">
      <input type = "file" name="uploaded">
      <input type="submit">
    </form>
  </body>
</html>
<?php

$filename = $_FILES['uploaded']['name'];
$filetype = $_FILES['uploaded']['type'];
$filesize = $_FILES['uploaded']['size'];
$tmpname = $_FILES['uploaded']['tmp_name'];
if($filetype!="image/gif")
  exit("gif only");
$uploaddir = 'upload/';
$target_path = $uploaddir.basename($filename);

$im =imagecreatefromgif($tmpname);
$newimagepath = $uploaddir.$filename;
imagegif($im,$newimagepath);

echo $newimagepath;
?>