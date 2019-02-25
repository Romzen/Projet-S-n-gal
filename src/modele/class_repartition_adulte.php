<?php

class Repartition_adulte {

    private $selectAll;
    private $selectByMois;
    private $selectAll2;
    private $selectByMoisAtyp;
    private $selectAnnee;

    public function __construct($db) {
        //Compte le nombre de protocole par mois par protocole par année ou le type protocole = 1
        $this->selectAll = $db->prepare("SELECT p.id_proto,p.nom_proto
                                         FROM PROTOCOLE p where p.type_protocole = 1   ");
        $this->selectByMois = $db->prepare("SELECT p.id_proto, p.nom_proto, YEAR(sp.date) as annee,MONTH(sp.date), count(sp.id_patient) as compteur
                                            FROM PATIENT pa inner join SUIVI_PRESENCE sp on pa.id_patient = sp.id_patient 
                                            inner join PROTOCOLE p on sp.idProto = p.id_proto
                                            WHERE FLOOR(DATEDIFF(SYSDATE(),pa.date_de_naissance)/365)>18 and p.type_protocole = 1 AND MONTH(sp.date)=:mois AND id_proto=:id_proto AND YEAR(sp.date) =:annee");

        $this->selectAll2 = $db->prepare("SELECT p.id_proto,p.nom_proto
                                          FROM PROTOCOLE p where p.type_protocole = 2");

        $this->selectByMoisAtyp = $db->prepare("SELECT p.id_proto, p.nom_proto, YEAR(sp.date) as annee,MONTH(sp.date), count(sp.id_patient) as compteur
                                            FROM PATIENT pa inner join SUIVI_PRESENCE sp on pa.id_patient = sp.id_patient 
                                            inner join PROTOCOLE p on sp.idProto = p.id_proto
                                            WHERE FLOOR(DATEDIFF(SYSDATE(),pa.date_de_naissance)/365)>18 and p.type_protocole = 2 AND MONTH(sp.date)=:mois AND id_proto=:id_proto AND YEAR(sp.date) =:annee");
        $this->selectAnnee = $db->prepare("SELECT YEAR(sp.date) as annee 
                                             FROM SUIVI_PRESENCE sp
                                             GROUP BY annee");
    }

    public function selectAll() {
        $this->selectAll->execute();
        return $this->selectAll->fetchAll();
    }

    public function selectByMois($mois, $id_proto, $annee) {
        $this->selectByMois->execute(array(':mois' => $mois, ':id_proto' => $id_proto, ':annee' => $annee));
        return $this->selectByMois->fetch();
    }

    public function selectAll2() {
        $this->selectAll2->execute();
        return $this->selectAll2->fetchAll();
    }

    public function selectByMoisAtyp($mois, $id_proto, $annee) {
        $this->selectByMoisAtyp->execute(array(':mois' => $mois, ':id_proto' => $id_proto, ':annee' => $annee));
        return $this->selectByMoisAtyp->fetch();
    }

    public function selectAnnee() {
        $this->selectAnnee->execute();
        return $this->selectAnnee->fetchAll();
    }

}
