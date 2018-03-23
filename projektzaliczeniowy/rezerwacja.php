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
      <div id="potw">
  <?php
  $db = new PDO('sqlite:cinema.db');
    if(isset($_POST['id_seans'])) {
      $id_seans = $_POST['id_seans'];
    }

    if(isset($_POST['s']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']) && isset($_POST['tel'])) 
    {
      $name = $_POST['name'];
      $surname = $_POST['surname'];
      $email = $_POST['email'];
      $tel = $_POST['tel'];
      $dobrze = true;
      $flaga = true;
        if(!preg_match('/^[a-ząęźżśóćńłA-ZĄĘŹŻŚÓĆŃŁ]*$/', $name)) {$dobrze = false;}
        if(!preg_match('/^[a-ząęźżśóćńłA-ZĄSĘŹŻŚÓĆŃŁ\-]+[a-ząęźżśóćńłA-ZAĘŹŻŚÓĆŃŁ]*$/', $surname)) {$dobrze = false;}
        if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-z0-9\-]+(\.?[a-z0-9\-]+)*@[a-z0-9\-]+(\.[a-z0-9\-]+)+$/i',$email)) {$dobrze = false;}
        if(!preg_match('/^[0-9]{9}$/', $tel)) {$dobrze = false;}

      //weryfikacja dostępności miejsc

        foreach ($_POST['s'] as $c) {
          $seat = explode("v",$c);
          $query = "SELECT row, seat FROM reservations WHERE id_seans=$id_seans AND row=$seat[0] AND seat=$seat[1]";
          $seat_check = $db->query($query)->fetch();
            if($seat_check != NULL){
              $flaga = false;
            }
        }

        if($flaga == true && $dobrze == true) 
        {
          $query2 = "SELECT number FROM reservations ORDER BY id_res DESC LIMIT 1";
          $a = $db->query($query2)->fetch();
            if($a == NULL) {
              $a[0] = 1;
            }
            else{
              $a[0] += 1;
            }

          print '<div id="confirm_header">Numer rezerwacji: <b>'.$a[0].'</b> </div>';

          print '<div id="confirm_body">';
            foreach($_POST['s'] as $c){
              $seat = explode("v",$c);
              print 'Rząd: <b>'.$seat[0].'</b> Miejsce: <b>'.$seat[1]. '</b></br>';
              $query3 = "SELECT id_res FROM reservations ORDER BY id_res DESC LIMIT 1";
              $array = $db->query($query3)->fetch();
                if($array == NULL){
                  $array[0] = 1;
                }
                else{
                  $array[0] += 1;
                }
              $array[0] += 1;
              $date = date("Y-m-d H:i:s");
              $sql = "INSERT INTO reservations(id_res, id_seans, row, seat, time_res, number, name, surname, tel, email) VALUES(:id_res, :id_seans, :row, :seat, :time_res, :number, :name, :surname, :tel, :email)";
              $res = $db->prepare($sql);
              $res->bindValue(':id_res', $array[0]);
              $res->bindValue(':id_seans', $id_seans);
              $res->bindValue(':row', $seat[0]);
              $res->bindValue(':seat', $seat[1]);
              $res->bindValue(':time_res', $date);
              $res->bindValue(':number', $a[0]);
              $res->bindValue(':name', $name);
              $res->bindValue(':surname', $surname);
              $res->bindValue(':tel', $tel);
              $res->bindValue(':email', $email);
              $res->execute();
            }
          print '</br>';
          //info o seansie

          $query4 = "SELECT * FROM seanse WHERE id_seans = $id_seans";
          $inf_query = $db->query($query4)->fetch();
                  print ' Sala: <b>'.$inf_query['id_room'].'</b>';
                  print ' | Film: <b>'.$inf_query['title'].'</b>';
                  $day_array = array(1=>'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
                      $day_string = $day_array[$inf_query['day']];  
                  print ' | <b>'.$day_string.'</b>';
                  print ' <b>'.$inf_query['time'].'</b></br></br>';
          print 'Twoje dane: </br></br>';      
          print 'Imię: <b>'.$name.'</b></br>';
          print 'Nazwisko: <b>'.$surname.'</b></br>';
          print 'E-mail: <b>'.$email.'</b></br>';
          print 'Numer telefonu: <b>'.$tel.'</b></br></br>';
          print '</div>';
          print '<div id="confirm_footer"><a href="index.php" class="button">STRONA GŁÓWNA</a></div>';
        }
        else {
          if($dobrze == false){ print '<p>Błednie wypełniony formularz!</p>'; }
          if($flaga == false){ print '<p>Wybrane miejsca nie są już dostępne!</p>'; }


          print '<p>Aby wrócić do formularza rezerwacji - <u><a href="wybor.php?id_seans='.$id_seans.'" title="kliknij, aby wrócić">kliknij tutaj...</a></u></p>';
        }
    }
    else {
      print '<p><b>Błąd! Nie zaznaczyłeś/aś miejsc!</b></p>';
      print '<p>Aby wrócić do formularza rezerwacji - <u><a href="wybor.php?id_seans='.$id_seans.'" title="kliknij, aby wrócić">kliknij tutaj...</a></u></p>';
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