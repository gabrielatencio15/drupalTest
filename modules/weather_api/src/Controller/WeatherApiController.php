<?php

namespace Drupal\weather_api\Controller;

use Drupal\Core\Controller\ControllerBase;

class WeatherApiController extends ControllerBase {

  public function Page(){

    $database = \Drupal::database();
    $query = $database->query("SELECT * FROM tbparametros");
    $result = $query->fetchAll();

    $endpoint = $result[0]->value;



    // $get_data = callAPI('GET', 'https://api.openweathermap.org/data/2.5/weather?q=Maracaibo,ve&appid=2dfbc79d79b64cba3c4969f09ab3ef96', false);
    //   $response = json_decode($get_data, true);
    //   $errors = $response['response']['errors'];
    //   $data = $response['response']['data'][0];

    //   print_r();

    $items = [
      // ['title' => 'Noticia 1'],
      // ['title' => 'Noticia 2'],
      // ['title' => 'Noticia 3'],
      // ['title' => 'Noticia 4'],
    ];

    return [
      '#theme' => 'weather_api',
      '#items' => $items,
      '#title' => 'TOOPOOOO',
      '#nombre' => $endpoint
    ];

  }
   
}