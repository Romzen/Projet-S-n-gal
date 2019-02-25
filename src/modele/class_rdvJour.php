<?php
class RdvJour{
    
 private $date;
 private $date2;
 
    function __construct($valeur='now'){
 
        $this->date = new DateTime($valeur);
        $this->date2 = new DateTime($valeur);
        //$timeZone = new DateTimeZone('Europe/Paris');
        $timeZone = new DateTimeZone('Africa/Dakar');
        $this->date->setTimezone($timeZone);
        $this->date2->setTimezone($timeZone);
 
    }

   public function dateJour(){
     
     return $this->date->format("d/m/Y");
     
    
    }
    
    public function dateJour2(){
     
     return $this->date->format("Y-m-d");
     
    
    }
    
    function jourPrecedent(){
        
        
        $dateVeille = $this->date2->modify("-1 day");
        return $dateVeille->format("d-m-Y");
 }
 
}
?>