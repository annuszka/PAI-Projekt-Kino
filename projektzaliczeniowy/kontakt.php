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
     <header>
        <h2>Kontakt</h2>

      </header>
      <div class="contact">
          <div class="sub">
            <i class="icon-desktop"></i>
            <span id="sub">Programowanie Aplikacji Internetowych</span>
          </div>
          <div class="in">
            <i class="icon-user"></i>
            <span id="in">Anna Rybak</span>
          </div>
          <div class="mail">
            <i class="icon-mail"></i> 
            <span id="mail">annrybak92@gmail.com</span>
          </div>
          <div class="al">
            <i class="icon-note"></i>
            <span id="al">10646</span>
          </div>
          <div class="gr">
            <i class="icon-school"></i>
            <span id="gr">412</span>
          </div>
          <div class="kier">
            <i class="icon-smile"></i>
            <span id="kier">Informatyka, sem. IV</span>
          </div>
          <div class="schl">
            <i class="icon-college"></i>
            <span id="schl">WSZ</span>
          </div>

      </div>

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