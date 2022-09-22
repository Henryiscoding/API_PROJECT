<?php
//login get , post and update

require_once './vendor/autoload.php';

$app = new Slim\App();
$app->get('/get_login',  function () {
require_once 'db.php';

  $query = "SELECT name , email , password from users";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
          $data[] = $row;
    }

    return json_encode($data);
});

$app->post('/post_login',  function () {
require_once 'db.php';

  $query = "INSERT INTO `users`(`name`, `email`, `password`) VALUES ('$name','$email','$password')";
  if ($link->query($sql) === TRUE) {
    $last_id = $link->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id;
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }

//possible ?
$stmt = $link->prepare("INSERT INTO `users`(`name`, `email`, `password`) VALUES ('?','?','?')");
$stmt->bind_param("sss", $firstname, $lastname, $email);


$data = $request->getParsedBody();
$name = $data['name'];
$email = $data['email'];
$password = $data['password'];
$stmt->execute();

echo "New records created successfully";

});

$app->update('/update_login',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $user_id = $data['user_id'];
  $email = $data['email'];
  $password = $data['password'];
  $name = $data['name'];

  $query = "UPDATE `users` SET`name`='$name',`email`='$email',`password`='$password' WHERE id = '$user_id'";
  if ($link->query($sql) === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $link->error;
  }
});
//possible ?
$app->update('/update_login',  function () {
  require_once 'db.php';

  $data = $request->getParsedBody();
  $name = $data['name'];
  $email = $data['email'];
  $password = $data['password'];
  $stmt->execute();
  $token = (rand ( 10000 , 99999 ));
  
    $query = "UPDATE `users` SET`token`='$token' WHERE id = '$user_id'";
    if ($link->query($sql) === TRUE) {
      echo "Record updated successfully";
    } else {
      echo "Error updating record: " . $link->error;
    }
  });

//cart get, update , insert and delete

$app->get('/get_cart',  function () {
require_once 'db.php';

  $query = "SELECT * from cart";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
        $data[] = $row;
    }

    return json_encode($data);
});

$app->post('/post_cart',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $id = $data['id'];
  $user_id = $data['user_id'];
  $pid = $data['pid'];
  $cart_name = $data['cart_name'];
  $cart_price = $data['cart_price'];
  $cart_qauntity = $data['cart_qauntity'];
  $cart_image = $data['cart_image'];

  $query = "INSERT INTO `cart`(`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$id','$user_id','$pid','$cart_name','$cart_price','$cart_qauntity','$cart_image')";
  if ($link->query($sql) === TRUE) {
    $last_id = $link->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id;
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }
});

//possible ?

$app->get('/get_max_cart',  function () {
  require_once 'db.php';
  
    $query = "SELECT qauntity from cart";
    $result = $link->query($query);
  
      while ($row = $result->fetch_assoc()){
          $data[] = $row;
      }
  
      return json_encode($data);
  });

$data = $request->getParsedBody();
$id = $data['id'];
$user_id = $data['user_id'];
$pid = $data['pid'];
$cart_name = $data['cart_name'];
$cart_price = $data['cart_price'];
$cart_qauntity = $data['cart_qauntity'];
$cart_image = $data['cart_image'];

$stmt = $link->prepare("INSERT INTO `cart`(`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('?','?','?','?','?','?','?')");
$stmt->bind_param("iiisiis", $id, $user_id, $pid, $cart_name, $cart_price, $cart_qauntity, $cart_image);

if((array_sum($data) + $cart_qauntity) < 30){
$stmt->execute();
echo "New records created successfully";
}
else{
  echo "you may only have 30 items in your cart";
}


$app->update('/update_cart',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $new_cart_qauntity = $data['new_cart_qauntity'];
  $update_pid = $data['pid'];

  $query = "UPDATE `cart` SET `quantity`='' WHERE pid = '$update_pid'";
  if ($link->query($sql) === TRUE) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . $link->error;
  }
});

$app->delete('/delete_cart',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $delete_pid = $data['pid'];

  $query = "DELETE FROM `cart` WHERE pid = '$delete_pid'";
  if ($link->query($sql) === TRUE) {
    echo "Record deleted successfully";
  } else {
    echo "Error deleting record: " . $link->error;
  }
});

// get products

$app->get('/get_products',  function () {
  require_once 'db.php';
  
  $query = "SELECT * from products";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
          $data[] = $row;
    }

    return json_encode($data);
});

// wishlist get and post

$app->get('/get_wishlist',  function () {
  require_once 'db.php';
  
  $query = "SELECT * from products";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
          $data[] = $row;
    }

    return json_encode($data);
});

$app->post('/post_wishlist',  function () {
  require_once 'db.php';

  $data = $request->getParsedBody();
  $id = $data['id'];
  $user_id = $data['user_id'];
  $pid = $data['pid'];
  $wishlist_name = $data['wishlist_name'];
  $wishlist_price = $data['wishlist_price'];
  $wishlist_qauntity = $data['wishlist_qauntity'];
  $wishlist_image = $data['wishlist_image'];
  
  $query = "INSERT INTO `wishlist`(`id`, `user_id`, `pid`, `name`, `price`, `qauntity`, `image`) VALUES ('$id','$user_id','$pid','$wishlist_name','$wishlist_price','$wishlist_qauntity','$wishlist_image')";
  if ($link->query($sql) === TRUE) {
    $last_id = $link->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id;
  } else {
    echo "Error: " . $sql . "<br>" . $link->error;
  }
});

$app->run();


