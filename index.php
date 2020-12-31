<!doctype html>
<html lang="fr">
<head>
    <title>Page d'accueil</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

</head>
<body>

<?php
    session_start();

    if(!isset($_SESSION['id'])) { ?>
        <div class="container text-center mt-5">
    <h1>Page d'accueil</h1>
        <table class="table mt-5">
            <tr>
                <td><a href="inscription.php">Inscription</a></td>
                <td><a href="login.php">Connexion</a></td>
            </tr>
        </table>
    </div>
    <?php
    } else {
        echo "<h1 class='text-center mt-5'>Bonjour ". $_SESSION['username']."</h1>"; ?>

        <table class="table text-center">
            <tr>
                <td><a href="profil.php">Profil</a></td>
                <td><a href="deconnexion.php">Se déconnecter</a></td>
            </tr>
        </table>

        <?php
    }


?>

    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.14.0/js/all.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>