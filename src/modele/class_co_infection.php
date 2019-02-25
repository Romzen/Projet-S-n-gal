<?php

    class Co_infection
    {
        private $selectAll;
        private $selectId;
        private $insert;
        private $delete; 
        
        public function __construct($db) 
        {
            $this -> selectAll = $db->prepare("select * From TYPE_CO_INFECTION");
            $this->insert = $db->prepare("insert into TYPE_CO_INFECTION (nom_co_infection, description) values (:nom_co_infection, :desc)");
            $this-> delete = $db->prepare('DELETE FROM TYPE_CO_INFECTION where id_co_infection=:id_co_infection ');
        }
        
        public function selectAll()
        {
            $this->selectAll->execute();
            return $this->selectAll->fetchAll();
        }
        
         public function insert($nom_co_infection, $description)
        {
            $this->insert->execute(array(':nom_co_infection'=>$nom_co_infection, ':desc'=>$description));
            if ($this->insert->errorCode()!=0){
                print_r($this->insert->errorInfo()); 
               
            }return $this->insert->rowCount();
}
        
         public function selectId($id_co_infection){
            $this->selectId->execute(array(':id_co_infection' => $id_co_infection));
            if ($this->selectId->errorCode()!=0){
                print_r($this->selectId->errorInfo());  
            }
            return $this->selectId->fetch();
        }
        
        public function delete($id_co_infection){
            $this->delete->execute(array(':id_co_infection' => $id_co_infection));
            if ($this->delete->errorCode()!=0){
                print_r($this->delete->errorInfo());  
            }
            return $this->delete->rowCount();
        }
    }