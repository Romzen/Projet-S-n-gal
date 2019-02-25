<?php

function actionMedicament($twig,$db){
    
    $form = array();    
    $medicament = new Medicament($db);
    $form['valide1'] = true;
    $form['valide2'] = true;
    $form['valide3'] = true;
    
    //Si on appuie sur le bouton pour ajouter un médicament
    if(isset($_POST['btacronyme'])){
        
        //On récupère les variables nécessaires à l'ajout d'un nouvel acronyme
        $acronyme_medicament = $_POST['acronyme_medicament'];
        $nom_medicament = $_POST['nom_medicament'];        
        $liste =$medicament->selectAll();
        //Pour chaque medicament se trouvant dans la liste des medicaments
        foreach ($liste as $medic)
        {
            //Si l'acronyme du medicament entré par l'utilisateur correspond à l'acronyme d'un médicament de la liste
            if($acronyme_medicament==$medic['acronyme_medicament'])
            {
                //On affiche un message d'erreur
                $form['valide1'] = false;
                $form['message1'] = 'L\'acronyme existe déja !!!';
            }
            //Si le nom d'un medicament entré par l'utilisateur corresponds à l'acronyme d'un médicament de la liste
            if($nom_medicament==$medic['nom_medicament']){
            
                //On affiche un message d'erreur
                $form['valide2'] = false;
                $form['message2'] = 'La signification existe deja';
            }
        }
        if($form['valide1'] == true && $form['valide2'] == true){
        $exec = $medicament->insertAll($acronyme_medicament,$nom_medicament);  
        }
        //Si le nom et l'acronyme entrés ne sont pas déjà présents dans la liste
        if($exec!=false){
            $form['valide'] = true;
            $form['valide3'] = false;
            $form['message'] = 'Vous avez ajouté un acronyme';
        }
        //Sinon, on affiche
        else{
            $form['valide'] = false;
            $form['valide3'] = false;
            $form['message'] = 'Impossible d\'ajouter un acronyme';
        }
    }
    
            if(isset($_POST['btSupprimer'])){
           $cocher = $_POST['cocher'];
           $form['valide'] = true;
            foreach ( $cocher as $id){
                $exec=$medicament->deleteOne($id); 
                    if (!$exec){
                            $form['valide2'] = false;  
                            $form['message2'] = 'Problème de suppression dans la table medicament';   
                    }   
            }
        }
        
        if(isset($_GET['id'])){
        $exec=$medicament->deleteOne($_GET['id']);
            if (!$exec){
                    $form['valide4'] = false;  
                    $form['message4'] = 'Problème de suppression dans la table medicament'; 
            }
            else{
                    $form['valide4'] = true;  
                    $form['message4'] = 'Produit supprimé avec succès';
                }       
        }

    //On instancie la classe médicament et on récupère la liste de tous les médicaments ainsi que le nombre de médicaments dans la liste
    
    $listeMedicament = $medicament->selectAll();
    //$nb = count($liste);
    
    echo $twig->render('medicament.html.twig', array('form'=>$form,'listeMedicament'=>$listeMedicament));
}    
    
     function actionModifMedicament($twig, $db){
        $form = array();
        $medicament = new Medicament ($db);
        
            if(isset($_GET['id_medicament'])){
                
                $unMedicament = $medicament->selectOne($_GET['id_medicament']); 
        
                if ($unMedicament!=null){ 
                      $form['medicament'] = $unMedicament;
                      $medicament = new Medicament ($db);
                }
             
                else{
                      $form['valide1'] = false;
                      $form['message1'] = 'Medicament incorrect';  
                }
            }
            
                else{
                        if(isset($_POST['btModifier'])){
                            
                                    
                                    $id_medicament = $_POST['id_medicament'];
                                    $acronyme_medicament = $_POST['acronyme_medicament'];
                                    $nom_medicament = $_POST['nom_medicament'];
                                   
                                    
                                    $exec=$medicament->updateAll($id_medicament, $acronyme_medicament, $nom_medicament);
                        
                        
                            if(!$exec){
                                $form['valide'] = false;
                                $form['message'] = 'Echec de la modification';
                            
                            }
            
                            else{
                                $form['valide'] = true;
                                $form['message'] = 'Modification réussie';  
                            } 
                        }
        
                }
                
                
                //$unMedicament = $medicament ->selectAll();
         echo $twig->render('modifmedicament.html.twig', array('form'=>$form));      
    }
    
    

?>