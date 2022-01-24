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
use Drupal\Core\Link;

/**
 * Provides the form for adding cities.
 */
class CityForm extends FormBase 
{

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dn_city_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state,$record = NULL) 
  {
   
    $form['nombreCiudad'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre completo de la Ciudad'),
      '#maxlength' => 50,
	    '#attributes' => array(
       'class' => ['txt-class'],
       ),
      '#default_value' =>'',
	    '#prefix' => '<div id="div-nombreCiudad">',
      '#suffix' => '</div><div id="div-nombreCiudad-message"></div>',
    ];

	  $form['codCiudad'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre abreviado de la Ciudad (Minúsculas)'),
      '#maxlength' => 28,
	    '#attributes' => array(
       'class' => ['txt-class'],
       ),
      '#default_value' => '',
      '#prefix' => '<div id="div-codCiudad">',
      '#suffix' => '</div><div id="div-codCiudad-message"></div>',
    ];

    $form['idPais'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ID del País al que pertenece la Ciudad'),
      '#maxlength' => 5,
	    '#attributes' => array(
       'class' => ['txt-class'],
       ),
      '#default_value' => '',
      '#prefix' => '<div id="div-idPais">',
      '#suffix' => '</div><div id="div-idPais-message"></div>',
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
	 $render_array['#attached']['library'][] = 'wa_city_manager/global_styles';
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
    $response->addCommand(new InvokeCommand('#edit-nombreCiudad','removeAttr',['style']));
    $response->addCommand(new HtmlCommand('#div-nombreCiudad-message', ''));
    $response->addCommand(new InvokeCommand('#edit-codCiudad','removeAttr',['style']));
    $response->addCommand(new HtmlCommand('#div-codCiudad-message', ''));
    $response->addCommand(new InvokeCommand('#edit-idPais','removeAttr',['style']));
    $response->addCommand(new HtmlCommand('#div-idPais-message', ''));
    $response->addCommand(new InvokeCommand('.txt-class', 'val', ['']));
    
    return $response;
    
  }
   

  public function saveDataAjaxCallback(array &$form, FormStateInterface $form_state) 
  {
  
    $conn = Database::getConnection();

    $field = $form_state->getValues();

    $re_url = Url::fromRoute('wa_city_manager.city');
    
    $fields["nombreCiudad"] = $field['nombreCiudad'];
    $fields["codCiudad"] = $field['codCiudad'];
    $fields["idPais"] = $field['idPais'];

    $response = new AjaxResponse();

	
    if($fields["nombreCiudad"] == '')
    {
      $css = ['border' => '1px solid red'];
      $text_css = ['color' => 'red'];
      $message = ('¡El nombre de la Ciudad es un valor requerido!');

      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-nombreCiudad', $css));
      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#div-nombreCiudad-message', $text_css));
      $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#div-nombreCiudad-message', $message));
      return $response;
    }
    else if($fields["codCiudad"] == '')
    {
      $css = ['border' => '1px solid red'];
      $text_css = ['color' => 'red'];
      $message = ('¡El código de la Ciudad es un valor requerido!');

      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-codCiudad', $css));
      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#div-codCiudad-message', $text_css));
      $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#div-codCiudad-message', $message));
      return $response;
    }
    else if($fields["idPais"] == '')
    {
      $css = ['border' => '1px solid red'];
      $text_css = ['color' => 'red'];
      $message = ('¡El ID del País al que pertenece la Ciudad es un valor requerido!');

      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#edit-idPais', $css));
      $response->addCommand(new \Drupal\Core\Ajax\CssCommand('#div-idPais-message', $text_css));
      $response->addCommand(new \Drupal\Core\Ajax\HtmlCommand('#div-idPais-message', $message));
      return $response;
    }
    else
    {
      $conn->insert('ciudadespinches')
          ->fields($fields)->execute();
      
      $dialogText['#attached']['library'][] = 'core/drupal.dialog.ajax';
      
      $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_city_manager\Form\CityTableForm','All');

      $response->addCommand(new HtmlCommand('.result_message','' ));
      $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
      $response->addCommand(new HtmlCommand('.pagination','' ));
      $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.pagination', getPagerCity()));
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
  
