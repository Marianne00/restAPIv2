<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');


    include_once '../../../config/Database.php';
    include_once '../../../models/Users.php';


    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Users Class
    $users = new Users($db); 

    //Get ID from URL
    $users->stud_id = isset($_GET['stud_id']) ? $_GET['stud_id'] : null;

    if($users->stud_id != null){
           //Get Post
        $users->singleStudent();

        //Create array
        $student_arr = array(
            'stud_id' => $users->stud_id,
            'name' => $users->name,
            'section' => $users->section_name
        );

        print_r(json_encode($student_arr));
    }else{
        echo json_encode(
            array(
                "message" => "Student ID cannot be null"
            )
        );
    }
 