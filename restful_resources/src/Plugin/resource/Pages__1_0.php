<?php

/**
 * @file
 * Contains \Drupal\restful_resources\Plugin\resource\Pages__1_0.
 */

namespace Drupal\restful_resources\Plugin\resource;
use Drupal\restful\Http\RequestInterface;
use Drupal\restful\Plugin\resource\ResourceInterface;
use Drupal\restful\Plugin\resource\ResourceNode;

/**
 * Class Pages__1_0
 * @package Drupal\restful_resources\Plugin\resource
 *
 * @Resource(
 *   name = "pages:1.0",
 *   resource = "pages",
 *   label = "Pages",
 *   description = "Export the pages with all authentication providers.",
 *   authenticationTypes = TRUE,
 *   authenticationOptional = TRUE,
 *   dataProvider = {
 *     "entityType": "node",
 *     "bundles": {
 *       "page"
 *     },
 *   },
 *   majorVersion = 1,
 *   minorVersion = 0
 * )
 */
class Pages__1_0 extends ResourceNode implements ResourceInterface {

  /**
   * {@inheritdoc}
   */
  protected function publicFields() {
    $public_fields = parent::publicFields();
    unset($public_fields['self']);
    $public_fields['nid'] = array(
      'wrapper_method' => 'getIdentifier',
      'wrapper_method_on_entity' => TRUE,
      'methods' => array(RequestInterface::METHOD_GET),
    );
    $public_fields['description'] = array('property' => 'body', 'sub_property' => 'value');

    return $public_fields;
  }

}
