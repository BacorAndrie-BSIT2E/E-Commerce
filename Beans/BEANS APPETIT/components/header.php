<header class="header">

   <section class="flex">

      <nav class="navbar">
      <a href="home_page.php" class="nav-logo">
      <h2 class="logo-text"> Bean Appétit </h2>

         <a href="home_page.php">Home</a>
         <a href="add_product.php">Add product</a>
         <a href="view_products.php"> Products</a>
         <a href="orders.php">My orders</a>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $total_cart_items = $count_cart_items->rowCount();
         ?>
         <a href="shopping_cart.php" class="cart-btn">cart</a>
      </nav>

      <div id="menu-btn" class="fas fa-bars"></div>
   </section>

</header>