# elFinder Plugin (Roundcube)
Directly integrate elFinder as App in to Roundcube with SSO. You can find elFinder at GitHub (https://github.com/Studio-42/elFinder). 

# Changelog
**v1.2.2**
 - fix for path check
 
**v1.2.1**
 - Small fix for Classic skin
 
**v1.2.0**
 - You can now add files from the server storage with the hekp of elFinder.
 - Fix for wrong path issue
 
**v1.1.0**
 - There is now a special connector.roundcube.php file, so that you can use this, instead of connector.minimal.php
 - Configuration of basepath is now done in config.inc.php
 - It's now possible to save E-Mail Attachments in the configured storage. For this, there is a new config parameter in config.inc.php to specify the right path. If this path doesn't exist, it will be created automatically.

**v1.0.1**
 - I have changed the authentication. Now I use directly the Roundcube session to login and build the path for elFinder. This is more secure than my previous approach.
 - Addes skin support for classic skin 

**v1.0**
 - This is the first release and somewhat like a first test. You have to download and configure elFinder yourself, since this is not the goal of this plugin.

# Installation
1. Extract the downloaded archive into Roundcube’s plugin directory `<roundcube>/plugins/` and rename it to `storage`.
2. Download elFinder from https://github.com/Studio-42/elFinder
3. Extract elFinder in to the plugin directory. I have some example preconfigured files from eLfinder 2.1.30 (they end with *.example)
4. Configure elFinder to your needs. Mostly this follows the instructions on the elFinder Source.
5. There is now a roundcube.html. THis file is preconfigured to work together with Roundcube
6. Activate the plugin in /config/config.inc.php in the way that you add it to the active plugins array, like $config['plugins'] = array('storage');

# Remarks
- Login is done with the same credentials as in Roundcube.
- Information on how to configure elFinder can be found in the elFinder project
