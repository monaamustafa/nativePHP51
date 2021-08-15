<?php
require "config.php";
session_start();
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>
<div class="container">
    <div class="row users">
        <div class="col-4">
            
        <h3>users</h3>
            <?php
             $stmt= $con->prepare('SELECT count(user_id) FROM users WHERE role=3');
             $stmt->execute();
             $count=$stmt->fetchColumn();
            ?>
            <a href="members.php"><?=$count?> <i class="fas fa-users"></i></a>
        </div>
        <div class="col-4">
            
        <h3>Admins</h3>
            <?php
             $stmt= $con->prepare('SELECT count(user_id) FROM users WHERE role=2');
             $stmt->execute();
             $count=$stmt->fetchColumn();
            ?>
<a href="members.php"><?=$count?> <i class="fas fa-users"></i></a>
            </div>
        <div class="col-4">
            
        <h3>Admins</h3>
            <?php
             $stmt= $con->prepare('SELECT count(user_id) FROM users WHERE role=1');
             $stmt->execute();
             $count=$stmt->fetchColumn();
            
            ?>
<a href="members.php"><?=$count?> <i class="fas fa-users-cog"></i></a>
            </div>
    </div>
</div>
<?php include "includes/footer.php"?>