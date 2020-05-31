### v1.4.4
- Updated ACE Editor to 1.4.10
- Updated elFinder to 2.1.56
- Remove refresh, when storage plugin is active
- Fix for RC 1.4.x final
- Fix elFinder CodeMirror implementation
- Fix for save attachments

### v1.4.3
- Fix and customization for Roundcube 1.4 RC2
- Added CHANGELOG.md to release
- Fixed typo

### v1.4.2
- Fix for missing elopen.html
- Fix for missing volume icon
- Add language autoload

### v1.4.1
- Fix for #7
- Updated to elFinder 2.1.49
- Merged Pull Request for Elastic Icon

### v1.4.0
- Added support for Roundcube 1.4 Elastic Skin

### v1.2.4
- Update to elFinder 2.1.37 with fix for directory traversal vulnerability
- fixing backward compatibility in larry skin
- moving debug var to a better position

### v1.2.3
 - simplified path configuration. You need only to set `$config['storage_basepath']`. Use tha variable `%u` to specify the username
 - set `$config['storage_debug']` to true, to display calculated path in Roundcubes error.log file

### v1.2.2
 - Fix for button sometimes disabled in taskbar

### v1.2.1
 - Small fix for Classic skin
 
### v1.2.0
 - You can now add files from the server storage with the hekp of elFinder.
 - Fix for wrong path issue
 
### v1.1.0
 - There is now a special connector.roundcube.php file, so that you can use this, instead of connector.minimal.php
 - Configuration of basepath is now done in config.inc.php
 - It's now possible to save E-Mail Attachments in the configured storage. For this, there is a new config parameter in config.inc.php to specify the right path. If this path doesn't exist, it will be created automatically.

### v1.0.1
 - I have changed the authentication. Now I use directly the Roundcube session to login and build the path for elFinder. This is more secure than my previous approach.
 - Addes skin support for classic skin 

### v1.0
 - This is the first release and somewhat like a first test. You have to download and configure elFinder yourself, since this is not the goal of this plugin.