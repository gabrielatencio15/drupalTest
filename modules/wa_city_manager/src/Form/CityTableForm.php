<?php
namespace Drupal\wa_city_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Link;

/**
 * Construye la grilla de Países.
 */
class CityTableForm extends FormBase 
{
	
  /**
 * {@inheritdoc}
 */
  public function getFormId() 
  {
    return 'dn_city_table_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$pageNo = NULL) 
  {
    
    $header = [
      'idCiudad' => $this->t('ID de la Ciudad'),
      'nombreCiudad' => $this->t('Nombre de la Ciudad'),
      'codCiudad' => $this->t('Nombre abreviado de la Ciudad'),
      'idPais' => $this->t('ID del País al que pertenece'),
      'activo'=> $this->t('Activo'), 
      'opt' =>$this->t('Operaciones'),
    ];

    if($pageNo != '')
    {
      $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $this->get_cities($pageNo),
        '#empty' => $this->t('No hay ciudades registradas'),
      ];
    }
    else
    {
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $this->get_cities("All"),
        '#empty' => $this->t('No hay ciudades registradas'),
      ];
    }

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'wa_city_manager/global_styles';
    $form['#theme'] = 'city_form';
    $form['#prefix'] = '<div class="result_message">';
    $form['#suffix'] = '</div>';

    return $form;

  }

  public function validateForm(array &$form, FormStateInterface $form_state) 
  {
	 
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state)
  {	  
	   
  }
  
  function get_cities($opt) 
  {
    $res = array();

    if($opt == "All")
    {

      $results = \Drupal::database()->select('tblistaciudades', 'ci');
    
      $results->fields('ci');
      $results->range(0, 15);
      $results->orderBy('ci.idCiudad','DESC');
      $res = $results->execute()->fetchAll();
      $ret = [];
    }
    else
    {
      $query = \Drupal::database()->select('tblistaciudades', 'ci');
      
      $query->fields('ci');
      $query->range($opt*15, 15);
      $query->orderBy('ci.idCiudad','DESC');
      $res = $query->execute()->fetchAll();
      $ret = [];
    }

    foreach ($res as $row) 
    {
      $edit = Url::fromUserInput('/ajax/wa_city_manager/cities/edit/' . $row->idCiudad);
      $delete = Url::fromUserInput('/del/wa_city_manager/cities/delete/' . $row->idCiudad,array('attributes' => array('onclick' => "return ((confirm('¿Está seguro?')) ? true : false)")));
        
      $edit_link = Link::fromTextAndUrl(t('Edit'), $edit);
      $delete_link = Link::fromTextAndUrl(t('Delete'), $delete);
      $edit_link = $edit_link->toRenderable();
      $delete_link  = $delete_link->toRenderable();
      $edit_link['#attributes'] = ['class'=>'use-ajax'];
      $delete_link['#attributes'] = ['class'=>'use-ajax'];
	 
       
      $mainLink = t('@linkApprove  @linkReject', array('@linkApprove' => $edit_link, '@linkReject' => $delete_link));
      
	  
      $ret[] = [
        'idCiudad' => $row->idCiudad,
        'nombreCiudad' => $row->nombreCiudad,
		    'codCiudad' => $row->codCiudad,
        'idPais' => $row->idPais,
		    'activo' => $row->activo,
        'opt' => render($delete_link),
		    'opt1' => render($edit_link),
      ];
    }
    return $ret;
  }
	
}