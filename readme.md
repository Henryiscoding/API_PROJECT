API Shop Endpoint Project

-This project is set up with two files, one being the database connect file labeled "db.php" , an htaccess folder and a php file name index.php which has all the queries and endpoints. This  was done to avoid multiple vendor errors which likely come from inconcurrent php versions on ones code editor , local host and or laptop.

-The queries have been set up as follows
-The user login Get , Post and Update/patch  
-The user cart Get , Post , Update/patch and Delete
-Get all products
-Get and Post for the users wishlist

All user specific queries use a where function that is linked to either the users id number or the users token which are both pre-assigned integers which allow the backend to keep track of which users data to interact with which can be seen with the use of the super global $_cookies.

To execute this code and have it visible on your browser you will need to install the following applications or extensions. Each link will provide an in depth explaination of how to install the application.

-A web server of your choice eg Wamp , Mamp or Xampp - <a href="https://www.w3resource.com/php/installation/install-wamp.php" target="_blank">Google</a>(this link is for wamp!)

-Composer - <a href="https://getcomposer.org/download/" target="_blank">Google</a>

-Slim - <a href="https://www.geeksforgeeks.org/slim-framework-installation-and-configuration/" target="_blank">Google</a>
