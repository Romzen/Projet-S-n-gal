<?php
	
    class Droit {

        private $selectAll;
        private $listeDroit;

        public function __CONSTRUCT($db) {
            
            $this->selectAll   = $db->prepare("SELECT * FROM DROIT");
            
            $this->listeDroit  = $db->prepare("SELECT nom_grade,remarque FROM GRADE G inner join ASS_GRADE_DROIT A ON G.id_grade = A.id_grade inner join DROIT D ON A.id_droit = D.id_droit");
        }

        public function selectAll() {
            $this->selectAll->execute();
            return $this->selectAll->fetchAll();
        }
        
        public function listeDroit(){
            $this->listeDroit->execute();
            if ($this->listeDroit->errorCode()!=0){
                print_r($this->listeDroit->errorInfo());  
            }
            return $this->listeDroit->fetchAll();
        }
    }
	
?>