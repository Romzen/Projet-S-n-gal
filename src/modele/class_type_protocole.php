<?php
	
    class Type_protocole {

        private $selectAll;
        private $updateAll;

        public function __CONSTRUCT($db) {

            $this->selectAll = $db->prepare("SELECT * FROM TYPE_PROTOCOLE");
            $this->updateAll = $db->prepare("update type_protocole set type_protocole=:type_protocole where id_type_protocole=:id_type_protocole");

        }

        public function selectAll() {
            $this->selectAll->execute();
            if ($this->selectAll->errorCode()!=0){
                 print_r($this->selectAll->errorInfo());  
            }
            return $this->selectAll->fetchAll();
        }
        
        public function updateAll($id_type_protocole, $type_protocole){
        $r = true;
        $this->updateAll->execute(array(':id_type_protocole'=>$id_type_protocole, ':type_protocole'=>$type_protocole,));
        if ($this->updateAll->errorCode()!=0){
             print_r($this->updateAll->errorInfo());  
             $r=false;
        }
        return $r;
    }
    }
	
?>
