<?php

@include 'config.php';

// session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title">customer messages</h1>

   <div class="box-container">

      <?php
       $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
       if(mysqli_num_rows($select_message) > 0){
          while($fetch_message = mysqli_fetch_assoc($select_message)){
      ?>
      <div class="admin-message-card">
         <div class="admin-message-header">
            <span class="admin-message-user"><i class="fas fa-user"></i> User ID: <?php echo $fetch_message['user_id']; ?></span>
            <span class="admin-message-name"><i class="fas fa-user-tag"></i> <?php echo htmlspecialchars($fetch_message['name']); ?></span>
         </div>
         <div class="admin-message-body">
            <div class="admin-message-info">
               <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($fetch_message['number']); ?></div>
               <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($fetch_message['email']); ?></div>
            </div>
            <div class="admin-message-text">
               <i class="fas fa-comment-dots"></i> <?php echo htmlspecialchars($fetch_message['message']); ?>
            </div>
         </div>
         <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">Delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no customer messages received!</p>';
      }
      ?>
   </div>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>