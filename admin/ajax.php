<?php
require "../function.php";

if ($_POST['toDo'] == "update") { // toDo variable qu'on declare dans l'ajax //
    // UPDATE //
    $colonne = $_POST['colonne'];
    try {
        $sql = "UPDATE boissons SET $colonne=? WHERE id=?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(array(
            $_POST['valeur'],
            $_POST['id']
        ));
    } catch (Exception $e) {
        print "Erreur ! " . $e->getMessage() . "<br/>";
    };
    // Delete //
} elseif ($_POST['toDo'] == "delete") {

    try {
        $sql = "DELETE FROM boissons WHERE id=?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(array(
            $_POST['id']
        ));
    } catch (Exception $e) {
        print "Erreur ! " . $e->getMessage() . "<br/>";
    }
    echo 'La boisson N° ' . $_POST['id'] . ' a bien été supprimée';
    // INSERT //
} elseif ($_POST['toDo'] == "insert") {

    try {
        $nom = $_POST["nom"];
        $stock = $_POST["stock"];
        $condiment = $_POST["condiment"];

        $insertSQL = "insert into boissons (nom,stock,condiment) values('$nom', '$stock', '$condiment')";
        $insert = $connect->prepare($insertSQL);
        $insert->execute();
    } catch (Exception $e) {
        echo "Problème de persistence";
        // var_dump($e);
    }
    // Recuperer le dernier ID ajouter //
    $last_id = $connect->lastInsertId();

    // echo 'La boisson ' . $_POST['nom'] . ' a bien été ajoutée';
    echo $last_id;
}
