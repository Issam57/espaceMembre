<?php
    require "include/header.php";
    require "PHPMailer/PHPMailerAutoload.php";
    require "include/connexion.php";

    if(isset($_POST['mdp_forget'])) {

        function token_random_string($length=20) {
            $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $token = "";
            for($i=0; $i<$length; $i++) {
                $token.=$str[rand(0,strlen($str)-1)];
            }
            return $token;
        }

        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            Entrez une adresse email valide
            </div>";
        } else {

            require "include/connexion.php";

            $req = $bdd->prepare("SELECT * FROM table_membres WHERE email = :email");
            $req->bindvalue(':email', $_POST['email']);
            $req->execute();

            $result = $req->fetch();

            $nombre = $req->rowCount();

            if($nombre != 1) {
                $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            L'email saisi ne correspond à aucun utilisateur
            </div>";
            } else {
                if($result['validation'] != 1 ) {

                    $token = token_random_string(20);

                    

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
                    $mail->addAddress($_POST['email']);

                    $mail->isHTML(true);

                    $mail->Subject=utf8_decode("Confirmation de l'adresse email");
                    $mail->Body=utf8_decode("Afin de valider votre inscription, veuillez cliquez sur ce lien svp : 
                    <a href='http://localhost/espaceMembre/verification.php?email=".$_POST['email']."&token=".$token."'>Confirmation</a>

                    ");

                    if(!$mail->send()) {
                        $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Email non envoyé !
                        </div>";
                        echo "Erreur: ".$mail->ErrorInfo;
                    } else {
                        $message = "<div class='alert alert-success alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Votre email n'est pas confirmée, veuillez vérifier votre messagerie
                        </div>";
                    }
                } else {

                    $token = token_random_string(20);

                    $req1 = $bdd->prepare("SELECT * FROM recup_mdp WHERE email = :email");
                    $req1->bindvalue(':email', $_POST['email']);
                    $req1->execute();

                    $nombre1 = $req1->rowCount();

                    if($nombre1 == 0) {

                        $req2 = $bdd->prepare("INSERT INTO recup_mdp(email,token) VALUES(:email, :token)");
                        $req2->bindvalue(':email', $_POST['email']);
                        $req2->bindvalue(':token', $token);
                        $req2->execute();

                    } else {

                        $req3 = $bdd->prepare("UPDATE recup_mdp SET token = :token WHERE email = :email");
                        $req3->bindvalue(':token', $token);
                        $req3->bindvalue(':email', $_POST['email']);
                        $req3->execute();

                    }

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
                        $mail->addAddress($_POST['email']);

                        $mail->isHTML(true);

                        $mail->Subject=utf8_decode("Réinitialisation du mot de passe");
                        $mail->Body=utf8_decode("Afin de réinitialiser votre mot de passe, veuillez cliquez sur ce lien svp : 
                        <a href='http://localhost/espaceMembre/newmdp.php?email=".$_POST['email']."&token=".$token."'>Reinitialisation du mot de passe</a>

                        ");

                        if(!$mail->send()) {
                            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            Email non envoyé !
                            </div>";
                            echo "Erreur: ".$mail->ErrorInfo;
                        } else {
                            $message = "<div class='alert alert-success alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                            Nous vous avons envoyé les info pour réinitialiser le mot de passe
                            </div>";
                        }
                }
            }
        }
    }

    ?> 

    <body>
    <div class="container">
        <div class="Back">
            <i class="fa fa-arrow-left" onclick="Back()"></i>
        </div>
        <p class="h2 text-center">Mot de passe oublié</p>
        <p class="h6 text-center">Merci d'entrer votre adresse email ci-dessous, nous vous enverrons un courriel pour réinitialiser votre mot de passe</p>
        <?php if(isset($message)) echo $message ?>
        <form action="" method="post" id="login_form">
            <div class="form-group text-white">
                <label>Votre Email</label>
                <input class="form-control" type="email" name="email"  placeholder="Votre Email">
                <span class="Error"></span>
            </div>
            <div class="form-group text-center"><br>
                <input class="btn btn-primary btn-block" type="submit" name="mdp_forget" value="Réinitialiser mot de passe"/>
            </div>
        </form>
    </div>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>