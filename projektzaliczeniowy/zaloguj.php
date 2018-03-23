<?php

  session_start();


$user = isset($_POST['user']) ? substr(strip_tags($_POST['user']),0,64) : '';
$pass = isset($_POST['pass']) ? $_POST['pass'] : '';
if(!empty($user) && !empty($pass))
  {
  //sprawdzenie konta w bazie
  //zabezpieczenie danych SQL przed znakami specjalnymi - prepare
  $pass = md5($pass);

   $db = new PDO('sqlite:users.db');
  $sql = "SELECT * FROM users WHERE user=:user AND pass=:pass";
  $res = $db->prepare($sql);
  $res->bindValue(':user', $user);
  $res->bindValue(':pass', $pass);
  $res->execute();
  if($row=$res->fetch())
    {
    print '<p>Witaj <b>'.$row['user'].'</p>'.PHP_EOL;
   // header('Location: panel.php');
    $_SESSION['user'] = $row['user'];
    $_SESSION['priv'] = $row['priv'];
  }
else
    {
    print '<p class="error">Niepoprawne dane logowania</p>'.PHP_EOL;
    unset($_SESSION['priv'], $_SESSION['user']);
    }
  }
  print 'wpisano: <br />';
  print $user.'<br/>';
  print $pass;


?>

  