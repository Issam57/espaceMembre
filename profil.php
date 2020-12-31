<?php
    require "include/header.php";

    session_start();

    if(isset($_SESSION['id'])) {

    
?>

<style>
    body {
        background: linear-gradient(blue,grey);
        min-height: 100vh;
        
    }
</style>

<div class="container mt-5">
    <p style="color:white" class="h1 text-center border border-light">Mon Profil</p>
    <div class="preview text-center">
        <img class="preview-img" src="http://simpleicon.com/wp-content/uploads/account.png" alt="Preview Image" width="200" height="200"/>
        <span class="Error"></span>
    </div>
</div>

    <table class="table mt-5 text-center text-white">
        <tr><td>Nom d'utilisateur: </td> <td><?=$_SESSION['username']?></td></tr>
        <tr><td>Email: </td> <td><?=$_SESSION['email']?></td></tr>
        <tr><td><button class="btn btn-info"><a style="text-decoration:none;color:white" href="modif_profil.php">Modifier profil</a></button></td></tr>
    </table>

<?php } ?>