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
      <article class="zapowiedzi">
          <header>
            <h2>Zapowiedzi</h2>
          </header>
          
          <?php 
            $db = new PDO('sqlite:cinema.db');
            $day_array = array(1=>'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
            $movie_query = ($db->query("select * from ZAPOWIEDZI group by TITLE")); //zapytanie o wszystkie filmy z tabeli zapowiedzi, zgrupowane po tytule

           //petla wyswietlajaca zapowiedzi filmow
            while ($r = $movie_query->fetch()){
                $cover = $r['cover'];
                print '<section class="zapowiedz">';
                print '<figure> <img src="'.$cover.'" width="60% height="60%" alt="plakat"/>';
                print '<figcaption>'.$r['title'].'</figcaption></figure>';
                print '<div class="opis"><p>'.$r['description'].'</br></br>Czas trwania: '.$r['movie_time'].'</br></br> Premiera: '.$r['premiere_date'].'</p></div>';
                
            }
            print '</div>';
            print '</section>';

          ?>
 
       </article>
      
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