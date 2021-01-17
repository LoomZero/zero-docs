<?php

namespace Drupal\zero_proposal\Controller;

use Drupal\zero_entitywrapper\Content\ContentWrapper;
use Drupal\zero_entitywrapper\Wrapper\EntityWrapper;
use Drupal\zero_theme\Controller\APIControllerBase;

class ZeroProposalController extends APIControllerBase {

  public function vote() {
    $params = $this->params([
      'id',
      'name',
      'option',
      'comment' => '',
    ]);

    $proposal = ContentWrapper::load('node', $params['id']);
    $votes = [];
    foreach ($proposal->getEntities('field_proposal_votes') as $vote) {
      $votes[strtolower($vote->getValue('field_title'))] = [
        'user' => $vote->getValue('field_title'),
        'option' => $vote->getValue('field_key'),
        'comment' => $vote->getValue('field_comment'),
        'id' => $vote->id(),
        'entity' => $vote,
      ];
    }

    if (isset($votes[strtolower($params['name'])])) {
      $vote = $votes[strtolower($params['name'])];
      /** @var ContentWrapper $wrapper */
      $wrapper = $vote['entity'];
      $entity = $wrapper->entity();
      $entity->set('field_key', $params['option']);
      $entity->set('field_comment', $params['comment']);
      $entity->save();
      return $this->response([
        'text' => 'Oh! Du hast deine Meinung geändert? OK! Danke ' . $params['name'],
      ]);
    } else {
      $vote = EntityWrapper::createNew('paragraph', 'vote', [
        'field_title' => $params['name'],
        'field_key' => $params['option'],
        'field_comment' => $params['comment'],
      ]);
      $vote->entity()->save();
      $proposal->entity()->get('field_proposal_votes')->appendItem($vote->entity());
      $proposal->entity()->save();

      return $this->response([
        'text' => 'Danke ' . $params['name'],
      ]);
    }
  }

  public function chartdata() {
    $params = $this->params([
      'id',
      'key' => NULL,
    ]);

    $items = [];

    $wrapper = ContentWrapper::load('node', $params['id']);

    foreach ($wrapper->getEntities('field_proposal_votes') as $vote) {
      if ($vote->getValue('field_key') === $params['key'] || $params['key'] === NULL) {
        $items[] = [
          'name' => $vote->getValue('field_title'),
          'comment' => $vote->getValue('field_comment'),
          'key' => $vote->getValue('field_key'),
        ];
      }
    }

    $output = '';
    foreach ($items as $item) {
      $output .= $this->render($wrapper->view()->component('media/chart-vote/chart-vote.twig', $item, NULL, '/themes/zero/gulp/src/components'))->__toString();
    }

    return $this->response([
      'output' => $output,
    ]);
  }

}
