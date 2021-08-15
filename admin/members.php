<?php $action = isset($_GET['action'])?$_GET['action'] : 'index' ;?>
<?php 
   require "config.php"; 
   session_start();
?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>

<?php if($action == 'index'):?>
<?php
if(isset($_GET['role'])&&$_GET['role']=='admin'){
    $permission='!3'; //admins and modiretors
}else{
    $permission='3';
}
    //display all users only
    $stmt = $con->prepare('SELECT * FROM users WHERE role='.$permission);
    $stmt->execute();
    $users = $stmt->fetchAll(); // h3mlhom call bel asmi batt3t el database 
   //  echo "<pre>";
   //   print_r($users);
   //  echo "</pre>";
 ?>
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Pic</th>
                <th scope="col">username</th>
                <th scope="col">email</th>
                <th scope="col">created at</th>
                <th scope="col">action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user):?>
            <tr>
                <th><img src="public/images/<?=$user['image']?>" style="height:5vh;"></th>
                <td><?= $user['username']?></td>
                <td><?= $user['email']?></td>
                <td><?= $user['created_at']?></td>
                <td>
                    <a class="btn btn-info" href="members.php?action=show&selection=<?= $user['user_id']?>">show</a>
                    <?php 
                        $isadmin= 1;
                        if($_SESSION['ROLE'] == $isadmin):
                    ?>
                    <a class="btn btn-warning" href="members.php?action=edit&selection=<?= $user['user_id']?>">edit</a>
                    <a class="btn btn-danger" href="members.php?action=delete&selection=<?= $user['user_id']?>">delete</a>
                    <?php endif?>
                </td>
            </tr>
            <?php endforeach?>
        </tbody>
    </table>
    <a href="members.php?action=create" class="btn btn-primary">add user</a>
</div>
<?php elseif($action == 'show'):?>
    <?php
        $userid = is_numeric($_GET['selection'])&&intval($_GET['selection'])?$_GET['selection']:0;
        $stmt=$con->prepare('SELECT * FROM users WHERE user_id=?');
        $stmt->execute(array($userid));
        $user = $stmt->fetch();
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        ?>

<?php elseif($action == 'create'):?>
    <!-- Start Create Form  -->
    <div class="container">
        <h1 class="text-center">Add User</h1>
        <form method="POST" action="members.php?action=store" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username">
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" name="useremail">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="userpassword">
                <input type="hidden" class="form-control" name="defaultpassword" value="123456">
            </div>
            <div class="mb-3">
                <label class="form-label">Full name</label>
                <input type="text" class="form-control" name="userfullname">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload image</label>
                <input type="file" class="form-control" name="image">
            </div>
            <button type="submit" class="btn btn-primary ">Submit</button>
        </form>
    </div>
    <!-- End Create Form  -->
<?php elseif($action == 'store'):?>
    <?php 
        if($_SERVER['REQUEST_METHOD'] =='POST'){
            $avatarname =$_FILES['image']['name'];
            $avatatype =$_FILES['image']['type'];
            $avatartmp =$_FILES['image']['tmp_name'];
            $allowavatarextension=array('image/jpeg','image/jpeg','image/jpg');
            if(in_array($avatatype,$allowavatarextension)){
                $randomename = rand(0 , 555)."_".$avatarname;
                $destnation= 'public\images\\'. $randomename;
                move_uploaded_file($avatartmp,$destnation);
            }
            $username = $_POST['username'];
            $password = empty($_POST['userpassword'])? sha1($_POST['defaultpassword']): sha1($_POST['userpassword']);
            $email = $_POST['useremail'];
            $fullname = $_POST['userfullname'];
            $errorFrormArray=array();
            if(empty($username)){
                $errorFrormArray[]="sorry enter valid name";
            }
            if(strlen($username)<4){
                $errorFrormArray[]="sorry must be more than 4 characters";
            }
            if(empty($errorFrormArray)){
                $stmt = $con->prepare('INSERT INTO users (username , password , email , fullname , created_at, role,image) VALUES( ? , ? , ? , ? , now() , 3,?)');
                $stmt->execute(array($username , $password , $email , $fullname,$randomename));
            header('location:members.php');   
            }else{
                foreach($errorFrormArray as $error){
                    echo $error . "<br>";
                }
            }
        }
    ?>

<!-- moooona edit -->
<?php elseif($action == 'edit'):?>
<?php 
     $userid =is_numeric($_GET['selection'])&&intval($_GET['selection'])?$_GET['selection']:0;
     $stmt= $con->prepare('SELECT * FROM users WHERE user_id=?');
     $stmt->execute(array($userid));
     $user= $stmt->fetch(); 
      
   ?>
<div class="container">
        <h1 class="text-center">Edit User</h1>
        <form method="POST" action="members.php?action=update">
            <input type="hidden" value="<?=$user['user_id'] ?>" name="userid">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= $user['username']?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" name="useremail" value="<?= $user['email']?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="newpassword">
                <input type="hidden" class="form-control" name="oldpassword" value="<?=$user['password'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Full name</label>
                <input type="text" class="form-control" name="userfullname" value="<?= $user['fullname']?>">
            </div>
            <button type="submit" class="btn btn-primary ">Update</button>
        </form>
    </div>
    
<?php elseif($action == 'update'):?>
    <?php
    if($_SERVER['REQUEST_METHOD'] =='POST'){
        $userid =$_POST['userid'];
        $username =$_POST['username'];
        $email =$_POST['useremail'];
        $fullname =$_POST['userfullname'];
        $password =empty($_POST['newpassword'])?$_POST['oldpassword']:sha1('$_POST[newpassword]');
        $stmt=$con->prepare('UPDATE users SET username=? , password=? , email=? , fullname=? WHERE user_id=?');
        $stmt->execute(array($username,$password ,$email , $fullname , $userid ));
        header('location:members.php');
    }
    ?>
<?php elseif($action == 'delete'):?>
<?php 
         $userid =is_numeric($_GET['selection'])&&intval($_GET['selection'])?$_GET['selection']:0;
         $stmt= $con->prepare('DELETE FROM users WHERE user_id=?');
         $stmt->execute(array($userid));
         header('location:members.php')
      ?>
<?php else:?>
<?php endif?>

<?php include "includes/footer.php"?>