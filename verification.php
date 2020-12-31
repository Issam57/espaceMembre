<?php
    require "include/header.php";

    require "include/connexion.php";


    if($_GET) {

        if(isset($_GET['email'])) {
            $email = $_GET['email'];
        }
        if(isset($_GET['token'])) {
            $token = $_GET['token'];
        }

        if(!empty($email) && !empty($token)) {
            $req = $bdd->prepare("SELECT * FROM table_membres WHERE email=:email");
            
            $req->bindvalue(':email', $email);

            $req->execute();

            $nombre = $req->rowCount();

            if($nombre == 1) {
                $update = $bdd->prepare("UPDATE table_membres SET validation=:validation, token=:token WHERE email=:email");

                $update->bindvalue(':validation', 1);
                $update->bindvalue(':token', 'valide');
                $update->bindvalue(':email', $email);

                $result = $update->execute();

                if($result) {
                    echo " 
                    <script>
                        alert('Votre adresse email est bien confirmée');
                        document.location.href='connexion.php';
                    </script>";
                }
            }
        }

    }

    if(isset($message)) {echo $message;}