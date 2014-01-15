<?php
/**
 * @addtogroup Media
 */
class DiaHandler extends ImageHandler
{
    /**
     * Always render the dia file to an SVG
     * @param File $file
     * @return bool
     */
    function canRender($file)
    {
        return true;
    }

    /**
     * Always render the dia file to an SVG
     * @param File $file
     * @return bool
     */
    function mustRender($file)
    {
        return true;
    }

    /**
     * An SVG is an vector
     * @param $file
     * @return bool
     */
    function isVectorized($file)
    {
        return true;
    }

    /**
     * Normalizes the parameters.
     * @param File $image
     * @param $params
     * @return bool
     */
    function normaliseParams($image, &$params)
    {
        if (!isset($params['height'])) {
            $imageSize = $this->getDiaSvgImageSize($image->getLocalRefPath());
            $params['height'] = File::scaleHeight($imageSize['width'], $imageSize['height'], $params['width']);
        }

        return true;
    }

    /**
     * Transforms the .dia file to an .svg file.
     * @param File $image
     * @param string $dstPath
     * @param string $dstUrl
     * @param array $params
     * @param int $flags
     * @return MediaTransformError|MediaTransformOutput|ThumbnailImage
     */
    function doTransform($image, $dstPath, $dstUrl, $params, $flags = 0)
    {
        global $wgScriptPath;
        $this->normaliseParams($image, $params);
        $srcPath = $image->getLocalRefPath();
        $dstPath = $srcPath . '.svg';
        $dstUrl = $wgScriptPath . substr($dstPath, strpos($dstPath, '/images/'));

        if ($flags & self::TRANSFORM_LATER || $this->convertToSvg($srcPath, $dstPath, $error)) {
            return new ThumbnailImage($image, $dstUrl, $dstPath, $params);
        } else {
            return new MediaTransformError(
                'thumbnail_error', $params['width'], $params['height'], $error
            );
        }
    }

    /**
     * Converts a .dia file to an .svg file, by executing the 'dia' command.
     * @param $srcPath
     * @param $dstPath
     * @param &$error
     * @return bool
     */
    function convertToSvg($srcPath, $dstPath, &$error)
    {
        global $wgDIACommand;
        $input = wfEscapeShellArg($srcPath);
        $output = wfEscapeShellArg($dstPath);
        $command = "$wgDIACommand $input --export=$output --filter=svg";
        $error = wfShellExec($command, $retval, array(), array(), array('duplicateStderr' => true));
        return $retval === 0;
    }

    /**
     * Gets the diagrams' size.
     * @param File $file
     * @param string $path
     * @param bool $metadata
     * @return array
     */
    function getImageSize($file, $path, $metadata = false)
    {
        // Force the (re)creation of the SVG file
        $this->convertToSvg($path, $path. '.svg', $error);

        // Get and return the imageSize
        $imageSize = $this->getDiaSvgImageSize($path);
        return array(
            $imageSize['width'],
            $imageSize['height'],
            'SVG',
            'width="'.  $imageSize['width']. '" height="'. $imageSize['height']. '"'
        );
    }

    /**
     * Gets the size of an Dia diagram or SVG file created by Dia.
     * Note: the root element of an Dia SVG contains width and height in centimeters.
     * @param $path - The path of a `.dia` or `.svg` file.
     * @return array
     */
    function getDiaSvgImageSize($path){
        try {
            // Set the $diaPath and $svgPath
            if(strpos($path, '.svg') === false){
                $diaPath = $path;
                $svgPath = $path . '.svg';
            }
            else{
                $svgPath = $path;
                $diaPath = str_replace('.svg', '', $path);
            }

            // Create the SVG if it doesn't exist yet
            if(!file_exists($svgPath)){
                $this->convertToSvg($diaPath, $svgPath, $error);
            }
            // Load the SVG, and use the width and height attributes to get the size.
            $xml = simplexml_load_file($svgPath);

            return array(
                'width' => $this->cmToPx($xml['width']),
                'height' => $this->cmToPx($xml['height'])
            );
        } catch (Exception $e) {
            return array(
                'width' => 0,
                'height' => 0
            );
        }
    }

    /**
     * Converts from centimeters to pixels
     * @param $cm
     * @return float
     */
    function cmToPx($cm)
    {
        $cm = str_replace('cm', '', $cm);
        return round($cm * 37.8);
    }

    /**
     * Returns the filetype of the thumbnailes: svg
     * @param String $ext
     * @param String $mime
     * @param null $params
     * @return array
     */
    function getThumbType($ext, $mime, $params = NULL)
    {
        return array('svg', 'image/xml+svg');
    }

    /**
     * Gets the description of the file.
     * @param File $file
     * @return String
     */
    function getLongDesc($file)
    {
        global $wgLang;
        return wfMsg('dia-long-desc');
    }
}
