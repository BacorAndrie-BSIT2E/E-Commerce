<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30);
}

if(isset($_POST['add'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $ext = pathinfo($image, PATHINFO_EXTENSION);
   $rename = create_unique_id().'.'.$ext;
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_files/'.$rename;

   if($image_size > 2000000){
      $warning_msg[] = 'Image size is too large!';
   }else{
      $add_product = $conn->prepare("INSERT INTO `products`(id, name, price, image) VALUES(?,?,?,?)");
      $add_product->execute([$id, $name, $price, $rename]);
      move_uploaded_file($image_tmp_name, $image_folder);
      $success_msg[] = 'Product added!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/header.php'; ?>

<section class="product-form">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>PRODUCT INFO</h3>
      <p>PRODUCT NAME <span>*</span></p>
      <input type="text" name="name" placeholder="ENTER PRODUCT NAME" required maxlength="50" class="box">
      <p>PRODUCT PRICE <span>*</span></p>
      <input type="number" name="price" placeholder="ENTER PRODUCT PRICE" required min="0" max="9999999999" maxlength="10" class="box">
      <p>PRODUCT IMAGE <span>*</span></p>
      <input type="file" name="image" required accept="image/*" class="box">
      <input type="submit" class="btn" name="add" value="add product">
      
   </form>

</section>



<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script src="js/script.js"></script>

<?php include 'components/alert.php'; ?>

</body>
</html>