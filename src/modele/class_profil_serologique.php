<?php

    class Profil_serologique {

        private $selectAll;
        private $selectOne;

        public function __CONSTRUCT($db) {
            $this->selectAll = $db->prepare("SELECT id_profil,nom_profil FROM PROFIL_SEROLOGIQUE");
            $this->selectOne = $db->prepare("SELECT * FROM PROFIL_SEROLOGIQUE WHERE id_profil=:id_profil");
        }

        public function selectAll() {
            $this->selectAll->execute();
            return $this->selectAll->fetchAll();
        }
        
        public function selectOne($id_profil) {
        $this->selectOne->execute(array(':id_profil' => $id_profil));
        return $this->selectOne->fetch();
    }
    }

?>