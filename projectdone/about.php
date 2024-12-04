<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="images/logo.png">
   <link rel="icon" type="image/png" sizes="16x16" href="images/logo.png">
   <link rel="manifest" href="/site.webmanifest">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">

   <div class="row">

      <div class="image">
      <img src="images/abt.jpg" alt="" width="180" height="700">
      </div>

   </div>

</section>

<section class="reviews">
   
   <h1 class="heading">Meet Our Team</h1>

   <div class="swiper reviews-slider">

   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <img src="images/" alt="">
         <p>BSIT</p>
         <h3> <a href="" target="_blank">Encinas Cedrick Dave</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/" alt="">
         <p>BSIT</p>
         <h3><a href="" target="_blank">Santos Cid Eric</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/" alt="">
         <p>BSIT</p>
         <h3><a href="" target="_blank">Salonga Jhello</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/" alt="">
         <p>BSIT</p>
         <h3><a href="" target="_blank">Mangaser Jean Cristian</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/" alt="">
         <p>BSIT</p>
         <h3><a href="" target="_blank">Ballano Dexter</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/" alt="">
         <p>BSIT</p>
         <h3><a href=""  target="_blank"></a></h3>
      </div>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>