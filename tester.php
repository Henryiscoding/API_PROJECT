<?php
//login get , post and insert

require_once './vendor/autoload.php';

$app = new Slim\App();
$app->insert('/update_login',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $new_password = $data['new_password'];
 
  $stmt = $link->prepare("UPDATE `users` SET `password`='?' WHERE id = '?'");
  $stmt->bind_param('ss', $new_password, $_COOKIE[$user_id]);
   
  if(!isset($_COOKIE[$token_name])) {
    echo "you are not authorised to complete this action";
  } else {
    $stmt->execute();
    echo "password updated";
  }
});


//cart get, insert , insert and delete

$app->get('/get_cart',  function () {
require_once 'db.php';

  $query = "SELECT  `name`, `price`, `quantity`, `image` FROM `cart` where user_id = '$_COOKIE[$user_id]'";
  $result = $link->query($query);

    while ($row = $result->fetch_assoc()){
        $data[] = $row;
        $original_qauntity = "cart_qauntity";
        $row["qauntity"] = $qauntity_int;
        setcookie($cart_qauntity, $qauntity_int, time() + (86400), "/");
    }

    return json_encode($data);
});



//possible ?

$app->get('/post_cart',  function () {
  require_once 'db.php';

  $data = $request->getParsedBody();
  $id = $data['id'];
  $user_id = $_COOKIE[$user_id];
  $pid = $data['pid'];
  $cart_name = $data['cart_name'];
  $cart_price = $data['cart_price'];
  $cart_qauntity = $data['cart_qauntity'];
  $cart_image = $data['cart_image'];

  $stmt = $link->prepare("INSERT INTO `cart`(`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('?','?','?','?','?','?','?')");
  $stmt->bind_param("iiisiis", $id, $user_id, $pid, $cart_name, $cart_price, $cart_qauntity, $cart_image);

  if(( $_COOKIE[$qauntity_int] + $cart_qauntity) < 30){
    $stmt->execute();
    echo "New records created successfully";
  }
  else{
        echo "you may only have 30 items in your cart";
      }
});

//fix
$app->UPDATE('/update_cart',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $new_cart_qauntity = $data['new_cart_qauntity'];
  $insert_pid = $data['pid'];

  $query = "UPDATE `cart` SET `quantity`='$new_cart_qauntity' WHERE pid = '$insert_pid' AND user_id = '$_COOKIE[$user_id]' ";
  if ($link->query($sql) === TRUE) {
    echo "Record insertd successfully";
  } else {
    echo "Error updating record: " . $link->error;
  }
});

$app->delete('/delete_cart',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $delete_pid = $data['pid'];

  $query = "DELETE * FROM `cart` WHERE pid = '$delete_pid AND user_id = '$_COOKIE[$user_id]''";
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
  
  $query = "SELECT  `name`, `price`, `qauntity`, `image` FROM `wishlist` WHERE user_id = '$_COOKIE[$user_id]'";
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
  $user_id = $_COOKIE[$user_name];
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


