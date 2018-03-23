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
      
      <h3>seanse</h3>
<!-- obsluga zapisania danych z formularza -->
<?php
$db = new PDO('sqlite:cinema.db'); //polaczenie z baza danych
$sql="CREATE TABLE IF NOT EXISTS seanse(id_seans INTEGER PRIMARY KEY, title TEXT,
 day INTEGER, time TEXT, id_room INTEGER, cover TEXT, description TEXT, movie_time TEXT)";
$db->exec($sql);
if(isset($_POST['title'])) //jesli przeslano dane z formularza
  { //odczyt wszystkich danych z formularza
    $id_seans = intval($_POST['id_seans']);
    $title=strip_tags($_POST['title']);
    $day = intval($_POST['day']);
    $time=strip_tags($_POST['time']);
    $id_room = intval($_POST['id_room']);
    $cover=strip_tags($_POST['cover']);
    $description=strip_tags($_POST['description']);
    $movie_time=strip_tags($_POST['movie_time']);



  if($id_seans==0) //nowy seans
  {

    if(!empty($title) || !empty($day) || !empty($time) || !empty($id_room) || !empty($cover) || !empty($description) || !empty($movie_time))
    {
    $flaga=true;
    $flaga1=true;
    $flaga2=true;
    $flaga3=true;
    $flaga4=true;
    if(empty($title) || !preg_match('/^[0-9A-ZĄĆĘŁŃÓŚŻŹa-ząćęłńóśżź ]+$/',$title))
      
      {print('<p class="error">Niepoprawny tytuł</p>'.PHP_EOL); $flaga = false;}
    if(empty($day) || !preg_match('/^[0-7]{1}+$/',$day))
      {print('<p class="error">Niepoprawny dzien</p>'.PHP_EOL);$flaga1 = false;}
    if(empty($time) || !preg_match('/^[0-9\:]{3}[0-9]{2}$/',$time))
     { print('<p class="error">Niepoprawna godzina</p>'.PHP_EOL);$flaga2 = false;}
    if(empty($id_room) || !preg_match('/^[1-9]{1}[0-9]*$/',$id_room))
      {print('<p class="error">Niepoprawne id sali</p>'.PHP_EOL);$flaga3 = false;}
      if(empty($cover) || !preg_match('/^[a-zA-Z0-9\-\.\/]+$/',$cover))    
      {print('<p class="error">Niepoprawna ściezka do pliku z plakatem</p>'.PHP_EOL);$flaga4 = false;}
       
    } 
    if ($flaga=true || $flaga1=true || $flaga2=true || $flaga3=true || $flaga4=true) 
    {

          $sq = "select * from seanse where title=:title and day=:day and time=:time and id_room=:id_room and cover=:cover and description=:description and movie_time=:movie_time";
         $a = $db->prepare($sq); 
        //kompilacja polecenia SQL
        $a->bindParam(':title',$title,PDO::PARAM_STR,80);
        $a->bindParam(':day',$day,PDO::PARAM_INT);
        $a->bindParam(':time',$time,PDO::PARAM_STR,80);
        $a->bindParam(':id_room',$id_room,PDO::PARAM_INT);
        $a->bindParam(':cover',$cover,PDO::PARAM_STR,80);
        $a->bindParam(':description',$description,PDO::PARAM_STR,80);
        $a->bindParam(':movie_time',$movie_time,PDO::PARAM_STR,80);

        $a->execute(); //wykonanie polecenia SQL po wstawieniu parametrow


          if (!($ble = $a->fetch())) {
             $sql="INSERT INTO seanse(title,day, time, id_room, cover, description,movie_time) 
                VALUES(:title, :day, :time, :id_room, :cover, :description, :movie_time)";
           print 'Seans został dodany do bazy.';
          }
          else
          {
           print 'Podany seans już istnieje w bazie.';
          
          }
    }else { print 'błednie wypełniony formularz';}
  }
  else //edycja danych starego uzytkownika
    $sql="UPDATE seanse SET title=:title, day=:day, time=:time, id_room=:id_room, cover=:cover, description=:description, movie_time=:movie_time WHERE id_seans=$id_seans";  
  $res=$db->prepare($sql); //kompilacja polecenia SQL
  $res->bindParam(':title',$title,PDO::PARAM_STR,80);
  $res->bindParam(':day',$day,PDO::PARAM_INT);
  $res->bindParam(':time',$time,PDO::PARAM_STR,80);
  $res->bindParam(':id_room',$id_room,PDO::PARAM_INT);
  $res->bindParam(':cover',$cover,PDO::PARAM_STR,80);
  $res->bindParam(':description',$description,PDO::PARAM_STR,80);
  $res->bindParam(':movie_time',$movie_time,PDO::PARAM_STR,80);

  $res->execute()>0; //wykonanie polecenia SQL po wstawieniu parametrow
  }
?>
<!-- formularz edycji danych -->
<?php

if(isset($_GET['id_seans'])) //jesli 'kliknieto' jakis seans
  { //pobranie danych 'kliknietego' seansu
    $id_seans=intval($_GET['id_seans']);
    $sql="SELECT * FROM seanse WHERE id_seans=$id_seans";
    $res=$db->query($sql);
    $row=$res->fetch();
  }
else $row=array('id_seans'=>0,'title'=>'','day'=>'','time'=>'','id_room'=>'','cover'=>'','description'=>'','movie_time'=>''); 


?>

<form method="post" action="?">
<input type="hidden" name="id_seans" value="<?php print $row['id_seans']; ?>" />
Tytuł filmu: <input type="text" name="title" required="required" 
                 value="<?php print $row['title']; ?>" /><br /> 
Dzień wyświetlania(1-7) : <input type="text" name="day" required="required" placeholder="Nr od 1 do 7"
                      value="<?php print $row['day']; ?>" /><br />
Godzina seansu: <input type="text" name="time" required="required" placeholder="xx:xx"
              value="<?php print $row['time']; ?>" /><br />
Nr sali: <input type="text" name="id_room" required="required" placeholder="x"
              value="<?php print $row['id_room']; ?>" /><br />
Plakat(ścieżka do pliku): <input type="text" name="cover" required="required" placeholder="img/cover/film5.jpg" value="<?php print $row['cover']; ?>" /><br />
Opis: <input type="text" name="description" required="required" 
              value="<?php print $row['description']; ?>" /><br />                            
Czas trwania: <input type="text" name="movie_time" required="required" placeholder="x godz. x min."
                 value="<?php print $row['movie_time']; ?>" /><br />
<input type="submit" value="Zapisz" />

</form>
<hr />
<!-- tabela seansow -->
<table>
 <thead>
  <tr><th>#id</th><th>usuń</th><th>Tytuł</th><th>Dzień (nr)</th><th>Godzina</th><th>Sala</th><th>Plakat</th><th>Opis</th><th>Czas trwania</th></tr>
 </thead>
 <tbody>
<?php
$sql="SELECT * FROM seanse ORDER BY id_seans LIMIT 1000";
$res=$db->query($sql); //pobranie danych wszystkich (max 1000) seansow
if($res) while($row=$res->fetch())
  { //wypisanie danych - klikniecie w id pozwala edytowac dane
    
  print '<tr><td><a href="?id_seans='.$row['id_seans'].'">'.$row['id_seans'].'</a></td>'; 
  print '<td><a href="usuns.php?id_seans='.$row['id_seans'].'">X</a></td>';
  print '<td>'.$row['title'].'</td>'; 
  print '<td>'.$row['day'].'</td>'; 
  print '<td>'.$row['time'].'</td>'; 
  print '<td>'.$row['id_room'].'</td>'; 
  print '<td>'.$row['cover'].'</td>'; 
  print '<td>'.$row['description'].'</td>'; 
  print '<td>'.$row['movie_time'].'</td></tr>'.PHP_EOL; 
  }
?>
 </tbody>
</table>




<?php
        print '<br /><a href="panel.php" class="button">Powrót do panelu</a>'.PHP_EOL;
        print '<a href="index.php" class="button">Powrót do strony głównej</a>'.PHP_EOL;        
          print '<a href="wyloguj.php" class="button">Wyloguj się</a>'.PHP_EOL;
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