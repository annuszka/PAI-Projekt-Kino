<?php
session_start();
$db = new PDO('sqlite:users.db');

if(!isset($_SESSION['user']))
{header('location:logowanie.php');
exit;
}

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
      <?php

        print 'Co chcesz zrobić?: <br /> <br />';
        //  print '<p><a href="edytrepertuar.php" class="panel">Dodaj/Usuń film z reperturu</a></p>'.PHP_EOL;
        // print '<p><a href="edytzap.php" class="panel">Dodaj/usuń zapowiedzi</a></p>'.PHP_EOL;
         print '<p><a href="edytrezerwacje.php" class="panel">Usuń rezerwację</a></p>'.PHP_EOL;
         print '<p><a href="dodajseans.php" class="panel">Dodaj/usuń seanse</a></p>'.PHP_EOL;
         
         print '<br />';
          print '<p><a href="wyloguj.php" class="button">Wyloguj się</a></p>'.PHP_EOL;
      ?>

  
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

