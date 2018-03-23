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
      
      <h3>Rezerwacje</h3>
<!-- obsluga zapisania danych z formularza -->
<?php
$db = new PDO('sqlite:cinema.db'); //polaczenie z baza danych
$sql="CREATE TABLE IF NOT EXISTS reservations(id_res INTEGER PRIMARY KEY, id_seans INTEGER,
 row INTEGER, seat INTEGER, time_res TIMESTAMP, number INTEGER, name TEXT, surname TEXT, tel INTEGER, email TEXT)";
$db->exec($sql);
if(isset($_POST['title'])) //jesli przeslano dane z formularza
  { //odczyt wszystkich danych z formularza
    $id_res = intval($_POST['id_res']);
    $id_seans = intval($_POST['id_seans']);
    $row=strip_tags($_POST['row']);
    $number = intval($_POST['number']);
    $seat=strip_tags($_POST['seat']);
    $tel = intval($_POST['tel']);
    $name=strip_tags($_POST['name']);
    $surname=strip_tags($_POST['surname']);
    $email=strip_tags($_POST['email']);



  if($id_res==0) //nowy seans
  {

  /*  if(!empty($title) || !empty($day) || !empty($time) || !empty($id_room) || !empty($cover) || !empty($description) || !empty($movie_time))
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
    { */

          $sq = "select * from reservations where id_seans=:id_seans and row=:row and seat=:seat and time_res=:time_res and number=:number and name=:name and surname=:surname and tel=:tel and email=:email";
         $a = $db->prepare($sq); 
        //kompilacja polecenia SQL
         $a->bindParam(':id_seans',$id_seans,PDO::PARAM_INT);
        $a->bindParam(':row',$row,PDO::PARAM_STR,80);
        $a->bindParam(':seat',$seat,PDO::PARAM_STR,80);
        $a->bindParam(':time_res',$time_res,PDO::PARAM_STR,80);
        $a->bindParam(':number',$number,PDO::PARAM_INT);
        $a->bindParam(':name',$name,PDO::PARAM_STR,80);
        $a->bindParam(':surname',$surname,PDO::PARAM_STR,80);
        $a->bindParam(':tel',$tel,PDO::PARAM_INT);
        $a->bindParam(':email',$email,PDO::PARAM_STR,80);

        $a->execute(); //wykonanie polecenia SQL po wstawieniu parametrow


          if (!($ble = $a->fetch())) {
             $sql="INSERT INTO seanse(id_seans,row, seat, time_res, number, name, surname, tel, email) 
                VALUES(:id_seans, :row, :seat, :time_res, :number, :name, :surname, :tel, :email)";
           print 'Rezerwacja została dodana do bazy.';
          }
          else
          {
           print 'Podana rezerwacja już istnieje w bazie.';
          
          }
   /* }else { print 'błednie wypełniony formularz';}*/
  }
  else //edycja danych starego uzytkownika
    $sql="UPDATE reservations SET id_seans=:id_seans, row=:row, seat=:seat, time_res=:time_res, number:number, name:name, surname:surname, tel:tel, email:email ";  
  $res=$db->prepare($sql); //kompilacja polecenia SQL
    $a->bindParam(':id_seans',$id_seans,PDO::PARAM_INT);
        $a->bindParam(':row',$row,PDO::PARAM_STR,80);
        $a->bindParam(':seat',$seat,PDO::PARAM_STR,80);
        $a->bindParam(':time_res',$time_res,PDO::PARAM_STR,80);
        $a->bindParam(':number',$number,PDO::PARAM_INT);
        $a->bindParam(':name',$name,PDO::PARAM_STR,80);
        $a->bindParam(':surname',$surname,PDO::PARAM_STR,80);
        $a->bindParam(':tel',$tel,PDO::PARAM_INT);
        $a->bindParam(':email',$email,PDO::PARAM_STR,80);

  $res->execute()>0; //wykonanie polecenia SQL po wstawieniu parametrow
  }
?>
<!-- formularz edycji danych -->
<?php

if(isset($_GET['id_res'])) //jesli 'kliknieto' jakis seans
  { //pobranie danych 'kliknietego' seansu
    $id_seans=intval($_GET['id_res']);
    $sql="SELECT * FROM reservations WHERE id_res=$id_res";
    $res=$db->query($sql);
    $row=$res->fetch();
  }
else $row=array('id_res'=>0,'id_seans'=>'','row'=>'','seat'=>'','time_res'=>'','number'=>'','name'=>'','surname'=>'','tel'=>'','email'=>''); 


?>

<form method="post" action="?">
<input type="hidden" name="id_res" value="<?php print $row['id_res']; ?>" />
id seansu: <input type="hidden" name="id_seans" required="required" 
                 value="<?php print $row['id_seans']; ?>" /><br /> 
Rząd : <input type="hidden" name="row" required="required" 
                      value="<?php print $row['row']; ?>" /><br />
Miejsce: <input type="hidden" name="seat" required="required" 
              value="<?php print $row['seat']; ?>" /><br />
Czas rezerwacji: <input type="hidden" name="time_res" required="required" 
              value="<?php print $row['time_res']; ?>" /><br />
nr rezerwacji: <input type="hidden" name="number" required="required" value="<?php print $row['number']; ?>" /><br />
Imie: <input type="hidden" name="name" required="required" 
              value="<?php print $row['name']; ?>" /><br />                            
Nazwisko: <input type="hidden" name="surname" required="required" 
                 value="<?php print $row['surname']; ?>" /><br />
Telefon: <input type="hidden" name="tel" required="required" 
                 value="<?php print $row['tel']; ?>" /><br />
E-mail: <input type="hidden" name="email" required="required" 
                 value="<?php print $row['email']; ?>" /><br />
<input type="submit" value="Zapisz" />

</form>
<hr />
<!-- tabela seansow -->
<table>
 <thead>
  <tr><th>#id</th><th>usuń</th><th>id seansu</th><th>rzad </th><th>miejsce</th><th>czas rez</th><th>nr</th><th>imie</th><th>nazwisko</th><th>telefon</th><th>email</th></tr>
 </thead>
 <tbody>
<?php
$sql="SELECT * FROM reservations ORDER BY id_res LIMIT 1000";
$res=$db->query($sql); //pobranie danych wszystkich (max 1000) seansow
if($res) while($row=$res->fetch())
  { //wypisanie danych - klikniecie w id pozwala edytowac dane
    
  print '<tr><td><a href="?id_res='.$row['id_res'].'">'.$row['id_res'].'</a></td>'; 
  print '<td><a href="usunrez.php?id_res='.$row['id_res'].'">X</a></td>';
  print '<td>'.$row['id_seans'].'</td>'; 
  print '<td>'.$row['row'].'</td>'; 
  print '<td>'.$row['seat'].'</td>'; 
  print '<td>'.$row['time_res'].'</td>'; 
  print '<td>'.$row['number'].'</td>'; 
  print '<td>'.$row['name'].'</td>'; 
  print '<td>'.$row['surname'].'</td>'; 
  print '<td>'.$row['tel'].'</td>'; 
  print '<td>'.$row['email'].'</td></tr>'.PHP_EOL; 
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