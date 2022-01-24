<?php

namespace Drupal\wa_api_manager\Form;

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
use Drupal\Core\Link;

/**
 * Provides the form for adding params.
 */
class ApiForm extends FormBase 
{

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dn_api_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$record = NULL) 
  {
   
    $form['param'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Parámetro'),
      '#maxlength' => 50,
	    '#attributes' => array(
       'class' => ['txt-class'],
       ),
      '#default_value' =>'',
	    '#prefix' => '<div id="div-param">',
      '#suffix' => '</div><div id="div-param-message"></div>',
    ];

	  $form['value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Valor del Parámetro'),
      '#maxlength' => 28,
	    '#attributes' => array(
       'class' => ['txt-class'],
       ),
      '#default_value' => '',
      '#prefix' => '<div id="div-value">',
      '#suffix' => '</div><div id="div-value-message"></div>',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['Save'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
	    '#ajax' => ['callback' => '::saveDataAjaxCallback'] ,
      '#value' => $this->t('Save') ,
    ];
	 $form['actions']['clear'] = [
      '#type' => 'submit',
      '#ajax' => ['callback' => '::clearForm','wrapper' => 'form-div'] ,
      '#value' => 'Clear',
     ];
	 $render_array['#attached']['library'][] = 'wa_api_manager/global_styles';
    return $form;

  }
  
   /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) 
  {
        	
  }

 
  public function clearForm(array &$form, FormStateInterface $form_state) 
  {
    $response = new AjaxResponse();
    $response->addCommand(new InvokeCommand('.txt-class', 'val', ['']));
    $response->addCommand(new InvokeCommand('#edit-param','removeAttr',['style']));
    $response->addCommand(new HtmlCommand('#div-param-message', ''));
    $response->addCommand(new InvokeCommand('#edit-value','removeAttr',['style']));
    $response->addCommand(new HtmlCommand('#div-value-message', ''));
    $response->addCommand(new InvokeCommand('.txt-class', 'val', ['']));
    
    return $response;
    
  }
   

  public function saveDataAjaxCallback(array &$form, FormStateInterface $form_state) 
  {
  
    $conn = Database::getConnection();

    $field = $form_state->getValues();

    $re_url = Url::fromRoute('wa_api_manager.api');
    
    $fields["param"] = $field['param'];
    $fields["value"] = $field['value'];

    $response = new AjaxResponse();

	
    if($fields["param"] == '')
    {
      $css = ['border' => '1px solid red'];
      $text_css = ['color' => 'red'];
      $message = ('¡El Parámetro es un valor requerido!');

      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-param', $css));
      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#div-param-message', $text_css));
      $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#div-nombreCiudad-message', $message));
      return $response;
    }
    else if($fields["value"] == '')
    {
      $css = ['border' => '1px solid red'];
      $text_css = ['color' => 'red'];
      $message = ('¡El valor es requerido!');

      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-value', $css));
      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#div-value-message', $text_css));
      $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#div-value-message', $message));
      return $response;
    }
    else
    {
      $conn->insert('tbParametros')
          ->fields($fields)->execute();
      
      $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
      
      $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_api_manager\Form\ApiTableForm','All');

      $response->addCommand(new HtmlCommand('.result_message','' ));
      $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
      $response->addCommand(new HtmlCommand('.pagination','' ));
      $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.pagination', getPagerApi()));
      $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link', 'removeClass', array('active')));
      $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link:first', 'addClass', array('active')));
      $response->addCommand(new InvokeCommand('.txt-class', 'val', ['']));
    
      return $response;
    }
   
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array & $form, FormStateInterface $form_state) 
  {

  }

}
  
