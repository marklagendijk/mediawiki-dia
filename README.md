# MediaWiki Dia plugin
This MediaWiki Dia plugin allows [Dia diagrams]((http://dia-installer.de/) to be embedded/rendered inside MediaWiki pages. It can be used to have thumbnails of specified size to be automatically generated from the uploaded .dia files.

This is achieved by having Dia installed on the server, and calling Dia to transform the `.dia` file to `.svg`.

## Installation
1. Install Dia on your MediaWiki server, and add it to your PATH, if neccesary. Verify whether it works by opening a command prompt and executing `dia -h`.
2. Create a `Dia` folder in the extensions folder of your MediaWiki installation.
3. [Download](https://github.com/marklagendijk/mediawiki-dia/archive/master.zip) and extract the plugin into the created folder.
4. To activate the extension, add the following lines to your LocalSettings.php file (near the end):

   ``` php
   require_once( "$IP/extensions/Dia/Dia.php" ); # Load Dia extension
   $wgFileExtensions[] = 'dia';                  # Allow uploading of dia files
   ```
5. Make sure that you have MediaWiki configured to allow for file uploads, this settings is already in LocalSettings.php, but defaults to `false`:
 
   ``` php
   $wgEnableUploads = true;
   ```
6. If you have an older version you might need to run the appropriate `mimetype` patch. Try uploadin a `.dia` file to see if this is neccesary.

## Usage
### Uploading
Just upload any `.dia` file. If everything is working correctly, it will show a thumbnail of the diagram.

### Displaying
Add an `File` tag pointing to the `.dia` file. The diagram will always display with the correct ratio, specified `width` and `height` are treated as maximums. Some examples:
```
Default size:
[[File:MyDiagram.dia]]

Specify width and height:
[[File:MyDiagram.dia|500x300px]]

Specify width only:
[[File:MyDiagram.dia|500px]]

Specify height only:
[[File:MyDiagram.dia|x300px]]
```

### Editing
1. Click on a displayed Dia diagram. This wil take you to the page of the diagram file.
2. Click the link with the filename to download the `.dia` file. If this does not work, use right-click -> save as.
3. Edit the `.dia` file on your local machine, using [Dia](http://dia-installer.de), and save your changes.
4. Upload the changed `.dia` file.


