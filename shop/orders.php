<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <script>
      window.onload = function() {
         <?php if(isset($_GET['message'])): ?>
            alert("<?= $_GET['message']; ?>");
         <?php endif; ?>
      };
   </script>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Placed Orders.</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Phone Number : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total price : <span>Rs.<?= $fetch_orders['total_price']; ?>/-</span></p>
      <p> Order status : <span style="color:<?php if($fetch_orders['status'] == 'pending'){ echo 'red'; }elseif($fetch_orders['status'] == 'completed'){ echo 'green'; }elseif($fetch_orders['status'] == 'Out for Delivery'){ echo 'blue'; }elseif($fetch_orders['status'] == 'canceled'){ echo 'orange'; }else{ echo 'black'; }; ?>"><?= $fetch_orders['status']; ?></span> </p>
      
      <?php 
      $order_date = new DateTime($fetch_orders['placed_on']);
      $current_date = new DateTime();
      $interval = $current_date->diff($order_date);
      $days_difference = $interval->days;

      if ($fetch_orders['status'] == 'returned'): ?>
         <p style="color: orange;">Order Returned</p>

      <?php elseif ($fetch_orders['status'] == 'completed' && $days_difference <= 15): ?>
         <form action="return_order.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <button type="submit" name="return_order" class="btn">Return Product</button>
         </form>

      <?php elseif ($fetch_orders['status'] == 'Out for Delivery' && $fetch_orders['status'] != 'canceled'): ?>
         <p style="color: blue;">Out for Delivery</p>
         <form action="cancel_order.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <button type="submit" name="cancel_order" class="btn">Cancel Order</button>
         </form>

      <?php elseif ($fetch_orders['status'] == 'pending' && $fetch_orders['status'] != 'canceled'): ?>
         <form action="cancel_order.php" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <button type="submit" name="cancel_order" class="btn">Cancel Order</button>
         </form>

      <?php elseif ($fetch_orders['status'] == 'canceled'): ?>
         <p style="color: orange;">Order Canceled</p>
      <?php endif; ?>

   </div>
   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>