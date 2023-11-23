<?php

/**
 * @package   Astroid Framework
 * @author    Astroid Framework https://astroidframe.work
 * @copyright Copyright (C) 2023 AstroidFrame.work.
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

use Joomla\CMS\Uri\Uri;
defined('_JEXEC') or die;
if (!defined('_ASTROID')) {
   try {
      Astroid\Framework::init();
   } catch (\Exception $e) {
      die('Please install and activate <a href="https://www.astroidframe.work/" target="_blank">Astroid Framework</a> in order to use this template.');
   }
}

require_once "helper.php"; // Template's Helper

$document = Astroid\Framework::getDocument(); // Astroid Document
$params = Astroid\Framework::getTemplate()->getParams(); // Astroid Params

Astroid\Helper\Head::meta(); // site meta
Astroid\Component\Utility::custom(); // custom code
Astroid\Helper\Head::scripts(); // site scripts
Astroid\Helper\Head::favicon(); // site favicon
Astroid\Component\Utility::error(); // error page styling
Astroid\Component\Utility::typography();

/** @var JDocumentError $this */

$errorContent = $params->get('error_404_content', '');
$errorButton = $params->get('error_call_to_action', '');

$bodyAttrs = [];
if ($params->get('background_setting_404') == 'video' && !empty($params->get('background_video_404', ''))) {
   $document->addScript('vendor/astroid/js/videobg.js', 'body');
   $bodyAttrs[] = 'data-jd-video-bg="' . Uri::root() . Astroid\Helper\Media::getPath() . '/' . $params->get('background_video_404', '') . '"';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>
   <meta charset="utf-8" />
   <title><?php echo $this->title; ?> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></title>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
   <?php
   echo $document->renderMeta();
   echo Astroid\Helper\Head::styles();
   $document->astroidCustomCSS();
   echo $document->renderLinks();
   echo $document->getStylesheets();
   echo $document->getScripts('head');
   echo $document->getCustomTags('head');
   echo $document->astroidInlineCSS();
   ?>
</head>

<body class="error-page" <?php echo implode(' ', $bodyAttrs); ?>>
   <div class="container">
      <div class="row">
         <div class="col-12 text-center align-self-center">
            <?php
            if (!empty($errorContent)) {
               echo str_replace('{errormessage}', htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'), str_replace('{errorcode}', $this->error->getCode(), $document->loadModule($errorContent)));
            } else {
            ?>
               <div class="py-5">
                  <h2 class="display-1"><?php echo $this->error->getCode(); ?></h2>
                  <h5 class="display-4"><?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></h5>
               </div>
            <?php
            }
            ?>
            <a class="btn btn-backtohome" href="<?php echo Uri::root(); ?>" role="button"><?php echo $errorButton; ?></a>
         </div>
      </div>
      <?php if ($this->debug) : ?>
         <div class="row bg-white">
            <div class="col py-3 text-center">
               <code class="p-3">
                  ERROR <?php echo $this->error->getCode(); ?> - <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?> in <?php echo htmlspecialchars($this->error->getFile(), ENT_QUOTES, 'UTF-8'); ?>:<?php echo $this->error->getLine(); ?>
               </code>
            </div>
         </div>
      <?php endif; ?>
      <?php if ($this->debug) : ?>
         <div class="row">
            <div class="col mb-5 bg-white">
               <?php echo $this->renderBacktrace(); ?>
               <?php // Check if there are more Exceptions and render their data as well 
               ?>
               <?php if ($this->error->getPrevious()) : ?>
                  <?php $loop = true; ?>
                  <?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly 
                  ?>
                  <?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions 
                  ?>
                  <?php $this->setError($this->_error->getPrevious()); ?>
                  <?php while ($loop === true) : ?>
                     <p><strong><?php echo JText::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                     <p>
                        <?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                        <br /><?php echo htmlspecialchars($this->_error->getFile(), ENT_QUOTES, 'UTF-8'); ?>:<?php echo $this->_error->getLine(); ?>
                     </p>
                     <?php echo $this->renderBacktrace(); ?>
                     <?php $loop = $this->setError($this->_error->getPrevious()); ?>
                  <?php endwhile; ?>
                  <?php // Reset the main error object to the base error 
                  ?>
                  <?php $this->setError($this->error); ?>
               <?php endif; ?>
            </div>
         </div>
      <?php endif; ?>
   </div>
   <?php
   echo $document->getScripts('body');
   echo $document->getCustomTags('body');
   ?>
</body>

</html>