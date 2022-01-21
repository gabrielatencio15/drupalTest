<?php

namespace Drupal\weather_api\Controller;

use Drupal\Core\Controller\ControllerBase;

require_once(drupal_get_path('module', 'weather_api') . '/src/API/ApiResources.php');
require_once(drupal_get_path('module', 'weather_api') . '/src/DB/DBResources.php');


class WeatherApiController extends ControllerBase {

  public function Page(){

    //use GuzzleHttp\Exception\RequestException;

    $consulta_ciudades = query_db('SELECT codPais, nombrePais, codCiudad, nombreCiudad FROM CiuPaiDisponibles ORDER BY nombrePais ASC, nombreCiudad ASC');
    // $lista_paises = '';


    return [
      '#theme' => 'weather_api',
      '#items' => $items,
      '#title' => '',
      '#nombre' => 'Consulta el clima de tu ciudad',//$endpoint
      '#listaciudades' => $consulta_ciudades
    ];

  }
   
}