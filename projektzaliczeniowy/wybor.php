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
        <h2>Rezerwacja miejsc</h2>

      </header>
             <?php
                $db = new PDO('sqlite:cinema.db');
                //sprawdzanie poprawnosci pobranego linku $_GET
                $query = "SELECT max(id_seans) from seanse";
                $max = $db->query($query)->fetch();
                    
                if(isset($_GET['id_seans']) && (($_GET['id_seans']) > 0 && ($_GET['id_seans']) <= $max[0])){
                      $id_seans = $_GET['id_seans'];
                      $query2 = "select * from SEANSE where ID_SEANS = $id_seans";
                      $inf_query = $db->query($query2)->fetch();
                      $query3 = "select * from SALE where ID_ROOM = (select ID_ROOM from SEANSE where ID_SEANS = $id_seans)";
                      $s_query = $db->query($query3)->fetch();
                      $day = $inf_query['day'];
                      $day_array = array(1=>'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
                      $day_string = $day_array[$day];          
            ?>
             
              
        <div id="top_sala">
                <?php
                  //info o seansie
                  print ' Sala: <b>'.$inf_query['id_room'].'</b>';
                  print ' | Film: <b>'.$inf_query['title'].'</b>';
                  print ' | <b>'.$day_string.'</b>';
                  print ' <b>'.$inf_query['time'].'</b>';
                ?>
        </div>

        <div class="form">
                            
            <div class="sala">
                <div id="ekran">EKRAN</div> 

                <?php
                   print '<form action="rezerwacja.php" method="post"><input type="hidden" name="go" value="res"><input type="hidden" name="id_seans" value="'.$id_seans.'">';
                    //petla wyswietlajaca miejsca na sali
                    for ($i = 1; $i <= $s_query['rows_number']; $i++){
                      print '<div id="lp">'.$i.'</div>';
                        for ($l = 1; $l <= $s_query['seats_per_row']; $l++){
                            $q = "SELECT id_res from reservations where row = $i and seat = $l and id_seans = $id_seans";
                            $qu = $db->query($q);
                            $qq = $qu->fetch();
                              if ($qq != NULL){

                                print '<input type="checkbox" id="'.$i.$l.'" name="s[]" value="'.$i.'v'.$l.'" disabled><label for="'.$i.$l.'"><span>'.$l.'</span></label>';
                              }
                               else{
                                 print '<input type="checkbox" id="'.$i.$l.'" name="s[]" value="'.$i.'v'.$l.'" /><label for="'.$i.$l.'"><span>'.$l.'</span></label>';
                               }
                        }
                       print '</br>';
                    }
                ?>

            </div>
             
                
            <div class="dane">
            <?php
              //przed formularzem przetwarzanie danych
              //odebranie danych
                if(isset($_POST['name'])) $imie = $_POST['name'];
                if(isset($_POST['surname'])) $naz = $_POST['surname'];
                if(isset($_POST['email'])) $email = $_POST['email'];
                if(isset($_POST['tel'])) $tel = $_POST['tel'];
                
              //sprawdzanie danych i komunikatyu o bledach
              if(!empty($name) || !empty($surname) || !empty($email) || !empty($tel))
              {
              if(empty($name) || !preg_match('/^[A-ZĄĘŹŻŚÓĆŃŁ][a-ząęźżśóćńł]+$/',$name))
                print('<p class="error">Niepoprawne imię</p>'.PHP_EOL);
              if(empty($surname) || !preg_match('/^[A-ZĄĘŹŻŚÓĆŃŁ][a-ząęźżśóćńł\-]+$/',$surname))
                print('<p class="error">Niepoprawne nazwisko</p>'.PHP_EOL);
                if(empty($email) || !preg_match('/^[a-z0-9\-]+(\.?[a-z0-9\-]+)*@[a-z0-9\-]+(\.[a-z0-9\-]+)+$/i',$email))    //i oznacza ze case insensitive?
                print('<p class="error">Niepoprawny email</p>'.PHP_EOL);
                if(empty($tel) || !preg_match('/^\+?[0-9]+$/',$tel))
                print('<p class="error">Niepoprawny numer telefonu</p>'.PHP_EOL);  
                               
                }
                ?>
                <h3>Aby zarezerwować wybrane miejsca, proszę podać swoje dane.</h3><br>
                <input type="text" name="name" required placeholder="Imię" pattern="[A-ZĄĘŹŻŚÓĆŃŁ ]{1}[a-ząęźżśóćńł]*" title="Imię musi rozpoczynać się wielką literą"><br /><br />
                <input type="text" name="surname" required placeholder="Nazwisko" pattern="[A-ZĄĘŹŻŚÓĆŃŁ]{1}[a-ząęźżśóćńł\-]*[A-ZĄĘŹŻŚÓĆŃŁa-ząęźżśóćńł]*" title="Nazwisko musi rozpoczynać się wielką literą"><br /><br />
                 <input type="email" name="email" required placeholder="login@serwer.domena" pattern="[a-z0-9\._%+-]+@[a-z0-9]+\.[a-z]{2,4}$" /><br />
                <br /> <input type="text" name="tel" required  maxlength="9" placeholder="Nr tel:xxxxxxxxx" pattern="[0-9]{9}"/><br />
  
            </div>

            <div id="footer_sala">
                <a href="repertuar.php" class="button">Powrót</a>
                <input class="btn" type="submit" value="Rezerwuj"></form></div>
            </div>
            <?php
                }
                else {
                   print '<p><b>Błąd, nie ma takiego seansu!</b></p>';
                   print '<p><u><a href="index.php" title="kliknij, aby wrócić">Powrót do strony głównej</a></u></p>';
                }
            ?>
            
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