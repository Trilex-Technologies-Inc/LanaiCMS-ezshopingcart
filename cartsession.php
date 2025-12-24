<?

### EzShoping Cart ###
if (empty($_COOKIE['cartref'])) {
   setcookie("cartref", md5(uniqid(rand())), time()+(60*60*24*30));
}

if (isset($_REQUEST['clrcook']) && $_REQUEST['clrcook'] == "clear") {
    setcookie("cartref", "", time() - 3600); // Expire cookie properly
}

?>


