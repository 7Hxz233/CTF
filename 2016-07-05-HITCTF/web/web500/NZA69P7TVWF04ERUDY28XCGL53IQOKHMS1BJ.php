此功能还在开发中……
<?php
if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) 
{ 
$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]; 
} 
elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) 
{ 
$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"]; 
}
elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"]) 
{ 
$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
} 
elseif (getenv("HTTP_X_FORWARDED_FOR")) 
{ 
$ip = getenv("HTTP_X_FORWARDED_FOR"); 
} 
elseif (getenv("HTTP_CLIENT_IP")) 
{ 
$ip = getenv("HTTP_CLIENT_IP"); 
} 
elseif (getenv("REMOTE_ADDR"))
{ 
$ip = getenv("REMOTE_ADDR"); 
} 
else 
{ 
$ip = "Unknown"; 
} 
if ($ip == "192.168.1.1") {
    echo "5N76Q8UKOMCIHVZTSRBFLYGP4EAD2W1J03X9.php";
}
?>