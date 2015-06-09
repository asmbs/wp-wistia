<?php

namespace Wistia\Admin;


/**
 * Plugin settings manager.
 *
 */
class SettingsManager
{
  /**
   * The setting key used in the database.
   */
  const SETTING_BASENAME  = 'wistia_settings';
  const SETTING_GROUPNAME = 'wistia_settings-group';
  const ADMIN_PAGE_ID     = 'wp_wistia_admin';

  /**
   * @var  array  An array of plugin settings.
   */
  protected $settings = [];

  protected $renderer;

  // -------------------------------------------------------------------------------------------

  /**
   * Constructor; hooks into the Settings API and loads existing plugin settings.
   */
  public function __construct()
  {
    // Hook into the Settings API
    add_action('admin_init', [$this, 'registerSettings']);
    add_action('admin_menu', [$this, 'addMenuPage']);

    // Load current settings
    $this->settings = get_option(self::SETTING_BASENAME, []);

    // Initialize the renderer
    $this->renderer = new SettingsRenderer($this);
  }

  public function getSetting($key, $default = null)
  {
    if (array_key_exists($key, $this->settings)) {
      return $this->settings[$key];
    }

    return $default;
  }

  public function getFieldID($key)
  {
    return sprintf('%s-%s', self::SETTING_BASENAME, $key);
  }

  public function getFieldName($key)
  {
    return sprintf('%s[%s]', self::SETTING_BASENAME, $key);
  }

  public function getFieldArguments($key, $description = null, $args = null)
  {
    return array_replace_recursive([
      'key'         => $key,
      'id'          => $this->getFieldID($key),
      'label_for'   => $this->getFieldID($key),
      'name'        => $this->getFieldName($key),
      'description' => $description,
      'classes'     => ''
    ], $args);
  }

  // -------------------------------------------------------------------------------------------

  public function registerSettings()
  {
    register_setting(self::SETTING_GROUPNAME, self::SETTING_BASENAME);
    
    // Section: API Keys
    // -----------------------------------------------------------------

    add_settings_section(
      'wistia_section-api_keys',
      __('API Settings'),
      [$this->renderer, 'renderAPISettingsSection'],
      self::ADMIN_PAGE_ID
    );

    add_settings_field(
      'url_prefix',
      __('URL Prefix'),
      [$this->renderer, 'renderTextField'],
      self::ADMIN_PAGE_ID,
      'wistia_section-api_keys',
      $this->getFieldArguments(
        'url_prefix',
        null,
        [
          'before' => '<code>http://</code>',
          'after'  => '<code>.wistia.com</code>',
        ]
      )
    );
    add_settings_field(
      'upload_key',
      __('Upload Key'),
      [$this->renderer, 'renderTextField'],
      self::ADMIN_PAGE_ID,
      'wistia_section-api_keys',
      $this->getFieldArguments(
        'upload_key',
        __('This API key should have <strong>upload permissions only</strong>. It will be '
          .'visible in the source of any front-end pages that use the JavaScript uploader.'),
        [
          'classes' => 'regular-text',
        ]
      )
    );
  }

  // -------------------------------------------------------------------------------------------

  /**
   * Register the WordPress admin page.
   */
  public function addMenuPage()
  {
    $hook = add_options_page(
      __('Wistia Integration Settings'),
      __('Wistia'),
      'manage_options',
      self::ADMIN_PAGE_ID,
      [$this->renderer, 'renderSettingsPage']
    );
    error_log('hook: '. $hook);
  }
}
