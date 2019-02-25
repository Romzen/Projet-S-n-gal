$(document).ready(function()
{
    $(".erreur").hide();
        
    
        /*($('#num_inclusionTEST')).focusout(function(){
            $(this).css('background-color', 'red');
            alert($('#num_inclusionTEST').val());
	});*/
        
        
        $('#btPatient').click(function(e){
            var cp=0;
            cp += verifierDate($('#date_de_naissance'),$('#divDateNaissance'));
            cp += verifier($('#num_id_national'),$('#dividnational'));
            cp += verifier($('#num_inclusion'),$('#divnuminclusion'));
            cp += verifierDate($('#date_inclusion'),$('#divDateInclusion'));
            if(cp!=4){
                e.preventDefault(); // on annule la fonction par défaut du bouton d'envoi
            }
        
        });  
        
    
        $('#btValider').click(function(e){
            var cp=0;
            cp += verifierMoisDispensation($('#date_dispensation'),$('#divErreurMois')) 
            if($('#etat_dispensation').val()!=1){
                    cp=11;
            }
            if(cp==1){
                
                
                cp += verifierNombre($('#poids'),$('#divpoids'));
                cp += verifier($('#observations'),$('#divobservations'));
                cp += verifierNombre($('#nb_jours_traitement'),$('#divnbjourtraitement'));
                cp += verifier($('#protocole'),$('#divprotocole'));
                cp += verifierDate($('#pUpDate'),$('#divDateDebutTraitement'));
                cp += verifierDate($('#date_dispensation'),$('#divDateDisp'));
                cp += verifierDate($('#date_rdv'),$('#divrdv'));
                cp += verifierJourFerie($('#date_rdv'),$('#divjourferie'));
            }
            if(cp==9){
            cp += comparerDate($('#date_rdv') , $('#date_fin_traitement'),$('#divComparerRdv'));
            cp += comparerDate( $('#date_dispensation') , $('#pUpDate'),$('#divComparerDateDisp'));
            }
            if(cp!=11){
                e.preventDefault(); // on annule la fonction par défaut du bouton d'envoi
            }

         });
         
        $('#btUtilisateur').click(function(e){
            var cp=0;
            cp += verifier($('#nom_utilisateur'),$('#divnom'));
            cp += verifier($('#prenom_utilisateur'),$('#divprenom'));
            cp += verifier($('#login'),$('#divlogin'));
            cp += verifier($('#mdp'),$('#divmdp'));
            cp += comparerMDP($('#mdp') , $('#mdp2'),$('#divconfirmation'))
            if(cp!=5){
                e.preventDefault(); // on annule la fonction par défaut du bouton d'envoi
            }

         });
         
        function verifierMoisDispensation(champ,divErreur){
            var cp=0;
            var date= champ.val();
            var from = date.split("-")
            var year = from[0];
            var month = from[1];
            var day = from[2];
            var moisDisp = $('#mois').val();
            if(month!=moisDisp){
                afficherErreur(true,champ,divErreur);
            }
            else{
                cp=1;
                afficherErreur(false,champ,divErreur);
            }
            return cp;
            
        }
    
        function verifier(champ,divErreur){
            var cp=0;
            if(champ.val() == ""){ // si le champ est vide
                afficherErreur(true,champ,divErreur); // Fonction qui va afficher l'erreur
            }
            else{
                cp=1;
                afficherErreur(false,champ,divErreur); // Fonction qui va remet le formulaire basique s'il n'y a pas erreur
            }
            return cp;
        
        }
        
        function verifierDate(champ,divErreur){
            var cp=0;
            var date= champ.val();
            var from = date.split("-")
            var year = from[0];
            var month = from[1];
            var day = from[2];
            
            if(year.length != 4 || year<1900 || year>2019 ){
                afficherErreur(true,champ,divErreur);
            }            
            else{
                cp=1;
                afficherErreur(false,champ,divErreur);             
            }
            return cp;
        }
        
        function verifierNombre(champ,divErreur){
            var cp=0;
            var nb = champ.val();
            if(nb == ""){ // si le champ est vide
                afficherErreur(true,champ,divErreur);
            }
            else if(isNaN(nb)==false){
                cp=1;
                afficherErreur(false,champ,divErreur);
            }
            else{
                afficherErreur(true,champ,divErreur);
            }
            return cp;
        }
        
        function afficherErreur(valeur,champ,divErreur){
            if(valeur==true){
                divErreur.show()
                champ.css({ // on rend le champ rouge
    	        borderColor : 'red',
    	        color : 'red',
                //backgroundColor : 'blue'
    	    });
            }
            else{
                divErreur.hide();
                champ.css({ // on remet le style des champs comme on l'avait défini dans le style CSS
                borderColor : '#ccc',
                color : '#555',
                //backgroundColor : 'white
                
                });
            }
        }
        
        function verifierJourFerie(champ,divErreur){
            var cp=1;
            var dateDepart= champ.val(); 
            var from = dateDepart.split("-")
            var year = from[0];
            var month = from[1];
            var day = from[2];
            var date = new Date(year,month,day);
            
            var jour= date.getDay();
            var jourFerie= [year+"-01-01" , year+"-05-01",year+"-05-08",year+"-07-14",year+"-08-15",year+"-11-01",year+"-11-11",year+"-12-25"]
            
            if(jour==2 || jour==3){
                cp=0;
                afficherErreur(true,champ,divErreur);
            }
            else{
                for(var i=0 ; i<jourFerie.length; i++){
                    if(dateDepart==jourFerie[i]){
                        cp=0;
                        afficherErreur(true,champ,divErreur);
                    }
                }
            }
            if(cp==1){
               afficherErreur(false,champ,divErreur);
            }
            return cp;
        }
        
        function comparerDate(dateA1 , dateA2 ,divErreur){
            var cp=0;
            var date1 = dateA1.val();
            var date2 = dateA2.val();
            var from1 = date1.split("-") , from2 = date2.split("-") ;
            var year1 = from1[0] , year2 = from2[0]
            var month1 = from1[1] , month2 = from2[1]
            var day1 = from1[2] , day2 = from2[2]

            date1 = new Date(year1 , month1 , day1);
            date2 = new Date(year2 , month2 , day2);

            if(date1 > date2 ){
                afficherErreur(true,dateA1,divErreur);
                afficherErreur(true,dateA2,divErreur);
            }
            else {
                cp=1;
                afficherErreur(false,dateA1,divErreur);
                afficherErreur(false,dateA2,divErreur);
            }
            return cp;
        }
        
        function comparerMDP( champ1 , champ2 , divErreur){
            var cp=0;
            var mdp = champ1.val();
            var confirm = champ2.val();
            
            if(mdp==confirm){    
                afficherErreur(false,champ2,divErreur)
                cp=1;
            }
            else{
                afficherErreur(true , champ2,divErreur)
                
            }
            return cp;
        }
});

