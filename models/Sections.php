<?php


    class Sections {
         //Database Properties 
        private $conn;
        
        //Constructor   
        public function __construct($db){
            $this->conn = $db;
        }
        
        //Section properties
        public $section_id;
        public $section;
        public $old_section;
        public $course_id;
        public $course;
        public $errors = array();
        
        public function addSection() {
            $insertQuery = "INSERT INTO sections
                             SET
                              course_id = :course_id,
                              section = :section";
            
            $stmt = $this->conn->prepare($insertQuery);
            $this->course_id = htmlspecialchars(strip_tags($this->course_id));
            $this->section = htmlspecialchars(strip_tags($this->section));
            $stmt->bindParam(':course_id', $this->course_id);
            $stmt->bindParam(':section', $this->section);
            if($stmt->execute()){
                return true;
            }else{
                
                printf("Error: %s".\n, $stmt->err);
                return false;
            }
        }
        
        public function getCourseID() {
            $query = "SELECT course_id FROM courses
                        WHERE course_id = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->course);
            $stmt->execute();
            
            if($stmt->rowCount()>0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->course_id = $row['course_id'];
                return true;
            }else{
                $this->errors['field'] = "course";
                $this->errors['message'] = "Course entered is non existent.";
            }
        }
        
        public function validateSection() {
            if(!preg_match("/[a-z_\-0-9]/i", $this->section)){
                 $this->errors['field'] = "section";
                 $this->errors['message'] = "Letters, number and hypens are the only characters allowed.";
            }else{
                return true;
            }
        }
        
        
    }
        