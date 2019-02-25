<?php
	
    class Ass_grade_droit 
    {
        private $selectAll;
        private $selectOneGrade;

        public function __CONSTRUCT($db) 
        {
            $this->selectAll = $db->prepare("SELECT * FROM ASS_GRADE_DROIT");
            $this->selectOneGrade = $db->prepare("SELECT * FROM ASS_GRADE_DROIT where id_grade=:id_grade");
        }

        public function selectAll() 
        {
            $this->selectAll->execute();
            return $this->selectAll->fetchAll();
        }

        public function selectOneGrade($id_grade) 
        {
            $this->selectOneGrade->execute(array(':id_grade' => $id_grade ));
            return $this->selectOneGrade->fetchAll();
        }
    }
	
?>