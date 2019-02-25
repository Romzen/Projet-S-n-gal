<?php

class Inclusion {
    private $selectAll;
    private $insertAll;
    private $selectOne;
    private $selectOne2;

    public function __construct($db) {
        $this->selectAll = $db->prepare("SELECT * FROM INCLUSION");
        $this->insertAll = $db->prepare("INSERT INTO INCLUSION ( id_inclusion ,type_inclusion ) values(  :id_inclusion, :type_inclusion)");
        $this->selectOne=$db->prepare("select * from INCLUSION where id_inclusion=:id_inclusion");
        $this->selectOne2=$db->prepare("select * from INCLUSION where type_inclusion=:type_inclusion");

    }

    public function selectAll(){
        $this->selectAll->execute();
        return $this->selectAll->fetchAll();
    }    
    
    public function insertAll($id_inclusion, $type_inclusion){
        $this->insertAll->execute(array(':id_inclusion' => $id_inclusion,':type_inclusion' => $type_inclusion));
        return $this->insertAll->rowCount();
    }

    public function selectOne($id_inclusion) {
        $this->selectOne->execute(array(':id_inclusion' => $id_inclusion));
        return $this->selectOne->fetch();
    }
    
    public function selectOne2($type_inclusion) {
        $this->selectOne2->execute(array(':type_inclusion' => $type_inclusion));
        return $this->selectOne2->fetch();
    }
}

?>
