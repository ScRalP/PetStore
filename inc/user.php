<?php

/* classe representant les tuples de la table produit */
class User {
    
	private $id;
	private $username;
	private $email;
    private $password;
    private $role;

	public function __construct($id = -1, $username = "", $email = "", $password = "", $role = "") {
		$this->id       = $id;
		$this->username = $username;
		$this->email    = $email;
        $this->password = $password;
        $this->role    = $role;
	}

        /* GETTER */
	public function getId      () { return $this->id;       }
	public function getUsername() { return $this->username; }
	public function getEmail   () { return $this->email;    }
	public function getPassword() { return $this->password; }
    public function getRole    () { return $this->role;     }

	public function __toString() {
		$res  = "";
        
        $res .= "id user: "  .$this->id       ."\n";
		$res .= "username: " .$this->username ."\n";
		$res .= "email: "    .$this->email    ."\n";
        $res .= "password: " .$this->password ."\n";
        $res .= "role: "     .$this->role     ."\n";

        $res .= "<br/>";
        
		return $res;
	}
}
