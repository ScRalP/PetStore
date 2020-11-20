<?php

/*classe representant les tuples de la table produit */
class Product {
    
	private $id;
	private $name;
	private $price;
	private $description;

	public function __construct($id = -1, $name = "", $price = -1, $description = null) {
		$this->id            = $id;
		$this->name          = $name;
		$this->price         = $price;
		$this->description   = $description;
	}

        /* GETTER */
	public function getId         () { return $this->id;          }
	public function getName       () { return $this->name;        }
	public function getPrice      () { return $this->price;       }
	public function getDescription() { return $this->description; }

	public function __toString() {
		$res  = "";
        
        $res .= "id product: "  .$this->id          ."\n";
		$res .= "name: "        .$this->name        ."\n";
		$res .= "price: "       .$this->price       ."\n";
		$res .= "description: " .$this->description ."\n";
        $res .= "<br/>";
        
		return $res;
	}
}
