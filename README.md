# Roundcube elFinder Plugin
Directly integrate elFinder as App in to Roundcube with SSO. You can find elFinder at GitHub (https://github.com/Studio-42/elFinder). 

# Issues
Please create issues in the [Codeberg.org](https://codeberg.org/Offerel/Roundcube_Storage/issues) issue tracker. I have disabled the issue tracker in GitHub.com because the repo there is only used as a workaround and for compatibility reasons.

# Contribution
If you would like to support this Plugin, you have various options. You can report bugs via the [Issue Tracker](https://codeberg.org/Offerel/Roundcube_Storage/issues). This helps me to fix these bugs as quickly as possible.

Another option is to help translate the plugin into other languages. This is relatively easy to do via Weblate. The following languages are currently available:
[![Status](https://translate.codeberg.org/widget/roundcube-storage/multi-auto.svg)](https://translate.codeberg.org/engage/roundcube-storage/)

# Installation
- Extract the downloaded archive into Roundcube’s plugin directory `<roundcube>/plugins/` and rename it to `storage`.
- Copy config.inc.php.dist to `config.inc.php`
- Change `$config['storage_basepath']` in this config.inc.php to the root path for elFinder. This defines, where the root for the filemanager starts. Use the variable %u for the Roundcube username.
- Activate the plugin in /config/config.inc.php in the way that you add it to the active plugins array, like `$config['plugins'] = array('storage');`