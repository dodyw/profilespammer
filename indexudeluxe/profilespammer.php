<?php

  include "../application.php";
  
  RunPreFilter(__FILE__);

  $query = "select * from idx_users where biography <> ''";
  $result = mysql_query($query);
  
  $i=0;
  
  $spammer = array();
  
  while ($row = mysql_fetch_array($result)) {
    if (strpos($row["biography"], 'href')!== false) {
      $spammer[] = $row["username"];
      $i++;
      $out .= '<p><b>'.$i.' <a href="user_edit.php?username='.$row["username"].'">'.$row["username"].'</a> ['.$row["homepage"].']</b><br>'.htmlentities($row["biography"]);
    }
  }
  
  ////////
  
  if (count($spammer)) {
    $op = "<p>You can perform one of the following actions: <a href='profilespammer.php?act=clean'>CLEAN the profile information</a> or <a href='profilespammer.php?act=delete'>DELETE the users</a>.</p>";
    
    foreach ($spammer as $k => $v) {
     	$spammer[$k] = "'$v'";
    }
    
    $spammer_s = implode(',', $spammer);
    
    if ($act=='clean') {
      $query = "update idx_users set biography = '', homepage = '' where username in ($spammer_s)";
      $result = mysql_query($query);
    }
    elseif ($act=='delete') {
      $query = "delete from idx_users where username in ($spammer_s)";
      $result = mysql_query($query);
    }
    
  }
  
  
  
  //RunPostFilter(__FILE__);

  
?>
<h1>Profile page spammers!</h1>

<p>Found <?php print $i; ?> users spamming profile page.</p>

<?php print $op; ?>

<?php print $out; ?>