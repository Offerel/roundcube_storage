# elFinder Plugin (Roundcube)
Directly integrate elFinder as App in to Roundcube with SSO. You can find elFinder at GitHub (https://github.com/Studio-42/elFinder). 

# Changelog
v1.0.1
  I have changed the authentication. Now I use directly the Roundcube session to login and build the path for elFinder. This is more secure than my previous approach. 

v1.0 
  This is the first release and somewhat like a first test. You have to download and configure elFinder yourself, since this is not the goal of this plugin.

# Installation
1. Extract the downloaded archive into Roundcube’s plugin directory `<roundcube>/plugins/` and rename it to `storage`.
2. Download elFinder from https://github.com/Studio-42/elFinder
3. Extract elFinder in to the plugin directory. I have some example preconfigured files from eLfinder 2.1.30 (they end with *.example)
4. Configure elFinder to your needs. Mostly this follows the instructions on the elFinder Source.
5. There are preconfigured files for elfinder, which you use as a starting point. If you want make use of the Authentication, take care that you dont remove the authentication part in connector.minimal.example
6. Rename elfinder.html.example and connector.minimal.example, so that you can use this files 
7. Activate the plugin in /config/config.inc.php in the way that you add it to the active plugins array, like $config['plugins'] = array('storage');

# Remarks
- Login is done with the same credentials as in Roundcube.
- Information on how to configure elFinder can be found in the elFinder project
