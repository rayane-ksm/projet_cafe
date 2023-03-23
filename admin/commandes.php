<h3>Commandes</h3>
<?php

try {
                $sql = "SELECT nom,gobelet,quantite,commandes.condiment,boissons.stock 
                    FROM commandes 
                    INNER JOIN boissons ON commandes.boisson = boissons.id ";
                $stmt = $connect->prepare($sql);
                $stmt->execute(array());
            } catch (Exception $e) {
                print "Erreur ! " . $e->getMessage() . "<br/>";
            }
            while($results = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo 'Boisson: ' .$results['nom'] . ' | Gobelet: '. $results['gobelet'] . ' | Quantité:  '. $results['quantite'] . ' ' . $results['condiment'] . ' | Stock:  ' . $results['stock'] .'<br>';
            }
            print_r($results);

?>