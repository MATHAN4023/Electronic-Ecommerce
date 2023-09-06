<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <section class="flex">
      <div class="logo d-flex" style="height:50px;">
         <div class="logo_img" style="width:100px;overflow:hidden;">
            <img src="https://images-platform.99static.com//9J188KNa1yorbE-x_YGpAC_6k9g=/0x0:969x969/fit-in/500x500/99designs-contests-attachments/101/101759/attachment_101759249" alt="" height="100px" width="100px" style="width: 100%;height: 180%;object-fit: cover;margin-top:-20%">
         </div>
         <div class="d-flex" style="justify-content: center; align-items: center;">
            <a href="home.php" class="logo">E-Gadgets</a>
         </div>

      </div>

      <nav class="navbar" style="text-transform:capitalize">
         <a href="index.php">home</a>
         <a href="about.php">about</a>
         <a href="menu.php">menu</a>
         <a href="orders.php">orders</a>
         <a href="contact.php">contact</a>
      </nav>

      <div class="icons">
         <?php
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="search.php"><i class="fas fa-search"></i></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <p class="name"><?= $fetch_profile['name']; ?></p>
            <div class="flex">
               <a href="profile.php" class="btn" style="margin-top: 1rem;
   display: inline-block;
   font-size: 2rem;
   padding:1rem 3rem;
   cursor: pointer;
   text-transform: capitalize;
   transition: .2s linear;background-color:yellow;color:black">profile</a>
               <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
            </div>
            <p class="account">
               <a href="login.php">login</a> or
               <a href="register.php">register</a>
            </p>
         <?php
         } else {
         ?>
            <p class="name">please login first!</p>
            <a href="login.php" class="btn">login</a>
         <?php
         }
         ?>
      </div>

   </section>

</header>