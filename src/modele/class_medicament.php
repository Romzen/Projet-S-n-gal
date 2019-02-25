<?php
	
    class Medicament {

        private $db;
        private $selectAll;
        private $insertAll;
        private $deleteOne;
        private $updateAll;
        private $selectOne;

        public function __CONSTRUCT($db) {
            
            $this->db = $db;

            $this->selectAll = $db->prepare("SELECT * FROM MEDICAMENT");

            $this->insertAll = $db->prepare("INSERT INTO MEDICAMENT(acronyme_medicament, nom_medicament) values(:acronyme_medicament, :nom_medicament)");

            $this->deleteOne=$db->prepare("delete from MEDICAMENT where id_medicament=:id_medicament");

            $this->updateAll=$db->prepare("update MEDICAMENT SET acronyme_medicament=:acronyme_medicament, nom_medicament=:nom_medicament WHERE id_medicament=:id_medicament");

            $this->selectOne=$db->prepare("select * from MEDICAMENT where id_medicament=:id_medicament");
        }

        public function selectAll() {
            $liste = $this->selectAll->execute();
        if ($this->selectAll->errorCode()!=0){
             print_r($this->selectAll->errorInfo());  
        }
        return $this->selectAll->fetchAll();
    
        }

        public function insertAll($acronyme_medicament, $nom_medicament) {
            $this->insertAll->execute(array(':acronyme_medicament' => $acronyme_medicament,':nom_medicament' => $nom_medicament));
            if ($this->insertAll->errorCode()!=0){
             print_r($this->insertAll->errorInfo());  
        }
            return $this->insertAll->rowCount();
        }

        public function deleteOne($id_medicament){
            $this->deleteOne->execute(array(':id_medicament' => $id_medicament));
            if ($this->deleteOne->errorCode()!=0){
             print_r($this->deleteOne->errorInfo());  
        }
            return $this->deleteOne->rowCount();
        }

        public function updateAll($id_medicament, $acronyme_medicament, $nom_medicament){
                $r = true;
                $this->updateAll->execute(array(':id_medicament'=>$id_medicament,':acronyme_medicament'=>$acronyme_medicament, ':nom_medicament'=>$nom_medicament));
            if ($this->updateAll->errorCode()!=0){
             print_r($this->updateAll->errorInfo());  
             $r=false;
            }
            return $r;
        }

        public function selectOne($id_medicament) {
            $this->selectOne->execute(array(':id_medicament' => $id_medicament));
            if ($this->selectOne->errorCode()!=0){
             print_r($this->selectOne->errorInfo());  
        }
            return $this->selectOne->fetch();
        }
    }
	
?>