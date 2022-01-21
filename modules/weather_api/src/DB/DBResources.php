<?php

    function query_db($queryString){
        try
        {
            $database = \Drupal::database();
            $query = $database->query($queryString);
            $result = $query->fetchAll();
    
            return $result;
        }
        catch(RequestException $e)
        {
            return $e;
        }
    }

?>


