
<?php

    require_once('connection.php');

    header('Access-Control-Allow-Origin: *');
    ini_set('max_execution_time', '3000');


    $source_name = $_POST["source_name"];
    $source_url = $_POST["source_url"];
    $source_begin = $_POST["source_begin"];
    $source_end = $_POST["source_end"];



    function get_file($source_url){
        return file_get_contents($source_url);
    }
    $content = get_file($source_url) ;

    function restrict($content, $source_begin, $source_end){
        $start = strpos($content, $source_begin);
        $end = strpos($content, $source_end, $start);
        $paragraph = substr($content, $start, $end-$start+strlen($source_end));
        $paragraph =  preg_replace('/[^a-z]+/i', ' ', $paragraph);
        $split = explode(" ",$paragraph);
        $freq=array();
        for($i=0;$i<count($split);$i++){
            $freq[$split[$i]] = strlen($split[$i]);
        }
        return $freq;
    }

    $freq = restrict();



    $sql = "INSERT INTO source (source_name, source_url , source_begin , source_end  , parsed_dtm )
    VALUES ('$source_name', '$source_url','$source_begin', '$source_end',CURRENT_TIMESTAMP())";
    $conn->query($sql);

    $sql="SELECT source_id FROM `source` order by source_id Desc  limit 1";

    $result = $conn->query($sql);
    $source_id=0;

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $source_id = $row["source_id"];
        }
    } else {
        echo "0 results";
    }

    foreach($freq as $x => $x_value) {
        // echo "Key=" . $x . ", Value=" . $x_value;
        // echo "<br>";

        $sql = "INSERT INTO occurrence (source_id, word , freq )VALUES ('$source_id', '$x','$x_value')";
        $conn->query($sql);

    }

    header("Location: report.html");
?>