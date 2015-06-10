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
   * @var  Base\PluginCore
   */
  protected $container;

  // -------------------------------------------------------------------------------------------
  
  /**
   * Constructor; initialize the integration.
   */
  public function __construct(Base\PluginCore $container)
  {
    $this->container = $container;

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

  public function enqueueUploadScript()
  {
    // Enqueue the main script
    add_action('wp_enqueue_scripts', function() {
      wp_enqueue_script('wistia_uploads');
    });

    // Load the upload key
    $settings = $this->container->getService('settings');
    $uploadKey = $settings->getSetting('upload_key', false);
    if ($uploadKey) {
      add_action('wp_print_scripts', function() use ($uploadKey) {
        ?>
<script type="text/javascript">var $wk = '<?php echo $uploadKey ?>';</script>
        <?
      });
    }
  }
}
