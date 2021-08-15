<!-- byb2 fe el connection bata3 el database  -->

<?php
$des='mysql:host=localhost;dbname=ecommers51';
$username='root';
$password='';

try{

    $con = new PDO($des , $username , $password);
    // echo 'connect';

}
catch(PDOException $error){
echo $error->getMessage();
}
?>








