<?php
// Connexion à la base de donnée //
function connexion_bdd(){

    try{
	$connect_pdo = new PDO('mysql:host=localhost;dbname=machine_cafe',
    'root',"");
    // echo "Connexion réussie";
    }
    catch (Exception $ex){
       echo "Connexion echouée";
       var_dump($ex);
    }
    return $connect_pdo;
}

$connect = connexion_bdd();

// ajouter une commande //

function insertion_commande($connect){
    try{
            $boisson = $_POST["boisson"];
            $gobelet = $_POST["gobelet"];
            $quantite = $_POST["ing"];
            $condiment = $_POST["cond"];
             

        $insertSQL = "insert into commandes (boisson,gobelet,quantite,condiment) values('$boisson', '$gobelet', '$quantite', '$condiment')";
        $insert = $connect->prepare($insertSQL);
        $insert->execute();
    }
    catch(Exception $e){
        echo "Problème de persistence";
        var_dump($e);
    }
}

// ajouter un snack //

function insertion_snack($connect){
    try{
            $boisson = $_POST["boisson"];
            $gobelet = $_POST["gobelet"];
            $quantite = $_POST["ing"];
            $condiment = $_POST["cond"];
             

        $insertSQL = "insert into commandes (boisson,gobelet,quantite,condiment) values('$boisson', '$gobelet', '$quantite', '$condiment')";
        $insert = $connect->prepare($insertSQL);
        $insert->execute();
    }
    catch(Exception $e){
        echo "Problème de persistence";
        var_dump($e);
    }
}

// Supprimer commande //
function supp_materiel($connect,$id){
    try{
        $deleteSQL = "delete from commandes where id=".$id;
        $insert = $connect->prepare($deleteSQL);
        $insert->execute();
    }
    catch(Exception $e){
        echo "Problème de persistence";
        var_dump($e);
    }
    
}

/// Fonction random ///

function random($car) {
    $string = '';
    $chaine = 'abcdefghijklmnpqrstuvwxy1234567890';
    srand((double)microtime()*1000000);
    for($i=0; $i<$car; $i++) {
      $string .= $chaine[rand()%strlen($chaine)];
    }
      return $string;
  }
//   echo random(50);



