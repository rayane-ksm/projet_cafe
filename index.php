<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet café</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery.js"></script>
</head>

<body>

    <div class="contain">

        <form method="POST" id="order" action="main_ajout.php">

            <p>Machine a café</p>

            Choisir votre boisson

            <select autofocus required onchange="boissonChange(this)" name="boisson" id="selectBoissons">
                <option disabled selected>---</option>

                <?php
                require "function.php";

                try {
                    $sql = "SELECT * FROM boissons";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute(array());
                } catch (Exception $e) {
                    print "Erreur ! " . $e->getMessage() . "<br/>";
                }

                while ($results = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . $results['id'] . '"';
                    if ($results['stock'] == 0) {
                        echo 'disabled';
                    }
                    echo '> ' . $results['nom'] . '('.$results['stock'].')  </option>';
                }
                ?>
            </select>

            <p>
                Avec gobelet ?

                <label for="yes">Oui</label>
                <input value="oui" id="yes" name="gobelet" type="radio" checked>

                <label for="no">Non</label>
                <input value="non" id="no" name="gobelet" type="radio">
            </p>

            <p>
            <p id="alim">Quantité :</p>

            <input id="condiment" type="range" name="ing" min="0" max="5" value="0" oninput="sucreChange(this)">
            <span id="quantite">0</span>
            </p>
            <input type="hidden" name="cond" id="cond" value="">
            <button class="button-74" type="submit" name="commander">Commander</button>
        </form>
        <a href="admin/index.php">Login</a>
        <a href="inscription.php">Inscription</a>
    </div>

    <script type="text/javascript">
        
        let emot = document.getElementById('quantite')

        function sucreChange(qty) {

            emot.innerHTML = document.querySelector('#condiment').value;
           
        }

    
    </script>
</body>

</html>