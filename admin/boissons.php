<h3>Stocks</h3>
<div id="container">
    <?php
    // if (isset($_POST['modifier'])) {
    //     try {
    //         $sql = "UPDATE boissons SET nom=?, stock=?, condiment=? WHERE id=?";
    //         $stmt = $connect->prepare($sql);
    //         $stmt->execute(array(
    //             $_POST['nom'],
    //             $_POST['stock'],
    //             $_POST['condiment'],
    //             $_POST['id']
    //         ));
    //     } catch (Exception $e) {
    //         print "Erreur ! " . $e->getMessage() . "<br/>";
    //     }
    // } else if (isset($_POST['supprimer'])) {
    //     try {
    //         $sql = "DELETE FROM boissons WHERE id=?";
    //         $stmt = $connect->prepare($sql);
    //         $stmt->execute(array(
    //             $_POST['id']
    //         ));
    //     } catch (Exception $e) {
    //         print "Erreur ! " . $e->getMessage() . "<br/>";
    //     }
    //     echo 'La boisson N° ' . $_POST['id'] . ' a bien été supprimée';
    //  elseif (isset($_POST['ajouter'])) {
    //     print_r($_POST);
    //     try {
    //         $nom = $_POST["nom"];
    //         $stock = $_POST["stock"];
    //         $condiment = $_POST["condiment"];

    //         $insertSQL = "insert into boissons (nom,stock,condiment) values('$nom', '$stock', '$condiment')";
    //         $insert = $connect->prepare($insertSQL);
    //         $insert->execute();
    //     } catch (Exception $e) {
    //         echo "Problème de persistence";
    //         // var_dump($e);
    //     }
    //     echo 'La boisson ' . $_POST['nom'] . ' a bien été ajoutée';
    // }

    ////////////////////lister les fichier d'un repertoire : glob//////////////////////////
    // $path = "../admin/img/*";
    // $filesList = glob($path);
    // print_r($filesList);
   

    /////////////////// Découper nom image en 2 en array ///////////////
    // $image = "rayane.png";
    // $image = explode('.',$image);
    // print_r($image);
    // echo '<br>';
    // echo $image[0];
    // echo '<br>';
    // echo $image[1];
    // echo '<hr>';    

    try {
        $sql = "SELECT * FROM connectadmin";
        $stmt = $connect->prepare($sql);
        $stmt->execute(array());
    } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($_SESSION['admin']['role']);//
    if($_SESSION['admin']['role'] === 1){

    // UPLOAD //
    if (isset($_POST['upload'])) {
        $sendImg=true;
        // print_r($_POST);
        // echo '<hr>';
        // print_r($_FILES['img']); // details de image

        $weight=$_FILES['img']['size']; // taille de l'image
        // echo '<br> Poids '.$weight;

        $weightMax = 3000000;

        if($weight>$weightMax) {
            // echo 'Le fichier est trop lourd';
        }
        
        $type=$_FILES['img']['type']; // Type de l'image
        // echo '<br> type 1 = '.$type;

        $type = str_replace('image/','',$type);
        // echo '<br>type 2 = '.$type;

        $allowedFormats=['jpg','jpeg','gif','bpm','png'];

        if(!in_array($type,$allowedFormats)){
            // echo '<br><p class="text-warning"> Le fichier n\'est pas une image';
            $sendImg=false;
        }

        if($sendImg){
            $path = "../admin/img/*";
            $filesNames = glob($path);
            foreach($filesNames as $key => $value){
                $imgActuelle=str_replace('../admin/img/','',$value);
                // echo '<br>nomImgActuelle>'.$imgActuelle.'<br>';
                $imgActuelle = explode('.',$imgActuelle);
                $imgActuelleNom = $imgActuelle[0];
                $imgActuelleExtention = $imgActuelle[1];
                if($imgActuelleNom == 'boisson_'.$_POST['id']){
                    // echo 'Meme Image<br>';
                    unlink('../admin/img/'.$imgActuelleNom.'.'.$imgActuelleExtention);
                }else{
                    // echo 'No <br>';
                }
            }

            // echo '<br> OK fichier envoyé';
            $imageNom = 'boisson_'.$_POST['id'].'.'.$type;
            move_uploaded_file($_FILES['img']['tmp_name'],'img/'.$imageNom);
            try {
                $sql = "UPDATE boissons SET image=? WHERE id=?";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array(
                    $imageNom,
                    $_POST['id']
                ));
            } catch (Exception $e) {
                print "Erreur ! " . $e->getMessage() . "<br/>";
            };
        }else{
            // echo '<br> Le fichier n\'a pas pu etre envoyer';
        }

        // 2ème METHODE //
        // if(!strstr($type,'jpeg') && !strstr($type, 'gif') && !strstr($type, 'jpg') && !strstr($type, 'png')){
        //     echo '<p class="text-warning"> Le fichier n\'est pas une image';
        // }

        echo '<hr>';
    }

    try {
        $sql = "SELECT * FROM boissons";
        $stmt = $connect->prepare($sql);
        $stmt->execute(array());
    } catch (Exception $e) {
        print "Erreur ! " . $e->getMessage() . "<br/>";
    }

    while ($results = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // print_r($results); echo '<hr>'; 
    ?>
         <!-- Formulaire pour modifier -->
        <form method="POST" enctype="multipart/form-data" data-id="<?php echo $results['id']; ?>">

            <div>
                <div class="parcourir">
                    <input type="file" name="img" id = "parcourir">
                    <div id="texte"> Deposer votre image </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $results['id'];?>">
                <input type="submit" name="upload" style="font-size: medium;">
                <br>
            </div>

            <!-- Ajouter une image à chaque boisson -->
            <?Php
            // Taille de l'image //
            // print_r(getimagesize('img/'.$results['image']));
            
            if(@is_array(getimagesize('img/'.$results['image']))){
                echo '<img class="image" src="img/'.$results['image'].'">';
            }else{
                echo '<i class="fas fa-glass-martini-alt"></i>';
            }
            ?>
            <div>
            <input type="text" class="update" name="nom" data-colonne="nom" value="<?php echo $results['nom']; ?>">
            <input type="number" name="stock" data-colonne="stock" value="<?php echo $results['stock']; ?>" class="update">
            <input type="text" class="update" name="condiment" data-colonne="condiment" value="<?php echo $results['condiment']; ?>">
            <input type="button" name="supprimer" class="supp" value="supprimer">
            
            </div>
        </form>
        <hr>


    <?php } ?>
</div>

<!-- formulaire pour nouveau produit -->
<p>Ajouter un produit</p>
<form method="post" data-id="<?php echo $results['id']; ?>" id="newAjout">
    <input type="text"  id="newName" placeholder="Nom">
    <input type="text"  id="newStock" placeholder="Stock">
    <input type="text"  id="newCondiment" placeholder="Condiment">
    <input type="button" id="create" name="ajouter" value="ajouter">
</form>

<!-- Pour le OK de validation -->
<div id="ajaxUpdate" style="background-color: green;border-radius:50% ;margin:11px;position:fixed;top:0px;right:48%;padding:11px;display:none">Mise à jour</div>
<div id="ajaxCreate" style="background-color: green;border-radius:50% ;margin:11px;position:fixed;top:0px;right:48%;padding:11px;display:none">Ajouté</div>
<div id="ajaxDelete" style="background-color: red;border-radius:50% ;margin:11px;position:fixed;top:0px;right:48%;padding:11px;display:none">Supprimé</div>

<?php }else{
    try {
        $sql = "SELECT * FROM boissons";
        $stmt = $connect->prepare($sql);
        $stmt->execute(array());
    } catch (Exception $e) {print "Erreur ! " . $e->getMessage() . "<br/>";}

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);}
    //print_r($results[1]['nom'])
    echo '<p><img class="image" src="img/'.$results[0]['image'].'"> Boisson : '.$results[0]['nom'].' / Stock : '.$results[0]['stock'].' / Condiment : '.$results[0]['condiment'].'</p>';
    echo '<p><img class="image" src="img/'.$results[1]['image'].'"> Boisson : '.$results[1]['nom'].' / Stock : '.$results[1]['stock'].' / Condiment : '.$results[1]['condiment'].'</p>';
    echo '<p><img class="image" src="img/'.$results[2]['image'].'"> Boisson : '.$results[2]['nom'].' / Stock : '.$results[2]['stock'].' / Condiment : '.$results[2]['condiment'].'</p>';
    ?>

<script>
    // Pour démarrer l'ajax //
    $(document).ready(function() {
        // Update //
        $(document).on('blur','.update', function() {
            // BOUTON OK D'ALERTE VALIDER //
            $('#ajaxUpdate').fadeIn().delay(1000).fadeOut()
            // valeur que l'on change//
            valeur = $(this).val()
            // Ajouter un data-colonne du nom de l'input ou on clique (attr poUr ajouter une propriété)
            colonne = $(this).attr('data-colonne')
            // ID = la valeur de la data-id du formulaire le plus proche
            id = $(this).closest('form').attr('data-id')
            $.ajax({
                    method: "POST",
                    url: "ajax.php",
                    data: {
                        valeur: valeur,
                        colonne: colonne,
                        id: id,
                        toDo:'update'
                    },
                    success: function(retour) {
                        console.log("success");
                        // console.log(retour)
                    }
                })
                .done(function() {
                    console.log('done');
                })
                .fail(function() {
                    alert("Une erreur est survenue");
                });
        })

        // SUPPRIMER //
        $(document).on('click','.supp', function() {

            $('#ajaxDelete').fadeIn().delay(1000).fadeOut()

            id = $(this).closest('form').attr('data-id')
            // Fermer le formulaire le plus proche //
            $(this).closest('form').fadeOut()

            $.ajax({
                    method: "POST",
                    url: "ajax.php",
                    data: {
                        id: id,
                        toDo:'delete'
                    },
                    success: function(retour) {
                        console.log("success");
                        console.log(retour)
                    }
                })
                .done(function() {
                    console.log('done');
                })
                .fail(function() {
                    alert("Une erreur est survenue");
                });
        })

        // Ajouter //
        $('#create').click(function(){

            $('#ajaxCreate').fadeIn().delay(1000).fadeOut()

            nom=$('#newName').val()
            stock=$('#newStock').val()
            condiment=$('#newCondiment').val()

            $.ajax({
                    method: "POST",
                    url: "ajax.php",
                    data: {
                        nom:nom,
                        stock:stock,
                        condiment:condiment,
                        toDo:'insert'
                    },
                    success: function(retour) {
                        console.log("success");

                        console.log('last_id = '+retour) // retour = last_id de la page insert

                        theClone=$('#newAjout').clone() // cloner le formulaire newAjout
                        theClone.removeAttr('id')

                        theClone.find('#create').val('supprimer').addClass('supp') // trouve l'input avec l'ID create et change la valeur et ajoute lui une class 

                        theClone.find('#newName').attr('data-colonne','nom') // Ajouter data-colonne : nom dans l'input de l'ID newName
                        theClone.find('#newStock').attr('data-colonne','stock')
                        theClone.find('#newCondiment').attr('data-colonne','condiment')

                        theClone.find('#newName').addClass('update') // Ajouter une class à un input d'ID newName.
                        theClone.find('#newStock').addClass('update')
                        theClone.find('#newCondiment').addClass('update')

                        theClone.find('#create').attr('id','') // Remplacer l'ID par rien
                        theClone.find('#newName').attr('id','')
                        theClone.find('#newStock').attr('id','')
                        theClone.find('#newCondiment').attr('id','')

                        
                        theClone.attr('data-id',retour) // Ajouter data-id = retour(last_id)

                        console.log(theClone)

                        theClone.appendTo($('#container')) // envoyer theClone dans la div d'ID contaier
                        
                        
                        

                    }
                })
                .done(function() {
                    console.log('done');
                })
                .fail(function() {
                    alert("Une erreur est survenue");
                });



        })


    })
</script>