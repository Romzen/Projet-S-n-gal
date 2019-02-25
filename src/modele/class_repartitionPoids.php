<?php

class Repartition_Poids {

    private $db;
    private $selectAll;
    private $selectProto;
    private $selectTest;

    function __construct($db) {
        $this->selectAll = $db->prepare("SELECT pa.poids , count(pa.id_patient) as compteur
                                         FROM PATIENT pa inner join PROTOCOLE p on p.id_proto = pa.protocole
                                         WHERE p.id_proto=:id_proto
                                         GROUP BY p.nom_proto, pa.poids ");
        $this->selectProto = $db->prepare("SELECT p.nom_proto, p.id_proto FROM PROTOCOLE p inner join PATIENT pa on pa.protocole = p.id_proto GROUP BY pa.protocole");
        $this->selectTest = $db->prepare("SELECT COUNT(p.id_patient) as compteur, p.poids, p.protocole
                                          FROM PATIENT p 
                                          
                                          GROUP BY p.protocole");
    }

    public function selectAll($id_proto) {
        $this->selectAll->execute(array(':id_proto' => $id_proto));
        return $this->selectAll->fetchAll();
    }

    public function selectProto() {
        $this->selectProto->execute();
        return $this->selectProto->fetchAll();
    }
    
    public function selectTest() {
        $this->selectTest->execute();
        return $this->selectTest->fetchAll();
    }

}
