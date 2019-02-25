<?php

function verif($parametres)
{
    //Iinitialisation du message et du code i. Initialisation d'une variable de parcours i à 0
    $resultat['message']='';
    $resultat['code']=0 ;
    $i=0 ;
    
    while((list($key,$val)=each($parametres))&&(empty($resultat['message'])))
    //Pour chaque ligne de paramètre, on récupère sa clef et sa valeur      
    {
        if(isset($_POST[$key]))
        //Si la clef est présente dans l'adresse URL
        {
            if ($val['type']=='email')
            //Et si la valeur est du type email
            {
                if(!preg_match('#^[\w.+-]+@[\w.-]+\.[a-z]{2,6}$#i', $val['value']))
                //Si on trouve dans l'adresse mail un caractère invalide
                {
                    $resultat['message']= 'Vous devais saisir '.$val['label'].' valide';
                    //On enregistre le message dans la variable message
                }
                if(empty($_POST[$key]))
                //Si la clef est vide
                {
                    $resultat['message']='Vous devais saisir '.$val['label'] ;
                    //On enregistre le message dans la variable message
                }
            }

            if ($val['type']=='mdp')
            //Si la valeur est de type mdp
            {
                if(strlen($val['value']) < 5)
                //Si la longueur de la chaine de caractères est inférieure à 5
                {
                    $resultat['message']= 'Vous devais saisir '.$val['label'].' plus long';
                    //On enregistre le message dans al variable message
                }

                if(empty($_POST[$key]))
                //Si le mdp est vide
                {
                    $resultat['message']='Vous devais saisir '.$val['label'] ;
                    //On enregistre le message dans la variable message
                }
            }

            if ($val['type']=='char')
            //Si la valeur est de type texte
            {
                if (strlen($val['value']) < 3 )
                //Si la longueur de la chaine est inférieure à 3 caractères
                {
                    $resultat['message']= 'Vous devais saisir '.$val['label'].' avec plus de 2 caractères';
                    //On affiche ce message
                }
                if (strlen($val['value']) > 60 )
                //Si la longueur de la chaine de caractères est supérieure à 602 caractères
                {
                    $resultat['message']= 'Vous devais saisir '.$val['label'].' avec moins de 60 caractères';
                    //On affiche ce message
                }
                if(empty($_POST[$key]))
                //Si le champ est vide
                {
                    $resultat['message']='Vous devais saisir '.$val['label'] ;
                    //On affiche ce message
                }
            }

            if ($val['null']=='no')
            //Si la variable n'existe pas
            {
                if(empty($_POST[$key]))
                {
                    $resultat['message']='Vous devais saisir '.$val['label'] ;
                    //On affiche ce message
                }
            }

        }
        else
        //Sinon si la clef n'existe pas    
        {
            echo'<div class="alert alert-danger" role="alert">la variable '.$val['label']. ' n\'existe pas !</div>';
            //Affichage du message d'erreur
        }

        $i++ ;
        //On incrémente la variable i
    }
    
    if(!empty($resultat['message']))
    //Si le message est vide
    {
	$resultat['code']=1 ;
        //On passe le code 1
    }
    
    return $resultat ;
    //Retourner le resultat
}

?>