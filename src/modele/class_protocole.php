<?php

class Protocole {

    private $db;
    private $selectAll;
    private $insertAll;
    private $selectOne;
    private $deleteOne;
    private $updateAll;
    private $selectProtoNa;
    private $selectProtoAt;

    public function __construct($db) {

        $this->db = $db;

        $this->insertAll = $db->prepare("INSERT INTO PROTOCOLE(nom_proto, type_protocole, adulte, enfant, remarque) values(:nom_proto, :type_protocole, :adulte, :enfant, :remarque)");

        $this->selectAll = $db->prepare("select * from PROTOCOLE");

        //$this->selectAll  =  $db->prepare("select p.id_proto,p.nom_proto,p.type_protocole,p.adulte,p.enfant, p.remarque, t.id_type_protocole, t.type_protocole from PROTOCOLE p inner join TYPE_PROTOCOLE t on p.type_protocole = t.id_type_protocole order by nom_proto ");

        $this->selectOne = $db->prepare("select * from PROTOCOLE where id_proto=:id_proto");

        $this->deleteOne = $db->prepare("delete from PROTOCOLE where id_proto=:id_proto");

        $this->selectProtoNa = $db->prepare("select * from PROTOCOLE where type_protocole=1");

        $this->selectProtoAt = $db->prepare("select * from PROTOCOLE where type_protocole=2");

        $this->updateAll = $db->prepare("update PROTOCOLE SET nom_proto=:nom_proto, type_protocole=:type_protocole, adulte=:adulte, enfant=:enfant, remarque=:remarque WHERE id_proto=:id_proto");
    }

    public function insertAll($nom_proto, $type_protocole, $cocherA, $cocherE, $remarque) {

        $r = true;
        $this->insertAll->execute(array(':nom_proto' => $nom_proto, ':type_protocole' => $type_protocole, ':adulte' => $cocherA, ':enfant' => $cocherE, ':remarque' => $remarque));
        if ($this->insertAll->errorCode() != 0) {
            print_r($this->insertAll->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectAll() {
        $liste = $this->selectAll->execute();
        if ($this->selectAll->errorCode() != 0) {
            print_r($this->selectAll->errorInfo());
        }
        return $this->selectAll->fetchAll();
    }

    public function selectOne($id_proto) {
        $this->selectOne->execute(array(':id_proto' => $id_proto));
        return $this->selectOne->fetch();
    }

    public function deleteOne($id_proto) {
        $this->deleteOne->execute(array(':id_proto' => $id_proto));
        return $this->deleteOne->rowCount();
    }

    public function updateAll($id_proto, $type_protocole, $nom_proto, $adulte, $enfant, $remarque) {
        $r = true;
        $this->updateAll->execute(array(':id_proto' => $id_proto, ':type_protocole' => $type_protocole, ':nom_proto' => $nom_proto,
            ':adulte' => $adulte, ':enfant' => $enfant, ':remarque' => $remarque));
        if ($this->updateAll->errorCode() != 0) {
            print_r($this->updateAll->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectProtoNa() {
        $this->selectProtoNa->execute();
        return $this->selectProtoNa->fetchAll();
    }

    public function selectProtoAt() {
        $this->selectProtoAt->execute();
        return $this->selectProtoAt->fetchAll();
    }

}

?>