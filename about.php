<?php

@include 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>about us</h3>
    <p> <a href="home.php">home</a> / about </p>
</section>

<section class="about">

    <div class="flex">

        <div class="image">
            <img src="images/about-img-1.png" alt="">
        </div>

        <div class="content">
            <h3>why choose us?</h3>
            <p>At PhoneSell, we offer the latest smartphones from top brands like Apple, Samsung, and Google. Enjoy competitive prices, fast delivery, and excellent customer support.</p>
            <a href="shop.php" class="btn">shop now</a>
        </div>

    </div>

    <div class="flex">

        <div class="content">
            <h3>what we provide?</h3>
            <p>We provide a wide range of smartphones, from flagship iPhones to budget-friendly Xiaomi models. Our secure checkout and reliable shipping ensure a seamless shopping experience.</p>
            <a href="contact.php" class="btn">contact us</a>
        </div>

        <div class="image">
            <img src="images/about-img-2.png" alt="">
        </div>

    </div>

    <div class="flex">

        <div class="image">
            <img src="images/about-img-3.png" alt="">
        </div>

        <div class="content">
            <h3>who we are?</h3>
            <p>PhoneSell is your trusted online store for premium smartphones. With a focus on quality and customer satisfaction, we bring you the best devices from leading brands.</p>
            <a href="#reviews" class="btn">clients reviews</a>
        </div>

    </div>

</section>

<section class="reviews" id="reviews">

    <h1 class="title">client's reviews</h1>

    <div class="box-container">

        <div class="box">
            <img src="images/review-1.png" alt="">
            <p>I bought an iPhone 14 from PhoneSell, and the experience was fantastic! Fast shipping and the phone was in perfect condition.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Kenbon Leta</h3>
        </div>

        <div class="box">
            <img src="images/review-2.png" alt="">
            <p>The Galaxy S23 I ordered arrived quickly, and the customer service was top-notch. Highly recommend PhoneSell!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Dawit Ademe</h3>
        </div>

        <div class="box">
            <img src="images/review-3.png" alt="">
            <p>PhoneSell's selection of Google Pixel phones is amazing. My Pixel 8 is awesome, and the price was unbeatable.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Selam Tesfaye</h3>
        </div>

        <div class="box">
            <img src="images/review-4.png" alt="">
            <p>I got a OnePlus 11 from PhoneSell, and it's everything I wanted. Great quality and smooth delivery process.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Mariamawit Ketema</h3>
        </div>

        <div class="box">
            <img src="images/review-5.png" alt="">
            <p>The Xiaomi 14 I purchased is fantastic, and PhoneSell's support team was super helpful. Will shop again!</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Aster Aweke</h3>
        </div>

        <div class="box">
            <img src="images/review-6.png" alt="">
            <p>My Huawei P60 Pro from PhoneSell is stunning. The website is easy to use, and delivery was quick.</p>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Elshaday Bililign</h3>
        </div>

    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>