<?php

    function query_db($queryString){
        try
        {
            $database = \Drupal::database();
            $query = $database->query($queryString);
            $result = $query->fetchAll();
            
            if(empty($result))
                return false;
                
            return $result;
        }
        catch(RequestException $e)
        {
            return $e;
        }
    }

?>


