<?php

$client = \Drupal::httpClient();
$people = [];

try {
  $response = $client->get('https://api.openweathermap.org/data/2.5/weather?q=Maracaibo,ve&appid=2dfbc79d79b64cba3c4969f09ab3ef96');
  $result = json_decode($response->getBody(), TRUE);
  //print_r($result);
//   foreach($result as $item) {
//     $people[] = $item; 
//   }
    
  //print_r($result['cod']);
}
catch (RequestException $e) {
  // log exception
}