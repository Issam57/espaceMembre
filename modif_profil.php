<?php
    require "include/header.php";

    session_start();

    if(isset($_POST['modification']) && isset($_SESSION['id'])) {

        $username   = $_POST['username'];
        $mdp        = $_POST['mdp'];
        $mdp2       = $_POST['mdp2'];

        $id = $_SESSION['id'];

        if(empty($username) || !preg_match('/[a-zA-Z0-9]+/', $username)) {

            

            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            Votre username doit être une chaîne de caractère alphanumérique !
            </div>";
        
        } elseif(empty($mdp) || $mdp != $mdp2) {

            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            Veuillez entrer un mot de passe valide !
            </div>";
        } else {

            require "include/connexion.php";

            $requete = $bdd->prepare("SELECT * FROM table_membres WHERE username = :username");
            $requete->bindvalue(':username', $username);
            $requete->execute();
            $result = $requete->fetch();

        if($result) {
            $message = "<div class='alert alert-danger alert-dismissible text-center'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            Votre identifiant existe déjà, choisissez un autre svp !
            </div>";
        } else {

            $hash = password_hash($mdp, PASSWORD_BCRYPT);

            $req = $bdd->prepare("UPDATE membres.table_membres SET username = :username, mdp = :mdp WHERE id = :id");

            $req->bindvalue(':username', $username);
            $req->bindvalue(':mdp', $hash);
            $req->bindvalue(':id', $id);

            $req->execute();

            header("Location: index.php");
        }
        }
    }
?>
    <body>
        <div class="container">
            <p class="h2 text-center">Modifier les infos</p>
            <?php if(isset($message)) echo $message ?>
            <form id="login-form" action="" method="post">
                <div class="preview text-center">
                    <img class="preview-img" src="http://simpleicon.com/wp-content/uploads/account.png" alt="Preview Image" width="200" height="200"/>
                    <span class="Error"></span>
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input class="form-control" type="text" name="username"  placeholder="Votre Username" value=<?= $_SESSION['username'] ?> >
                    <span class="Error"></span>
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input class="form-control" type="password" name="mdp"  placeholder="Mot de passe">
                    <span class="Error"></span>
                </div>
                <div class="form-group">
                    <label for="password2">Confirmation du mot de passe</label>
                    <input class="form-control" type="password" name="mdp2"  placeholder="Confirmez mot de passe"/>
                    <span class="Error"></span>
                </div>
                <div class="form-group text-center"><br>
                    <input class="btn btn-primary btn-block" type="submit" name="modification" value="Modifier"/><br>
                </div>
                
            </form>
        </div>
    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>