<?php
namespace Drupal\wa_country_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Link;

/**
 * Construye la grilla de Países.
 */
class CountryTableForm extends FormBase 
{
	
  /**
 * {@inheritdoc}
 */
  public function getFormId() 
  {
    return 'dn_country_table_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$pageNo = NULL) 
  {
    
    $header = [
      'idPais' => $this->t('ID del Pais'),
      'nombrePais' => $this->t('Nombre del País'),
      'codPais' => $this->t('Nombre abreviado del País'),
      'activo'=> $this->t('Activo'), 
      'opt' =>$this->t('Operaciones'),
    ];

    if($pageNo != '')
    {
      $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $this->get_countries($pageNo),
        '#empty' => $this->t('No hay países registrados'),
      ];
    }
    else
    {
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $this->get_countries("All"),
        '#empty' => $this->t('No hay países registrados'),
      ];
    }

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'wa_country_manager/global_styles';
    $form['#theme'] = 'country_form';
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
  
  function get_countries($opt) 
  {
    $res = array();

    if($opt == "All")
    {

      $results = \Drupal::database()->select('paisespinches', 'co');
    
      $results->fields('co');
      $results->range(0, 15);
      $results->orderBy('co.idPais','DESC');
      $res = $results->execute()->fetchAll();
      $ret = [];
    }
    else
    {
      $query = \Drupal::database()->select('paisespinches', 'co');
      
      $query->fields('co');
      $query->range($opt*15, 15);
      $query->orderBy('co.idPais','DESC');
      $res = $query->execute()->fetchAll();
      $ret = [];
    }

    foreach ($res as $row) 
    {
      $edit = Url::fromUserInput('/ajax/wa_country_manager/countries/edit/' . $row->idPais);
      $delete = Url::fromUserInput('/del/wa_country_manager/countries/delete/' . $row->idPais,array('attributes' => array('onclick' => "return ((confirm('¿Está seguro?')) ? true : false)")));
        
      $edit_link = Link::fromTextAndUrl(t('Edit'), $edit);
      $delete_link = Link::fromTextAndUrl(t('Delete'), $delete);
      $edit_link = $edit_link->toRenderable();
      $delete_link  = $delete_link->toRenderable();
      $edit_link['#attributes'] = ['class'=>'use-ajax'];
      $delete_link['#attributes'] = ['class'=>'use-ajax'];
	 
       
      $mainLink = t('@linkApprove  @linkReject', array('@linkApprove' => $edit_link, '@linkReject' => $delete_link));
      
	  
      $ret[] = [
        'idPais' => $row->idPais,
        'nombrePais' => $row->nombrePais,
		    'codPais' => $row->codPais,
		    'activo' => $row->activo,
        'opt' => render($delete_link),
		    'opt1' => render($edit_link),
      ];
    }
    return $ret;
  }
	
}