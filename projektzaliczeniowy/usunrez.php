<?php
session_start();
$db = new PDO('sqlite:cinema.db'); //polaczenie z baza danych


if(isset($_GET['id_res'])) //jesli 'kliknieto' jakas rezerwacje
  {
    $id_res=intval($_GET['id_res']);
   if($id_res>0) 
    { 
        $sql="DELETE FROM reservations WHERE id_res=$id_res"; 
        $db->exec($sql); 
    
        header("location: edytrezerwacje.php");
    }
    else 
    {
      print "Wybrana rezerwacja nie istnieje w bazie.";
        header("location: edytrezerwacje.php");
    }
  }

?>