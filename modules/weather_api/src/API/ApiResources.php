<?php

  $client = \Drupal::httpClient();

  function isApiAvailable($url)
  {
    try 
    {
      $response = $client->get($url);
      $result = json_decode($response->getBody(), TRUE);
  
      return $result;
        
    }
    catch (RequestException $e) 
    {
      return $e;
    }
  }

?>