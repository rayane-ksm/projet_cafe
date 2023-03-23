<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../style.css">

</head>

<body>
        <div class="contain">
        <h3>Récuperation Mot de Passe</h3>

        <form method="POST">
                <label for="nom">Nom d'utilisateur</label>
                <input type="text" name="login" placeholder="login" autofocus><br>
                <label for="mail">Email</label>
                <input type="text" name="mail" placeholder="mail" autofocus><br>
                <button type="submit" name="recup">Envoyer</button>
        </form>






        <?php
        require "../function.php";

        if (isset($_POST['recup'])) {
            try { 
                $sql="SELECT pass FROM connectadmin WHERE login=? AND email=? ";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array($_POST['login'],$_POST['mail']));
                } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
                $results=$stmt->fetch(PDO::FETCH_ASSOC);
                // // Voir tous les resultats
                print_r($results);
                // // Boucler les resultats
            if (empty($results)){
                echo 'pas trouvé';
                echo '<hr>';
            }else{
                echo 'trouvé';
                echo '<hr>';
            }
        }
 
?>

        </div>
</body>

</html>