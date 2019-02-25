<?php

 class Grade {

        private $insertAll;
        private $selectAll;
        private $selectName;
        private $selectOne;
        private $updateAll;


        public function __construct($db) {

            $this->insertAll = $db->prepare("INSERT INTO GRADE( nom_grade, id_role) values( :nom_grade, :id_role)");

            $this->selectAll=$db->prepare("select * from GRADE ");
            
            $this->selectName=$db->prepare("select nom_grade from GRADE ");

            $this->selectOne=$db->prepare("select * from GRADE where id_grade=:id_grade");

            $this->updateAll=$db->prepare("update GRADE SET nom_grade=:nom_grade,id_role=:id_role WHERE id_grade=:id_grade");

            $this->deleteOne=$db->prepare("delete from GRADE where id_grade=:id_grade");


        }

        public function insertAll($nom_grade, $id_role){
            $this->insertAll->execute(array(':nom_grade' => $nom_grade,':id_role' => $id_role));
            if ($this->insertAlls->errorCode()!=0){
                print_r($this->insertAll->errorInfo());  
            }
            return $this->insertAll->rowCount();
        }

        public function selectAll() {
            $this->selectAll->execute();
            if ($this->selectAll->errorCode()!=0){
                print_r($this->selectAll->errorInfo());  
            }
            return $this->selectAll->fetchAll();
        }
        
        public function selectName() {
            $this->selectName->execute();
            if ($this->selectName->errorCode()!=0){
                print_r($this->selectName->errorInfo());  
            }
            return $this->selectName->fetchAll();
        }

        public function selectOne($id_grade) {
            $this->selectOne->execute(array(':id_grade' => $id_grade));
            if ($this->selectOne->errorCode()!=0){
                print_r($this->selectOne->errorInfo());  
            }
            return $this->selectOne->fetch();
        }


        public function updateAll($nom_grade, $id_role){
            $this->updateAll->execute(array(':nom_grade' => $nom_grade,':id_role' => $id_role));
            if ($this->updateAll->errorCode()!=0){
                print_r($this->updateAll->errorInfo());  
            }
            return $this->updateAll->rowCount();
        }

        public function deleteOne($id_grade){
            $this->deleteOne->execute(array(':id_grade' => $id_grade));
            if ($this->deleteOne->errorCode()!=0){
                print_r($this->deleteOne->errorInfo());  
            }
            return $this->deleteOne->rowCount();
        }
    }