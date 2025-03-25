<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick view</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading">Quick view</h1>

   <?php
     $pid = $_GET['pid'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
     $select_products->execute([$pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name"><?= $fetch_product['name']; ?></div>
            <div class="flex">
               <div class="price"><span>Rs.</span><?= $fetch_product['price']; ?><span>/-</span></div>
               <input type="number" name="qty" class="qty" min="1" max="999" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <div class="details"><?= $fetch_product['details']; ?></div>
            <div class="details"><strong>Dimensions:</strong> <?= $fetch_product['dimensions']; ?></div> <!-- New dimensions field -->
            <div class="flex-btn">
               <input type="submit" value="add to cart" class="btn" name="add_to_cart">
               <input class="option-btn" type="submit" name="add_to_wishlist" value="add to wishlist">
            </div>
         </div>
      </div>
   </form>

   <!-- Recommended Products Section -->
   <div class="recommended-products">
      <h2>Frequently Bought Together</h2>
      <div class="recommended-products-container">
      <?php
         // Query to get frequently bought together products
         $select_recommendations = $conn->prepare("
            SELECT product_id_1 as product_id, count 
            FROM product_pairs 
            WHERE product_id_2 = ? 
            UNION 
            SELECT product_id_2 as product_id, count 
            FROM product_pairs 
            WHERE product_id_1 = ? 
            ORDER BY count DESC 
            LIMIT 3
         ");
         $select_recommendations->execute([$pid, $pid]);

         if($select_recommendations->rowCount() > 0){
            while($fetch_recommendation = $select_recommendations->fetch(PDO::FETCH_ASSOC)){
               $recommended_product_id = $fetch_recommendation['product_id'];

               // Fetch product details
               $select_product_details = $conn->prepare("SELECT id, name, price, image_01 FROM products WHERE id = ?");
               $select_product_details->execute([$recommended_product_id]);
               $fetch_recommended_product = $select_product_details->fetch(PDO::FETCH_ASSOC);

               if($fetch_recommended_product) {
      ?>
      <div class="recommended-product box">
         <img src="uploaded_img/<?= htmlspecialchars($fetch_recommended_product['image_01']); ?>" alt="">
         <div class="name"><?= htmlspecialchars($fetch_recommended_product['name']); ?></div>
         <div class="price"><span>Rs.</span><?= htmlspecialchars($fetch_recommended_product['price']); ?><span>/-</span></div>
         <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_recommended_product['id']); ?>" class="btn">View</a>
      </div>
      <?php
               }
            }
         } else {
            echo '<p class="empty">No recommendations available.</p>';
         }
      ?>
      </div>
   </div>

   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>