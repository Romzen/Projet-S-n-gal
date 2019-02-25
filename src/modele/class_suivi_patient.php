<?php

    class Suivi
    {

        private $insertAll;
        private $selectAll;
        private $selectSuiviModifie;


        public function __construct($db){
            $this->insertAll = $db->prepare("INSERT INTO SUIVI_PRESENCE(id_patient, date, idProto) values( :id_patient, :today,:protocole)");
            $this->selectAll = $db->prepare("select * from SUIVI_PRESENCE ");   
            $this->selectSuiviModifie=$db->prepare("SELECT * FROM `SUIVI_PRESENCE` WHERE id_patient=:id_patient GROUP BY `annee`,`mois`");
        }

        public function insertAll($id_patient,$today,$protocole)
        {
            $this->insertAll->execute(array(':id_patient' => $id_patient,':today'=>$today,':protocole'=>$protocole));
            return $this->insertAll->rowCount();
        }

        public function selectAll() {
            $this->selectAll->execute();
            return $this->selectAll->fetchAll();
        }

       
        public function getSuiviModifie($id_patient) {
            $this->selectSuiviModifie->execute(array(':id_patient' => $id_patient));
            return $this->selectSuiviModifie->fetchAll();
        }

    }

?>
