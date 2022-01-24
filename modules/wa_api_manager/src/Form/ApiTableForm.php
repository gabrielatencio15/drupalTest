<?php
namespace Drupal\wa_api_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Link;

/**
 * Construye la grilla de Parámetros.
 */
class ApiTableForm extends FormBase 
{
	
  /**
 * {@inheritdoc}
 */
  public function getFormId() 
  {
    return 'dn_api_table_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$pageNo = NULL) 
  {
    
    $header = [
      'id_param' => $this->t('ID del Parámetro'),
      'param' => $this->t('Parámetro'),
      'value' => $this->t('Valor del Parámetro'),
      'opt' =>$this->t('Operaciones'),
    ];

    if($pageNo != '')
    {
      $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $this->get_apis($pageNo),
        '#empty' => $this->t('No hay parámetros registrados'),
      ];
    }
    else
    {
        $form['table'] = [
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $this->get_apis("All"),
        '#empty' => $this->t('No hay parámetros registrados'),
      ];
    }

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'wa_api_manager/global_styles';
    $form['#theme'] = 'api_form';
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
  
  function get_apis($opt) 
  {
    $res = array();

    if($opt == "All")
    {

      $results = \Drupal::database()->select('tbParametros', 'par');
    
      $results->fields('par');
      $results->range(0, 15);
      $results->orderBy('par.id_param','DESC');
      $res = $results->execute()->fetchAll();
      $ret = [];
    }
    else
    {
      $query = \Drupal::database()->select('par', 'par');
      
      $query->fields('par');
      $query->range($opt*15, 15);
      $query->orderBy('par.id_param','DESC');
      $res = $query->execute()->fetchAll();
      $ret = [];
    }

    foreach ($res as $row) 
    {
      $edit = Url::fromUserInput('/ajax/wa_api_manager/apis/edit/' . $row->id_param);
      $delete = Url::fromUserInput('/del/wa_api_manager/apis/delete/' . $row->id_param,array('attributes' => array('onclick' => "return ((confirm('¿Está seguro?')) ? true : false)")));
        
      $edit_link = Link::fromTextAndUrl(t('Edit'), $edit);
      $delete_link = Link::fromTextAndUrl(t('Delete'), $delete);
      $edit_link = $edit_link->toRenderable();
      $delete_link  = $delete_link->toRenderable();
      $edit_link['#attributes'] = ['class'=>'use-ajax'];
      $delete_link['#attributes'] = ['class'=>'use-ajax'];
	 
       
      $mainLink = t('@linkApprove  @linkReject', array('@linkApprove' => $edit_link, '@linkReject' => $delete_link));
      
	  
      $ret[] = [
        'id_param' => $row->id_param,
        'param' => $row->param,
		    'value' => $row->value,
        'opt' => render($delete_link),
		    'opt1' => render($edit_link),
      ];
    }
    return $ret;
  }
	
}