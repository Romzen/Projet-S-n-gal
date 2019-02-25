<?php

    class Patient {
        private $db;
        private $insertAll;
        private $insert2;
        private $selectAll;
        private $selectOne;
        private $selectOne2;
        private $selectOne3;
        private $selectId;
        private $updateAll;
        private $updateProto;
        private $deleteOne;
        private $selectAge;
        private $listePatient;
        private $selectAnnee;
        
        private $getPatientByNumIDNat;
 
        public function __construct($db) {
            
            $this->db = $db ; 
            $this->insertAll = $db->prepare("INSERT INTO PATIENT(num_id_national, num_inclusion, etat_patient,profil_serologique, sexe, date_de_naissance,
                                            protocole, poids, ligne, co_infections, date_inclusion, inclusion) values( :num_id_national, :num_inclusion,:etat_patient, :profil_serologique, :sexe,
                                            :date_de_naissance, :protocole, :poids, :ligne, :co_infections, :date_inclusion , :inclusion)");

            $this->insert2 = $db->prepare("INSERT INTO SUIVI_PRESENCE(id_patient, date, idProto,poids,ligne,profil_serologique) values( :id_patient, :today,:protocole, :poids,:ligne,:profil_serologique)");
            
             
            $this->selectAll=$db->prepare("select * from PATIENT ");
            
            
            $this->selectAll2=$db->prepare("select month(date_inclusion) as moisInscription , year(date_inclusion) as yearInscription,sexe,date_inclusion, dateInscription, id_patient,DATEDIFF(:date , date_de_naissance) as AgeEnJour from PATIENT ");

            $this->selectOne3=$db->prepare("select * from PATIENT where num_id_national LIKE CONCAT('%', :num_id_national, '%') ORDER BY num_id_national");

            $this->selectOne2=$db->prepare("select * from PATIENT where num_id_national=:num_id_national");

            $this->selectOne=$db->prepare("select * from PATIENT where num_inclusion=:num_inclusion");

            $this->selectId=$db->prepare("select * from PATIENT where id_patient=:id_patient");
            
            $this->selectAge=$db->prepare("select FLOOR(DATEDIFF(CURRENT_DATE,date_de_naissance)/365) as Age from PATIENT where id_patient=:id_patient");

            $this->updateAll=$db->prepare("update PATIENT SET num_id_national=:num_id_national,num_inclusion=:num_inclusion,
                                          profil_serologique=:profil_serologique, sexe=:sexe, date_de_naissance=:date_de_naissance, 
                                          protocole=:protocole,  poids=:poids , ligne=:ligne, co_infections=:co_infections , date_inclusion=:date_inclusion , inclusion=:inclusion WHERE id_patient=:id_patient");

            $this->updateProto=$db->prepare("update PATIENT SET protocole=:protocole WHERE id_patient=:id_patient");
            
            $this->updateEtat=$db->prepare("update PATIENT set etat_patient=:etat_patient where id_patient=:id_patient");

            $this->deleteOne=$db->prepare("delete from PATIENT where id_patient=:id_patient");
            
            
            $this->listePatient = $db->prepare("SELECT P.id_patient,P.num_id_national,P.num_inclusion,nom_profil,P.sexe,P.date_de_naissance,P.date_inclusion,P.poids,max(rdv) as maxRdv,max(date_dispensation) as maxDispen,nom_etat_dispen,nom_proto,type_inclusion,nom_ligne
                                                FROM ETAT_DISPENSATION ED LEFT OUTER JOIN DISPENSATION D ON  ED.id_etat_dispen  =D.etat_dispensation
                                                                          LEFT OUTER JOIN PATIENT P ON D.id_patient = P.id_patient
                                                                          LEFT OUTER JOIN PROFIL_SEROLOGIQUE PS ON P.profil_serologique = PS.id_profil

                                                                          LEFT OUTER JOIN INCLUSION I ON P.inclusion = I.id_inclusion                          
                                                                          LEFT OUTER JOIN PROTOCOLE PR ON PR.id_proto = P.protocole                          
                                                                          LEFT OUTER JOIN LIGNE L ON P.ligne = L.id_ligne
                                                GROUP BY P.id_patient;");
            
            $this->selectAnnee=$db->prepare("SELECT YEAR(di.date_dispensation) as annee 
                                             FROM DISPENSATION di
                                             GROUP BY annee");
        }

        public function insertAll($num_id_national, $num_inclusion,$etat_patient, $profil_serologique, $sexe, $date_de_naissance, $protocole, $poids, $ligne, $co_infections, $date_inclusion ,$inclusion){
            $this->insertAll->execute(array(':num_id_national' => $num_id_national,':num_inclusion' => $num_inclusion,':etat_patient' => $etat_patient,':profil_serologique' => $profil_serologique,
                    ':sexe' => $sexe,':date_de_naissance' => $date_de_naissance,':protocole' => $protocole,':poids' => $poids,':ligne' => $ligne, ':co_infections' => $co_infections, ':date_inclusion' => $date_inclusion, ':inclusion' => $inclusion));
            if ($this->insertAll->errorCode()!=0){
                print_r($this->insertAll->errorInfo());  
                 
            }return $this->insertAll->rowCount();
        }
        
        public function insert2($id_patient,$today,$protocole,$poids,$ligne,$profil_serologique){
            $this->insert2->execute(array(':id_patient' => $id_patient,':today'=>$today,':protocole'=>$protocole,':poids'=>$poids,':ligne'=>$ligne,':profil_serologique'=>$profil_serologique));
            if ($this->insert2->errorCode()!=0){
                print_r($this->insert2->errorInfo()); 
               
            }return $this->insert2->rowCount();
        }
        
        
        public function selectAll() {
            $this->selectAll->execute();
            if ($this->selectAll->errorCode()!=0){
                print_r($this->selectAll->errorInfo());  
            }
            return $this->selectAll->fetchAll();
        }
        
        public function selectAll2($date) {
            $this->selectAll2->execute(array(':date' => $date));
            if ($this->selectAll2->errorCode()!=0){
                print_r($this->selectAll2->errorInfo());  
            }
            return $this->selectAll2->fetchAll();
        }
        
        public function selectOne3( $num_id_national) {
            $this->selectOne3->execute(array(':num_id_national' => $num_id_national));
            if ($this->selectOne3->errorCode()!=0){
                print_r($this->selectOne3->errorInfo());  
            }
            return $this->selectOne3->fetchAll();
        }

        public function selectOne2($num_id_national) {
            $this->selectOne2->execute(array(':num_id_national' => $num_id_national));
            if ($this->selectOne2->errorCode()!=0){
                print_r($this->selectOne2->errorInfo());  
            }
            return $this->selectOne2->fetch();
        }

        public function selectOne($num_inclusion) {
            $this->selectOne->execute(array(':num_inclusion' => $num_inclusion));
            if ($this->selectOne->errorCode()!=0){
                print_r($this->selectOne->errorInfo());  
            }
            return $this->selectOne->fetch();
        }

        public function selectId($id_patient) {
            $this->selectId->execute(array(':id_patient' => $id_patient));
            if ($this->selectId->errorCode()!=0){
                print_r($this->selectId->errorInfo());  
            }
            return $this->selectId->fetch();
        }

        public function selectAge($id_patient) {
            $this->selectAge->execute(array(':id_patient' => $id_patient));
            if ($this->selectAge->errorCode()!=0){
                print_r($this->selectAge->errorInfo());  
            }
            return $this->selectAge->fetch();
        }
        
        public function updateAll($id_patient, $num_inclusion, $num_id_national, $profil_serologique, $sexe, $date_de_naissance, $protocole, $poids, $ligne, $co_infections, $date_inclusion , $inclusion){
            $this->updateAll->execute(array(':id_patient' => $id_patient,':num_inclusion' => $num_inclusion,':num_id_national' => $num_id_national,
                ':profil_serologique' => $profil_serologique,':sexe' => $sexe,':date_de_naissance' => $date_de_naissance,':protocole' => $protocole,
                ':poids' => $poids,':ligne' => $ligne, 'co_infections' => $co_infections, ':date_inclusion' => $date_inclusion , ':inclusion' => $inclusion));
            if ($this->updateAll->errorCode()!=0){
                print_r($this->updateAll->errorInfo());  
            }
            return $this->updateAll->rowCount();
        }

        public function updateProto($id_patient, $protocole){
            $this->updateProto->execute(array(':id_patient' => $id_patient,':protocole' => $protocole));
            if ($this->updateProto->errorCode()!=0){
                print_r($this->updateProto->errorInfo());  
            }
            return $this->updateProto->rowCount();
        }
        
        public function updateEtat($id_patient, $etat_patient){
            $this->updateEtat->execute(array(':id_patient' => $id_patient,':etat_patient' => $etat_patient));
            if ($this->updateEtat->errorCode()!=0){
                print_r($this->updateEtat->errorInfo());  
            }
            return $this->updateEtat->rowCount();
        }

        public function deleteOne($id_patient){
            $this->deleteOne->execute(array(':id_patient' => $id_patient));
            if ($this->deleteOne->errorCode()!=0){
                print_r($this->deleteOne->errorInfo());  
            }
            return $this->deleteOne->rowCount();
        }
        
        public function listePatient() {
            $this->listePatient->execute();
            if ($this->listePatient->errorCode()!=0){
                print_r($this->listePatient->errorInfo());  
            }
            return $this->listePatient->fetchAll();
        }
        
        public function selectAnnee() 
        {
            $this->selectAnnee->execute();
            return $this->selectAnnee->fetchAll();
        }
    }
    
?>
