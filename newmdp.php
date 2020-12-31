<?php
    require "include/header.php";
    require "PHPMailer/PHPMailerAutoload.php";
    require "include/connexion.php";

    if($_GET) {

        if(isset($_GET['email'])) {
            $email = $_GET['email'];
        }
        if(isset($_GET['token'])) {
            $token = $_GET['token'];
        }

        if(!empty($email) && !empty($token)) {

            $req = $bdd->prepare("SELECT * FROM recup_mdp WHERE email = :email AND token = :token");

            $req->bindvalue(':email', $email);
            $req->bindvalue(':token', $token);

            $req->execute();

            $nombre = $req->rowCount();

            if($nombre != 1) {
                header("Location: login.php");
            } else {

                if(isset($_POST['new_mdp'])) {

                    if(empty($_POST['pass']) || $_POST['pass'] != $_POST['pass2']) {
                        
                        $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Veuillez entrer un mot de passe valide !
                        </div>";
                    } else {

                        $hash = password_hash($_POST['pass'], PASSWORD_BCRYPT);

                        $req2 = $bdd->prepare("UPDATE table_membres SET mdp = :mdp WHERE email = :email");
                        $req2->bindvalue(':email', $email);
                        $req2->bindvalue(':mdp', $hash);

                        $result2 = $req2->execute();

                        if($result2) {
                            echo"
                                <script>
                                    alert('Votre mot de passe a bien été réinitialiser');
                                    document.location.href='login.php';
                                </script>
                            ";
                        } else {
                            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            Votre mot de passe n'a pas été réinitialisé
                            </div>";
                            header("Location: login.php"); 
                        }
                    }
                }
            }
        }

    } else {
        header("Location: inscription.php");
    }

?>

<body>
    <div class="container">
        <div class="Back">
            <i class="fa fa-arrow-left" onclick="Back()"></i>
        </div>
        <p class="h2 text-center">Nouveau mot de passe</p>
        <p class="h6 text-center"><i>Merci d'entrer un nouveau mot de passe</i></p>
        <?php if(isset($message)) echo $message ?>
        <form action="" method="post" id="login_form">
            <div class="form-group text-white">
                <label for="pass">Nouveau mot de passe</label>
                <input class="form-control" type="password" name="pass"  placeholder="Entrez le nouveau mot de passe">
            </div>
            <div class="form-group text-white">
                <label for="pass2">Confirmez mot de passe</label>
                <input class="form-control" type="password" name="pass2"  placeholder="Confirmez mot de passe">
            </div>
            <div class="form-group text-center"><br>
                <input class="btn btn-primary btn-block" type="submit" name="new_mdp" value="Valider"/>
            </div>
        </form>
    </div>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>