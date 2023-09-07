<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>E-Gadgets</title>


   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="icon" href="./project images/logo.png" type="image/x-icon">
   <link rel="shortcut icon" href="./project images/logo.png" type="image/x-icon">


</head>

<body>

   <?php include 'components/user_header.php'; ?>



   <section class="hero">

      <div class="swiper hero-slider">

         <div class="swiper-wrapper">

            <div class="swiper-slide slide">
               <div class="content">
                  <span>order now</span>
                  <h3>Electronic components</h3>
                  <a href="menu.php" class="btn" style="margin-top: 1rem;
   display: inline-block;
   font-size: 2rem;
   padding:1rem 3rem;
   cursor: pointer;
   text-transform: capitalize;
   transition: .2s linear;background-color:yellow;color:black">see menus</a>
               </div>
               <div class="image">
                  <img src="./project images/Raspberry.webp" alt="">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="content">
                  <span>order now</span>
                  <h3>Drone Parts</h3>
                  <a href="menu.php" class="btn" style="margin-top: 1rem;
   display: inline-block;
   font-size: 2rem;
   padding:1rem 3rem;
   cursor: pointer;
   text-transform: capitalize;
   transition: .2s linear;background-color:yellow;color:black">see menus</a>
               </div>
               <div class="image">
                  <img src="./project images/drone.png" alt="">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="content">
                  <span>order now</span>
                  <h3>Motors</h3>
                  <a href="menu.php" class="btn" style="margin-top: 1rem;
   display: inline-block;
   font-size: 2rem;
   padding:1rem 3rem;
   cursor: pointer;
   text-transform: capitalize;
   transition: .2s linear;background-color:yellow;color:black">see menus</a>
               </div>
               <div class="image">
                  <img src="./project images/Motor.webp" alt="">
               </div>
            </div>

         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

   <section class="category">

      <h1 class="title">Category</h1>

      <div class="box-container">

         <a href="category.php?category=Electronic components" class="box">
            <img src="./project images/Resistor.webp" alt="">
            <h3>Electronic components</h3>
         </a>

         <a href="category.php?category=Drone Parts" class="box">
            <img src="./project images/Drone-Frame.webp" alt="">
            <h3>Drone Parts</h3>
         </a>

         <a href="category.php?category=E-Bike parts" class="box">
            <img src="./project images/e-bike.webp" alt="">
            <h3>E-Bike parts</h3>
         </a>

         <a href="category.php?category=3D Printers part" class="box">
            <img src="./project images/3d printers.webp" alt="">
            <h3>3D Printers part</h3>
         </a>

      </div>

   </section>




   <section class="products">

      <h1 class="title">Recent Updates</h1>

      <div class="box-container">

         <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <form action="" method="post" class="box">
                  <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                  <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                  <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                  <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
                  <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                  <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
                  <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
                  <div class="content flex" style="overflow-y:auto;">
                     <div class="" style="display: flex;flex-direction: column;justify-content: space-between;height: 200px;width: 100%;">
                        <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat m-4"><?= $fetch_products['category']; ?></a>
                        <div class="name"><?= $fetch_products['name']; ?></div>
                        <div class="flex">
                           <div class="price"><span>$</span><?= $fetch_products['price']; ?></div>
                           <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                        </div>
                     </div>
                  </div>
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>

      </div>

      <div class="more-btn">
         <a href="menu.php" class="btn" style="margin-top: 1rem;
   display: inline-block;
   font-size: 2rem;
   padding:1rem 3rem;
   cursor: pointer;
   text-transform: capitalize;
   transition: .2s linear;background-color:yellow;color:black">veiw all</a>
      </div>

   </section>


















   <?php include 'components/footer.php'; ?>


   <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

   <!-- custom js file link  -->
   <script src="js/script.js"></script>

   <script>
      var swiper = new Swiper(".hero-slider", {
         loop: true,
         grabCursor: true,
         effect: "flip",
         pagination: {
            el: ".swiper-pagination",
            clickable: true,
         },
      });
   </script>

</body>

</html>