<?php

include 'components/connect.php';

$pageTitle = "Bean Appétit- Home";
$companyName = "Bean Appétit";
$address = "Cavite State University-Imus Campus";
$email = "beanappétit@gmail.cpm";
$phone = "(123) 456-7890";
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title><?= $pageTitle ?></title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">


   <style>
      body {
         font-family: 'Arial', sans-serif;
         margin: 0;
         padding: 0;
         box-sizing: border cover;
         background-color:rgb(66, 21, 13);
         color: #333;
      }

      .hero {
   background-image: url('kapehan.jpg'); 
   background-size: cover; 
   background-position: center; 
   background-repeat: no-repeat; 
   background-attachment: fixed; 
   height: 100vh; 
   width: 100vw; 
   display: flex;
   align-items: center;
   justify-content: center;
   color: #fff;
   text-align: center;
   padding: 0 1rem;
      }

      .hero h1 {
         font-size: 6rem;
         margin-bottom: 1rem;
         color:rgb(228, 240, 247);
         height: 80px;
         width: 1000px;
         border-radius: 10px;
         background: rgba(0, 0, 0, 0.5);
         margin:auto;
         margin-top:50px;
        
      }

      .hero p {
         font-size: 3rem;
         margin-bottom: 3rem;
      }

      .hero a {
         background-color: rgb(117, 11, 8);
         background-position: center;
         background-size: cover;
         background-position: center;
         color: rgb(214, 236, 233);
         width: 70vw;
         height: 7vh;
         font-size: 2rem;
         border-radius: 100px;
         transition: 0.3s;
         text-align: center;
         display: flex;
         justify-content: center;
         align-items: center;       
         margin-top: 20px; 
      }

      .hero a:hover {
         background-color:rgb(0, 0, 0); 
         color: #fff;
      }
      .contact-section {
   background-image: url('kapehan.jpg');
   background-size: cover;
   background-position: center;
   background-repeat: no-repeat;
   background-attachment: fixed; 
   height: 80vh;
   display: flex;
   justify-content: center;
   align-items: center;
   color: #fff; 
   text-align: center;
   padding: 0 1rem; 
}

.contact-content {
   background: rgba(0, 0, 0, 0.5);
   padding: 2rem;
   border-radius: 10px;
   max-width: 600px;
}

.contact-content h2 {
   font-size: 2.5rem;
   margin-bottom: 1rem;
}

.contact-content p {
   font-size: 1.2rem;
   margin: 0.5rem 0;
   line-height: 1.6;
}

      footer {
         background-color: rgb(117, 11, 8);
         color: #fff;
         padding: 1rem;
         text-align: center;
      }

      @media (max-width:991px) {
         html {
            font-size: 55%;
         }
      }

      @media (max-width:768px) {
         #menu-btn {
            display: inline-block;
         }

         .header .flex .navbar {
            position: absolute;
            top: 99%;
            left: 0;
            right: 0;
            background-color: var(--black);
            padding: 1rem 2rem;
            border-top: 0.1rem solid var(--white);
            clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
            transition: 0.2s linear;
         }

         .header .flex .navbar.active {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
         }

         .header .flex .navbar a {
            display: block;
            width: 100%;
            margin: 1rem 0;
            padding: 1rem 0;
         }
      }

      @media (max-width:450px) {
         html {
            font-size: 50%;
         }

         .heading {
            font-size: 2.5rem;
         }

         .products .box-container {
            grid-template-columns: 1fr;
         }

         .orders .box-container {
            grid-template-columns: 1fr;
         }
      }
   </style>
</head>
<body>
    

<?php include 'components/header.php'; ?>


<section class="hero">
   <div>    
      <h1>Welcome to <?= $companyName ?></h1>
      <p>Your daily dose of caffeine and comfort</p>
      <a href="view_products.php" class="btn">View Products</a>
   </div>
</section>

<section class="contact-section">
   <div class="contact-content">
      <h2>Contact Us</h2>
      <p>Cavite State University-Imus Campus</p>
      <p>Email: Beanappetit@cvsu.edu.ph Phone: (123) 456-7890</p>
   </div>
</section>


<footer>
   <p>&copy; <?= date('Y') ?> <?= $companyName ?>. All rights reserved.</p>
</footer>

</body>
</html>
