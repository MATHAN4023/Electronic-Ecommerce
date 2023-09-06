<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<?php
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
};

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_STRING);
    $address = $_POST['address'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {

        if ($address == '') {
            $message[] = 'please add your address!';
        } else {

            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            // Include the Razorpay PHP SDK
            require 'C:\xampp\htdocs\dashboard\robu clone\Electronic-Ecommerce\razorpay-php-master\razorpay-php-master\Razorpay.php';

            // Initialize Razorpay with your API key and secret key
            $razorpayApiKey = 'rzp_test_InqF9HsZIjNaZV';
            $razorpaySecretKey = 'PzvrPMRlcQSIeFSvKt4yMPoa';
            $razorpay = new Razorpay\Api\Api($razorpayApiKey, $razorpaySecretKey);

            // Create a Razorpay order 
            $orderData = [
                'amount' => $total_price * 100,
                'currency' => 'INR',
                'receipt' => 'order_' . time(),
            ];

            $razorpayOrder = $razorpay->order->create($orderData);
            $razorpayOrderId = $razorpayOrder->id;

            // Generate a Razorpay payment button
            echo "<form action='verify_payment.php' method='POST' id='razorpay-form'>
             <script src='https://checkout.razorpay.com/v1/checkout.js'
                 data-key='$razorpayApiKey'
                 data-amount='$total_price * 100'
                 data-currency='INR'
                 data-order_id='$razorpayOrderId'
                 data-buttontext='Pay with Razorpay'
                 data-name='Your Store Name'
                 data-description='Payment for Order #$razorpayOrderId'
                 data-image='./project images/3d printers.webp'
                 data-prefill.name='$name'
                 data-prefill.email='$email'
                 data-prefill.contact='$number'
                 data-theme.color='#F37254'></script>
             <input type='hidden' name='razorpay_order_id' value='$razorpayOrderId'>
             <input type='hidden' name='total_price' value='$total_price'>
             <input type='hidden' name='submit' value='place_order'>
         </form>";
            exit();
        }
    } else {
        $message[] = 'your cart is empty';
    }
}

?>
<input type="hidden" custom="Hidden Element" name="Hidden">
<style>
    .razorpay-payment-button {
        display: none;
    }
</style>
<script>
    $(document).ready(function() {
        $('.razorpay-payment-button').click();
    });
</script>
<!-- 
    
if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['address'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {

      if ($address == '') {
         $message[] = 'please add your address!';
      } else {

         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         // Include the Razorpay PHP SDK
         require 'C:\xampp\htdocs\dashboard\robu clone\Electronic-Ecommerce\razorpay-php-master\razorpay-php-master\Razorpay.php';

         // Initialize Razorpay with your API key and secret key
         $razorpayApiKey = 'rzp_test_InqF9HsZIjNaZV';
         $razorpaySecretKey = 'PzvrPMRlcQSIeFSvKt4yMPoa';
         $razorpay = new Razorpay\Api\Api($razorpayApiKey, $razorpaySecretKey);

         // Create a Razorpay order 
         $orderData = [
            'amount' => $total_price * 100,
            'currency' => 'INR',
            'receipt' => 'order_' . time(),
         ];

         $razorpayOrder = $razorpay->order->create($orderData);
         $razorpayOrderId = $razorpayOrder->id;

         // Generate a Razorpay payment button
         echo "<form action='verify_payment.php' method='POST' id='razorpay-form'>
             <script src='https://checkout.razorpay.com/v1/checkout.js'
                 data-key='$razorpayApiKey'
                 data-amount='$total_price * 100'
                 data-currency='INR'
                 data-order_id='$razorpayOrderId'
                 data-buttontext='Pay with Razorpay'
                 data-name='Your Store Name'
                 data-description='Payment for Order #$razorpayOrderId'
                 data-image='./project images/3d printers.webp'
                 data-prefill.name='$name'
                 data-prefill.email='$email'
                 data-prefill.contact='$number'
                 data-theme.color='#F37254'></script>
             <input type='hidden' name='razorpay_order_id' value='$razorpayOrderId'>
             <input type='hidden' name='total_price' value='$total_price'>
             <input type='hidden' name='submit' value='place_order'>
         </form>";
         exit();
      }
   } else {
      $message[] = 'your cart is empty';
   }
}
 -->