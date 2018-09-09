<?php

namespace Drupal\youtube_channel\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\vendor\guzzlehttp\guzzle\src\Exception;
use Drupal\Core\Link;

/**
* Defines a twitter block block type.
 *
 * @Block(
 *   id = "youtube_channel_block",
 *   admin_label = @Translation("Youtube Channel Block"),
 *   category = @Translation("Youtube"),
 * )
 */
class YoutubeChennelBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    $config = $this->getConfiguration();

    $form['youtube_channel'] = [
      '#type' => 'fieldset',
      '#title' => t('Youtube channel settings'),
    ];

    $form['youtube_channel']['youtube_api_key'] = [
      '#type' => 'textfield',
      '#title' => t('Youtube Google API Key'),
      '#size' => 40,
      '#default_value' => $config['youtube_api_key'],
      '#required' => TRUE,
    ];

    $form['youtube_channel']['youtube_id'] = [
      '#type' => 'textfield',
      '#title' => t('Youtube Channel ID'),
      '#size' => 40,
      '#default_value' => $config['youtube_id'],
      '#required' => TRUE,
    ];

    $form['youtube_channel']['youtube_video_limit'] = [
      '#type' => 'textfield',
      '#title' => t('Youtube Channel video limit'),
      '#size' => 40,
      '#default_value' => $config['youtube_video_limit'],
      '#required' => TRUE,
    ];

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    foreach (['youtube_channel'] as $fieldset) {
      $fieldset_values = $form_state->getValue($fieldset);
      foreach ($fieldset_values as $key => $value) {
        $this->setConfigurationValue($key, $value);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = $this->getConfiguration();

    $baseUrl = 'https://www.googleapis.com/youtube/v3/';
    // https://developers.google.com/youtube/v3/getting-started
    $apiKey = $config['youtube_api_key'];
    // If you don't know the channel ID see below
    $channelId = $config['youtube_id'];

    $params = [
      'id'=> $channelId,
      'part'=> 'contentDetails',
      'key'=> $apiKey
    ];
    $url = $baseUrl . 'channels?' . http_build_query($params);
    $json = json_decode(file_get_contents($url), true);

    $playlist = $json['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

    $params = [
      'part'=> 'snippet',
      'playlistId' => $playlist,
      'maxResults'=> $config['youtube_video_limit'],
      'key'=> $apiKey
    ];
    $url = $baseUrl . 'playlistItems?' . http_build_query($params);
    $json = json_decode(file_get_contents($url), true);

    $youtubes=[];

    foreach($json['items'] as $key => $value) {
      $youtube_id = $value['snippet']['resourceId']['videoId'];
      $videos[$youtube_id] = $value['snippet']['thumbnails']['default']['url'];
    }


    $youtubes['content'] = $videos;

    $build[] = [
      '#theme' => 'youtube_channel',
      '#youtubes' => $youtubes,
    ];

    $build['#attached']['library'][] = 'youtube_channel/youtube_channel';

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}