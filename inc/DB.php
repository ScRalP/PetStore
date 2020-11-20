<?php

require 'product.php';
require 'user.php';
require 'cart.php';
require 'product_cart.php';
require 'orders.php';

class DB
{
	private static $instance = null;
	private $connect = null;

	private function __construct()
	{
		// Connexion à la base de données
		$connStr = 'mysql:host=localhost;dbname=petstore';
		try {
			// Connexion à la base
			$this->connect = new PDO($connStr, 'root', 'toor');
		} catch (PDOException $e) {
			echo "probleme de connexion :" . $e->getMessage();
			return null;
		}
	}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			try {
				self::$instance = new DB();
			} catch (PDOException $e) {
				echo $e;
			}
		}

		$obj = self::$instance;

		if (($obj->connect) == null) {
			self::$instance = null;
		}
		return self::$instance;
	}

	public function close()
	{
		$this->connect = null;
	}

	/************************************************************************/
	//	Methode uniquement utilisable dans les méthodes de la class DB 
	//	permettant d'exécuter n'importe quelle requête SQL
	//	et renvoyant en résultat les tuples renvoyés par la requête
	//	sous forme d'un tableau d'objets
	//	param1 : texte de la requête à exécuter (éventuellement paramétrée)
	//	param2 : tableau des valeurs permettant d'instancier les paramètres de la requête
	//	NB : si la requête n'est pas paramétrée alors ce paramètre doit valoir null.
	//	param3 : nom de la classe devant être utilisée pour créer les objets qui vont
	//	représenter les différents tuples.
	//	NB : cette classe doit avoir des attributs qui portent le même que les attributs
	//	de la requête exécutée.
	//	ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	//	que d'éléments dans le tableau passé en second paramètre.
	//	NB : si la requête ne renvoie aucun tuple alors la fonction renvoie un tableau vide
	/************************************************************************/
	private function execQuery($request, $tparam, $nomClasse)
	{
		//on prépare la requête
		$stmt = $this->connect->prepare($request);
		//on indique que l'on va récupére les tuples sous forme d'objets instance de Client
		$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $nomClasse);
		//on exécute la requête
		if ($tparam != null) {
			$stmt->execute($tparam);
		} else {
			$stmt->execute();
		}
		//récupération du résultat de la requête sous forme d'un tableau d'objets
		$tab = array();
		$tuple = $stmt->fetch(); //on récupère le premier tuple sous forme d'objet
		if ($tuple) {
			//au moins un tuple a été renvoyé
			while ($tuple != false) {
				$tab[] = $tuple; //on ajoute l'objet en fin de tableau
				$tuple = $stmt->fetch(); //on récupère un tuple sous la forme
				//d'un objet instance de la classe $nomClasse	       
			} //fin du while	           	     
		}
		return $tab;
	}

	/************************************************************************/
	//	Methode utilisable uniquement dans les méthodes de la classe DB
	//	permettant d'exécuter n'importe quel ordre SQL (update, delete ou insert)
	//	autre qu'une requête.
	//	Résultat : nombre de tuples affectés par l'exécution de l'ordre SQL
	//	param1 : texte de l'ordre SQL à exécuter (éventuellement paramétré)
	//	param2 : tableau des valeurs permettant d'instancier les paramètres de l'ordre SQL
	//	ATTENTION : il doit y avoir autant de ? dans le texte de la requête
	//	que d'éléments dans le tableau passé en second paramètre.
	/************************************************************************/
	private function execUpdate($request, $tparam)
	{
		$stmt = $this->connect->prepare($request);
		$res = $stmt->execute($tparam); //execution de l'ordre SQL
		return $stmt->rowCount();
	}

	/*************************************************************************
	 * Fonctions qui peuvent être utilisées dans les scripts PHP
	 *************************************************************************/

		/* PRODUCT */
	public function getProducts()
	{
		$request = 'SELECT * FROM product';
		return $this->execQuery($request, null, 'product');
	}

	public function getProductById($id)
	{
		$products = $this->getProducts();

		foreach( $products as $product ){
			if( $product->getId() == $id ){
				return $product;
			}
		}

		return null;
	}

	public function getProductsByCartId($cart_id){
		$productsCarts = $this->getProductsCarts();
		$products = []; //Le tableau de produit qu'on va renvoyer

		foreach($productsCarts as $productCart){
			if($productCart->getCartId() == $cart_id){
				array_push( $products , $this->getProductById( $productCart->getProductId() ) );
			}
		}

		return $products;
	}

	public function insertProduct($id, $name, $price, $description)
	{
		$request = 'INSERT INTO product VALUES(?,?,?,?)';
		$tparam = array($id, $name, $price, $description);
		return $this->execUpdate($request, $tparam);
	}

	public function updateProduct($id, $name, $price, $description)
	{
		$request = 'UPDATE product SET name = ?, price = ?, description = ? WHERE id = ?';
		$tparam = array($name, $price, $description, $id);
		return $this->execUpdate($request, $tparam);
	}

	public function deleteProduct($id)
	{
		$request = 'DELETE FROM product WHERE id = ?';
		$tparam = array($id);
		return $this->execUpdate($request, $tparam);
	}

		/* USER */
	public function getUsers(){
		$request = 'SELECT * FROM user';
		return $this->execQuery($request, null, 'user');
	}
	
	public function getUserByEmail($email){
		$users = $this->getUsers();

		foreach($users as $user){
			if( $user->getEmail() == $email ){
				return $user;
			}
		}

		return null;
	}
	
	public function getUserById($id){
		$users = $this->getUsers();

		foreach($users as $user){
			if( $user->getId() == $id ){
				return $user;
			}
		}

		return null;
	}

	public function insertUser($id, $username, $email, $password, $roles)
	{
		$request = 'INSERT INTO user VALUES(?,?,?,?,?)';
		$tparam = array($id, $username, $email, $password, $roles);
		return $this->execUpdate($request, $tparam);
	}

	public function updateUser($id, $username, $email, $password, $roles)
	{
		$request = 'UPDATE user SET username = ?, email = ?, password = ?, roles = ? WHERE id = ?';
		$tparam = array($username, $email, $password, $roles, $id);
		return $this->execUpdate($request, $tparam);
	}

	public function deleteUser($id)
	{
		$request = 'DELETE FROM user WHERE id = ?';
		$tparam = array($id);
		return $this->execUpdate($request, $tparam);
	}

		/* CART */
	public function getCarts(){
		$request = 'SELECT * FROM cart';
		return $this->execQuery($request, null, 'cart');
	}

	public function getCartByUserId($user_id){
		$carts = $this->getCarts();

		foreach($carts as $cart){
			if($cart->getUserId() == $user_id){
				return $cart;
			}
		}

		return null;
	}

	public function insertCart($id, $user_id)
	{
		$request = 'INSERT INTO cart VALUES(?,?)';
		$tparam = array($id, $user_id);
		return $this->execUpdate($request, $tparam);
	}

	public function updateCart($id, $user_id)
	{
		$request = 'UPDATE cart SET user_id = ? WHERE id = ?';
		$tparam = array($user_id, $id);
		return $this->execUpdate($request, $tparam);
	}

	public function deleteCart($id)
	{
		$request = 'DELETE FROM cart WHERE id = ?';
		$tparam = array($id);
		return $this->execUpdate($request, $tparam);
	}
	
		/* PRODUCT CART */
	public function getProductsCarts(){
		$request = 'SELECT * FROM product_cart';
		return $this->execQuery($request, null, 'product_cart');
	}

	public function getProductsCart($product_id, $cart_id){
		$productsCarts = $this->getProductsCarts();
		$productsCart = [];

		foreach($productsCarts as $productCart){
			if( $productCart->getProductId() == $product_id && $productCart->getCartId() == $cart_id ){
				array_push( $productsCart , $productCart );
			}
		}

		return $productsCart;
	}
	
	public function insertProductCart($id, $product_id, $cart_id)
	{
		$request = 'INSERT INTO product_cart VALUES(?,?,?)';
		$tparam = array($id, $product_id, $cart_id);
		return $this->execUpdate($request, $tparam);
	}

	public function updateProductCart($id, $product_id, $cart_id)
	{
		$request = 'UPDATE product_cart SET product_id = ?, cart_id = ? WHERE id = ?';
		$tparam = array($product_id, $cart_id, $id);
		return $this->execUpdate($request, $tparam);
	}

	public function deleteProductCart($id)
	{
		$request = 'DELETE FROM product_cart WHERE id = ?';
		$tparam = array($id);
		return $this->execUpdate($request, $tparam);
	}

		/* ORDERS */
	public function getOrders(){
		$request = 'SELECT * FROM orders';
		return $this->execQuery($request, null, 'orders');
	}
	
	public function getOrdersByUserId($user_id){
		$orders = $this->getOrders();
		$ordersRet = [];

		foreach($orders as $order){
			if( $order->getUserId() == $user_id ){
				array_push( $ordersRet, $order );
			}
		}

		return $ordersRet;
	}

	public function insertOrder($id, $date, $cart_id, $user_id)
	{
		$request = 'INSERT INTO orders VALUES(?,?,?,?)';
		$tparam = array($id, $date, $cart_id, $user_id);
		return $this->execUpdate($request, $tparam);
	}

	public function updateOrder($id, $date, $cart_id, $user_id)
	{
		$request = 'UPDATE orders SET date = ?, cart_id = ?, user_id = ? WHERE id = ?';
		$tparam = array($date, $cart_id, $user_id, $id);
		return $this->execUpdate($request, $tparam);
	}

	public function deleteOrder($id)
	{
		$request = 'DELETE FROM orders WHERE id = ?';
		$tparam = array($id);
		return $this->execUpdate($request, $tparam);
	}

} //fin classe DB
