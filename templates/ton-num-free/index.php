<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

$app  = JFactory::getApplication();
$user = JFactory::getUser();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
    <?php echo $this->params->get('headcode'); ?>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/tonnum.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/tonnum-m.css" type="text/css" />

    <link rel="shortcut icon" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/slide.js"></script>
      <!--htmlgstop -->
	<?php @ini_set('display_errors', '0'); ?> 

</head>
<body>

<div id="head-slide">
	<div id="top" class="container-fluid">
    	<div id="top-body" class="container">
        	<div id="logo" class="col-xs-12 col-sm-12 col-md-3 col-lg-3 ">
				<jdoc:include type="modules" name="logo" style="xhtml" />
        	</div>
        	<div id="menu" class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
				<jdoc:include type="modules" name="menu" style="xhtml" />
        	</div>
        	<div id="social" class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				<jdoc:include type="modules" name="social" style="xhtml" />
        	</div>
        </div>
	</div>

</div>
<?php if ($this->countModules('slide')) : ?>
<div id="slide" class="container-fluid">
	<jdoc:include type="modules" name="slide" style="xhtml" />
</div>
<?php endif; ?>
<?php if ($this->countModules('recommend')) : ?>
<div id="recommend" class="container-fluid">
	<div id="recommend-body" class="container">
		<jdoc:include type="modules" name="recommend" style="xhtml" />
	</div>
</div>
<?php endif; ?>
<?php if ($this->countModules('breadcrumbs')) : ?>
<div id="breadcrumbs" class="container-fluid">
	<div id="breadcrumbs-body" class="container">
		<jdoc:include type="modules" name="breadcrumbs" style="xhtml" />
	</div>
</div>
<?php endif; ?>
<div id="component" class="container-fluid">
	<div id="component-body" class="container">
        <?php if ($this->countModules('right-module')) : ?>
        <div id="component-left" class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <?php endif; ?>
    		<div id="component-left" class="col-lg-12">
				<jdoc:include type="message" />
				<jdoc:include type="component" />
        	</div>
        <?php if ($this->countModules('right-module')) : ?>
        </div>
        <?php endif; ?>
        <?php if ($this->countModules('right-module')) : ?>
    	<div id="component-right" class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div id="component-right-module" class="col-lg-12">
				<jdoc:include type="modules" name="right-module" style="xhtml" />
        	</div>
    	</div>
        <?php endif; ?>
	</div>
</div>
<?php if ($this->countModules('latest')) : ?>
<div id="latest" class="container-fluid">
	<div id="latest-body" class="container">
		<div id="latest-left" class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
			<jdoc:include type="modules" name="latest" style="xhtml" />
		</div>
        <div id="latest-right" class="col-xs-12 col-sm-12 col-md-3 col-lg-3" >

			<div id="author" class="col-lg-12">
				<jdoc:include type="modules" name="author" style="xhtml" />
        	</div>
            <div id="banner" class="col-lg-12">
				<jdoc:include type="modules" name="banner" style="xhtml" />
        	</div>
		</div>
	</div>
</div>
<?php endif; ?>
<?php if ($this->countModules('gallery')) : ?>
<div id="gallery" class="container">
	<jdoc:include type="modules" name="gallery" style="xhtml" />
</div>
<?php endif; ?>
<?php if ($this->countModules('contact')) : ?>
<div id="contact" class="container-fluid">
	
		<jdoc:include type="modules" name="contact" style="xhtml" />

</div>
<?php endif; ?>
<div id="footer" class="container-fluid">
	<div id="footer-body" class="container">
		<jdoc:include type="modules" name="footer" style="xhtml" />
	</div>
</div>
<div id="copyright" class="container-fluid">
	<div id="copyright-body" class="container">

			<!-- ให้เครดิตเราหน่อยนะ โปรดอย่าลบ hitztheme -->
            <div id="copyright" style="text-align:center;"><jdoc:include type="modules" name="copyright" style="xhtml" /></div>
			<div id="designby" style="text-align:center; font-size:12px;"> ฟรี Template Joomla โดย <a href="https://www.hitztheme.com">Hitztheme </a><br/></div>

	</div>
</div>

<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
