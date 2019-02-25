<?php

    class Rapport
    {
        private $selectAll;
        private $selectAllExceptId;
        private $selectInscritAvant ;
        private $selectMois ; 
        private $selectAnnee;
        private $selectAllPatient;
        private $selectPatientSuvis;
        private $selectJdeMois;
        private $selectInscritCeMois;
        
        public function __construct($db) 
        {
            $this->selectAll=$db->prepare("SELECT * FROM RAPPORT");
            
            $this->selectJdeMois = $db->prepare("SELECT DAY(LAST_DAY(:date)) as 'nbrJour'");
            $this->selectAllPatient=$db->prepare("SELECT p.* 
                                                  FROM PATIENT p inner join SUIVI_PRESENCE sp on p.id_patient = sp.id_patient
                                                  WHERE YEAR(sp.date) = :annee and MONTH(sp.date) = :mois
                                                  GROUP BY p.id_patient");
            $this->selectAllExceptId=$db->prepare("SELECT Age, Sexe, Nb_patient_suivit, Nb_patient_nouveau, Nb_patient_decede,
                                                  Nb_patient_PDV, Nb_patient_PDV_revenu, Nb_patient_transfere_TE, Nb_patient_transfere_TA, 
                                                  Nb_patient_suivit_regulierement FROM RAPPORT ORDER BY Age");
            $this->selectInscritAvant = $db->prepare("SELECT p.*, FLOOR(DATEDIFF(:date,p.date_de_naissance)/365) as 'age', FLOOR(DATEDIFF(:date,p.date_inclusion)) as 'date en jour'
                                                  FROM PATIENT p 
                                                  WHERE FLOOR(DATEDIFF(:date,p.date_inclusion)) > 30");
            $this->selectInscritCeMois = $db->prepare("SELECT p.*, FLOOR(DATEDIFF(:date,p.date_de_naissance)/365) as 'age', FLOOR(DATEDIFF(:date,p.date_inclusion)) as 'date en jour'
                                                  FROM PATIENT p 
                                                  WHERE FLOOR(DATEDIFF(:date,p.date_inclusion)) < 30");
        
            $this->selectPatientSuvis = $db->prepare("SELECT p.*, FLOOR(DATEDIFF(:date,p.date_de_naissance)/365) as 'age', FLOOR(DATEDIFF(:date,p.date_inclusion)) as 'date en jour', d.etat_dispensation
                                                  FROM PATIENT p INNER JOIN DISPENSATION d on p.id_patient = d.id_patient
                                                  WHERE FLOOR(DATEDIFF(:date,p.date_inclusion)) > 30 and d.etat_dispensation = 1
                                                  GROUP BY p.id_patient");   
            
            $this->selectMois=$db->prepare("SELECT MONTH(:date)as mois");
            $this->selectAnnee=$db->prepare("SELECT YEAR(:date)as annee");
            $this->selectEtatDisp=$db->prepare("select * from dispensation where month(date_dispensation)=:mois and year(date_dispensation)=:year and id_patient=:id_patient");
            $this->selectAge=$db->prepare("SELECT sexe,FLOOR(DATEDIFF(current_date,date_de_naissance)/365) as Age FROM PATIENT where dateInscription < :dateInscription");
            $this->selectAnnee=$db->prepare("SELECT YEAR(sp.date) as annee 
                                             FROM SUIVI_PRESENCE sp
                                             GROUP BY annee");
        }

        public function selectAll() 
        {
            $this->selectAll->execute();
            return $this->selectAll->fetchAll();
        }
        
        public function selectInscritCeMois($date) 
        {
            $this->selectInscritCeMois->execute(array(':date'=>$date));
            return $this->selectInscritCeMois->fetchAll();
        }
        
        public function selectJdeMois($date) 
        {
            $this->selectJdeMois->execute(array(':date'=>$date));
            return $this->selectJdeMois->fetchAll();
        }
        
        public function selectPatientSuvis($date) 
        {
            $this->selectPatientSuvis->execute(array(':date'=>$date));
            return $this->selectPatientSuvis->fetchAll();
        }
        
        public function selectAllPatient($annee,$mois) 
        {
            $this->selectAllPatient->execute(array(':annee'=>$annee,':mois'=>$mois));
            return $this->selectAllPatient->fetchAll();
        }
       
        public function selectAnnee() 
        {
            $this->selectAnnee->execute();
            return $this->selectAnnee->fetchAll();
        }
        
        public function selectAllExceptId()
        {
            $this->selectAllExceptId->execute();
            return $this->selectAllExceptId->fetchAll();
        }
        
        public function selectInscritAvant($date)
        {
            $this->selectInscritAvant->execute(array(':date'=>$date));
            return $this->selectInscritAvant->fetchAll();
        }
        
        public function selectMois($date)
        {
            $this->selectMois->execute(array(':date' => $date));
            return $this->selectMois->fetch();
        }
        
        public function selectEtatDisp($mois, $year , $id_patient)
        {
            $this->selectEtatDisp->execute(array(':mois' => $mois,':year' => $year,':id_patient' => $id_patient));
            return $this->selectEtatDisp->fetch();
        }
    }

?>
