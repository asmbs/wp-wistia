<?php

namespace Wistia\Admin;
?>

<div class="wrap">
  <h2><?php echo get_admin_page_title() ?></h2>
  <form action="options.php" method="post">
  
  <?php
  settings_fields(SettingsManager::SETTING_GROUPNAME);
  do_settings_sections(SettingsManager::ADMIN_PAGE_ID);
  ?>

  <?php submit_button() ?>

  </form>
</div>
