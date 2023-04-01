<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="contain">

    <?php
    
    require "function.php";

    $showForm = true;
    
    if(isset($_POST['btnInscription'])){
        
        try {
            $sql = "SELECT * FROM users WHERE mail=?";
            $stmt = $connect->prepare($sql);
            $stmt->execute(array(
                $_POST['email']              
            ));
        } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!empty($results)){
            echo '‼ Mail déjà utilisé ‼';
        }else{

            $mdpOne = $_POST['passOne'];
            $mdpTwo = $_POST['passTwo'];

            if($mdpOne != $mdpTwo){
                echo '‼ Les mots de passe doivent êtres identiques ‼';
                $showForm = true;
            }else{
                $nom = $_POST["nom"];
                $prenom = $_POST["prenom"];
                $mail = $_POST["email"];
                $passOne = $_POST["passOne"];
                $showForm=false;
                try {
                    $sql = "INSERT INTO users (nom,prenom,mail,pass) values ('$nom','$prenom','$mail','$passOne')";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute();
                } catch (Exception $e) {
                            echo "Problème de persistence";
                            // var_dump($e);
                        }
                        echo '<h1>Inscription réussie</h1>';
            }
        
    }
    

    }
    if($showForm){
        ?> 
        <h2>Formulaire inscription</h2>

        <form method="POST" id="login" class="form_admin">
            <label for="name">Nom</label>
            <input type="text" name="nom" placeholder="Nom">
            <label for="surname">Prénom</label>
            <input type="text" name="prenom" placeholder="Prénom">
            <label for="mail">Adresse email</label>
            <input type="text" name="email" placeholder="Mail">
            <label for="pass1">Mot de passe</label>
            <input type="text" name="passOne" placeholder="Mot de passe">
            <label for="pass2">Confirmer mot de passe</label>
            <input type="text" name="passTwo" placeholder="Confirmez mot de passe">
            <br>
            <button type="submit" name="btnInscription">Valider</button>
        </form>
                
                <?php
    }
    
    
    ?>

        <a href="admin/index.php">Login</a>
        <a href="index.php">Commander</a>

    </div>


</body>
</html>