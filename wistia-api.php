<?php
/**
 * Plugin Name: Wistia API Integration
 * Plugin URI:  https://github.com/asmbs/wp-wistia-api
 * Version:     1.0.0-alpha
 * Description: Integrates Wistia's developer APIs and provides scaffolding for video post types.
 * Author:      The A-TEAM
 * Author URI:  https://github.com/asmbs
 */

namespace Wistia;


/**
 * Plugin wrapper.
 *
 */
class PluginCore
{
  /**
   * @var  Admin\SettingsManager  Plugin settings manager.
   */
  protected $settingsManager;

  // -------------------------------------------------------------------------------------------

  /**
   * Constructor; loads the rest of the plugin.
   */
  public function __construct()
  {
    include_once 'admin/settings-manager.php';
    include_once 'admin/settings-renderer.php';

    $this->init();
  }

  /**
   * Initialize plugin components.
   */
  private function init()
  {
    $this->settingsManager = new Admin\SettingsManager();
  }
}

new PluginCore();
