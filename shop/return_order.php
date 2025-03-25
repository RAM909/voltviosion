<?php
include 'components/connect.php';
session_start();

if(!isset($_SESSION['user_id'])){
   header('location:user_login.php');
   exit;
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['return_order'])){
   $order_id = $_POST['order_id'];
   $order_id = filter_var($order_id, FILTER_SANITIZE_NUMBER_INT);

   $update_order = $conn->prepare("UPDATE `orders` SET status = 'returned' WHERE id = ? AND user_id = ?");
   $update_order->execute([$order_id, $user_id]);

   $message[] = 'We will arrange a return ASAP';
   header("Location: orders.php?message=" . urlencode(end($message)));
   exit;
}
?>
