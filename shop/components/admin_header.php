<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>



<header class="header">
   <section class="flex">
      

      <div class="sidebar">
      <h1>AdminPanel</h1>
         <nav class="sidebar-nav">
         
         <a href="dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a>
         <a href="placed_orders.php"><i class="fas fa-shopping-cart me-2"></i>Orders</a>
         <a href="products.php"><i class="fas fa-box me-2"></i>Products</a>
         <a href="users_accounts.php"><i class="fas fa-users me-2"></i>Users</a>
         <a href="admin_accounts.php"><i class="fas fa-user-shield me-2"></i>Admins</a>
         <a href="messages.php"><i class="fas fa-envelope me-2"></i>Messages</a>
         
         </nav>
         <a href="../components/admin_logout.php" class="logout-btn" onclick="return confirm('logout from the website?');"><h3>logout</h3></a> 
      </div>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>
   </section>
</header>
