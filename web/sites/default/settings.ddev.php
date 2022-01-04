<?php

// ddev
if (getenv('IS_DDEV_PROJECT') == 'true') {
  # database
  $host = empty(getenv('DDEV_PHP_VERSION')) ? '127.0.0.1' : 'db';
  $port = empty(getenv('DDEV_PHP_VERSION')) ? -1 : 3306;
  $databases['default']['default']['database'] = 'db';
  $databases['default']['default']['username'] = 'root';
  $databases['default']['default']['password'] = 'root';
  $databases['default']['default']['host'] = $host;
  $databases['default']['default']['port'] = $port;
  $databases['default']['default']['driver'] = 'mysql';
  $databases['default']['default']['prefix'] = '';
  $databases['default']['default']['collation'] = 'utf8mb4_general_ci';

  # solr (https://ddev.readthedocs.io/en/stable/users/extend/additional-services/)
  $config['search_api.server.foo_core']['backend_config']['connector_config']['scheme'] = 'http';
  $config['search_api.server.foo_core']['backend_config']['connector_config']['host'] = 'solr';
  $config['search_api.server.foo_core']['backend_config']['connector_config']['port'] = '8983';
  $config['search_api.server.foo_core']['backend_config']['connector_config']['core'] = 'dev';
  $config['search_api.server.foo_core']['backend_config']['connector_config']['timeout'] = 10;
  $config['search_api.server.foo_core']['backend_config']['connector_config']['index_timeout'] = 10;
  $config['search_api.server.foo_core']['backend_config']['connector_config']['optimize_timeout'] = 15;

  # swiftmailer (https://github.com/drud/ddev/issues/2706)
  $config['swiftmailer.transport']['transport'] = 'smtp';
  $config['swiftmailer.transport']['smtp_host'] = '127.0.0.1';
  $config['swiftmailer.transport']['smtp_port'] = '1025';
  $config['swiftmailer.transport']['smtp_encryption'] = 0;

  # disable apc class loader for development
  $settings['class_loader_auto_detect'] = FALSE;

  # do not change file permissions for development
  $settings['skip_permissions_hardening'] = TRUE;
}
