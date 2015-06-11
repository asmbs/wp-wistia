<?php

namespace Wistia\Admin;


/**
 * Plugin settings renderer; built to separate rendering and setting I/O concerns.
 *
 */
class SettingsRenderer
{
  /**
   * @var  SettingsManager
   */
  protected $manager;

  // -------------------------------------------------------------------------------------------

  /**
   * Constructor; initialize the renderer and link it to the settings manager.
   */
  public function __construct(SettingsManager $manager)
  {
    $this->manager = $manager;
  }

  // -------------------------------------------------------------------------------------------
  
  /**
   * Render the 'API Settings' section description.
   */
  public function renderAPISettingsSection()
  {
    _e('Enter your account URL prefix and your upload API key so WordPress can connect to '
      .'your account through the Upload API.');
  }

  // -------------------------------------------------------------------------------------------

  /**
   * Render a text field.
   */
  public function renderTextField($args = [])
  {
    $fieldValue = $this->manager->getSetting($args['key']);

    if (isset($args['before'])) {
      echo $args['before'] .' ';
    }

    printf(
      '<input type="text" id="%1$s" name="%2$s" value="%3$s" class="%4$s">',
      $args['id'],
      $args['name'],
      $fieldValue,
      isset($args['classes']) ? $args['classes'] : ''
    );

    if (isset($args['after'])) {
      echo $args['after'] .' ';
    }

    if (isset($args['description'])) {
      printf('<p class="description">%s</p>', $args['description']);
    }
  }

  // -------------------------------------------------------------------------------------------

  /**
   * Render the settings page
   */
  public function renderSettingsPage()
  {
    if (!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once 'templates/settings.php';
  }
}
