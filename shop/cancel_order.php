<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   header('location:user_login.php');
   exit;
}

if(isset($_POST['cancel_order'])){
   $order_id = $_POST['order_id'];

   // Update the order status to 'canceled'
   $update_order = $conn->prepare("UPDATE `orders` SET status = 'canceled' WHERE id = ? AND user_id = ?");
   $update_order->execute([$order_id, $user_id]);

   // Redirect back to orders page with a success message
   header('location:orders.php?message=Order canceled successfully');
   exit;
}

?>
