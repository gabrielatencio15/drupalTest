<?php

namespace Drupal\weather_api\Controller;

use Drupal\Core\Controller\ControllerBase;

class WeatherApiController extends ControllerBase {

  public function page(){

    $items = [
      ['title' => 'Noticia 1'],
      ['title' => 'Noticia 2'],
      ['title' => 'Noticia 3'],
      ['title' => 'Noticia 4'],
    ];

    return [
      '#theme' => 'weather_api',
      '#items' => $items,
      '#title' => 'Weather API'
    ];

  }

}