<?php
namespace Drupal\wa_api_manager\Controller;
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
 * Class ManageApiController.
 *
 * @package Drupal\wa_api_manager\Controller
 */
class ApiController extends ControllerBase 
{

/**
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * The ApiController constructor.
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
  public function manageApis() 
  {
    $form['form'] = $this->formBuilder()->getForm('Drupal\wa_api_manager\Form\ApiForm');
    $render_array = $this->formBuilder()->getForm('Drupal\wa_api_manager\Form\ApiTableForm','All');
	  $form['form1'] = $render_array;
	  $form['form']['#suffix'] = '<div class="pagination">'.getPagerApi().'</div>';
    
    return $form;
  }

  /**
   * {@inheritdoc}
   * Elimina el Parámetro seleccionado.
   */
  public function deleteApiAjax($cid) 
  {
    $res = \Drupal::database()->query("DELETE FROM parametrospinches where id_param = :id_param", array(':id_param' => $cid)); 
	  $render_array = $this->formBuilder->getForm('Drupal\wa_api_manager\Form\ApiTableForm','All');
	  $response = new AjaxResponse();
	  $response->addCommand(new HtmlCommand('.result_message','' ));
	  $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
	  $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link', 'removeClass', array('active')));
	  $response->addCommand(new \Drupal\Core\Ajax\InvokeCommand('.pagination-link:first', 'addClass', array('active')));
	   
    return $response;
  }
  
   /**
   * {@inheritdoc}
   * Actualiza los datos del Parámetro
   */
  public function editApiAjax($cid) 
  {
	  $conn = Database::getConnection();
    $query = $conn->select('parametrospinches', 'pa');
    $query->condition('id_param', $cid)->fields('pa');
    $record = $query->execute()->fetchAssoc();
    
	  $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_api_manager\Form\ApiEditForm',$record);
	 
	  $response = new AjaxResponse();
	  $response->addCommand(new OpenModalDialogCommand('Editar Parámetro', $render_array, ['width' => '800']));
	 
    return $response;
  }

  /**
   * {@inheritdoc}
   * Elimina el Parámetro.
   */
  
  public function tablePaginationAjax($no)
  {
	  $response = new AjaxResponse();
	  $render_array = \Drupal::formBuilder()->getForm('Drupal\wa_api_manager\Form\ApiTableForm',$no);
	  $response->addCommand(new HtmlCommand('.result_message','' ));
	  $response->addCommand(new \Drupal\Core\Ajax\AppendCommand('.result_message', $render_array));
		
	  return $response;
  }
  
}
