<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

// Handle product deletion
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   // Fetch product details
   $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $select_product->execute([$delete_id]);

   if($select_product->rowCount() > 0){
      $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);

      // Delete images from folder
      $image_paths = ['../uploaded_img/'.$fetch_product['image_01'], 
                      '../uploaded_img/'.$fetch_product['image_02'], 
                      '../uploaded_img/'.$fetch_product['image_03']];
      
      foreach($image_paths as $image){
         if(file_exists($image) && !empty($fetch_product['image_01'])){
            unlink($image);
         }
      }

      // Delete product from database
      $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
      $delete_product->execute([$delete_id]);

      $message[] = 'Product deleted successfully!';
   } else {
      $message[] = 'Product not found!';
   }
}

if(isset($_POST['add_product'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
   $dimensions = filter_var($_POST['dimensions'], FILTER_SANITIZE_STRING); // New dimensions field
   $category = $_POST['category'];

   $image_01 = filter_var($_FILES['image_01']['name'], FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   $image_02 = filter_var($_FILES['image_02']['name'], FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   $image_03 = filter_var($_FILES['image_03']['name'], FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Product name already exists!';
   } else {
      $insert_products = $conn->prepare("INSERT INTO `products`(name, details, price, dimensions, category, image_01, image_02, image_03) VALUES(?,?,?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $dimensions, $category, $image_01, $image_02, $image_03]);

      if($insert_products){
         if($image_size_01 > 2000000 || $image_size_02 > 2000000 || $image_size_03 > 2000000){
            $message[] = 'Image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name_01, $image_folder_01);
            move_uploaded_file($image_tmp_name_02, $image_folder_02);
            move_uploaded_file($image_tmp_name_03, $image_folder_03);
            $message[] = 'New product added!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>
<div class="main-content">
<section class="add-products">
   <h1 class="heading">Add Product</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="Enter product name" name="name">
         </div>
         <div class="inputBox">
            <span>Price (required)</span>
            <input type="number" class="box" required min="0" placeholder="Enter product price" name="price">
         </div>
         <div class="inputBox">
            <span>Details (required)</span>
            <textarea class="box" required placeholder="Enter product details" name="details" maxlength="1000"></textarea>
         </div>
         <div class="inputBox">
            <span>Dimensions (optional)</span>
            <textarea class="box" placeholder="Enter product dimensions" name="dimensions" maxlength="500"></textarea>
         </div>
         <div class="inputBox">
            <span>Category (required)</span>
            <select name="category" class="box" required>
               <option value="" disabled selected>Select a category</option>
               <option value="Wire">Wire</option>
               <option value="Cables">Cables</option>
               <option value="Tool">Hand Tools</option>
               <option value="modular">Switches</option>
               <option value="breaker">Circuit Protectors</option>
               <option value="power tool">Power Tools</option>
               <option value="solar panel">Solar Panel</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Image 01 (required)</span>
            <input type="file" class="box" required accept="image/jpg, image/jpeg, image/png" name="image_01">
         </div>
         <div class="inputBox">
            <span>Image 02</span>
            <input type="file" class="box" accept="image/jpg, image/jpeg, image/png" name="image_02">
         </div>
         <div class="inputBox">
            <span>Image 03</span>
            <input type="file" class="box" accept="image/jpg, image/jpeg, image/png" name="image_03">
         </div>
      </div>
      <input type="submit" class="btn" name="add_product" value="Add Product">
   </form>
</section>

<section class="show-products">
   <h1 class="heading">Products Added.</h1>
   <div class="box-container">
   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price">Rs. <span><?= $fetch_products['price']; ?></span>/-</div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
      </div>
   </div>
   <?php } } else { echo '<p class="empty">No products added yet!</p>'; } ?>
   </div>
</section>
</div>
<script src="../js/admin_script.js"></script>
</body>
</html>
