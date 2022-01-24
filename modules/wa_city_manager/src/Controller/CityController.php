<?php
namespace Drupal\wa_city_manager\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Routing;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\FormBuilder;

/**
 * Class ManageCityController.
 *
 * @package Drupal\wa_city_manager\Controller
 */
class CityController extends ControllerBase 
{

/**
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * The CityController constructor.
   *
   * @param \Drupal\Core\Form\FormBuilder $formBuilder
   *   The form builder.
   */
  public function __construct(FormBuilder $formBuilder) 
  {
    $this->formBuilder = $formBuilder;
  }
  
  /**
   * {@inheritdoc}
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The Drupal service container.
   *
   * @return static
   */

  public static function create(ContainerInterface $container) 
  {
    return new static(
      $container->get('form_builder')
    );
  }
  
  /**
   * {@inheritdoc}
   */
  public function manageCities() 
  {
    $form['form'] = $this->formBuilder()->getForm('Drupal\wa_city_manager\Form\CityForm');
    $render_array = $this->formBuilder()->getForm('Drupal\wa_city_manager\Form\CityTableForm','All');
	  $form['form1'] = $render_array;
	  $form['form']['#suffix'] = '<div class="pagination">'.getPagerCity().'</div>';
    
    return $form;
  }

  /**
   * {@inheritdoc}
   * Elimina la ciudad seleccionada.
   */
  public function deleteCityAjax($cid) 
  {
    $res = \Drupal::database()->query("DELETE FROM ciudadespinches where idCiudad = :idCiudad", array(':idCiudad' => $cid)); 
	  $render_array = $this->formBuilder->getForm('Drupal\wa_city_manager\Form\CityTableForm','All');
	  $response = new AjaxResponse();
	  $response->addCommand(new HtmlCommand('.result_message','' ));
	  $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
	  $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link', 'removeClass', array('active')));
	  $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link:first', 'addClass', array('active')));
	   
    return $response;
  }
  
   /**
   * {@inheritdoc}
   * Actualiza los datos del país
   */
  public function editCityAjax($cid) 
  {
	  $conn = Database::getConnection();
    $query = $conn->select('ciudadespinches', 'ci');
    $query->condition('idCiudad', $cid)->fields('ci');
    $record = $query->execute()->fetchAssoc();
    
	  $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_city_manager\Form\CityEditForm',$record);
	 
	  $response = new AjaxResponse();
	  $response->addCommand(new OpenModalDialogCommand('Editar Ciudad', $render_array, ['width' => '800']));
	 
    return $response;
  }

  /**
   * {@inheritdoc}
   * Elimina el país.
   */
  
  public function tablePaginationAjax($no)
  {
	  $response = new AjaxResponse();
	  $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_city_manager\Form\CityTableForm',$no);
	  $response->addCommand(new HtmlCommand('.result_message','' ));
	  $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
		
	  return $response;
  }
  
}
