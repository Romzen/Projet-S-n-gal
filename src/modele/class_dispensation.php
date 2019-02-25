<?php

class Dispensation {

    private $selectDerniereDispen;
    private $selectAll;
    private $insertAll;
    private $updateAll;
    private $selectId;
    private $selectOneYearMonth;
    private $selectDateInclusion;
    private $selectEtat;
    private $selectRDV;
    private $selectRDV2;
    private $selectRDVPatient;
    private $selectEtatRDV;
    private $selectDate;
    private $selectDateDisp;
    private $selectAllDateDisp;
    private $selectPatient;
    private $updatePresence;
    private $updateAbsence;
    private $selectAbs;
    private $selectUneDisp;

    public function __construct($db) {
        $this->selectDerniereDispen = $db->prepare("SELECT  date_dispensation AS derniereDisp , rdv  FROM `DISPENSATION` WHERE `id_patient`=:id_patient ORDER BY date_dispensation DESC");

        $this->insertAll = $db->prepare("INSERT INTO DISPENSATION(id_patient, etat_dispensation, date_dispensation,
                                            date_debut_traitement, nb_jours_traitement, date_fin_traitement, rdv,poids, observations) 
                                            values(:id_patient, :etat_dispensation, :date_dispensation, :date_debut_traitement, 
                                            :nb_jours_traitement, :date_fin_traitement, :rdv, :poids, :observations)");

        $this->selectAll = $db->prepare("select date_debut_traitement, date_dispensation, date_fin_traitement, etat_dispensation, id_dispensation, id_patient, nb_jours_traitement, observations, poids, DATE_FORMAT(rdv,'%d-%m-%Y') as rdv from DISPENSATION order by rdv ");

        $this->selectRDVDuJour = $db->prepare("select * from DISPENSATION where rdv=:date");


        $this->updateAll = $db->prepare("update DISPENSATION SET  id_patient=:id_patient , etat_dispensation=:etat_dispensation,
                                           date_dispensation=:date_dispensation, date_debut_traitement=:date_debut_traitement , 
                                           nb_jours_traitement=:nb_jours_traitement, date_fin_traitement=:date_fin_traitement , rdv=:rdv 
                                           , poids =:poids ,  observations=:observations WHERE id_dispensation=:id_dispensation and WHERE MONTH(date_dispensation)=:MONTH(date_dispensation) and WHERE YEAR(date_dispensation)=:YEAR(date_dispensation)");

        $this->selectId = $db->prepare("select * from DISPENSATION where id_patient=:id_patient");

        $this->selectOneYearMonth = $db->prepare("select d.*, ed.* , MONTH(d.date_dispensation) as 'mois' from DISPENSATION d inner join ETAT_DISPENSATION ed on d.etat_dispensation = ed.id_etat_dispen where id_patient=:id_patient and
                                                    MONTH(d.date_dispensation)=:mois AND YEAR(d.date_dispensation)=:annee");

        $this->selectDateInclusion = $db->prepare("SELECT MIN(date_dispensation) from DISPENSATION where id_patient=:id_patient");

        $this->selectEtat = $db->prepare("SELECT * FROM DISPENSATION INNER JOIN ETAT_DISPENSATION ON DISPENSATION.etat_dispensation=ETAT_DISPENSATION.id_etat_dispen WHERE id_patient=:id_patient AND
                                              date_dispensation = (select MAX(date_dispensation) FROM DISPENSATION 
                                              WHERE id_patient=:id_patient");

        $this->selectRDV = $db->prepare("select DISPENSATION.rdv, DISPENSATION.id_patient,ETAT_RDV.etat,DISPENSATION.etatRdv, PATIENT.num_id_national, PATIENT.num_inclusion from DISPENSATION inner join ETAT_RDV inner join PATIENT on DISPENSATION.etatRdv = ETAT_RDV.idEtat and DISPENSATION.id_patient=PATIENT.id_patient ");

        $this->selectRDV2 = $db->prepare("select DATE_FORMAT(DISPENSATION.rdv,'%d-%m-%Y') as rdv, DISPENSATION.id_patient,ETAT_RDV.etat,DISPENSATION.etatRdv, PATIENT.num_id_national, PATIENT.num_inclusion from DISPENSATION inner join ETAT_RDV inner join PATIENT on DISPENSATION.etatRdv = ETAT_RDV.idEtat and DISPENSATION.id_patient=PATIENT.id_patient group by DISPENSATION.id_patient ");

        $this->selectRDVPatient = $db->prepare("select DATE_FORMAT(DISPENSATION.rdv,'%d-%m-%Y') as rdv, DISPENSATION.id_patient,ETAT_RDV.etat,DISPENSATION.etatRdv, PATIENT.num_id_national, PATIENT.num_inclusion from DISPENSATION inner join ETAT_RDV inner join PATIENT on DISPENSATION.etatRdv = ETAT_RDV.idEtat and DISPENSATION.id_patient=PATIENT.id_patient where DISPENSATION.id_patient=:id_patient  ");

        $this->selectEtatRDV = $db->prepare("select etatRdv from DISPENSATION ");

        $this->selectDateDisp = $db->prepare("select etat_dispensation,date_dispensation,rdv from DISPENSATION where date_dispensation=:date_dispensation ");

        $this->selectDate = $db->prepare("select rdv from DISPENSATION where rdv=:rdv ");

        $this->selectAllDateDisp = $db->prepare("select date_dispensation from DISPENSATION where id_patient=:id_patient");

        $this->selectPatient = $db->prepare("select DISPENSATION.id_dispensation, DISPENSATION.id_patient, DATE_FORMAT(DISPENSATION.rdv,'%d-%m-%Y') as rdv, ETAT_RDV.etat,DISPENSATION.etatRdv, PATIENT.num_id_national, PATIENT.num_inclusion from DISPENSATION inner join ETAT_RDV inner join PATIENT on DISPENSATION.etatRdv = ETAT_RDV.idEtat and DISPENSATION.id_patient=PATIENT.id_patient where rdv=:rdv");

        $this->selectIdYear = $db->prepare("SELECT * FROM DISPENSATION WHERE id_patient=:id_patient AND YEAR(date_dispensation) = :annee ORDER BY date_dispensation ASC");

        $this->updatePresence = $db->prepare("update DISPENSATION set etatRdv=:etatRdv where id_dispensation=:id_dispensation");

        $this->updateAbsence = $db->prepare("update DISPENSATION set etatRdv=:etatRdv where id_dispensation=:id_dispensation");

        $this->selectAbs = $db->prepare("SELECT DISPENSATION.id_dispensation, DISPENSATION.id_patient, DATE_FORMAT(DISPENSATION.rdv,'%d-%m-%Y') as rdv, ETAT_RDV.etat,DISPENSATION.etatRdv FROM DISPENSATION inner join ETAT_RDV on DISPENSATION.etatRdv = ETAT_RDV.idEtat WHERE etatRdv=2 GROUP BY id_patient");

        $this->selectUneDisp = $db->prepare("select * 
                                             from DISPENSATION 
                                             WHERE id_dispensation=:id_dispensation 
                                             and MONTH(date_dispensation)=:mois) 
                                             and YEAR(date_dispensation)=:annee");
    }

    public function selectDerniereDispen($id_patient) {
        $this->selectDerniereDispen->execute(array(':id_patient' => $id_patient));
        if ($this->selectDerniereDispen->errorCode() != 0) {
            print_r($this->selectDerniereDispen->errorInfo());
        }
        return $this->selectDerniereDispen->fetch();
    }

    public function insertAll($id_patient, $etat_dispensation, $date_dispensation, $date_debut_traitement, $nb_jours_traitement, $date_fin_traitement, $rdv, $poids, $observations) {

        $this->insertAll->execute(array(
            ':id_patient' => $id_patient, ':etat_dispensation' => $etat_dispensation,
            ':date_dispensation' => $date_dispensation, ':date_debut_traitement' => $date_debut_traitement,
            ':nb_jours_traitement' => $nb_jours_traitement, ':date_fin_traitement' => $date_fin_traitement,
            ':rdv' => $rdv, ':poids' => $poids, ':observations' => $observations));
        if ($this->insertAll->errorCode() != 0) {
            print_r($this->insertAll->errorInfo());
        }
        return $this->insertAll->rowCount();
    }

    public function updateAll($id_dispensation, $id_patient, $etat_dispensation, $date_dispensation, $date_debut_traitement, $nb_jours_traitement, $date_fin_traitement, $rdv, $poids, $observations) {

        $this->updateAll->execute(array(':id_dispensation' => $id_dispensation, ':id_patient' => $id_patient,
            ':etat_dispensation' => $etat_dispensation, ':date_dispensation' => $date_dispensation,
            ':date_debut_traitement' => $date_debut_traitement, ':nb_jours_traitement' => $nb_jours_traitement,
            ':date_fin_traitement' => $date_fin_traitement, ':rdv' => $rdv,
            ':poids' => $poids, ':observations' => $observations));
        if ($this->updateAll->errorCode() != 0) {
            print_r($this->updateAll->errorInfo());
        }
        return $this->updateAll->rowCount();
    }

    public function selectAll() {
        $this->selectAll->execute();
        if ($this->selectAll->errorCode() != 0) {
            print_r($this->selectAll->errorInfo());
        }
        return $this->selectAll->fetchAll();
    }

    public function selectId($id_patient) {
        $this->selectId->execute(array(':id_patient' => $id_patient));
        if ($this->selectId->errorCode() != 0) {
            print_r($this->selectId->errorInfo());
        }
        return $this->selectId->fetchAll();
    }

    public function selectRDVDuJour($date) {
        $this->selectRDVDuJour->execute(array(':date' => $date));
        if ($this->selectRDVDuJour->errorCode() != 0) {
            print_r($this->selectRDVDuJour->errorInfo());
        }
        return $this->selectRDVDuJour->fetchAll();
    }

    public function selectOneYearMonth($id_patient, $mois, $annee) {
        $this->selectOneYearMonth->execute(array(':id_patient' => $id_patient, ':mois' => $mois, ':annee' => $annee));
        if ($this->selectOneYearMonth->errorCode() != 0) {
            print_r($this->selectOneYearMonth->errorInfo());
        }
        return $this->selectOneYearMonth->fetch();
    }

    public function selectDateInclusion($id_patient) {
        $this->selectDateInclusion->execute(array(':id_patient' => $id_patient));
        if ($this->selectDateInclusion->errorCode() != 0) {
            print_r($this->selectDateInclusion->errorInfo());
        }
        return $this->selectDateInclusion->fetch();
    }

    public function selectEtat($id_patient) {
        $this->selectEtat->execute(array(':id_patient' => $id_patient));
        if ($this->selectEtat->errorCode() != 0) {
            print_r($this->selectEtat->errorInfo());
        }
        return $this->selectEtat->fetch();
    }

    public function selectRDV() {
        $this->selectRDV->execute(array());
        if ($this->selectRDV->errorCode() != 0) {
            print_r($this->selectRDV->errorInfo());
        }
        return $this->selectRDV->fetchAll();
    }

    public function selectRDV2() {
        $this->selectRDV2->execute(array());
        if ($this->selectRDV2->errorCode() != 0) {
            print_r($this->selectRDV2->errorInfo());
        }
        return $this->selectRDV2->fetchAll();
    }

    public function selectRDVPatient($id_patient) {
        $this->selectRDVPatient->execute(array(':id_patient' => $id_patient));
        if ($this->selectRDVPatient->errorCode() != 0) {
            print_r($this->selectRDVPatient->errorInfo());
        }
        return $this->selectRDVPatient->fetchAll();
    }

    public function selectEtatRDV() {
        $this->selectEtatRDV->execute(array());
        if ($this->selectEtatRDV->errorCode() != 0) {
            print_r($this->selectEtatRDV->errorInfo());
        }
        return $this->selectEtatRDV->fetchAll();
    }

    public function selectDateDisp($date_dispensation) {
        $this->selectDateDisp->execute(array(':date_dispensation' => $date_dispensation));
        if ($this->selectDateDisp->errorCode() != 0) {
            print_r($this->selectDateDisp->errorInfo());
        }
        return $this->selectDateDisp->fetch();
    }

    public function selectDate($rdv) {
        $this->selectDate->execute(array(':rdv' => $rdv));
        if ($this->selectDate->errorCode() != 0) {
            print_r($this->selectDate->errorInfo());
        }
        return $this->selectDate->fetchAll();
    }

    public function selectAllDateDisp($id_patient) {
        $this->selectAllDateDisp->execute(array(':id_patient' => $id_patient));
        if ($this->selectAllDateDisp->errorCode() != 0) {
            print_r($this->selectAllDateDisp->errorInfo());
        }
        return $this->selectAllDateDisp->fetchAll();
    }

    public function selectPatient($rdv) {
        $this->selectPatient->execute(array(':rdv' => $rdv));
        if ($this->selectPatient->errorCode() != 0) {
            print_r($this->selectPatient->errorInfo());
        }
        return $this->selectPatient->fetchAll();
    }

    public function selectIdYear($id_patient, $annee) {
        $this->selectIdYear->execute(array(':id_patient' => $id_patient, ':annee' => $annee));
        if ($this->selectIdYear->errorCode() != 0) {
            print_r($this->selectIdYear->errorInfo());
        }
        return $this->selectIdYear->fetchAll();
    }

    public function updatePresence($id_dispensation, $etatRdv) {
        $r = true;
        $this->updatePresence->execute(array(':id_dispensation' => $id_dispensation, ':etatRdv' => $etatRdv));
        if ($this->updatePresence->errorCode() != 0) {
            print_r($this->updatePresence->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateAbsence($id_dispensation, $etatRdv) {
        $r = true;
        $this->updateAbsence->execute(array(':id_dispensation' => $id_dispensation, ':etatRdv' => $etatRdv));
        if ($this->updateAbsence->errorCode() != 0) {
            print_r($this->updateAbsence->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectAbs() {
        $this->selectAbs->execute();
        if ($this->selectAbs->errorCode() != 0) {
            print_r($this->selectAbs->errorInfo());
        }
        return $this->selectAbs->fetchAll();
    }
    
    public function selectUneDisp($mois, $annee) {
            $this->selectUneDisp->execute(array(':mois' => $mois, ':annee' => $annee));
            if ($this->selectUneDisp->errorCode()!=0){
                print_r($this->selectUneDisp->errorInfo());  
            }
            return $this->selectUneDisp->fetch();
        }

}

?>