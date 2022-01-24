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
use Drupal\Core\Link;

/**
 * Provides the form for adding countries.
 */
class CountryForm extends FormBase 
{

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dn_country_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$record = NULL) 
  {
   
    $form['nombrePais'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre completo del país'),
      '#maxlength' => 50,
	    '#attributes' => array(
       'class' => ['txt-class'],
       ),
      '#default_value' =>'',
	    '#prefix' => '<div id="div-nombrePais">',
      '#suffix' => '</div><div id="div-nombrePais-message"></div>',
    ];

	  $form['codPais'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Código de País'),
      '#maxlength' => 10,
	    '#attributes' => array(
       'class' => ['txt-class'],
       ),
      '#default_value' => '',
      '#prefix' => '<div id="div-codPais">',
      '#suffix' => '</div><div id="div-codPais-message"></div>',
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
	 $render_array['#attached']['library'][] = 'wa_country_manager/global_styles';
    return $form;

  }
  
   /**
   * {@inheritdoc}
   */
  public function validateForm(array & $form, FormStateInterface $form_state) {
        
		
  }

 
  public function clearForm(array &$form, FormStateInterface $form_state) 
  {
    $response = new AjaxResponse();
    $response->addCommand(new InvokeCommand('.txt-class', 'val', ['']));
    $response->addCommand(new InvokeCommand('#edit-nombrePais','removeAttr',['style']));
    $response->addCommand(new HtmlCommand('#div-nombrePais-message', ''));
    $response->addCommand(new InvokeCommand('#edit-codPais','removeAttr',['style']));
    $response->addCommand(new HtmlCommand('#div-codPais-message', ''));
    $response->addCommand(new InvokeCommand('.txt-class', 'val', ['']));
    
    return $response;
    
  }
   

  public function saveDataAjaxCallback(array &$form, FormStateInterface $form_state) 
  {
  
    $conn = Database::getConnection();

    $field = $form_state->getValues();

    $re_url = Url::fromRoute('wa_country_manager.country');
    
    $fields["nombrePais"] = $field['nombrePais'];
    $fields["codPais"] = $field['codPais'];
    $response = new AjaxResponse();

	
    if($fields["nombrePais"] == '')
    {
      $css = ['border' => '1px solid red'];
      $text_css = ['color' => 'red'];
      $message = ('¡El nombre del país es un valor requerido!');

      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-nombrePais', $css));
      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#div-nombrePais-message', $text_css));
      $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#div-nombrePais-message', $message));
      return $response;
    }
    else if($fields["codPais"] == '')
    {
      $css = ['border' => '1px solid red'];
      $text_css = ['color' => 'red'];
      $message = ('¡El código del país es un valor requerido!');

      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-codPais', $css));
      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#div-codPais-message', $text_css));
      $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#div-codPais-message', $message));
      return $response;
    }
    else
    {
      $conn->insert('paisespinches')
          ->fields($fields)->execute();
      
      $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
      
      $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_country_manager\Form\CountryTableForm','All');

      $response->addCommand(new HtmlCommand('.result_message','' ));
      $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
      $response->addCommand(new HtmlCommand('.pagination','' ));
      $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.pagination', getPager()));
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
  
