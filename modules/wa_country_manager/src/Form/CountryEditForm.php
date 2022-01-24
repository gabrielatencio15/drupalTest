<?php

namespace Drupal\wa_country_manager\Form;

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
 * Provee recurso para editar un país.
 */
class CountryEditForm extends FormBase 
{

  /**
   * {@inheritdoc}
   */
  public function getFormId() 
  {
    return 'dn_country_form_edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$record = NULL) 
  {
    $conn = Database::getConnection();
    
    $language = \Drupal::languageManager()->getLanguages();

    if(isset($record['idPais']))
    {
      $form['idPais'] = [
        '#type' => 'hidden',
        '#attributes' => array(
                                'class' => ['txt-class'],
                              ),
        '#default_value' => (isset($record['idPais'])) ? $record['idPais'] : '',
      ];
    }

    $form['nombrePais'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre del País'),
      '#required' => TRUE,
      '#maxlength' => 50,
      '#attributes' => array(
                            'class' => ['txt-class'],
                            ),
      '#default_value' => (isset($record['nombrePais'])) ? $record['nombrePais'] : '',
    ];

	 $form['codPais'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre abreviado del País'),
      '#required' => TRUE,
      '#maxlength' => 10,
	    '#attributes' => array(
                            'class' => ['txt-class'],
                            ),
      '#default_value' => (isset($record['codPais'])) ? $record['codPais'] : '', 
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
      '#ajax' => ['callback' => '::updateCountryData'] ,
      '#value' => (isset($record['nombrePais'])) ? $this->t('Update') : $this->t('Save') ,
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
 
  public function updateCountryData(array $form, FormStateInterface $form_state) 
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
	    $re_url = Url::fromRoute('wa_country_manager.country');
  
      $fields["nombrePais"] = $field['nombrePais'];
      $fields["codPais"] = $field['codPais'];
      $fields["activo"] = $field['activo'];
	
      $conn->update('tblistapaises')
           ->fields($fields)->condition('idPais', $field['idPais'])->execute();

      $response->addCommand(new OpenModalDialogCommand("Alerta", '¡Los datos han sido actualizados!', ['width' => 800]));
	    $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_country_manager\Form\CountryTableForm','All');
	
	
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
  
