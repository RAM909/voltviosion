<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
   exit;
};

$apiKey = "rzp_test_cXK0wwNBwcATYr";
$total_amount = isset($_POST['total_price']) ? $_POST['total_price'] * 100 : 0; // Amount in paise

if(isset($_POST['razorpay_payment_id'])){
    $payment_id = $_POST['razorpay_payment_id'];
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = "Razorpay";
    $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if($check_cart->rowCount() > 0){
        $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, payment_id, payment_status) VALUES(?,?,?,?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $payment_id, 'completed']);

        $order_id = $conn->lastInsertId();

        $cart_items = [];
        while($fetch_cart = $check_cart->fetch(PDO::FETCH_ASSOC)){
            $cart_items[] = $fetch_cart['pid'];
            $insert_order_items = $conn->prepare("INSERT INTO `order_items`(order_id, product_id, quantity) VALUES(?,?,?)");
            $insert_order_items->execute([$order_id, $fetch_cart['pid'], $fetch_cart['quantity']]);
        }

        // Update product pairs
        for ($i = 0; $i < count($cart_items); $i++) {
            for ($j = $i + 1; $j < count($cart_items); $j++) {
                $product_id_1 = min($cart_items[$i], $cart_items[$j]);
                $product_id_2 = max($cart_items[$i], $cart_items[$j]);

                $check_pair = $conn->prepare("SELECT * FROM `product_pairs` WHERE product_id_1 = ? AND product_id_2 = ?");
                $check_pair->execute([$product_id_1, $product_id_2]);

                if ($check_pair->rowCount() > 0) {
                    $update_pair = $conn->prepare("UPDATE `product_pairs` SET count = count + 1 WHERE product_id_1 = ? AND product_id_2 = ?");
                    $update_pair->execute([$product_id_1, $product_id_2]);
                } else {
                    $insert_pair = $conn->prepare("INSERT INTO `product_pairs`(product_id_1, product_id_2, count) VALUES(?,?,1)");
                    $insert_pair->execute([$product_id_1, $product_id_2]);
                }
            }
        }

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        $message[] = 'order placed successfully!';
        header('location:home.php'); // Redirect to the home page
        exit;
    }else{
        $message[] = 'your cart is empty';
    }
}
?>

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

<form action="payscript.php" method="POST">
<script
   src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $apiKey; ?>" // Enter the Test API Key ID generated from Dashboard → Settings → API Keys
    data-amount="<?php echo $total_amount; ?>" // Amount is in currency subunits. Hence, 29935 refers to 29935 paise or ₹299.35.
    data-currency="INR" // You can accept international payments by changing the currency code. Contact our Support Team to enable International for your account
    data-order_id="" // Replace with the order_id generated by you in the backend.
    data-buttontext="Pay with Razorpay"
    data-name="Voltvision"
    data-description="Electrical product at best"
    data-image="https://example.com/your_logo.jpg"
    
    data-theme.color="#F37254"
></script>
<input type="hidden" custom="Hidden Element" name="hidden"/>
<input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
<input type="hidden" name="number" value="<?php echo $_POST['number']; ?>">
<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
<input type="hidden" name="flat" value="<?php echo $_POST['flat']; ?>">
<input type="hidden" name="street" value="<?php echo $_POST['street']; ?>">
<input type="hidden" name="city" value="<?php echo $_POST['city']; ?>">
<input type="hidden" name="state" value="<?php echo $_POST['state']; ?>">
<input type="hidden" name="country" value="<?php echo $_POST['country']; ?>">
<input type="hidden" name="pin_code" value="<?php echo $_POST['pin_code']; ?>">
<input type="hidden" name="total_products" value="<?php echo $_POST['total_products']; ?>">
<input type="hidden" name="total_price" value="<?php echo $_POST['total_price']; ?>">
</form>

<style>
    .razorpay-payment-button { 
        display: none; 
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('.razorpay-payment-button').click();
    });
</script>