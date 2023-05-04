<?php

use WP_CLI\Utils;
use WP_CLI_Command;

if (!class_exists('Plugins_Scanner_Command')) {
    class Plugins_Scanner_Command extends WP_CLI_Command
    {
        /**
         * Scans the WordPress plugins folder and compares it with the valid plugin names list in ~/.wp-cli/plugins.txt
         *
         * ## EXAMPLES
         *
         *     wp plugins-scanner scan
         *
         */
        public function scan($args, $assoc_args)
        {
            // Get local plugins
            $local_plugins = $this->get_local_plugins();

            // Get the valid plugins list from ~/.wp-cli/plugins.txt
            $valid_plugins = $this->get_valid_plugins();

            // Compare and output the results
            $this->compare_and_output($local_plugins, $valid_plugins);
        }

        private function get_local_plugins()
        {
            $plugins_dir = WP_PLUGIN_DIR;
            $local_plugins = [];

            if (is_dir($plugins_dir)) {
                $plugin_folders = array_diff(scandir($plugins_dir), ['..', '.']);

                foreach ($plugin_folders as $folder) {
                    if (is_dir($plugins_dir . '/' . $folder)) {
                        $local_plugins[] = $folder;
                    }
                }
            }

            return $local_plugins;
        }

        private function get_valid_plugins()
        {
            $plugins_file = Utils\get_home_dir() . '/.wp-cli/plugins.txt';

            if (!file_exists($plugins_file)) {
                WP_CLI::error('The plugins.txt file does not exist in the ~/.wp-cli/ directory.');
                return [];
            }

            $valid_plugins = array_map('trim', file($plugins_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
            return $valid_plugins;
        }

        private function compare_and_output($local_plugins, $valid_plugins)
        {
            $unlisted_plugins = array_diff($local_plugins, $valid_plugins);

            if (empty($unlisted_plugins)) {
                WP_CLI::success('All installed plugins are in the valid plugins list.');
            } else {
                WP_CLI::warning('The following plugins are not in the valid plugins list:');
                foreach ($unlisted_plugins as $plugin) {
                    WP_CLI::line('- ' . $plugin);
                }
            }
        }
    }

    WP_CLI::add_command('plugins-scanner', 'Plugins_Scanner_Command');
}