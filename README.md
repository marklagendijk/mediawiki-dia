# MediaWiki Dia plugin
The [Dia](http://dia-installer.de/) extension allows Dia diagrams to be embedded/rendered inside MediaWiki pages. It can be used to have thumbnails of specified size to be automatically generated from the uploaded .dia files.

This is achieved by having Dia installed on the server, and calling Dia to transform the `.dia` file to `.svg`.

## Installation
1. Create a `Dia` folder in the extensions folder of your MediaWiki installation.
2. Download and extract the [plugin](https://github.com/marklagendijk/mediawiki-dia/archive/master.zip) into the created folder.
3. To activate the extension, add the following lines to your LocalSettings.php file (near the end):
   ``` php
   require_once( "$IP/extensions/Dia/Dia.php" ); # Load Dia extension
   $wgFileExtensions[] = 'dia';                  # Allow uploading of dia files
   ```
4. Make sure that you have MediaWiki configured to allow for file uploads, this settings is already in LocalSettings.php, but defaults to `false`:
   ``` php
   $wgEnableUploads = true;
   ```




