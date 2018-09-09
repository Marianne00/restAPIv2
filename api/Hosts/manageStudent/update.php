<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../../config/Database.php';
    include_once '../../../models/Users.php';
    include_once '../../../controllers/ErrorController.php';


    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Users Class
    $users = new Users($db); 


    $errorCont = new ErrorController();

    $student_id;

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    if($errorCont->validateStudentID($data->student_id, 'Student_ID')){

          if($users->verifyStudentID($data->student_id)){ 
              
                 if($data->update_id){
                     $student_id = $data->new_id;   
                 }else{
                     $student_id = $data->student_id;
                 }
              
                 if($errorCont->validateStudentID($student_id, 'New Student ID')){
                    if($errorCont->UpStudentFields($student_id, $data->section_name, $data->fname, $data->lname)) {
                        if($errorCont->validateName($data->fname, $data->mname, $data->lname)){
                            $users->new_id = $student_id;
                            $users->student_id = $data->student_id;
                            $users->section_name = $data->section_name;
                            $users->fname = $data->fname;
                            $users->mname = $data->mname;
                            $users->lname = $data->lname;

                            $users->getStudentSection();

                            if ($users->updateStudent()){
                                echo json_encode(
                                    array('message' => 'Student update completed.')
                                );
                            }else{
                                echo json_encode(
                                    array('message' => 'Student update failed.')
                                ); 
                            }

                        }else{
                            echo json_encode (
                                $errorCont->errors
                            );
                        }

                     }else{
                         echo json_encode (
                            $errorCont->errors
                        );
                     }
                 }else{
                     echo json_encode (
                        $errorCont->errors
                     );
                 }   
          }else{
              echo json_encode(
                array(
                    'field' => 'student_id',
                    'message' => 'student_id is non existent'
                )
              );
          }
    }else{
        echo json_encode (
            $errorCont->errors
        );
    }
