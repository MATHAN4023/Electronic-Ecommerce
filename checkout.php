<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){
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

   if($check_cart->rowCount() > 0){

      if($address == ''){
         $message[] = 'please add your address!';
      }else{
         
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);

         // Include the Razorpay PHP SDK
         require 'C:\xampp\htdocs\food website backend\razorpay-php-master\Razorpay.php';

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
                 data-image='your_logo.png'
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
   }else{
      $message[] = 'your cart is empty';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<style>
   .razorpay-payment-button{display: none;}
</style>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>checkout</h3>
   <p><a href="home.php">home</a> <span> / checkout</span></p>
</div>

<section class="checkout">

   <h1 class="title">orderrr summary</h1>

<form action="" method="post">

   <div class="cart-items">
      <h3>cart items</h3>
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
      <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">$<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
      <?php
            }
         }else{
            echo '<p class="empty">your cart is empty!</p>';
         }
      ?>
      <p class="grand-total"><span class="name">grand total :</span><span class="price">$<?= $grand_total; ?></span></p>
      <a href="cart.php" class="btn">veiw cart</a>
   </div>

   <input type="hidden" name="total_products" value="<?= $total_products; ?>">
   <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
   <input type="hidden" name="name" value="<?= $fetch_profile['name'] ?>">
   <input type="hidden" name="number" value="<?= $fetch_profile['number'] ?>">
   <input type="hidden" name="email" value="<?= $fetch_profile['email'] ?>">
   <input type="hidden" name="address" value="<?= $fetch_profile['address'] ?>">

   <div class="user-info">
      <h3>your info</h3>
      <p><i class="fas fa-user"></i><span><?= $fetch_profile['name'] ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= $fetch_profile['number'] ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= $fetch_profile['email'] ?></span></p>
      <a href="update_profile.php" class="btn">update info</a>
      <h3>delivery address</h3>
      <p><i class="fas fa-map-marker-alt"></i><span><?php if($fetch_profile['address'] == ''){echo 'please enter your address';}else{echo $fetch_profile['address'];} ?></span></p>
      <a href="update_address.php" class="btn">update address</a>
      <select name="method" class="box" required>
         <option value="" disabled selected>select payment method --</option>
         <option value="cash on delivery">cash on delivery(unavilable)</option>
         <option value="Pay online">Pay online</option>
      </select>
      <input type="submit" value="place order" class="btn <?php if($fetch_profile['address'] == ''){echo 'disabled';} ?>" style="width:100%; background:var(--red); color:var(--white);" name="submit">
      
   </div>

</form>
   
</section>

<script>
   window.addEventListener("load", function() {
      const paymentMethodSelect = document.querySelector("select[name='method']");
      const razorpayButton = document.querySelector(".razorpay-payment-button"); // Select by class name

      // Function to click the Razorpay button
      function clickRazorpayButton() {
         if (razorpayButton) {
            razorpayButton.click(); // Trigger a click event
         }
      }

      // Automatically click the Razorpay button after a short delay (adjust as needed)
      setTimeout(clickRazorpayButton, 1000); // 1000 milliseconds (1 second) delay

      paymentMethodSelect.addEventListener("change", function() {
         if (this.value === "Pay online") {
            clickRazorpayButton(); // Click the button when "Pay online" is selected
         }
      });
   });
</script>









<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->






<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>