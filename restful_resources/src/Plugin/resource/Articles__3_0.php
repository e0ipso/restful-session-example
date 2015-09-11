<?php

/**
 * @file
 * Contains \Drupal\restful_resources\Plugin\resource\Articles__3_0.
 */

namespace Drupal\restful_resources\Plugin\resource;

use Drupal\restful\Http\RequestInterface;
use Drupal\restful\Plugin\resource\DataInterpreter\DataInterpreterInterface;
use Drupal\restful\Plugin\resource\ResourceInterface;
use Drupal\restful\Plugin\resource\ResourceNode;

/**
 * Class Articles__3_0
 * @package Drupal\restful_resources\Plugin\resource
 *
 * @Resource(
 *   name = "articles:3.0",
 *   resource = "articles",
 *   label = "Articles",
 *   description = "Export the articles with all authentication providers.",
 *   authenticationTypes = TRUE,
 *   authenticationOptional = TRUE,
 *   dataProvider = {
 *     "entityType": "node",
 *     "bundles": {
 *       "article"
 *     },
 *     "idField": "uuid",
 *   },
 *   majorVersion = 3,
 *   minorVersion = 0
 * )
 */
class Articles__3_0 extends ResourceNode implements ResourceInterface {

  /**
   * {@inheritdoc}
   */
  protected function publicFields() {
    $public_fields = parent::publicFields();
    unset($public_fields['self']);
    $public_fields['uuid'] = array(
      'property' => 'uuid',
      'methods' => array(RequestInterface::METHOD_GET),
    );
    $public_fields['tags'] = array(
      'property' => 'field_tags',
      'referencedIdProperty' => 'name',
    );
    $public_fields['body'] = array('property' => 'body', 'sub_property' => 'value');
    $public_fields['related'] = array(
      'property' => 'field_related_content',
      'referencedIdProperty' => 'uuid',
      'resource' => array(
        'name' => 'articles',
        'majorVersion' => 3,
        'minorVersion' => 0,
      ),
    );
    $public_fields['heroImage'] = array(
      'property' => 'field_image',
      'referencedIdProperty' => 'uuid',
      'resource' => array(
        'name' => 'images',
        'majorVersion' => 1,
        'minorVersion' => 0,
      ),
    );
    $public_fields['associatedPage'] = array(
      'property' => 'field_associated_page',
      'resource' => array(
        'name' => 'pages',
        'resource' => array(
          'name' => 'pages',
          'majorVersion' => 1,
          'minorVersion' => 0,
        ),
      ),
    );
    $public_fields['slug'] = array(
      'methods' => array(RequestInterface::METHOD_GET, RequestInterface::METHOD_OPTIONS),
      'callback' => array($this, 'getUrlAlias'),
      'discovery' => array(
        'info' => array(
          'label' => 'Slug',
          'description' => 'URL alias according to pathauto.',
        ),
        'data' => array(
          'type' => 'string',
          'required' => FALSE,
          'read_only' => TRUE,
          'cardinality' => 1,
          'size' => 255,
        ),
        // Since this field is read only, do not expose a form element for it.
        'form_element' => array(),
      ),
    );

    return $public_fields;
  }

  /**
   * Custom callback to get the URL alias from an article.
   *
   * @param DataInterpreterInterface $interpreter
   *   The data interpreter for the current article entity.
   *
   * @return string
   *   The URL alias for the article.
   */
  public function getUrlAlias(DataInterpreterInterface $interpreter) {
    /** @var \EntityDrupalWrapper $wrapper */
    $wrapper = $interpreter->getWrapper();
    $path = sprintf('node/%d', $wrapper->getIdentifier());
    return drupal_get_path_alias($path);
  }
}
