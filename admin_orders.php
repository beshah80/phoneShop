<?php

@include 'config.php';

// session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_id'") or die('query failed');
   $message[] = 'payment status updated successfully!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">customer orders</h1>

   <div class="box-container">

      <?php
      
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="admin-order-card">
         <div class="admin-order-header">
            <span class="admin-order-user"><i class="fas fa-user"></i> User ID: <?php echo $fetch_orders['user_id']; ?></span>
            <span class="admin-order-date"><i class="fas fa-calendar-alt"></i> <?php echo $fetch_orders['placed_on']; ?></span>
            <span class="admin-order-status <?php echo $fetch_orders['payment_status'] == 'pending' ? 'pending' : 'paid'; ?>">
               <i class="fas fa-<?php echo $fetch_orders['payment_status'] == 'pending' ? 'hourglass-half' : 'check-circle'; ?>"></i>
               <?php echo htmlspecialchars(ucfirst($fetch_orders['payment_status'])); ?>
            </span>
         </div>
         <div class="admin-order-body">
            <div class="admin-order-info">
               <div><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($fetch_orders['name']); ?></div>
               <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($fetch_orders['number']); ?></div>
               <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($fetch_orders['email']); ?></div>
               <div><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($fetch_orders['address']); ?></div>
            </div>
            <div class="admin-order-details">
               <div><i class="fas fa-list"></i> <strong>Items:</strong> <?php echo htmlspecialchars($fetch_orders['total_products']); ?></div>
               <div><i class="fas fa-credit-card"></i> <strong>Method:</strong> <?php echo htmlspecialchars($fetch_orders['method']); ?></div>
               <div class="admin-order-total"><i class="fas fa-dollar-sign"></i> <strong>Total:</strong> <?php echo number_format($fetch_orders['total_price'], 2); ?> ETB</div>
            </div>
         </div>
         <form action="" method="post" class="admin-order-actions">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment" class="admin-order-select">
               <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <input type="submit" name="update_order" value="Update" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">Delete</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no phone orders placed yet!</p>';
      }
      ?>
   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>