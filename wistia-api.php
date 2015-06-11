<?php
/**
 * Plugin Name: Wistia API Integration
 * Plugin URI:  https://github.com/asmbs/wp-wistia-api
 * Version:     1.0.0
 * Description: Integrates Wistia's developer APIs and provides scaffolding for video post types.
 * Author:      The A-TEAM
 * Author URI:  https://github.com/asmbs
 */

namespace Wistia {

/**
 * Plugin wrapper.
 *
 */
class PluginCore
{
  /**
   * Plugin version.
   */
  const VERSION = '1.0.0';

  /**
   * @var  array  Service container.
   */
  protected $services = [];

  // -------------------------------------------------------------------------------------------

  /**
   * Constructor; loads the rest of the plugin.
   */
  public function __construct()
  {
    include_once 'admin/settings-manager.php';
    include_once 'admin/settings-renderer.php';
    include_once 'api/upload.php';

    $this->init();
  }

  /**
   * Initialize plugin components.
   */
  private function init()
  {
    $this->services['settings'] = new Admin\SettingsManager($this);
    $this->services['api.upload'] = new API\UploadIntegration($this);
  }

  // -------------------------------------------------------------------------------------------

  /**
   * Get a reference to a plugin service.
   *
   * @param   string  $key  The service key.
   * @return  mixed|null    A reference to the corresponding object, or null if one isn't
   *                        defined.
   */
  public function getService($key)
  {
    if (array_key_exists($key, $this->services)) {
      return $this->services[$key];
    }

    return null;
  }

  /**
   * Generate a URI/URL to a plugin resource.
   *
   * @param   string  $path  The optional path to a resource; if left empty, the base URI will
   *                         be returned.
   * @return  string         The resource URI.
   */
  public function getUri($path = '')
  {
    // Remove leading slash if it was included
    if ($path[0] == '/') $path = substr($path, 1);

    return plugin_dir_url(__FILE__) . $path;
  }

  /**
   * Generate an absolute path to a plugin resource.
   *
   * @param   string  $path  The optional path to a resource; if left empty, the base path will
   *                         be returned.
   * @return  string         The resource path.
   */
  public function getPath($path = '')
  {
    // Remove leading slash if it was included
    if ($path[0] == '/') $path = substr($path, 1);
    
    return plugin_dir_path(__FILE__) . $path;
  }
}

} // End Wistia namespace
namespace {

/**
 * Initialize or retrieve the instance of this plugin, or one of its services.
 *
 * @param   string  $service  An optional service key. If a key is supplied, the value of
 *                            PluginCore::getService() will be returned (either a reference
 *                            or null). If no key is supplied, the plugin itself is
 *                            returned.
 * @return  PluginCore|mixed|null
 */
function wistia($service = '')
{
  global $wistia;
  if (!$wistia || !($wistia instanceof Wistia\PluginCore)) {
    $wistia = new Wistia\PluginCore();
  }

  if (!empty($service)) {
    return $wistia->getService($service);
  }

  return $wistia;
}

// Initialize
wistia();

} // End global namespace
