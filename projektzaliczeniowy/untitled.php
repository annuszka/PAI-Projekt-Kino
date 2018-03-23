<!DOCTYPE html>
<html>
<head>
 <title>Konta użytkowników</title>
 <meta charset="utf-8" />
 <link rel="stylesheet" href="styl411.css" />
</head>
<body>
<h3>Baza osób</h3>
<!-- obsluga zapisania danych z formularza -->
<?php
$db = new PDO('sqlite:baza411.db'); //polaczenie z baza danych
$sql="CREATE TABLE IF NOT EXISTS konta(uid INTEGER PRIMARY KEY, nazwisko TEXT,
 imie TEXT, email TEXT UNIQUE, haslo TEXT, upr INT)";
$db->exec($sql);
if(isset($_POST['email'])) //jesli przeslano dane z formularza
  { //odczyt wszystkich danych z formularza
  $uid=intval($_POST['uid']);
  $nazw=strip_tags($_POST['nazw']);
  $imie=strip_tags($_POST['imie']);
  $email=strip_tags($_POST['email']);
  $haslo=$_POST['haslo'];
  if(strlen($haslo)<40) $haslo=sha1($haslo);
  $upr=0;
  if($uid==0) //nowy uzytkownik
    $sql="INSERT INTO konta(nazwisko,imie,email,haslo,upr) 
          VALUES(:nazw, :imie, :email, :haslo, :upr)";
  else //edycja danych starego uzytkownika
    $sql="UPDATE konta SET nazwisko=:nazw, imie=:imie, email=:email,
          haslo=:haslo, upr=:upr WHERE uid=$uid";  
  $res=$db->prepare($sql); //kompilacja polecenia SQL
  $res->bindParam(':nazw',$nazw,PDO::PARAM_STR,80);
  $res->bindParam(':imie',$imie,PDO::PARAM_STR,80);
  $res->bindParam(':email',$email,PDO::PARAM_STR,160);
  $res->bindParam(':haslo',$haslo,PDO::PARAM_STR,40);
  $res->bindParam(':upr',$upr,PDO::PARAM_INT);
  $res->execute()>0; //wykonanie polecenia SQL po wstawieniu parametrow
  }
?>
<!-- formularz edycji danych osobowych -->
<?php
if(isset($_GET['uid'])) //jesli 'kliknieto' jakas osobe
  { //pobranie danych 'kliknietej' osoby
  $uid=intval($_GET['uid']);
  $sql="SELECT * FROM konta WHERE uid=$uid";
  $res=$db->query($sql);
  $row=$res->fetch();
  }
else $row=array('uid'=>0,'nazwisko'=>'','imie'=>'','haslo'=>'','email'=>''); 

?>
<form method="post" action="?">
<input type="hidden" name="uid" value="<?php print $row['uid']; ?>" />
Nazwisko: <input type="text" name="nazw" required="required" pattern="^[A-Ż][a-ż]+"
                 value="<?php print $row['nazwisko']; ?>" /><br /> 
Imię: <input type="text" name="imie" required="required" pattern="^[A-Ż][a-ż]+"
             value="<?php print $row['imie']; ?>" /><br />
Email: <input type="email" name="email" required="required" 
              value="<?php print $row['email']; ?>" /><br />
Hasło: <input type="password" name="haslo" required="required" pattern=".{8,}"
                 value="<?php print $row['haslo']; ?>" /><br />
<input type="submit" value="Zapisz" /><br />
</form>
<hr />
<!-- tabela zarejestrowanych użytkowników -->
<table>
 <thead>
  <tr><th>#</th><th>Nazwisko</th><th>Imię</th><th>E-mail</th></tr>
 </thead>
 <tbody>
<?php
$sql="SELECT uid, nazwisko, imie, email FROM konta ORDER BY uid LIMIT 1000";
$res=$db->query($sql); //pobranie danych wszystkich (max 1000) osob
if($res) while($row=$res->fetch())
  { //wypisanie danych - klikniecie w UID pozwala edytowac dane
  print '  <tr><td><a href="?uid='.$row['uid'].'">'.$row['uid'].'</a></td>'; 
  print '<td>'.$row['nazwisko'].'</td>'; 
  print '<td>'.$row['imie'].'</td>'; 
  print '<td>'.$row['email'].'</td></tr>'.PHP_EOL; 
  }
?>
 </tbody>
</table>
</body>
</html>