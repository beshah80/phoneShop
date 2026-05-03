<?php

@include '../includes/config.php';

// session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:../login.php');
};

// Move all dashboard variable calculations here
$total_pendings = 0;
$select_pendings = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'pending'") or die('query failed');
while($fetch_pendings = mysqli_fetch_assoc($select_pendings)){
   $total_pendings += $fetch_pendings['total_price'];
}

$total_completes = 0;
$select_completes = mysqli_query($conn, "SELECT * FROM `orders` WHERE payment_status = 'completed'") or die('query failed');
while($fetch_completes = mysqli_fetch_assoc($select_completes)){
   $total_completes += $fetch_completes['total_price'];
}

$select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
$number_of_orders = mysqli_num_rows($select_orders);

$select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
$number_of_products = mysqli_num_rows($select_products);

$select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
$number_of_users = mysqli_num_rows($select_users);

$select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
$number_of_admin = mysqli_num_rows($select_admin);

$select_account = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
$number_of_account = mysqli_num_rows($select_account);

$select_messages = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
$number_of_messages = mysqli_num_rows($select_messages);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="../assets/css/admin_style.css">

</head>
<body>
   
<?php @include '../includes/admin_header.php'; ?>

<section class="dashboard">
    <h1 class="title">Admin Dashboard</h1>
    <div class="dashboard-grid">
        <div class="dashboard-card card-pending">
            <div class="card-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="card-info">
                <h3><?php echo $total_pendings; ?> ETB</h3>
                <p>Pending Payments</p>
            </div>
        </div>
        <div class="dashboard-card card-completed">
            <div class="card-icon"><i class="fas fa-check-circle"></i></div>
            <div class="card-info">
                <h3><?php echo $total_completes; ?> ETB</h3>
                <p>Completed Payments</p>
            </div>
        </div>
        <div class="dashboard-card card-orders">
            <div class="card-icon"><i class="fas fa-shopping-bag"></i></div>
            <div class="card-info">
                <h3><?php echo $number_of_orders; ?></h3>
                <p>Orders Placed</p>
            </div>
        </div>
        <div class="dashboard-card card-products">
            <div class="card-icon"><i class="fas fa-mobile-alt"></i></div>
            <div class="card-info">
                <h3><?php echo $number_of_products; ?></h3>
                <p>Phones Added</p>
            </div>
        </div>
        <div class="dashboard-card card-users">
            <div class="card-icon"><i class="fas fa-users"></i></div>
            <div class="card-info">
                <h3><?php echo $number_of_users; ?></h3>
                <p>Customers</p>
            </div>
        </div>
        <div class="dashboard-card card-admins">
            <div class="card-icon"><i class="fas fa-user-shield"></i></div>
            <div class="card-info">
                <h3><?php echo $number_of_admin; ?></h3>
                <p>Admin Users</p>
            </div>
        </div>
        <div class="dashboard-card card-accounts">
            <div class="card-icon"><i class="fas fa-id-card"></i></div>
            <div class="card-info">
                <h3><?php echo $number_of_account; ?></h3>
                <p>Total Accounts</p>
            </div>
        </div>
        <div class="dashboard-card card-messages">
            <div class="card-icon"><i class="fas fa-envelope"></i></div>
            <div class="card-info">
                <h3><?php echo $number_of_messages; ?></h3>
                <p>Customer Messages</p>
            </div>
        </div>
    </div>
    <div class="dashboard-charts">
        <canvas id="adminMainChart" height="120"></canvas>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../assets/js/admin_script.js"></script>

</body>
</html>