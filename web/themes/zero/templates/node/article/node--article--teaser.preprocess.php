<?php

/**
 * @var \Drupal\zero_entitywrapper\Content\ContentWrapper $wrapper
 */

$vars['body'] = $wrapper->view()->body('body');
$vars['items'] = $wrapper->view()->entities('field_items');
