<?php

namespace Drupal\vppn;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;

/**
 * Class vppnHandler.
 */
class vppnHandler {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;
  /**
   * Constructs a new vppnHandler object.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function getDefaultsForNode($form){
    return [];
  }

  public function checkIfContentTypeEnabled(){
    if(!\Drupal::currentUser()->hasPermission('use vppn')){
      return FALSE;
    }
    /** @var \Drupal\node\Entity\NodeType $nodeType */
    $routeName = \Drupal::routeMatch()->getRouteName();
    if($routeName == 'node.add') {
      $nodeType = \Drupal::routeMatch()->getParameters()->get('node_type');
      $nodeType = $nodeType->get('type');
      $config = \Drupal::config('vppn.vppnconfig')->get('vppn_node_list');
    } elseif ($routeName == 'entity.node.edit_form'){
      /** @var NodeInterface $nodeType */
      $nodeType = \Drupal::routeMatch()->getParameters()->get('node');
      $nodeType = $nodeType->getType();
      $config = \Drupal::config('vppn.vppnconfig')->get('vppn_node_list');
    }
    if(is_null($config)){
      return FALSE;
    }
    return in_array($nodeType,$config,TRUE);
  }

  public function cleanEntriesByEntityId($id){
    //pulisce tutte le tuple relative alla tabella
    $query = \Drupal::database()->delete('vppn');
    $query->condition('nid', $id);
    $count = $query->execute();
    return $count > 0 ? TRUE : FALSE;
  }

  public function insertRoleEntry($roleName,$value){
    //aggiorna
  }

}
