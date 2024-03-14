<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Form\Form;

$type = $this->input->get('type', '', 'string');
if (! $type) {
	echo 'ERROR : no type found';
	die;
}
Form::addFieldPath(JPATH_ROOT . '/administrator/components/com_maximenuck/elements');
// jimport( 'joomla.form.form' );
$pathFormXMLFile = JPATH_SITE . '/plugins/maximenuck/' . $type . '/params/' . $type . '_params.xml';
		// $pathFormXMLFile =  JPATH_COMPONENT . '/models/forms/publiannonce.xml';
		// echo  $pathFormXMLFile;
		
		// $form = Form::getInstance('myform', $pathToMyXMLFile);
		$form = new Form('maximenuckautoloadform');
		$form->loadFile($pathFormXMLFile);

// loads the language files from the frontend
$lang	= \Joomla\CMS\Factory::getLanguage();
$lang->load('plg_maximenuck_' . $type, JPATH_SITE . '/plugins/maximenuck/' . $type, $lang->getTag(), false);
$lang->load('mod_maximenuck', JPATH_SITE . '/modules/mod_maximenuck/', $lang->getTag(), false);
?>

<div class="ckinterface ckinterface-labels-big" id="ck-item-edition-item-params">
	<input id="thirdparty" name="thirdparty" class=""  value="1" disabled type="hidden" />
	<input id="type" name="type" class=""  value="autoload.<?php echo $type ?>" disabled type="hidden" />
	<input id="title" name="title" class=""  value="autoload.<?php echo $type ?>" disabled type="hidden" />
	<div class="ck-title"><?php echo \Joomla\CMS\Language\Text::_('MAXIMENUCK_SOURCE_' . strtoupper($type)); ?></div>
	<?php
	foreach ($form->getFieldsets() as $fieldset) 
	{
		$fields = $form->getFieldset($fieldset->name);
		if (count($fields)) 
		{
			foreach ($fields as $field) 
			{
				echo $field->renderField();
			}
		}
	}
	?>
</div>
<script>
function ckLoadEditionItem() {
	ckUpdateImagePreview();
}

function ckUpdateImagePreview(selector = '#ckimagepreview') {
	if ($ck('#imageurl').val()) {
		var imageurl = '<?php echo \Joomla\CMS\Uri\Uri::root(true) ?>' +  '/' + $ck('#imageurl').val();
	} else {
		var imageurl = '';
	}
	$ck(selector).attr('src', imageurl);
}

<?php
Maximenuck\CKFof::importPlugin('maximenuck');
$codes = Maximenuck\CKFof::triggerEvent('onMaximenuckGetJsByType' . ucfirst($type));
if (! empty($codes) && is_array($codes)) {
	foreach($codes as $code) {
		echo $code;
	}
}
?>
</script>
<style>
.control-label {
	width: 200px;
	line-height: 16px;
	float: none;
	display: inline-block;
	margin-top: 5px;
}
.control-label label {
	width: auto;
}
.controls {
	display: inline-block;
}
.controls input, .controls select {
	margin: 0 !important;
}
.control-group {
	margin: 5px 0;
}
.maximenuck-field-icon, .controls > div:first-child {
	display: none !important;
}

input[type="radio"] {
	display: inline-block !important;
	margin: 5px !important;
	float: none !important;
}

input[type="radio"] + label, input[type="radio"] + label:hover {
	width: auto;
	display: inline-block;
	vertical-align: middle;
	background: none;
	color: inherit;
}
</style>