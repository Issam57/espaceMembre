<?php
    require "include/header.php";

    require "include/connexion.php";

    if(isset($_POST['inscription'])) {

        $username   = $_POST['username'];
        $email      = $_POST['email'];
        $mdp        = $_POST['mdp'];
        $mdp2       = $_POST['mdp2'];


        if(empty($username) || !preg_match('/[a-zA-Z0-9]+/', $username)) {

            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            Votre username doit être une chaîne de caractère alphanumérique !
            </div>";
        
        } elseif(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            Entrez une adresse email valide
            </div>";

        } elseif(empty($mdp) || $mdp != $mdp2) {

            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            Veuillez entrer un mot de passe valide !
            </div>";
        } else {

            $requete = $bdd->prepare("SELECT * FROM table_membres WHERE username = :username");
            $requete->bindvalue(':username', $username);
            $requete->execute();
            $result = $requete->fetch();

            $requete1 = $bdd->prepare("SELECT * FROM table_membres WHERE email = :email");
            $requete1->bindvalue(':email', $email);
            $requete1->execute();
            $result1 = $requete1->fetch();

            if($result) {
                $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                Votre identifiant existe déjà, choisissez un autre svp !
                </div>";
            } elseif($result1) {
                $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                L'email choisi existe déjà, veuillez en choisir un autre !
                </div>";
            } else {

                function token_random_string($length=20) {
                    $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $token = "";
                    for($i=0; $i<$length; $i++) {
                        $token.=$str[rand(0,strlen($str)-1)];
                    }
                    return $token;
                }
    
                $token = token_random_string(20);


                $hash = password_hash($mdp, PASSWORD_BCRYPT);
    
                $req = $bdd->prepare("INSERT INTO table_membres(username, email, mdp, token) VALUES (:username, :email, :mdp, :token)");
    
                $req->bindvalue(':username', $username);
                $req->bindvalue(':email', $email);
                $req->bindvalue(':mdp', $hash);
                $req->bindvalue(':token', $token);
    
                $result = $req->execute();
    
                require "PHPMailer/PHPMailerAutoload.php";

                $mail = new PHPMailer();

                $mail->isSMTP();
                //$mail->SMTPDebug = 1;
                $mail->Host ='smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = "staiifi57@gmail.com";
                $mail->Password = "Issam261285";
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom("staiifi57@gmail.com");
                $mail->addAddress($email);

                $mail->isHTML(true);

                $mail->Subject="Confirmation de l'adresse email";
                $mail->Body= "Afin de valider votre inscription, veuillez cliquez sur ce lien svp : 
                <a href='http://localhost/espaceMembre/verification.php?email=".$email."&token=".$token."'>Confirmation</a>

                ";

                if(!$mail->send()) {
                    $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    Email non envoyé !
                    </div>";
                    echo "Erreur: ".$mail->ErrorInfo;
                } else {
                    $message = "<div class='alert alert-success alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    Un email vous a été envoyé, veuillez consulter votre messagerie
                    </div>";
                }
            }
        }
    }
?>

<style>
    body {
        background: linear-gradient(blue,grey);
        min-height: 100vh;
        
    }
</style>

<body>
    <div class="container">
        <div class="Back">
            <i class="fa fa-arrow-left" onclick="Back()"></i>
        </div>
        <p class="h2 text-center text-white">Inscription</p>
        <?php if(isset($message)) echo $message ?>
        <form action="" method="post">
            <div class="preview text-center">
                <img class="preview-img" src="http://simpleicon.com/wp-content/uploads/account.png" alt="Preview Image" width="200" height="200"/>
                <span class="Error"></span>
            </div>
            <div class="form-group text-white">
                <label>Username</label>
                <input class="form-control" type="text" name="username"  placeholder="Votre Username"/>
                <span class="Error"></span>
            </div>
            <div class="form-group text-white">
                <label>Email</label>
                <input class="form-control" type="email" name="email"  placeholder="Votre Email"/>
                <span class="Error"></span>
            </div>
            <div class="form-group text-white">
                <label>Mot de passe</label>
                <input class="form-control" type="password" name="mdp"  placeholder="Mot de passe"/>
                <span class="Error"></span>
            </div>
            <div class="form-group text-white">
                <label for="password2">Confirmation du mot de passe</label>
                <input class="form-control" type="password" name="mdp2"  placeholder="Confirmez mot de passe"/>
                <span class="Error"></span>
            </div>
            <div class="form-group text-center"><br>
                <input class="btn btn-primary btn-block" type="submit" name="inscription" value="Inscription"/><br>
                <a href="login.php">Se connecter</a>
            </div>
            
        </form>
    </div>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>