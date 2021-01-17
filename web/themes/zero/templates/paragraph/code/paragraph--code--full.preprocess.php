<?php

/**
 * @var \Drupal\zero_entitywrapper\Content\ContentWrapper $wrapper
 */

$vars['title'] = $wrapper->getValue('field_title');
$vars['code'] = $wrapper->view()->body('field_code');
$vars['code_type'] = $wrapper->getValue('field_code_type');
$vars['code_type_name'] = $wrapper->getListValue('field_code_type');
$vars['description'] = $wrapper->view()->body('field_description');
$vars['body'] = $wrapper->view()->body('field_subtext');
