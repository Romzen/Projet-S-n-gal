<?php

    class Etat_dispensation {

        private $selectOne;
        private $selectAll;

        public function __CONSTRUCT($db) {

            $this->selectOne = $db->prepare("SELECT * FROM ETAT_DISPENSATION where id_etat_dispen =:etat");

            $this->selectAll = $db->prepare("SELECT * FROM ETAT_DISPENSATION ");

        }

        public function selectOne($etat) {
            $this->selectOne->execute(array(':etat' => $etat));
            return $this->selectOne->fetch();
        }

        public function selectAll() {
            $this->selectAll->execute();
            return $this->selectAll->fetchAll();
        }
    }

?>
