<?php
/**
 * Setup for Dia plugin, a plugin that allows Dia (http://live.gnome.org/Dia) diagrams
 * to be rendered in MediaWiki pages.
 * 
 * Inspired by the orignal Dia plugin written by Marcel Toele, and maintained by Vitaliy Filippov.
 * The main difference is that this plugin displays the diagrams using `.svg` instead of `.png`, 
 * which solves most problems the original plugin had. 
 *
 * @package Dia
 * @subpackage Extensions
 * @author Mark Lagendijk
 * @copyright © 2014 Mark Lagendijk
 * @licence MIT License
 */

if (!defined('MEDIAWIKI'))
{
    echo("This file is an extension to the MediaWiki software and cannot be used standalone.\n");
    die(1);
}

// Credits
$wgExtensionCredits['other'][] = array(
    'name'        => 'Dia',
    'author'      => 'Mark Lagendijk',
    'url'         => 'http://mediawiki.org/wiki/Extension:Dia',
    'description' => 'Allows Dia diagrams to be rendered inside MediaWiki pages.',
);

// Add the DiaHandler via the Autoload mechanism.
$wgMediaHandlers['application/x-dia-diagram'] = 'DiaHandler';
$wgMediaHandlers['application/x-gzip'] = 'DiaHandler';
$wgAutoloadClasses['DiaHandler'] = dirname(__FILE__) . '/Dia.body.php';
$wgExtensionMessagesFiles['Dia'] = dirname(__FILE__) . '/Dia.i18n.php';
if (!in_array('dia', $wgFileExtensions))
    $wgFileExtensions[] = 'dia';

// Default Config
// Dia command, change this if it isn't in the default PATH
$wgDIACommand = 'dia';

