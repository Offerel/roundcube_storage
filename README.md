# elFinder Plugin (Roundcube)
Directly integrate elFinder as App in to Roundcube with SSO. You can find elFinder at GitHub (https://github.com/Studio-42/elFinder). 

# Changelog
1.0 This is the first release and somewhat like a first test. You have to download and configure elFinder yourself, since this is not the goal of this plugin.

# Installation
1. Extract the downloaded archive into Roundcube’s plugin directory `<roundcube>/plugins/` and rename it to `elfinder`.
2. Configure elFinder to your needs.
3. If every user should use his own directory, separate from other users, the `connector.minimal.php` has to be adjusted
4. First add at the beginning of this file, directly after the php start tag "session_start();" without the quotes
5. Before the path is set, eg. before the variable `$opts`, add the line `$user = $_SESSION['uf'].'/files';` 
6. Change the path, to include the `$user` variable

# Remarks
- Login is done with the same credentials as in Roundcube.
- An existing .htpasswd from Apache or nginx can be used as long as the redentials match those of Roundcube. The format should be recognized automatically by the plugin.
- Login to elfinder uses its own session, which is also terminated when logging out from Roundcube
- Information on how to configure elFinder can be found in the elFinder project
