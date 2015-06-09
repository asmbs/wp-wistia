<?php

namespace Wistia\API;

use Wistia as Base;


/**
 * Upload API integration.
 *
 */
class UploadIntegration
{
  /**
   * Constructor; initialize the integration.
   */
  public function __construct()
  {
    add_action('wp_enqueue_scripts', [$this, 'registerScripts'], 1);
  }

  // -------------------------------------------------------------------------------------------

  /**
   * Register upload support scripts.
   */
  public function registerScripts()
  {
    // Generate script URI
    $path = 'js/api/'. (WP_ENV == 'development' ? 'upload.js' : 'upload.min.js');
    $src = Base\wistia()->getUri($path);

    wp_register_script('wistia_uploads', $src, ['jquery'], Base\PluginCore::VERSION);
  }
}