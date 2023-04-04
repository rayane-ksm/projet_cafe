<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../jquery.js"></script>
    <script src="https://kit.fontawesome.com/8f45788873.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="contain">
        <?php
        require "../function.php";
        
        $showConnect = true;

        // Si on click sur le bouton deconnecter
        if (isset($_POST['deconnect'])) {
            // Deconnect la session
            unset($_SESSION['admin']);
        }

        // Sinon si on click sur le bouton connecter
        else if (isset($_POST['connect'])) {
            // print_r($_POST);
            // SELECT
            try {
                $sql = "SELECT * FROM connectadmin WHERE login=?";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array(
                    $_POST['login']
                ));
            } catch (Exception $e) {
                print "Erreur ! " . $e->getMessage() . "<br/>";
            }
            $results = $stmt->fetch(PDO::FETCH_ASSOC);
            // // Voir tous les resultats
            // print_r($results);
            // // Boucler les resultats
            // Si il y a rien dans $results
            if (empty($results)) {
                echo 'Vous n\'êtes pas inscrit';
                // echo '<hr>';
            } else {
                // echo 'trouvé';
                // echo '<hr>';

                $login = $results['login'];
                $pass = $results['pass'];

                    if ($_POST['pass'] == $pass) {
                        $_SESSION['admin'] = $results;
                        // print_r($_SESSION['admin']);
                        try {
                            $sql = "UPDATE connectadmin SET essai=? WHERE id=?";
                            $stmt = $connect->prepare($sql);
                            $stmt->execute(array(
                                0,
                                $results['id']
                            ));
                        } catch (Exception $e) {print "Erreur ! " . $e->getMessage();}
                    }
                    else if($results['essai']+1<3){
                        echo 'pass incorrect <br>';
                        $newR = $results['essai'] + 1;
                        echo 'essai '. $newR .'<br>';
                        try {
                            $sql = "UPDATE connectadmin SET essai=essai+1 WHERE id=?";
                            $stmt = $connect->prepare($sql);
                            $stmt->execute(array(
                                $results['id']
                            ));
                        } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}

                    }
                    else{
                        echo 'Nombre d\'essai dépassé <br>';
                        $showConnect=false;
                        
                    }
                }
            } 


        // Si on click sur le bouton valider du mdp oublié //
        else if (isset($_POST['passLostBtn'])) {
        
            try {
                $sql = "SELECT * FROM connectadmin WHERE email=?";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array(
                    $_POST['mail']              
                ));
            } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($results)){
                echo 'Vous n\'êtes pas inscrit';
                echo '<br>';
            }else{
                echo 'Trouvé !';
                echo '<br>';
                $key=random(50);

                    try {
                        $sql = "UPDATE connectadmin SET random=?, key_time=? WHERE id=?";
                        $stmt = $connect->prepare($sql);
                        $stmt->execute(array(
                            $key,
                            date("Y-m-d H:i:s"),
                            $results['id']
                        ));
                    } catch (Exception $e) {
                                print "Erreur ! " . $e->getMessage() . "<br/>";
                    }
                    $link='<a href="index.php?randkey='.$key.'">Cliquez ici pour créer un nouveau mot de passe</a>';
                    echo $link;
            }
        }

        // On verifie la key en GET //
        else if (isset($_GET['randkey'])) {
            // echo $_GET['randkey'].'<br>';
            $showForm=true; // Pour afficher le formulaire //
            // Chercher dans la table le GET randkey dans la colonne random
            try {
                $sql = "SELECT * FROM connectadmin WHERE random=?";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array(
                    $_GET['randkey']              
                ));
            } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
            $results = $stmt->fetch(PDO::FETCH_ASSOC);

            // On verifie si c'est la même KEY //
            if(empty($results)){
                echo 'pas trouvé <br>';
            }else{
                echo 'trouvé <br>';
                // print_r($results);
                
                $now= date("Y-m-d H:i:s"); // Date d'aujourd'hui //
                $dateClick = $results["key_time"]; // Date au moment de valider l'adresse mail //
                $validTime = strtotime("+10 minutes",strtotime($dateClick)); // Date key_time +10min en seconde //
                $date10 = date('Y-m-d H:i:s',$validTime); //Date +10min en format date //
                // echo '<p>Date actuelle: '. $now .'</p>'; 
                // echo '<p>Date du lien: '. $dateClick .'</p>';
                // echo '<p>Date + 10minutes: '. $date10 .'</p>';

                if ($now<$date10) {

                    echo 'Vous avez 10 minutes <br>';
                    

                    // Si on click sur le bouton valider du changement de mot de passe //

                    if (isset($_POST['validMdp'])) {
                        // print_r($_POST);
                        $mdp1 = $_POST['mdp1'];
                        $mdp2 = $_POST['mdp2'];

                        // Tester les 2 MDP //
                        if($mdp1 != $mdp2){
                            echo 'Les 2 MDP doivent êtres identiques';
                            $showForm = true;
                        }else{
                            $showForm=false; // Formulaire disparait car false //
                            try {
                                $sql = "UPDATE connectadmin SET pass=? WHERE id=?";
                                $stmt = $connect->prepare($sql);
                                $stmt->execute(array(
                                    $mdp1,
                                    $results['id']
                                ));
                            } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
                            echo 'Mot de passe modifié <br>';
                            
                        }

                    }
                    if($showForm){ // Formulaire apparait car true //
                        ?>
                        <form method="POST">
                            <input type="text" name="mdp1" placeholder="Entrez votre nouveau mot de passe" autofocus>
                            <input type="text" name="mdp2" placeholder="Confirmez votre mot de passe" autofocus>
                            <button type="submit" name="validMdp">Valider</button>
                        </form>
                        
                    <?php
                    }
                   

                }else{
                    echo 'Lien expiré <br>';
                }
            }
        }
        
    


        //*********************

        // Si on est pas connecté
        if (!isset($_SESSION['admin'])) {
            echo 'Vous n\'êtes pas connecté';
            if($showConnect){
            // Formulaire pour se connecter
            ?>
                <form method="POST" id="login" class="form_admin">
                    <label for="nom">Nom d'utilisateur</label>
                    <input type="text" name="login" placeholder="login" value="visit" autofocus><br>
                    <label for="mdp">Mot de passe</label>
                    <div id="mdpIc">
                    <input display="inline" id="passWord" type="password" value="visit" name="pass" placeholder="Mot de passe">
                    <i id="eye" class="fas fa-eye-slash"></i>
                    </div>
                    
                    <br>
                        <?php

                        $rand = rand(0,5);

                        try {
                            $sql = "SELECT * FROM z_captcha";
                            $stmt = $connect->prepare($sql);
                            $stmt->execute(array());
                        } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
                        
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        echo 'Merci de cliquer sur '.$results[$rand]['nom'].'<br>';
                        echo '<div class="contain_icons">';
                        foreach($results as $key=>$value){
                            echo '<i id="'.$value['id'].'" class="icons '.$value['icon'].'"></i>';
                        }
                        echo '</div>';
                        ?>
                    
                    <button id="btnConnect" type="submit" name="connect" style="display: none;">Se connecter</button>
                </form>
                <?php } ?>

                <script>
                    $(document).ready(function(){
                        $('.icons').on('click',function(){
                        idClicked = $(this).attr("id")
                        idRand = '<?php echo $rand ?>'
                        $('.icons').removeClass('iconActif')
                        $(this).addClass('iconActif')
                        console.log('idCliked '+idClicked+' idRand '+idRand)
                        btnConnecter = $('#btnConnect')
                        
                        if(idClicked-1==idRand){
                            btnConnecter.fadeIn(1000)
                            }
                            else{
                            btnConnecter.fadeOut();
                            console.log ('Vous etes un robot');
                            }
                        })
                    })

                </script>
                

                <form method="POST" class="form_forget">
                    <hr>
                    <button id="btnPerdu" type="button">Mot de passe oublié ? cliquez ici</button>
                    <div id="forget" style="display:none">
                    <input type="text" name="mail" placeholder="Entrez votre mail" autofocus>
                    <input type="submit" id="lostBtn" name="passLostBtn" autofocus>
                    </div>
                </form>

                <a href="../inscription.php">Inscription</a>
                <a href="../index.php">Commander</a>
                <?php }
        else {
                    echo 'Vous êtes connecté';
                    
                ?>

                <form method="POST">
                            <button type="submit" name="deconnect">Se deconnecter</button>
                </form>

                <div class="liens">

                <a href="index.php?page=boissons.php">Boissons</a>
                <a href="index.php?page=commandes.php">Commandes</a>
                </div>

                
            <?php

            if (isset($_GET['page'])) {
                include($_GET['page']);
            } else {
                include('commandes.php');
            }
        } 
        
        /// Date en seconde depuis janvier 1970 ///
        // $date1='2023-03-17 10:08:23'; 
        // $date2='2023-03-17 10:09:23';
        // echo strtotime($date1).'<br>'.strtotime($date2)

        
        
        ?>


        

    </div>


    
    

    <script>
        $(document).ready(function(){
            ///// cacher le mdp et changer l'icone en click //////
            // $('#eye').on('click',function(){
            //     console.log('eye')
            //     eye = $('#eye')
            //     console.log(eye)
            //     password = $('#passWord')
            //     console.log(password)

            //     if(password.attr('type') == 'password'){
                    
            //     // Changer l'icone
            //     eye.attr('class','fa fa-eye')
            //     // changer le type 
            //     password.attr('type','text')
            //     }else{
            //         eye.attr('class','fas fa-eye-slash')
            //         password.attr('type','password')
            //     }

            // })


            ////// MouseDown - MouseUp ///////
            eye = $('#eye')
            password = $('#passWord')

            eye
                .on('mousedown',function(){
                    console.log('mousedown')
                    eye.attr('class','fa fa-eye')
                    password.attr('type','text')
                })
                .on('mouseup',function(){
                    console.log('mouseup')
                    eye.attr('class','fas fa-eye-slash')
                    password.attr('type','password')
                })

            //// Mot de passe oublié //////
                /// Faire apparaître le formulaire ///
            
            btn = $('#btnPerdu')
            btn.on('click',function(){
                
                form_forget = $('#forget')
                console.log('sa fonctionne')

                if(form_forget.css('display') == 'none'){

                    form_forget.css('display','block')
                }
                else{
                    form_forget.css('display','none')
                }
            })


            ///// Générer MDP random /////

            



        })
    </script>


</body>

</html>