<?php
    require "include/header.php";

    if(isset($_POST['connexion'])) {

        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        require "include/connexion.php";

        $req = $bdd->prepare("SELECT * FROM table_membres WHERE email=:email");
        $req->execute(['email'=>$email]);
        $result = $req->fetch();

        if(!$result) {
            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                Erreur ! Veuillez entrer une adresse email valide
                </div>";
        } elseif($result['validation'] == 0) {
            function token_random_string($length=20) {
                $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $token = "";
                for($i=0; $i<$length; $i++) {
                    $token.=$str[rand(0,strlen($str)-1)];
                }
                return $token;
            }

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

                $mail->Subject=utf8_decode("Confirmation de l'adresse email");
                $mail->Body=utf8_decode("Afin de valider votre inscription, veuillez cliquez sur ce lien svp : 
                <a href='http://localhost/espaceMembre/verification.php?email=".$email."&token=".$token."'>Confirmation</a>

                ");

                if(!$mail->send()) {
                    $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    Email non envoyé !
                    </div>";
                    echo "Erreur: ".$mail->ErrorInfo;
                } else {
                    $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    Un email n'est toujours pas confirmé, veuillez vérifier votre messagerie !
                    </div>";
                }

        } else {
            $hashIsOk = password_verify($mdp, $result['mdp']);

            if($hashIsOk) {

                session_start();

                $_SESSION['id'] = $result['id'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['email'] = $email;

                if(isset($_POST['sesouvenir'])) {
                    setcookie("email", $email);
                    setcookie("mdp", $mdp);
                } else {
                    if(isset($_COOKIE['email'])) {
                        setcookie($_COOKIE['email'], "");
                    }
                    if(isset($_COOKIE['mdp'])) {
                        setcookie($_COOKIE['mdp'], "");
                    }
                }
                header("Location: index.php");

            } else {
                $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    Mot de passe incorrect
                    </div>";
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
        <p class="h2 text-center text-white">Connexion</p>
        <?php if(isset($message)) echo $message ?>
        <form action="" method="post" id="login_form">
            <div class="preview text-center">
                <img class="preview-img" src="http://simpleicon.com/wp-content/uploads/account.png" alt="Preview Image" width="200" height="200"/>
                <span class="Error"></span>
            </div>
            <div class="form-group text-white">
                <label>Email</label>
                <input class="form-control" type="email" name="email"  placeholder="Votre Email" value= <?php if(isset($_COOKIE['email'])) {echo $_COOKIE['email'];} ?>>
                <span class="Error"></span>
            </div>
            <div class="form-group text-white">
                <label>Mot de passe</label>
                <input class="form-control" type="password" name="mdp"  placeholder="Mot de passe" value= <?php if(isset($_COOKIE['mdp'])) {echo $_COOKIE['mdp'];} ?>>
                <span class="Error"></span>
            </div>
            <div class="form-group text-center text-white">
                <label for="sesouvenir">Se souvenir de moi</label>
                <input type="checkbox" name="sesouvenir" id="sesouvenir"  placeholder="Mot de passe"/>
                <span class="Error"></span>
            </div>
            <div class="form-group text-center"><br>
                <input class="btn btn-primary btn-block" type="submit" name="connexion" value="Connexion"/><br>
                <a style="text-decoration:none;color:white" href="mdp_forget.php"><i>Mot de passe oublié ?</i></a>
            </div>
            
        </form>
    </div>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>