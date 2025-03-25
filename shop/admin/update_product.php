<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){
   $pid = $_POST['pid'];
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);
   $dimensions = filter_var($_POST['dimensions'], FILTER_SANITIZE_STRING); // New dimensions field
   $category = $_POST['category'];

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ?, dimensions = ?, category = ? WHERE id = ?");
   $update_product->execute([$name, $price, $details, $dimensions, $category, $pid]);

   $message[] = 'Product updated successfully!';

   // Image update logic
   for ($i = 1; $i <= 3; $i++) {
      $image_field = 'image_0' . $i;
      $old_image = $_POST['old_' . $image_field];
      
      if (!empty($_FILES[$image_field]['name'])) {
         $image_name = $_FILES[$image_field]['name'];
         $image_tmp_name = $_FILES[$image_field]['tmp_name'];
         $image_folder = '../uploaded_img/' . $image_name;
         
         // Move the new image
         move_uploaded_file($image_tmp_name, $image_folder);

         // Update the database
         $update_image = $conn->prepare("UPDATE `products` SET $image_field = ? WHERE id = ?");
         $update_image->execute([$image_name, $pid]);

         // Delete old image
         if ($old_image && file_exists('../uploaded_img/' . $old_image)) {
            unlink('../uploaded_img/' . $old_image);
         }
      }
   }

   $message[] = 'Product images updated successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>
<div class="main-content">
<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
      <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
      <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">
      
      <span>Update Name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter product name" value="<?= $fetch_products['name']; ?>">
      
      <span>Update Price</span>
      <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>">
      
      <span>Update Details</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      
      <span>Dimensions (optional)</span>
      <textarea name="dimensions" class="box" placeholder="Enter product dimensions" cols="30" rows="5"><?= $fetch_products['dimensions']; ?></textarea>
      
      <span>Update Category</span>
      <select name="category" class="box" required>
         <option value="" disabled>Select a category</option>
         <option value="Wire" <?= ($fetch_products['category'] == 'Wire') ? 'selected' : ''; ?>>Wire</option>
         <option value="Cables" <?= ($fetch_products['category'] == 'Cables') ? 'selected' : ''; ?>>Cables</option>
         <option value="Tool" <?= ($fetch_products['category'] == 'Tool') ? 'selected' : ''; ?>>Hand Tools</option>
         <option value="power tool" <?= ($fetch_products['category'] == 'power tool') ? 'selected' : ''; ?>>Power Tools</option>
         <option value="solar panel" <?= ($fetch_products['category'] == 'solar panel') ? 'selected' : ''; ?>>Solar panel</option>
         <option value="breaker" <?= ($fetch_products['category'] == 'breaker') ? 'selected' : ''; ?>>circuit protectors</option>
         <option value="modular" <?= ($fetch_products['category'] == 'modular') ? 'selected' : ''; ?>>Switches</option>
      </select>
      
      <span>Update image 01</span>
      <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      
      <span>Update image 02</span>
      <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      
      <span>Update image 03</span>
      <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="Update">
         <a href="products.php" class="option-btn">Go Back.</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">No product found!</p>';
      }
   ?>

</section>
</div>
<script src="../js/admin_script.js"></script>
   
</body>
</html>
