<?php $action = isset($_GET['action'])?$_GET['action'] : 'index' ;?>
<?php 
    require "config.php"; 
    session_start();
?>
<?php include "includes/header.php"?>
<?php include "includes/navbar.php"?>

<?php if($action == 'index'):?>
<?php 
 //display all products 
    $stmt = $con->prepare('SELECT * FROM products');
    $stmt->execute();
    $products = $stmt->fetchAll(); // h3mlhom call bel asmi batt3t el database 
   //  echo "<pre>";
   //   print_r($products);
   //  echo "</pre>";
?>
<div class="container">
    <div class="row ">
        <?php foreach($products as $product):?>
        <div class="col-3 data">
            <div class="img-cont">
                <img src="public/images/products/<?=$product['image']?>" style="">
                <?php if($product['sale']==1): ?>
                <p class="sale"><?= $product['sale_amount']?> %</p>
                <?php endif?>
            </div>
            <h3><?= $product['product_name']?></h3>
            <div class="price">
                <strong>price:</strong>
                <?php if($product['sale']==1): ?>
                <del><?= $product['price']?>$</del>
                <small><?= $product['price'] - $product['sale_amount']/100 * $product['price']?>$</small>
                <?php else:?>
                <small><?= $product['price']?>$</small>
                <?php endif?>
            </div>
            <a class="btn btn-info" href="products.php?action=show&selection=<?= $product['product_id']?>">show</a>
            <a class="btn btn-warning" href="products.php?action=edit&selection=<?= $product['product_id']?>">edit</a>
            <a class="btn btn-danger"
                href="products.php?action=delete&selection=<?= $product['product_id']?>">delete</a>
        </div>
        <?php endforeach?>
    </div>
    <a href="products.php?action=create" class="btn btn-primary addMore">add product</a>
</div>

<?php elseif($action == 'show'):?>
<?php
    $product_id = is_numeric($_GET['selection'])&&intval($_GET['selection'])?$_GET['selection']:0;
    $stmt=$con->prepare('SELECT * FROM products WHERE product_id=?');
    $stmt->execute(array($product_id));
    $product = $stmt->fetch();
    // echo "<pre>";
    // print_r($product);
    // echo "</pre>";
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-4 showDataImg">
            <img src="public/images/products/<?=$product['image']?>" >
        </div>
        <div class="col-8">
            <h1><?= $product['product_name']?></h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
            <div class="price">
                <strong>price:</strong>
                <?php if($product['sale']==1): ?>
                <del><?= $product['price']?>$</del>
                <small><?= $product['sale_amount']/100 * $product['price']?>$</small>
                <?php else:?>
                <small><?= $product['price']?>$</small>
                <?php endif?>
            </div>
            <a class="btn btn-warning" href="products.php?action=edit&selection=<?= $product['product_id']?>">edit</a>
            <a class="btn btn-danger"
                href="products.php?action=delete&selection=<?= $product['product_id']?>">delete</a>
        </div>
    </div>
</div>
<?php elseif($action == 'create'):?>
<!-- Start Create Form  -->
<div class="container">
    <h1 class="text-center">Add product</h1>
    <form method="POST" action="products.php?action=store" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="product_name">
        </div>
        <div clas s="mb-3">
            <label class="form-label"> Product price</label>
            <input type="text" class="form-control" name="productPrice">
        </div>
        <div class="mb-3">
            <label class="form-label">Sale </label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sale" id="flexRadioDefault1" value="onsale">
                <label class="form-check-label" for="flexRadioDefault1">
                    On Sale
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sale" id="flexRadioDefault2" checked value="nosale">
                <label class="form-check-label" for="flexRadioDefault2">
                    No Sale
                </label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label"> Product sale amount</label>
            <input type="text" class="form-control" name="ProductSaleAmount">
        </div>
        <div class="mb-3">
            <label class="form-label">product image</label>
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
                $destnation= 'public\images\products\\'. $randomename;
                move_uploaded_file($avatartmp,$destnation);
            }
            $product_name = $_POST['product_name'];
            $productPrice = $_POST['productPrice'];
            $ProductSaleAmount = $_POST['ProductSaleAmount'];
            $sale = $_POST['sale'];
            $onSale=0;
            if($sale=="nosale"){
                $onSale=0;
            }else
            $onSale=1;
    //    echo $onSale;
    //           echo "<pre>";
    //  print_r($_POST);
    // echo "</pre>";
            $stmt = $con->prepare('INSERT INTO products (product_name, price, sale, sale_amount, added_by, image) VALUES (?, ?, ?, ?, 2,? )');
    // $stmt->execute(array($productPrice));
            $stmt->execute(array($product_name , $productPrice  ,$onSale, $ProductSaleAmount ,$randomename));
            header('location:products.php'); 
        }
    ?>

<!-- moooona edit -->
<?php elseif($action == 'edit'):?>
<?php 
     $product_id =is_numeric($_GET['selection'])&&intval($_GET['selection'])?$_GET['selection']:0;
     $stmt= $con->prepare('SELECT * FROM products WHERE product_id=?');
     $stmt->execute(array($product_id));
     $product= $stmt->fetch(); 
      
   ?>
<div class="container">
    <h1 class="text-center">Edit product</h1>
    <form method="POST" action="products.php?action=update">
        <input type="hidden" value="<?=$product['product_id'] ?>" name="product_id">
        <div class="mb-3">
            <label class="form-label">product Price</label>
            <input type="text" class="form-control" name="product_name" value="<?= $product['product_name']?>">
        </div>

        <div class="mb-3">
            <label class="form-label">product Price</label>
            <input type="number" class="form-control" name="productPrice" value="<?= $product['price']?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Sale </label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sale" id="flexRadioDefault1" value="onsale"
                <?php if($product['sale']==1):?>
                    checked>
                <?php else:?>
                    >
                    <?php endif?>
                <label class="form-check-label" for="flexRadioDefault1">
                    On Sale
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="sale" id="flexRadioDefault2" value="nosale"
                <?php if($product['sale']==0):?>
                    checked>
                <?php else:?>
                    >
                    <?php endif?>
                <label class="form-check-label" for="flexRadioDefault2">
                    No Sale
                </label>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">sale amount</label>
            <input type="number" class="form-control" name="sale_amount" value="<?=$product['sale_amount'] ?>">
        </div>
        <button type="submit" class="btn btn-primary ">Update</button>
    </form>
</div>

<?php elseif($action == 'update'):?>
<?php
    if($_SERVER['REQUEST_METHOD'] =='POST'){
        $product_name =$_POST['product_name'];
        $productPrice =$_POST['productPrice'];
        $sale = $_POST['sale'];
            $onSale=0;
            if($sale=="nosale"){
                $onSale=0;
            }else
            $onSale=1;
        $sale_amount =$_POST['sale_amount'];
        
    //           echo "<pre>";
    //  print_r($_POST);
    // echo "</pre>";
        $stmt=$con->prepare('UPDATE products SET product_name=? , price=? , sale=? , sale_amount=?  WHERE product_id=?');
        $stmt->execute(array($product_name,$productPrice ,$onSale , $sale_amount, $product_id ));
        header('location:products.php');
    }
    ?>
<?php elseif($action == 'delete'):?>
<?php 
         $product_id =is_numeric($_GET['selection'])&&intval($_GET['selection'])?$_GET['selection']:0;
         $stmt= $con->prepare('DELETE FROM products WHERE product_id=?');
         $stmt->execute(array($product_id));
         header('location:products.php')
      ?>
<?php else:?>
<?php endif?>

<?php include "includes/footer.php"?>