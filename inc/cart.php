<?php

/*classe representant les tuples de la table cart */
class Cart {
    
	private $id;
    private $user_id;

	public function __construct($id = -1, $user_id = -1) {
		$this->id      = $id;
		$this->user_id = $user_id;
	}

        /* GETTER */
	public function getId    () { return $this->id;      }
	public function getUserId() { return $this->user_id; }

	public function __toString() {
		$res  = "";
        
        $res .= "id cart: " .$this->id      ."\n";
		$res .= "id user: " .$this->user_id ."\n";
        $res .= "<br/>";
        
		return $res;
	}
}
