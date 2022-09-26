<?php 

$app->post('/post_cart',  function () {
require_once 'db.php';

  $data = $request->getParsedBody();
  $id = $data['id'];
  $user_id = $_COOKIE[$user_name];
  $pid = $data['pid'];
  $cart_name = $data['cart_name'];
  $cart_price = $data['cart_price'];
  $cart_qauntity = $data['cart_qauntity'];
  $cart_image = $data['cart_image'];

  $query = "INSERT INTO `cart`(`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES ('$id','$user_id','$pid','$cart_name','$cart_price','$cart_qauntity','$cart_image') ";
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
  
    $query = "SELECT SUM(qauntity) FROM cart where user_id = '$_COOKIE[$user_id]'";
    $result = $link->query($query);
    
    
      if ($result < 30){
        $stmt = $link->prepare("UPDATE `users` SET `password`='?' WHERE id = '?'");
        $stmt->bind_param('ss', $new_password, $_COOKIE[$user_id]);
      }
      else{
        echo "you have to many items in your cart";
      }
  
      return json_encode($data);
  });

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

if((array_sum($data) + $cart_qauntity) < 30){
$stmt->execute();
echo "New records created successfully";
}
else{
  echo "you may only have 30 items in your cart";
}