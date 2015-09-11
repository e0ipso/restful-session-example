<?php

/**
 * @file
 * Contains \Drupal\restful_resources\Plugin\resource\Images__1_0.
 */

namespace Drupal\restful_resources\Plugin\resource;

use Drupal\restful\Http\RequestInterface;
use Drupal\restful\Plugin\resource\DataInterpreter\DataInterpreterInterface;
use Drupal\restful\Plugin\resource\ResourceEntity;
use Drupal\restful\Plugin\resource\ResourceInterface;

/**
 * Class Images__1_0
 * @package Drupal\restful_resources\Plugin\resource
 *
 * @Resource(
 *   name = "images:1.0",
 *   resource = "images",
 *   label = "Images",
 *   description = "Export the images and image styles.",
 *   authenticationTypes = TRUE,
 *   authenticationOptional = TRUE,
 *   dataProvider = {
 *     "entityType": "file",
 *     "bundles": {
 *       "image"
 *     },
 *     "idField": "uuid",
 *   },
 *   majorVersion = 1,
 *   minorVersion = 0
 * )
 */
class Images__1_0 extends ResourceEntity implements ResourceInterface {

  /**
   * Flag indicating if the derivatives should output absolute URLs.
   *
   * @var bool
   */
  protected $isAbsolute = TRUE;

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
    $public_fields['url'] = array('property' => 'url');

    $image_styles = array('thumbnail', 'medium', 'large');
    $public_fields['styles'] = array(
      'callback' => array(
        array($this, 'generateStyleUris'),
        array($image_styles),
      ),
    );

    return $public_fields;
  }

  /**
   * Process callback to generate the image styles for the current file.
   *
   * @param DataInterpreterInterface $interpreter
   *   The data interpreter for the current article entity.
   * @param string[] $image_styles
   *   Array of image style names.
   *
   * @return array
   *   A list of URLs for this images' image styles.
   */
  public function generateStyleUris(DataInterpreterInterface $interpreter, array $image_styles) {
    // Call image_style_url with the retrieved $value for each $image_style.
    $uri = $interpreter->getWrapper()->value()->uri;
    return array_map(function ($image_style) use ($uri) {
      return url(image_style_url($image_style, $uri), array(
        'absolute' => $this->isAbsolute,
      ));
    }, $image_styles);
  }

}
