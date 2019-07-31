<?php // no direct access
defined('_JEXEC') or die('Restricted access');
//vmJsApi::jQuery();
//vmJsApi::chosenDropDowns();
JHtml::_('formbehavior.chosen', 'select');
$currentLanguage = JFactory::getLanguage();
$currentLanguageName = $currentLanguage->getName();
$isRTL = $currentLanguage->isRtl();
// Check of current language is RTL
if($isRTL) {
	$class = 'inputbox';
} else {
	$class = 'inputbox vm-chzn-select';
}
?>
<div class="currency-selector-module">
<!-- Currency Selector Module -->
<?php if ($text_before != '') { ?><p><?php echo $text_before ?></p><?php } ?>
<form action="<?php echo vmURI::getCleanUrl() ?>" method="post">
    <button class="btn btn-default" type="submit" name="submit" data-toggle="tooltip" title="<?php echo vmText::_('MOD_VIRTUEMART_CURRENCIES_CHANGE_CURRENCIES') ?>"><i class="fa fa-refresh"></i></button>
    <?php echo JHTML::_('select.genericlist', $currencies, 'virtuemart_currency_id', 'class="'.$class.'"', 'virtuemart_currency_id', 'currency_txt', $virtuemart_currency_id) ; ?>
</form>
</div>

