<?php

/* classe representant les tuples de la table cart */
class Product_Cart {
    
	private $id;
    private $product_id;
    private $cart_id;

	public function __construct($id = -1, $product_id = -1, $cart_id = -1) {
		$this->id         = $id;
		$this->product_id = $product_id;
		$this->cart_id    = $cart_id;
	}

        /* GETTER */
	public function getId       () { return $this->id;         }
	public function getProductId() { return $this->product_id; }
	public function getCartId   () { return $this->cart_id;    }

	public function __toString() {
		$res  = "";
        
        $res .= "id cart: "    .$this->id         ."\n";
		$res .= "id product: " .$this->product_id ."\n";
		$res .= "id cart: "    .$this->cart_id    ."\n";
        $res .= "<br/>";
        
		return $res;
	}
}
