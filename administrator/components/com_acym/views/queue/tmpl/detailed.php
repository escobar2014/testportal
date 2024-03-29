<?php
/**
 * @package	AcyMailing for Joomla
 * @version	6.1.5
 * @author	acyba.com
 * @copyright	(C) 2009-2019 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');
?><div id="acym__queue" class="acym__content">
    <form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl')); ?>" method="post" name="acyForm" data-abide novalidate>

        <?php
        $workflow = acym_get('helper.workflow');
        echo $workflow->display($this->steps, 'detailed', 1, false);
        ?>

        <?php if (empty($data['allElements']) && empty($data['search']) && empty($data['tag']) && empty($data['status'])) { ?>
            <div class="grid-x text-center">
                <h1 class="acym__listing__empty__title cell"><?php echo acym_translation('ACYM_YOU_DONT_HAVE_ANY_CAMPAIGN_IN_QUEUE'); ?></h1>
                <h1 class="acym__listing__empty__subtitle cell"><?php echo acym_translation('ACYM_SEND_ONE_AND_SEE_HOW_AMAZING_QUEUE_IS'); ?></h1>
            </div>
        <?php } else { ?>
            <div class="grid-x grid-margin-x">
                <div class="cell medium-auto">
                    <?php echo acym_filterSearch($data["search"], 'dqueue_search', 'ACYM_SEARCH_A_CAMPAIGN_NAME'); ?>
                </div>
                <div class="cell medium-auto">
                    <?php
                    $allTags = new stdClass();
                    $allTags->name = acym_translation('ACYM_ALL_TAGS');
                    $allTags->value = '';
                    array_unshift($data["tags"], $allTags);

                    echo acym_select($data["tags"], 'dqueue_tag', $data["tag"], 'class="acym__queue__filter__tags"', 'value', 'name');
                    ?>
                </div>
                <div class="xxlarge-4 xlarge-3 large-2 hide-for-medium-only hide-for-small-only cell"></div>
                <div class="cell medium-shrink">
                    <?php
                    echo acym_modal(
                        acym_translation('ACYM_SEND_ALL'),
                        '',
                        null,
                        'data-reveal-larger',
                        'class="button expanded" data-reload="true" data-ajax="true" data-iframe="&ctrl=queue&task=continuesend&id=0&totalsend=0"'
                    );
                    ?>
                </div>
            </div>
            <?php if (empty($data['allElements'])) { ?>
                <h1 class="cell acym__listing__empty__search__title text-center"><?php echo acym_translation('ACYM_NO_RESULTS_FOUND'); ?></h1>
            <?php } else { ?>
                <div class="grid-x acym__listing acym__listing__view__dqueue">
                    <div class="cell grid-x acym__listing__header">
                        <div class="acym__listing__header__title cell large-2 medium-3">
                            <?php echo acym_translation('ACYM_SENDING_DATE'); ?>
                        </div>
                        <div class="acym__listing__header__title cell medium-4">
                            <?php echo acym_translation('ACYM_CAMPAIGN'); ?>
                        </div>
                        <div class="acym__listing__header__title cell large-4 medium-3">
                            <?php echo acym_translation('ACYM_RECIPIENTS'); ?>
                        </div>
                        <div class="acym__listing__header__title cell medium-1 hide-for-small-only text-center">
                            <?php echo acym_translation('ACYM_TRY'); ?>
                        </div>
                        <div class="acym__listing__header__title cell medium-1 text-center">
                            <?php echo acym_translation('ACYM_DELETE'); ?>
                        </div>
                    </div>
                    <?php foreach ($data["allElements"] as $row) { ?>
                        <div elementid="<?php echo acym_escape($row->id.'_'.$row->user_id); ?>" class="cell grid-x acym__listing__row">
                            <div class="cell large-2 medium-3">
                                <?php echo acym_date($row->sending_date, 'j F Y H:i'); ?>
                            </div>
                            <div class="cell medium-4">
                                <div class="acym__listing__title acym_text_ellipsis">
                                    <?php echo $row->name; ?>
                                </div>
                            </div>
                            <div class="cell large-4 medium-3">
                                <a href="<?php echo acym_completeLink('users&task=edit&id='.$row->user_id); ?>">
                                    <?php
                                    if (empty($row->user_name)) {
                                        echo $row->email;
                                    } else {
                                        echo '<span class="hide-for-medium-only hide-for-small-only">'.$row->user_name.' (</span>'.$row->email.'<span class="hide-for-medium-only hide-for-small-only">)</span>';
                                    }
                                    ?>
                                </a>
                            </div>
                            <div class="cell medium-1 hide-for-small-only text-center">
                                <?php echo $row->try; ?>
                            </div>
                            <div class="cell medium-1 text-center">
                                <i class="fa fa-trash-o acym_toggle_delete acym_delete_queue" table="queue" method="deleteOne" elementid="<?php echo acym_escape($row->id.'_'.$row->user_id); ?>" confirmation="1"></i>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php echo $data['pagination']->display('dqueue'); ?>
            <?php } ?>
        <?php } ?>
        <?php acym_formOptions(false, 'detailed'); ?>
    </form>
</div>
