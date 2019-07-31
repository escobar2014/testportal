<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

if ( $displayData['params']->get('quote_text') ) {
	?>
	<div class="entry-quote">
		<blockquote>
			<p><i class="fa fa-quote-left"></i><?php echo $displayData['params']->get('quote_text'); ?><i class="fa fa-quote-right"></i></p>
			<?php if ( $displayData['params']->get('quote_author') ) { ?>
				<small><?php echo $displayData['params']->get('quote_author'); ?></small>
			<?php } ?>
		</blockquote>
	</div>
	<?php
}
