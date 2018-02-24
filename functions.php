<?php 
	//-------------------------SETUP--------------------------

	//set config file values
	include "config.php";

	//connect to database
	$DB_connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	session_start();
	date_default_timezone_set('America/Los_Angeles');

	/*NOTES: we can either just make functions or 
			organize it into object-oriented style by making classes*/

	//--------------------GLOBAL FUNCTIONS--------------------

	//say hi
	function say_hi()
	{
		echo "hi";
	}

	//taken from http://www.w3schools.in/php-script/time-ago-function/
	function get_time_ago( $time )
	{
		if ($time == null){return "undefined";}

	    $time_difference = time() - $time;
	
	    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
	    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'minute',
	                1                       =>  'second'
	    );
	
	    foreach( $condition as $secs => $str )
	    {
	        $d = $time_difference / $secs;
	
	        if( $d >= 1 )
	        {
	            $t = round( $d );
	            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
	        }
	    }
	}

	function display_credit_card($card_num)
	{
		$splitted_arr = str_split($card_num, 4);
		return $splitted_arr;
	}

	function create_slug($string){
   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
   return $slug;
	}




	//-------------------------CLASSES------------------------

	//user logic (register, login, validation)
	class tuffy_user
	{
		public $conn;	//necessary to connect to database

		//object constructor: 1st var: database link
		function __construct($DB_connection)
		{
			$this->conn = $DB_connection;
		}

		//Registers user into database
		public $register_usernameTaken = false;
		public $register_emailTaken = false;

		function register_user($username_post, $password_post, $email_post, $sec_question, $sec_answer)
		{
			$conn = $this->conn;
			$users_table = USERS_TABLE;

			//real_escape prevents code injection
			$username_post = $conn->real_escape_string($username_post);
			$password_post = $conn->real_escape_string($password_post);
			$email_post = $conn->real_escape_string($email_post);
			$sec_question = $conn->real_escape_string($sec_question);
			$sec_answer = $conn->real_escape_string($sec_answer);

			//password hash and salt
	  		$passHashed = password_hash($password_post, PASSWORD_DEFAULT);

			//Database values
	  		$selectQuery = "SELECT * FROM $users_table WHERE username = '$username_post'";
	  		$selectResult = $conn->query($selectQuery);

	  		//Is username already taken in database?
	  		if($selectResult->num_rows == 0)
  			{
  				$this->register_usernameTaken = false;
  			}else{
	  			$this->register_usernameTaken = true;
	  		}

	  		$selectQuery2 = "SELECT * FROM $users_table WHERE email = '$email_post'";
	  		$selectResult2 = $conn->query($selectQuery2);

	  		//Is username already taken in database?
	  		if($selectResult2->num_rows == 0)
  			{
  				$this->register_emailTaken = false;
  			}else{
	  			$this->register_emailTaken = true;
	  		}

		  	//If username and email is not in database, register!
	  		if(!$this->register_usernameTaken && !$this->register_emailTaken)
	  		{
				$insertQuery = "INSERT INTO $users_table (username, password, email, security_question, security_answer) VALUES ('$username_post', '$passHashed', '$email_post', '$sec_question', '$sec_answer')";
				$insertResult = $conn->query($insertQuery);

				if($insertResult)
				{
					//log them in after they successfully registers
					$this->login_user($username_post, $password_post);
					header("Location: http://" .$_SERVER['SERVER_NAME']);
					exit;
				}
			}
		}

		public $login_usernameFound = false;
		public $login_correctPassword = false;

		function login_user($username_post, $password_post)
		{
			$conn = $this->conn;
			$users_table = USERS_TABLE;

			//Check if username matches one in database
	  		$selectQuery = "SELECT * FROM $users_table WHERE username = '$username_post'";
	  		$usernameMatch = $conn->query($selectQuery);

	  		if($usernameMatch->num_rows == 0)
	  		{
	  			$this->login_usernameFound = false;
	  		}
	  		else
	  		{
	  			$this->login_usernameFound = true;
	  			//echo "Correct Username";
	  			$user_row = mysqli_fetch_assoc($usernameMatch); //Take the row where user was found
	  			$user_row_name = $user_row['username'];
	  			$user_row_pass = $user_row['password'];
	  			$user_row_ID = $user_row['id'];
	  			$user_row_type = $user_row['type'];
	  			$user_row_money = $user_row['money'];
	  			$user_row_email = $user_row['email'];
	  			$user_row_credit_card_num = $user_row['credit_card_num'];

	  			//password_verify checks the the algo-options and unique salt of $user_row_pass and applies to $password_post
	  			if(password_verify($password_post, $user_row_pass))
	  			{
	  				// Check if a newer hashing algorithm is available
				    if (password_needs_rehash($user_row_pass, PASSWORD_DEFAULT))
				    {
				        // If so, create a new hash, and replace the old one
				        $new_hash_pass = password_hash($password_post, PASSWORD_DEFAULT);
				        $updateQuery = "UPDATE $table SET password = '$new_hash_pass' WHERE id = '$user_row_ID'";
				       	$conn->query($updateQuery);
				    }
				    $this->login_correctPassword = true;
	  			}
	  			else{$this->login_correctPassword = false;}
	  		}

	  		//If log in successful, save session data and redirect
	  		if($this->login_usernameFound && $this->login_correctPassword)
	  		{
	  			$_SESSION['user']['id'] = $user_row_ID;
	  			$_SESSION['user']['username'] = $user_row_name;
	  			$_SESSION['user']['type'] = $user_row_type;
	  			$_SESSION['user']['money'] = $user_row_money;
	  			$_SESSION['user']['email'] = $user_row_email;
	  			$_SESSION['user']['credit_card_num'] = $user_row_credit_card_num;
	  			$_SESSION['user']['login_time'] = new DateTime("now");	//will use this later in is_loggedin() function
	  			
	  			//Cannot have any output before this like echo or print
	  			header("Location: http://" .$_SERVER['SERVER_NAME']);
	  			/* Make sure that code below does not get executed when we redirect. */
	  			exit;
	  		}

		}

		function is_loggedin()
		{
			$conn = $this->conn;

			if (isset($_SESSION['user']['id']))
			{
				$selectQuery = "SELECT username,money,type,email,credit_card_num FROM " .USERS_TABLE. " WHERE id = " . $_SESSION['user']['id'];
	  			$idMatch = $conn->query($selectQuery);

	  			if ($idMatch->num_rows == 1)
  				{
  					$user_row = mysqli_fetch_assoc($idMatch);

  					//update the values on client side
		  			$_SESSION['user']['username'] = $user_row['username'];
		  			$_SESSION['user']['type'] = $user_row['type'];
		  			$_SESSION['user']['money'] = $user_row['money'];
		  			$_SESSION['user']['email'] = $user_row['email'];
		  			$_SESSION['user']['credit_card_num'] = $user_row['credit_card_num'];
  					return true;
  				}
			}
			return false;
		}

		function logout()
		{
			//empty out session data for user
			$_SESSION['user'] = [];
		}

		function get_username($user_id)
		{
			$selectQ = "SELECT username FROM ".USERS_TABLE." WHERE id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);
			$info = mysqli_fetch_assoc($selectResult);

			return $info['username'];
		}

		function check_user_existance($username)
		{
			$selectQ = "SELECT username FROM ".USERS_TABLE." WHERE username = '$username'";
			$selectResult = $this->conn->query($selectQ);

			if($selectResult->num_rows == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		function update_email($user_id, $email)
		{
			//Check if email already exists
	  		$selectQ = "SELECT id FROM ".USERS_TABLE." WHERE email = '$email'";
	  		$emailMatch = $this->conn->query($selectQ);

	  		if($emailMatch->num_rows == 0)
	  		{
	  			$updateQ = "UPDATE ".USERS_TABLE." SET email = '$email' WHERE id = '$user_id'";
				$this->conn->query($updateQ);

				return true;
	  		}

	  		return false;
		}

		function update_password($user_id, $curr_password, $new_password)
		{
			$selectQ = "SELECT password FROM ".USERS_TABLE." WHERE id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);
			$user_info = mysqli_fetch_assoc($selectResult);

			//check if current password matches (TODO: i think need to use password_needs_rehash(); )
			if(password_verify($curr_password, $user_info['password']))
			{
				$hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);

				$updateQ = "UPDATE ".USERS_TABLE." SET password = '$hashed_pass' WHERE id = '$user_id'";

				if ($this->conn->query($updateQ))
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}

		function add_money($user_id, $money_amount)
		{

			$updateQ = "UPDATE ".USERS_TABLE." SET money = money + '$money_amount' WHERE id = '$user_id'";
			if ($this->conn->query($updateQ))
			{
				return true;
			}

			return false;
		}

		function insert_card_info($user_id, $card_num, $sec_code)
		{
			$updateQ = "UPDATE ".USERS_TABLE." SET credit_card_num = '$card_num', credit_card_security = '$sec_code' WHERE id = '$user_id'";

			if ($this->conn->query($updateQ))
			{
				return true;
			}

			return false;
		}

		function gift_money($amount, $sender_id, $receiver)
		{
			//add money to reciever
			$updateQ = "UPDATE ".USERS_TABLE." SET money = money - '$amount' WHERE id = '$sender_id'";
			$update1 = $this->conn->query($updateQ);

			//subtract money from sender
			$updateQ2 = "UPDATE ".USERS_TABLE." SET money = money + '$amount' WHERE username = '$receiver'";
			$update2 = $this->conn->query($updateQ2);

			if ($update1 && $update2)
			{
				return true;
			}
			return false;
		}

		function get_security_question($user_id)
		{
			$selectQ = "SELECT security_question FROM ".USERS_TABLE." WHERE id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$sec_question = mysqli_fetch_assoc($selectResult);
			}

			return $sec_question['security_question'];
		}

		function authenticate_security_question($user_id, $user_answer)
		{
			$selectQ = "SELECT security_answer FROM ".USERS_TABLE." WHERE id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$sec_answer = mysqli_fetch_assoc($selectResult);
				$sec_answer = $sec_answer['security_answer'];
			}
			else
			{
				return false;
			}

			if ($user_answer == $sec_answer)
			{
				return true;
			}

			return false;
		}
	}

	class tuffy_inventory
	{
		public $conn;	//necessary to connect to database

		//object constructor: 1st var: database link
		function __construct($DB_connection)
		{
			$this->conn = $DB_connection;
		}

		function inventory_add_item($name, $count, $price, $description)
		{
			$insertQuery = "INSERT INTO " .INVENTORY_TABLE. " (name, count, price, description) VALUES ('$name', '$count', '$price', '$description')";
			$insertResult = $this->conn->query($insertQuery);

			//if insert was successful
			if ($insertResult){return true;}
			return false;
		}

		function inventory_get_item($inventory_id)
		{
			$selectQ = "SELECT * FROM ".INVENTORY_TABLE." WHERE id = '$inventory_id'";
			$selectResult = $this->conn->query($selectQ);

			if (!$selectResult){return false;}

			$item = mysqli_fetch_assoc($selectResult);
			return $item;
		}

		function inventory_update_item($item_id, $name, $count, $price, $description)
		{
			$updateQ = "UPDATE ".INVENTORY_TABLE." SET name = '$name', count = '$count', price = '$price', description = '$description' WHERE id = '$item_id'";

			if ($this->conn->query($updateQ))
			{
				return true;
			}
			return false;
		}

		function inventory_display()
		{
			$selectQ = "SELECT * FROM ".INVENTORY_TABLE." LIMIT 10";
			$selectResult = $this->conn->query($selectQ);

			if (!$selectResult){return false;}

			$inventory_arr = array();
			while($table_row = $selectResult->fetch_assoc())
			{
				$row_data = array(
							'id' => $table_row['id'],
							'name' => $table_row['name'],
							'count' => $table_row['count'],
							'price' => $table_row['price'],
							'description' =>$table_row['description']
							);
				array_push($inventory_arr, $row_data);
			}

			return $inventory_arr;
		}

		function inventory_delete_item($inventory_id)
		{
			$deleteQ = "DELETE FROM ".INVENTORY_TABLE." WHERE id = '$inventory_id'";
			$deleteResult = $this->conn->query($deleteQ);

			if ($deleteResult){return true;}
			return false;
		}

		//TODO THIS
		function inventory_buy_item($inventory_id, $user_id, $buying_count)
		{
			$selectQ = "SELECT count FROM ".INVENTORY_TABLE." WHERE id = '$inventory_id'";
			$selectResult = $this->conn->query($selectQ);

			if (!$selectResult){return false;}

			//DECREASE COUNT FROM DATABASE
			$item = mysqli_fetch_assoc($selectResult);
			$item_count = $item['count'];

			$new_item_count = $item_count - $buying_count;

			$updateQ = "UPDATE ".INVENTORY_TABLE." SET count = '$new_item_count' WHERE id = '$inventory_id'";
			$this->conn->query($updateQ);

			//ADD TO SHOPPING CART

		}

		//TODO (#3): improve searching algorithm
		function search_item($item_name)
		{
			$selectQ = "SELECT * FROM ".INVENTORY_TABLE." WHERE name = '$item_name'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 0){return null;}

			$search_results = array();
			while($table_row = $selectResult->fetch_assoc())
			{
				array_push($search_results, $table_row);
			}
			return $search_results;
		}

		function add_to_cart($inventory_id, $user_id, $count)
		{
			//check if its already in cart
			$selectQ = "SELECT id FROM ".SHOPCART_TABLE." WHERE user_id = '$user_id' AND item_id = '$inventory_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$shopcart_info = mysqli_fetch_assoc($selectResult);
				$shopcart_id = $shopcart_info['id'];

				$updateQ = "UPDATE ".SHOPCART_TABLE." SET amount = amount + '$count' WHERE id = '$shopcart_id'";
				$updateResult = $this->conn->query($updateQ);

				if ($updateResult){return true;}
				return false;
			}
			else if ($selectResult->num_rows == 0)
			{
				$insertQ = "INSERT INTO ".SHOPCART_TABLE." (item_id, user_id, amount) VALUES ('$inventory_id', '$user_id', '$count')";
				$insertResult = $this->conn->query($insertQ);

				if ($insertResult){return true;}
				return false;
			}
		}

		function display_cart($user_id)
		{
			$selectQ = "SELECT * FROM ".SHOPCART_TABLE." WHERE user_id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);

			$shopcart_arr = array();
			while($table_row = $selectResult->fetch_assoc())
			{
				$item_data = $this->inventory_get_item($table_row['item_id']);

				$row_data = array(
							'id' => $item_data['id'],
							'name' => $item_data['name'],
							'stock_count' => $item_data['count'],
							'price' => $item_data['price'],
							'description' =>$item_data['description'],
							'in_cart_count' => $table_row['amount']
							);
				array_push($shopcart_arr, $row_data);
			}

			return $shopcart_arr;
		}

		function get_cart_count($user_id)
		{
			$selectQ = "SELECT COUNT(*) FROM ".SHOPCART_TABLE." WHERE user_id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);
			
			$cart_count = mysqli_fetch_assoc($selectResult);
			return $cart_count;
		}

		public $not_enough_money = false;

		//$items is an array of items with their info, return false if not enough stock
		function purchase_cart($user_id, $items, $total, $payment_method, $reward_money)
		{
			//get user money
			$selectQ = "SELECT money FROM ".USERS_TABLE." WHERE id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);
			$user_info = mysqli_fetch_assoc($selectResult);		

			//if not enough money using tuffy money
			if ($total > $user_info['money'] && $payment_method == "tuffy money")
			{
				$this->not_enough_money = true;
				return false;
			}

			//update items in inventory
			foreach ($items as $item)
			{
				//if not enough stock
				$item_db = $this->inventory_get_item($item['id']);
				if ($item['in_cart_count'] > $item_db['count'])
				{
					return false;
				}

				//update database count value
				$new_item_count = $item_db['count'] - $item['in_cart_count'];
				$updateQ = "UPDATE ".INVENTORY_TABLE." SET count = '$new_item_count' WHERE id = ".$item['id'];
				$this->conn->query($updateQ);

				//remove from cart
				$deleteQ = "DELETE FROM ".SHOPCART_TABLE." WHERE item_id = ".$item['id'];
				$this->conn->query($deleteQ);

				//add to orders table
				$this->insert_order($user_id, $item, $payment_method);

				//decrease user money
				if($payment_method == "tuffy money")
				{
					$new_user_money = $user_info['money'] - $total;
					$updateMoney = "UPDATE ".USERS_TABLE." SET money = '$new_user_money' WHERE id = ".$user_id;
					$this->conn->query($updateMoney);
				}

				//give user rewards money
				$updateQ2 = "UPDATE ".USERS_TABLE." SET money = money + '$reward_money' WHERE id = '$user_id'";
				$updateResult2 = $this->conn->query($updateQ2);
			}

		}


		//ORDERS
		function insert_order($user_id, $item, $payment_method)
		{
			$item_id = $item['id'];
			$item_name = $item['name'];
			$item_amt = $item['in_cart_count'];
			$item_price = $item['price'];
			$item_desc = $item['description'];
			$curr_time = new DateTime("now");
			$curr_time = $curr_time->format("Y-m-d H:i:s"); //this is how datetime is stored in sql


			$insertQ = "INSERT INTO ".ORDERS_TABLE." 
						(user_id, inventory_id, name, amount, price, description, date_ordered, payment_used) 
						VALUES ('$user_id', '$item_id', '$item_name', '$item_amt', '$item_price', '$item_desc', '$curr_time', '$payment_method')";

			$insertResult = $this->conn->query($insertQ);
		}

		function display_orders($user_id)
		{
			if ($user_id != "all")
			{
				$selectQ = "SELECT * FROM ".ORDERS_TABLE." WHERE user_id = '$user_id'";
			}
			else if ($user_id == "all")
			{
				$selectQ = "SELECT * FROM ".ORDERS_TABLE;
			}

			$selectResult = $this->conn->query($selectQ);

			$order_arr = array();
			while($table_row = $selectResult->fetch_assoc())
			{
				array_push($order_arr, $table_row);
			}

			return $order_arr;
		}

		function get_order($order_id)
		{
			$selectQ = "SELECT * FROM ".ORDERS_TABLE." WHERE id = '$order_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$order_info = mysqli_fetch_assoc($selectResult);
			}

			return $order_info;
		}

		function cancel_order($order)
		{
			//putting array values in strings is weird
			$order_id = $order['id'];
			$item_name = $order['name'];
			$item_amount = $order['amount'];
			$item_price = $order['price'];
			$payment_method = $order['payment_used'];
			$user_id = $order['user_id'];
			
			$selectQ = "SELECT id FROM ".INVENTORY_TABLE." WHERE name = '$item_name'";
			$selectResult = $this->conn->query($selectQ);

			//update inventory
			if ($selectResult->num_rows != 0)
			{	
				//move it back to inventory
				$updateQ = "UPDATE ".INVENTORY_TABLE." SET count = count + '$item_amount' WHERE name = '$item_name'";
				$this->conn->query($updateQ);
			}
			else
			{
				//item doesn't exist anymore, create a new one
				$this->inventory_add_item($item_name, $item_amount, $item_price, $order['description']);
			}

			//give user money back if used tuffy money (if credit card do nothing)
			if ($payment_method == "tuffy money") 
			{
				$updateQ2 = "UPDATE ".USERS_TABLE." SET money = money + ('$item_amount' * '$item_price') WHERE id = '$user_id'";
				$this->conn->query($updateQ2);
			}

			//delete from orders table
			$deleteQ = "DELETE FROM ".ORDERS_TABLE." WHERE id = '$order_id'";
			$deleteResult = $this->conn->query($deleteQ);

			if ($deleteResult){return true;}
			return false;
		}

		function return_request($order_id, $return_reason)
		{
			$updateQ = "UPDATE ".ORDERS_TABLE." SET return_request = 1, return_reason = '$return_reason' WHERE id = '$order_id'";
			$updateResult = $this->conn->query($updateQ);

			if ($updateResult){return true;}
			return false;
		}

		//admin only
		function display_return_requests()
		{
			$selectQ = "SELECT * FROM ".ORDERS_TABLE." WHERE return_request = 1";
			$selectResult = $this->conn->query($selectQ);

			$return_req_arr = array();
			while($table_row = $selectResult->fetch_assoc())
			{
				array_push($return_req_arr, $table_row);
			}

			return $return_req_arr;
		}

		//admin only
		//TODO: instead of deleting it from orders table, set it as complete instead
		function approve_return_request($order)
		{
			//putting array values in strings is weird
			$order_id = $order['id'];
			$item_name = $order['name'];
			$item_amount = $order['amount'];
			$item_price = $order['price'];
			$payment_method = $order['payment_used'];
			$user_id = $order['user_id'];
			
			$selectQ = "SELECT id FROM ".INVENTORY_TABLE." WHERE name = '$item_name'";
			$selectResult = $this->conn->query($selectQ);

			//update inventory
			if ($selectResult->num_rows != 0)
			{	
				//move it back to inventory
				$updateQ = "UPDATE ".INVENTORY_TABLE." SET count = count + '$item_amount' WHERE name = '$item_name'";
				$this->conn->query($updateQ);
			}
			else
			{
				//item doesn't exist anymore, create a new one
				$this->inventory_add_item($item_name, $item_amount, $item_price, $order['description']);
			}

			//give user money back if used tuffy money (if credit card do nothing)
			if ($payment_method == "tuffy money") 
			{
				$updateQ2 = "UPDATE ".USERS_TABLE." SET money = money + ('$item_amount' * '$item_price') WHERE id = '$user_id'";
				$this->conn->query($updateQ2);
			}
			
			//consider it approved in database
			$updateQ3 = "UPDATE ".ORDERS_TABLE." SET return_approved = 1, return_request = 0, has_arrived = 0 WHERE id = '$order_id'";
			$this->conn->query($updateQ3);



			//we made it to the end!
			return true;
		}

		//For delivery man to use (after they have delivered the order to the house), gonna use this for testing
		function complete_delivery($order_id)
		{
			$curr_time = new DateTime("now");
			$curr_time = $curr_time->format("Y-m-d H:i:s"); //this is how datetime is stored in sql

			$updateQ = "UPDATE ".ORDERS_TABLE." SET has_arrived = 1, date_arrived = '$curr_time' WHERE id = '$order_id'";
			$updateResult = $this->conn->query($updateQ);

			if ($updateResult) {return true;}
			return false;
		}

		function update_cart_count($user_id, $item_id, $new_amount)
		{
			$updateQ = "UPDATE ".SHOPCART_TABLE." SET amount = '$new_amount' WHERE user_id = '$user_id' AND item_id = '$item_id'";
			$updateResult = $this->conn->query($updateQ);

			if ($updateResult){return true;}
			return false;
		}

		function delete_cart_count($user_id, $item_id)
		{

			$deleteQ = "DELETE FROM ".SHOPCART_TABLE." WHERE user_id = '$user_id' AND item_id = '$item_id'";
			$deleteResult = $this->conn->query($deleteQ);

			if ($deleteResult){return true;}
			return false;
		}

		//WISHLIST
		function insert_wishlist($user_id, $item_id)
		{
			//check if it already exists
			$already_wished = $this->already_wishlisted($user_id, $item_id);

			if (!$already_wished)
			{
				$insertQ = "INSERT INTO ".WISHLIST_TABLE." (user_id, item_id) VALUES ('$user_id', '$item_id')";
				$this->conn->query($insertQ);
				return true;
			}

			//return false if its already in wishlist
			return false;
		}

		function already_wishlisted($user_id, $item_id)
		{
			$selectQ = "SELECT id FROM ".WISHLIST_TABLE." WHERE user_id = '$user_id' AND item_id = '$item_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 0)
			{
				return false;
			}

			return true;
		}

		//return list of item_id's for a user
		function display_wishlist($user_id)
		{
			$selectQ = "SELECT item_id FROM ".WISHLIST_TABLE." WHERE user_id = '$user_id'";
			$selectResult = $this->conn->query($selectQ);

			$wishlist_arr = array();
			while($table_row = $selectResult->fetch_assoc())
			{
				array_push($wishlist_arr, $table_row);
			}

			return $wishlist_arr;
		}


		//rate item
		function rate_item($order_id, $item_id, $rating)
		{
			//consider the order rated, so that user can only rate once per purchase.
			$updateQ = "UPDATE ".ORDERS_TABLE." SET rated = 1, personal_rating = '$rating' WHERE id = '$order_id'";
			$selectResult = $this->conn->query($updateQ);

			//calculate new rating of item (based on average), also add rate count by 1
			$updateQ2 = "UPDATE ".INVENTORY_TABLE." SET rating = (rating * rate_count + '$rating')/(rate_count + 1), rate_count = rate_count + 1 WHERE id = '$item_id'";
			$selectResult2 = $this->conn->query($updateQ2);

			if ($updateQ2 && $updateQ)
			{
				return true;
			}
			return false;
		}

		function order_rated($order_id)
		{
			$selectQ = "SELECT rated FROM ".ORDERS_TABLE." WHERE id = '$order_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$order_info = mysqli_fetch_assoc($selectResult);
				$is_rated = $order_info['rated'];
			}
			else
			{
				return false;
			}

			if ($is_rated == 1)
				{return true;}
			return false;
		}

		function get_item_rating($item_id)
		{
			$selectQ = "SELECT rating FROM ".INVENTORY_TABLE." WHERE id = '$item_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$item_info = mysqli_fetch_assoc($selectResult);
			}
			else
			{
				//if error just return 0
				return 0;
			}

			return $item_info['rating'];
		}

		function get_personal_rating($order_id)
		{
			$selectQ = "SELECT personal_rating FROM ".ORDERS_TABLE." WHERE id = '$order_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$item_info = mysqli_fetch_assoc($selectResult);
			}
			else
			{
				//if error just return 0
				return 0;
			}

			return $item_info['personal_rating'];
		}

		function get_num_of_rates($item_id)
		{
			$selectQ = "SELECT rate_count FROM ".INVENTORY_TABLE." WHERE id = '$item_id'";
			$selectResult = $this->conn->query($selectQ);

			if ($selectResult->num_rows == 1)
			{
				$item_info = mysqli_fetch_assoc($selectResult);
			}
			else
			{
				//if error just return 0
				return 0;
			}

			return $item_info['rate_count'];
		}
	}



	$tuffy_user = new tuffy_user($DB_connection);
	$tuffy_inventory = new tuffy_inventory($DB_connection);
?>