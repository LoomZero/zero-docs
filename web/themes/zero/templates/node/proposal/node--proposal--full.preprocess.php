<?php

/**
 * @var \Drupal\zero_entitywrapper\Content\ContentWrapper $wrapper
 */

use Drupal\zero_theme\Media\Chart;

$vars['title'] = $wrapper->getValue('title');
$vars['body'] = $wrapper->view()->body('body');

$form_options = [];
$votes = [];
$vars['options'] = [];
foreach ($wrapper->getEntities('field_proposal_options') as $option) {
  $vars['options'][$option->id()] = [
    'title' => $option->getValue('field_option'),
    'description' => $option->view()->entities('field_items'),
  ];
  $votes[$option->getValue('field_key')] = [
    'label' => $option->getValue('field_option'),
    'layout' => $option->getValue('field_layout_color'),
    'number' => 0,
  ];

  $form_options[$option->getValue('field_key')] = $option->getValue('field_option');
}


foreach ($wrapper->getEntities('field_proposal_votes') as $vote) {
  if (isset($votes[$vote->getValue('field_key')])) {
    $votes[$vote->getValue('field_key')]['number']++;
  }
}

$chart = new Chart($wrapper->getValue('title'));
foreach ($votes as $key => $vote) {
  $chart->addValue('"' . $vote['label'] . '" [' . $vote['number'] . ']', $vote['number'], ['id' => $wrapper->id(), 'key' => $key], $vote['layout']);
}
$vars['chart'] = $chart->render($wrapper);

$vars['vote_form'] = [
  '#theme' => 'zero_propose_vote_form',
  '#id' => $wrapper->id(),
  '#options' => $form_options,
];