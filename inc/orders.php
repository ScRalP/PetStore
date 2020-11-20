<?php

/*classe representant les tuples de la table orders */
class Orders {
    
	private $id;
    private $date;
    private $cart_id;
    private $user_id;

	public function __construct($id = -1, $date = null, $cart_id = -1, $user_id = -1) {
        $this->id      = $id;
        $this->date    = $date;
		$this->cart_id = $cart_id;
		$this->user_id = $user_id;
	}

        /* GETTER */
	public function getId    () { return $this->id;      }
	public function getDate  () { return $this->date;    }
    public function getCartId() { return $this->cart_id; }
    public function getUserId() { return $this->user_id; }

	public function __toString() {
		$res  = "";
        
        $res .= "id order: " .$this->id      ."\n";
		$res .= "date: "     .$this->date    ."\n";
		$res .= "id cart: "  .$this->cart_id ."\n";
		$res .= "id user: "  .$this->user_id ."\n";
        $res .= "<br/>";
        
		return $res;
	}
}
