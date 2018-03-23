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
          </ul>
        </nav>
     </header>
     <div class="content">
     <main>
      
<h3>PHP-77 Autoryzacja</h3>
<?php
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
        $_SESSION['user'] = $row['user'];
        $_SESSION['priv'] = $row['priv'];
        //linki do stron zgodnie z uprawnieniami
        print 'Co chcesz zrobić?: <br /> <br />';
        if(($_SESSION['priv'] & 1) > 0) {
          print '<p><a href="edytrepertuar.php">Dodaj/Usuń film z reperturu</a></p>'.PHP_EOL;
         print '<p><a href="edytzap.php">Dodaj/usuń zapowiedzi</a></p>'.PHP_EOL;
         print '<p><a href="edytrezerwacje.php">Dodaj/usuń rezerwację</a></p>'.PHP_EOL;
         print '<p><a href="edytseans.php">Dodaj/usuń seanse</a></p>'.PHP_EOL;
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
<input type="submit" value="Zaloguj" />
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