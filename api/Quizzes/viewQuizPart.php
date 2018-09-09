<?php
    // Headers
     header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';

    // Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    // Instantiate Quiz Class
    $quiz = new Quiz($db);
    $quiz->quizID = $_GET['quiz_id'];

    // View Query
    $result = $quiz->viewQuizParts();
    
    // Get Row Count of Quiz
    $rowcount = $result->rowCount();

    if ($rowcount > 0) {
        // Quiz array
        $quiz_arr['data'] = array();
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $quiz_item = array(
                'QuizID' => $quiz_id,
                'PartID' => $part_id,
                'PartTitle' => $part_title,
                'Duration' => $duration,
                'Type' => $type
            );
            
            // Push to data array
            array_push($quiz_arr['data'], $quiz_item);
        }
        
         //Convert to JSON
        echo json_encode($quiz_arr['data']);
    } else {
        // No Quiz
        echo json_encode(array(
            'message' => 'No Parts found.'
        ));
    }