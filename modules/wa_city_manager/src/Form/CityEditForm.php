<?php

namespace Drupal\wa_city_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\FormState;

/**
 * Provee recurso para editar una ciudad.
 */
class CityEditForm extends FormBase 
{

  /**
   * {@inheritdoc}
   */
  public function getFormId() 
  {
    return 'dn_city_form_edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$record = NULL) 
  {
    $conn = Database::getConnection();
    
    $language = \Drupal::languageManager()->getLanguages();

    if(isset($record['idCiudad']))
    {
      $form['idCiudad'] = [
        '#type' => 'hidden',
        '#attributes' => array(
                                'class' => ['txt-class'],
                              ),
        '#default_value' => (isset($record['idCiudad'])) ? $record['idCiudad'] : '',
      ];
    }

    $form['nombreCiudad'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre de la Ciudad'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#attributes' => array(
                            'class' => ['txt-class'],
                            ),
      '#default_value' => (isset($record['nombreCiudad'])) ? $record['nombreCiudad'] : '',
    ];

	 $form['codCiudad'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre abreviado de la Ciudad'),
      '#required' => TRUE,
      '#maxlength' => 28,
	    '#attributes' => array(
                            'class' => ['txt-class'],
                            ),
      '#default_value' => (isset($record['codCiudad'])) ? $record['codCiudad'] : '', 
    ];

  $form['idPais'] = [
    '#type' => 'textfield',
    '#title' => $this->t('ID del País al que pertenece la Ciudad'),
    '#required' => TRUE,
    '#maxlength' => 5,
    '#attributes' => array(
                          'class' => ['txt-class'],
                          ),
    '#default_value' => (isset($record['idPais'])) ? $record['idPais'] : '', 
  ];

	$form['activo'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Activo'),
      '#required' => TRUE,
      '#maxlength' => 1,
	    '#attributes' => array(
                            'class' => ['txt-class'],
                            ),
      '#default_value' => (isset($record['activo'])) ? $record['activo'] : '',
    ];
	
    $form['actions']['#type'] = 'actions';

    $form['actions']['Save'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#attributes' => [
          'class' => [
                        'use-ajax',
                    ],
                                ],
      '#ajax' => ['callback' => '::updateCityData'] ,
      '#value' => (isset($record['nombreCiudad'])) ? $this->t('Update') : $this->t('Save') ,
      ];
	
	  $form['#prefix'] = '<div class="form-div-edit" id="form-div-edit">';

	  $form['#suffix'] = '</div>';
	
	  $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $form;

  }
  
   /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) 
  {
		
  }
 
  public function updateCityData(array $form, FormStateInterface $form_state) 
  {
    $response = new AjaxResponse();

    // Si hay algún error, rerender el formulario.
    if($form_state->hasAnyErrors()) 
    {
      $response->addCommand(new ReplaceCommand('#form-div-edit', $form));
    }
    else 
    {
		  $conn = Database::getConnection();
	    $field = $form_state->getValues();
	    $re_url = Url::fromRoute('wa_city_manager.city');
  
      $fields["nombreCiudad"] = $field['nombreCiudad'];
      $fields["codCiudad"] = $field['codCiudad'];
      $fields["idPais"] = $field['idPais'];
      $fields["activo"] = $field['activo'];
	
      $conn->update('ciudadespinches')
           ->fields($fields)->condition('idCiudad', $field['idCiudad'])->execute();

      $response->addCommand(new OpenModalDialogCommand("Alerta", '¡Los datos han sido actualizados!', ['width' => 800]));
	    $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_city_manager\Form\CityTableForm','All');
	
	
	    $response->addCommand(new HtmlCommand('.result_message','' ));
	    $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
	    $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link', 'removeClass', array('active')));
	    $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link:first', 'addClass', array('active')));
	 
    }

    return $response;
  }
  
  
  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) 
  {
	
  }

}
  
