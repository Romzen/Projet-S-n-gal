<?php

class Graphe {

    private $selectGraphe;
    private $selectAnnee;

    public function __construct($db) {

        $this->selectGraphe = $db->prepare("SELECT p.num_id_national, d.date_dispensation, d.poids,DAY(d.date_dispensation) as 'jour', MONTH(d.date_dispensation) as mois, 
                                            YEAR(d.date_dispensation) as 'annee' 
                                            FROM DISPENSATION d INNER JOIN PATIENT p ON p.id_patient = d.id_patient 
                                            WHERE YEAR(d.date_dispensation) =:year and d.id_patient = :id_patient ORDER BY d.date_dispensation");

        $this->selectAnnee = $db->prepare("SELECT YEAR(d.date_dispensation) as annee FROM DISPENSATION d GROUP BY YEAR(d.date_dispensation)");
    }

    public function selectAnnee() {
        $this->selectAnnee->execute();
        if ($this->selectAnnee->errorCode() != 0) {
            print_r($this->selectAnnee->errorInfo());
        }
        return $this->selectAnnee->fetchAll();
    }

    public function selectGraphe($id_patient, $year) {
        $this->selectGraphe->execute(array(':id_patient' => $id_patient, ':year' => $year));
        if ($this->selectGraphe->errorCode() != 0) {
            print_r($this->selectGraphe->errorInfo());
        }
        return $this->selectGraphe->fetchAll();
    }

}

?>
