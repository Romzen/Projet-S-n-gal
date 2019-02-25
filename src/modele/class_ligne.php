<?php
	
class Ligne {

    private $selectAll;
    private $selectOne;

    public function __CONSTRUCT($db) {
        $this->selectAll = $db->prepare("SELECT * FROM LIGNE");
        $this->selectOne=$db->prepare("select * from LIGNE where id_ligne=:id_ligne");
    }

    public function selectAll() {
        $this->selectAll->execute();
        return $this->selectAll->fetchAll();
    }
    
    public function selectOne($id_ligne) {
        $this->selectOne->execute(array(':id_ligne' => $id_ligne));
        return $this->selectOne->fetch();
    }
    
}
	
?>