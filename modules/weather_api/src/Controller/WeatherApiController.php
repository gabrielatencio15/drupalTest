<?php

namespace Drupal\weather_api\Controller;

use Drupal\Core\Controller\ControllerBase;

require_once(drupal_get_path('module', 'weather_api') . '/src/DB/DBResources.php');

class WeatherApiController extends ControllerBase {

  public function Page(){

    $consulta_ciudades = query_db("SELECT codPais, nombrePais, codCiudad, nombreCiudad FROM tbListaPaises AS pai INNER JOIN tblistaciudades AS ciu ON pai.idPais = ciu.idPais AND pai.activo = 1 AND ciu.activo = 1 ORDER BY nombrePais ASC, nombreCiudad ASC");
    $consulta_api_endpoint = query_db("SELECT `value` FROM tbParametros WHERE `param` = 'endpoint'");
    $consulta_api_token = query_db("SELECT `value` FROM tbParametros WHERE `param` = 'API_ID'");
    $consulta_api_unit = query_db("SELECT `value` FROM tbParametros WHERE `param` = 'weather_unit'");

    $hayciudades = (!$consulta_ciudades) ? 0 : 1;
    $isapicomplete = (!$consulta_api_endpoint || !$consulta_api_token) ? 0 : 1;
    $weather_unit = (!$consulta_api_unit) ? 'imperial' : $consulta_api_unit[0]->value;
    
    return [
      '#theme' => 'weather_api',
      '#items' => $items,
      '#title' => '',
      '#title_component' => 'Consulta el clima de tu ciudad',//$endpoint
      '#listaciudades' => $consulta_ciudades,
      '#apiendpoint' => base64_encode($consulta_api_endpoint[0]->value),
      '#apitoken' => base64_encode($consulta_api_token[0]->value),
      '#hayciudades' => $hayciudades,
      '#isapicomplete' => $isapicomplete,
      '#weather_unit' => base64_encode($weather_unit)
    ];

  }
   
}