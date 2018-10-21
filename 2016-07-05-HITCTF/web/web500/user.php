<?php
function filter($str){
    if(stristr($str,'union')||stristr($str,'and')||
        stristr($str,'||')||stristr($str,'&&')||stristr($str,' ')) die("Please provide a permitted value.");
}

include "db.php";
filter($_GET["id"]);
$strsql="SELECT * FROM user where id =".$_GET["id"];
$result=mysqli_query($conn, $strsql);
$flag=false;
while($row=mysqli_fetch_row($result))
{
    echo "$row[0],$row[1],$row[2]";
    $flag=true;
}
if(!$flag)
{
    $strsql="SELECT * FROM user where id = 1";
    $result=mysqli_query($conn, $strsql);
    while($row=mysqli_fetch_row($result))
    {
        echo "$row[0],$row[1],$row[2]";
    }

}
mysqli_free_result($result);
mysqli_close($conn);
?>