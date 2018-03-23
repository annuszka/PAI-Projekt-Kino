<?php
session_start();

?>
<!DOCTYPE html>
<html>
  <head>
          <meta charset="utf-8" />
          <title>Kino Alternatywa</title>
          <link rel="stylesheet" href="style.css" />
          <link rel="stylesheet" href="css/fontello.css" />
          <link href="https://fonts.googleapis.com/css?family=Lato:400,900|Raleway:400,900&amp;subset=latin-ext" rel="stylesheet" />
  </head>
  <body>
     <header>
        <h1 class="logo"><i class="icon-video-2"></i> Kino Alternatywa</h1>

        <nav id="topnav">
          <ul class="menu">
            <li><a href="index.php">Strona główna</a></li>
            <li><a href="repertuar.php">Repertuar</a></li>
            <li><a href="cennik.php">Cennik</a></li>
            <li><a href="dojazd.php">Dojazd</a></li>
            <li><a href="kontakt.php">Kontakt</a></li>
            <li><a href="<?php if(!isset($_SESSION['user'])){ print 'logowanie.php">Zaloguj się</a></li>';} else {print 'panel.php">Panel admina</a></li>';} ?>
          </ul>
        </nav>
     </header>
     <div class="content">
     <main>
      
<h3>Zaloguj się </h3>
<?php
$user = isset($_POST['user']) ? substr(strip_tags($_POST['user']),0,64) : '';
$pass = isset($_POST['pass']) ? $_POST['pass'] : '';
if(!empty($user) && !empty($pass))
  {
 
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
        print '<p>Witaj <b>'.$row['user'].'!</p>'.PHP_EOL;
        $_SESSION['user'] = $row['user'];
        $_SESSION['priv'] = $row['priv'];
        //linki do stron zgodnie z uprawnieniami
        if(($_SESSION['priv'] & 1) > 0) {
         // header('location: panel.php');
          print '<a href="panel.php" class="button">panel</a>'.PHP_EOL;
          
          print '<a href="wyloguj.php" class="button">Wyloguj się</a>'.PHP_EOL;
          print '<br /><hr />';
        }
    }
  else
    {
    print '<p class="error">Niepoprawne dane logowania</p>'.PHP_EOL;
    unset($_SESSION['priv'], $_SESSION['user']);
    }
  }
?>
<form method="post" action="">
Login:  <br />
<input type="text" name="user" required="required"  value="<?php print $user; ?>" /><br /> 
Hasło: <br />
<input type="password" name="pass" required="required" /><br />
<input type="submit" value="Zaloguj" class="btn" />
</form>

<hr />
  
     </main>
     </div>
     
    <footer>
      <div class="socials">
            <div class="fb">
              <i class="icon-facebook-official"></i>
            </div>
            <div class="tw">
              <i class="icon-twitter"></i>
            </div>
            <div class="insta">
              <i class="icon-instagram"></i>
            </div>            
      </div> 

      <div class="info">
      Wszelkie prawa zastrzeżone &copy; 2017 Zapraszamy na seanse w kinie Alternatywa! | Autor: Anna Rybak
      </div>

    </footer>
  </body>
  
</html>