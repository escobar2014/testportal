<?php
/**
 * @package	AcyMailing for Joomla
 * @version	6.1.5
 * @author	acyba.com
 * @copyright	(C) 2009-2019 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');
?><div class="acym__content acym_area padding-vertical-1 padding-horizontal-2 margin-bottom-2">
	<div class="acym_area_title"><?php echo acym_translation('ACYM_CONFIDENTIALITY'); ?></div>
	<div class="grid-x grid-margin-x">
        <?php echo acym_switch('config[gdpr_export]', $data['config']->get('gdpr_export'), acym_translation('ACYM_GDPR_EXPORT_BUTTON'), array(), 'xlarge-3 medium-5 small-9', "auto", "tiny", 'export_config'); ?>
	</div>
</div>

<div class="acym__content acym_area padding-vertical-1 padding-horizontal-2">
	<div class="acym_area_title"><?php echo acym_translation('ACYM_TRACKING'); ?></div>

	<div class="grid-x margin-bottom-1">
		<label class="cell xlarge-3 small-5" for="from_as_replyto">
            <?php echo acym_translation('ACYM_TRACKINGSYSTEM'); ?>
		</label>

		<div class="cell auto">
            <?php $trackingMode = $data['config']->get('trackingsystem', 'acymailing'); ?>

			<input
					type="checkbox"
					name="config[trackingsystem][]"
					id="trackingsystem[0]"
					value="acymailing"
                <?php echo stripos($trackingMode, 'acymailing') !== false ? 'checked="checked"' : ''; ?>
			/>
			<label for="trackingsystem[0]">Acymailing</label>

			<input
					type="checkbox"
					name="config[trackingsystem][]"
					id="trackingsystem[1]"
					value="google"
                <?php echo stripos($trackingMode, 'google') !== false ? 'checked="checked"' : ''; ?>
			/>
			<label for="trackingsystem[1]">Google Analytics</label>

			<input type="hidden" name="config[trackingsystem][]" value="1"/>
		</div>
	</div>

	<div class="grid-x grid-margin-x">
        <?php echo acym_switch('config[trackingsystemexternalwebsite]', $data['config']->get('trackingsystemexternalwebsite'), acym_translation('ACYM_TRACKINGSYSTEM_EXTERNAL_LINKS'), array(), 'xlarge-3 medium-5 small-9', "auto", "tiny", 'external_config'); ?>
	</div>
</div>
