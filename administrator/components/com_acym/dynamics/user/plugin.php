<?php
/**
 * @package	AcyMailing for Joomla
 * @version	6.1.5
 * @author	acyba.com
 * @copyright	(C) 2009-2019 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');
?><?php

class plgAcymUser extends acymPlugin
{
    var $sendervalues = array();

    public function __construct()
    {
        parent::__construct();

        global $acymCmsUserVars;
        $this->cmsUserVars = $acymCmsUserVars;
    }

    public function dynamicText()
    {
        $onePlugin = new stdClass();
        $onePlugin->name = acym_translation_sprintf('ACYM_CMS_USER', 'Joomla');
        $onePlugin->plugin = __CLASS__;
        $onePlugin->help = 'plugin-taguser';

        return $onePlugin;
    }

    public function textPopup()
    {
        ?>

		<script language="javascript" type="text/javascript">
            <!--
            var selectedTag;

            function changeUserTag(tagname){
                if(!tagname) return;

                selectedTag = tagname;

                var string;
                var iscf = tagname.toLowerCase().indexOf('custom');

                if(iscf >= 0) string = '{usertag:' + tagname.substr(0, iscf) + '|type:custom'; else string = '{usertag:' + tagname;

                if(tagname.toLowerCase().indexOf('date') >= 0) string += '|type:date';
                string += '|info:' + jQuery('input[name="typeinfo"]:checked').val() + '}';

                setTag(string, jQuery('#' + tagname + 'option'));
            }

            -->
		</script>

        <?php

        $isAutomation = acym_getVar('string', 'automation');
        $text = '<div class="acym__popup__listing text-center grid-x">';

        $typeinfo = array();
        $typeinfo[] = acym_selectOption("receiver", acym_translation('ACYM_RECEIVER_INFORMATION'));
        $typeinfo[] = acym_selectOption("sender", acym_translation('ACYM_SENDER_INFORMATION'));
        if (!empty($isAutomation)) $typeinfo[] = acym_selectOption("current", acym_translation('ACYM_USER_TRIGGERING_AUTOMATION'));

        $text .= acym_radio($typeinfo, 'typeinfo', 'receiver', null, array('onclick' => 'changeUserTag(selectedTag)'));

        $fields = array(
            $this->cmsUserVars->username => 'ACYM_LOGIN_NAME',
            $this->cmsUserVars->name => 'ACYM_USER_NAME',
            $this->cmsUserVars->registered => 'ACYM_REGISTRATION_DATE',
            'groups' => 'ACYM_USER_GROUPS',
        );

        foreach ($fields as $fieldname => $description) {
            $text .= '<div class="grid-x medium-12 cell acym__listing__row acym__listing__row__popup text-left" id="'.$fieldname.'option" onclick="changeUserTag(\''.$fieldname.'\');" >
                        <div class="cell medium-6 small-12 acym__listing__title acym__listing__title__dynamics">'.$fieldname.'</div>
                        <div class="cell medium-6 small-12 acym__listing__title acym__listing__title__dynamics">'.acym_translation($description).'</div>
                     </div>';
        }

        if ('Joomla' == 'Joomla' && ACYM_J37) {
            $groups = acym_loadObjectList('SELECT id, title FROM #__fields_groups WHERE context = "com_users.user" AND state = 1 ORDER BY title ASC');
            $defaultGroup = new stdClass();
            $defaultGroup->id = 0;
            $defaultGroup->title = acym_translation('ACYM_NO_GROUP');
            array_unshift($groups, $defaultGroup);

            $customFields = acym_loadObjectList('SELECT id, title, group_id FROM #__fields WHERE context = "com_users.user" AND state = 1 ORDER BY title ASC');
            if (!empty($customFields)) {
                $text .= '<h1 class="acym__popup__plugin__title cell" style="margin-top: 20px;">'.acym_translation('ACYM_CUSTOM_FIELDS').'</h1>';

                foreach ($groups as $oneGroup) {
                    foreach ($customFields as $oneCF) {
                        if ($oneCF->group_id != $oneGroup->id) {
                            continue;
                        }
                        $text .= '<div class="grid-x medium-12 cell acym__listing__row acym__listing__row__popup text-left" id="'.$oneCF->id.'customoption" onclick="changeUserTag(\''.$oneCF->id.'custom\');" >
                                    <div class="cell medium-6 small-12 acym__listing__title acym__listing__title__dynamics">'.$oneCF->title.'</div>
                                 </div>';
                    }
                }
                $text .= '</table></div>';
            }
        }

        $text .= '</div>';
        echo $text;
    }

    public function replaceUserInformation(&$email, &$user, $send = true)
    {
        $extractedTags = $this->acympluginHelper->extractTags($email, 'usertag');
        if (empty($extractedTags)) {
            return;
        }

        if (empty($this->customFields) && 'Joomla' == 'Joomla' && ACYM_J37) {
            $this->customFields = acym_loadObjectList('SELECT * FROM #__fields WHERE context = "com_users.user"', 'id');
            foreach ($this->customFields as &$oneCF) {
                if (!empty($oneCF->fieldparams)) {
                    $oneCF->fieldparams = json_decode($oneCF->fieldparams, true);
                }
            }
        }

        $tags = array();
        $receivervalues = array();
        foreach ($extractedTags as $i => $mytag) {
            if (isset($tags[$i])) {
                continue;
            }
            $mytag->default = '';

            $values = new stdClass();
            $idused = 0;
            $save = false;

            if (!empty($mytag->info) && $mytag->info == 'sender' && !empty($email->creator_id)) {
                $idused = $email->creator_id;
                $save = true;
            }

            if (!empty($mytag->info) && $mytag->info == 'current') {
                continue;
            }

            if ((empty($mytag->info) || $mytag->info == 'receiver') && !empty($user->cms_id)) {
                $idused = $user->cms_id;
            }

            if (!empty($idused) && empty($this->sendervalues[$idused]) && empty($receivervalues[$idused])) {
                $receivervalues[$idused] = acym_loadObject('SELECT * FROM '.$this->cmsUserVars->table.' WHERE '.$this->cmsUserVars->id.' = '.intval($idused).' LIMIT 1');

                if ($save) {
                    $this->sendervalues[$idused] = $receivervalues[$idused];
                }
            }

            if (!empty($this->sendervalues[$idused])) {
                $values = $this->sendervalues[$idused];
            } elseif (!empty($receivervalues[$idused])) {
                $values = $receivervalues[$idused];
            }

            if ($mytag->id == 'groups') {
                $groups = acym_getGroupsByUser($idused, true, true);
                $values->groups = implode(', ', $groups);
            }

            if (empty($mytag->type)) {
                $mytag->type = '';
            }

            if ($mytag->type == 'custom' && 'Joomla' == 'Joomla') {
                $mytag->id = intval($mytag->id);
                if (empty($mytag->id)) {
                    $replaceme = '';
                } else {
                    $userFieldVals = acym_loadResultArray('SELECT value FROM #__fields_values WHERE item_id = '.intval($idused).' AND field_id = '.intval($mytag->id));

                    $fieldValues = trim(implode(', ', $userFieldVals), ', ');
                    if (empty($fieldValues)) {
                        $defaultValue = acym_loadObject('SELECT default_value, type FROM #__fields WHERE id = '.intval($mytag->id));
                        if (($defaultValue->type == 'user' && !empty($defaultValue->default_value)) || ($defaultValue->type != 'user' && strlen($defaultValue->default_value) > 0)) {
                            $userFieldVals = array($defaultValue->default_value);
                        }
                    }

                    foreach ($userFieldVals as &$oneFieldVal) {
                        switch ($this->customFields[$mytag->id]->type) {
                            case 'radio':
                            case 'list':
                            case 'checkboxes':
                                foreach ($this->customFields[$mytag->id]->fieldparams['options'] as $oneOPT) {
                                    if ($oneOPT['value'] == $oneFieldVal) {
                                        $oneFieldVal = $oneOPT['name'];
                                        break;
                                    }
                                }
                                break;

                            case 'usergrouplist':
                                if (empty($this->usergroups)) {
                                    $this->usergroups = acym_loadObjectList('SELECT id, title FROM #__usergroups', 'id');
                                }

                                $oneFieldVal = $this->usergroups[$oneFieldVal]->title;
                                break;

                            case 'imagelist':
                                if (strlen($this->customFields[$mytag->id]->fieldparams['directory']) > 1) {
                                    $oneFieldVal = '/'.$oneFieldVal;
                                } else {
                                    $this->customFields[$mytag->id]->fieldparams['directory'] = '';
                                }
                                $oneFieldVal = '<img src="images/'.$this->customFields[$mytag->id]->fieldparams['directory'].$oneFieldVal.'" />';
                                break;

                            case 'url':
                                $oneFieldVal = '<a target="_blank" href="'.$oneFieldVal.'">'.$oneFieldVal.'</a>';
                                break;

                            case 'sql':
                                if (empty($this->customFields[$mytag->id]->options)) {
                                    $this->customFields[$mytag->id]->options = acym_loadObjectList($this->customFields[$mytag->id]->fieldparams['query'], 'value');
                                }

                                $oneFieldVal = $this->customFields[$mytag->id]->options[$oneFieldVal]->text;
                                break;

                            case 'user':
                                $oneFieldVal = acym_currentUserName($oneFieldVal);
                                break;

                            case 'media':
                                $oneFieldVal = '<img src="'.$oneFieldVal.'" />';
                                break;

                            case 'calendar':
                                $format = $this->customFields[$mytag->id]->fieldparams['showtime'] == '1' ? 'Y-m-d H:i' : 'Y-m-d';
                                $oneFieldVal = acym_date(strtotime($oneFieldVal), $format);
                                break;
                        }
                    }

                    $replaceme = implode(', ', $userFieldVals);
                }
            } else {
                $replaceme = isset($values->{$mytag->id}) ? $values->{$mytag->id} : $mytag->default;
            }

            $tags[$i] = $replaceme;
            $this->acympluginHelper->formatString($tags[$i], $mytag);
        }

        $this->acympluginHelper->replaceTags($email, $tags);
    }

    public function onAcymDeclareConditions(&$conditions)
    {

        $allGroups = acym_getGroups();
        $groups = array();
        foreach ($allGroups as $group) {
            $groups[$group->id] = $group->text;
        }
        $operatorIn = acym_get('type.operatorin');

        $conditions['user']['acy_group'] = new stdClass();
        $conditions['user']['acy_group']->name = acym_translation('ACYM_GROUP');
        $conditions['user']['acy_group']->option = '<div class="intext_select_automation cell">';
        $conditions['user']['acy_group']->option .= $operatorIn->display('acym_condition[conditions][__numor__][__numand__][acy_group][in]');
        $conditions['user']['acy_group']->option .= '</div>';
        $conditions['user']['acy_group']->option .= '<div class="intext_select_automation cell">';
        $conditions['user']['acy_group']->option .= acym_select($groups, 'acym_condition[conditions][__numor__][__numand__][acy_group][group]', null, 'class="acym__select"');
        $conditions['user']['acy_group']->option .= '</div>';

        if ('Joomla' == 'Joomla') {
            $conditions['user']['acy_group']->option .= '<div class="cell grid-x medium-3">';
            $conditions['user']['acy_group']->option .= acym_switch('acym_condition[conditions][__numor__][__numand__][acy_group][subgroup]', 1, acym_translation('ACYM_INCLUDE_SUB_GROUPS'));
            $conditions['user']['acy_group']->option .= '</div>';
        }


        $cmsFields = array();
        foreach (acym_getColumns('users', false) as $key => $column) {
            $cmsFields[$column] = $column;
        }

        if ('Joomla' == 'Joomla' && ACYM_J37) {
            $query = 'SELECT id, title 
						FROM #__fields 
						WHERE context = "com_users.user"
							AND state = 1
							AND type IN ("calendar", "checkboxes", "color", "integer", "list", "radio", "sql", "text", "url")
						ORDER BY title ASC';
            $customFields = acym_loadObjectList($query);
            foreach ($customFields as $oneCF) {
                $cmsFields['cf_'.$oneCF->id] = $oneCF->title;
            }
        }
        $excluded = array('password', 'params', 'activation', 'lastResetTime', 'resetCount', 'optKey', 'otep', 'requireReset', 'user_pass', 'user_activation_key');
        foreach ($excluded as $oneExcluded) {
            unset($cmsFields[$oneExcluded]);
        }

        $operator = acym_get('type.operator');

        $conditions['user']['acy_cmsfield'] = new stdClass();
        $conditions['user']['acy_cmsfield']->name = acym_translation('ACYM_ACCOUNT_USER_FIELD');
        $conditions['user']['acy_cmsfield']->option = '<div class="intext_select_automation cell">';
        $conditions['user']['acy_cmsfield']->option .= acym_select($cmsFields, 'acym_condition[conditions][__numor__][__numand__][acy_cmsfield][field]', null, 'class="acym__select"');
        $conditions['user']['acy_cmsfield']->option .= '</div>';
        $conditions['user']['acy_cmsfield']->option .= '<div class="intext_select_automation cell">';
        $conditions['user']['acy_cmsfield']->option .= $operator->display('acym_condition[conditions][__numor__][__numand__][acy_cmsfield][operator]');
        $conditions['user']['acy_cmsfield']->option .= '</div>';
        $conditions['user']['acy_cmsfield']->option .= '<input class="intext_input_automation cell" type="text" name="acym_condition[conditions][__numor__][__numand__][acy_cmsfield][value]">';

        $conditions['classic']['acy_totaluser'] = new stdClass();
        $conditions['classic']['acy_totaluser']->name = acym_translation('ACYM_NUMBER_OF_USERS');
        $conditions['classic']['acy_totaluser']->option = '<div class="cell shrink acym__automation__inner__text">'.acym_translation('ACYM_THERE_IS').'</div>';
        $conditions['classic']['acy_totaluser']->option .= '<div class="intext_select_automation cell">';
        $conditions['classic']['acy_totaluser']->option .= acym_select(array('=' => acym_translation('ACYM_EXACTLY'), '>' => acym_translation('ACYM_MORE_THAN'), '<' => acym_translation('ACYM_LESS_THAN')), 'acym_condition[conditions][__numor__][__numand__][acy_totaluser][operator]', null, 'class="intext_select_automation acym__select"');
        $conditions['classic']['acy_totaluser']->option .= '</div>';
        $conditions['classic']['acy_totaluser']->option .= '<input type="number" min="0" class="intext_input_automation cell" name="acym_condition[conditions][__numor__][__numand__][acy_totaluser][number]">';
        $conditions['classic']['acy_totaluser']->option .= '<div class="cell shrink acym__automation__inner__text">'.acym_translation('ACYM_ACYMAILING_USERS').'</div>';

        $conditions['both']['acy_toss'] = new stdClass();
        $conditions['both']['acy_toss']->name = acym_translation('ACYM_TOSS');
        $conditions['both']['acy_toss']->option = '<input type="hidden" name="acym_condition[conditions][__numor__][__numand__][acy_toss][toss]" value="true"><div class="acym__automation__inner__text">'.acym_translation('ACYM_TOSS_DESC').'</div>';
    }

    public function onAcymDeclareFilters(&$filters)
    {
        $allGroups = acym_getGroups();
        $groups = array();
        foreach ($allGroups as $group) {
            $groups[$group->id] = $group->text;
        }
        $operatorIn = acym_get('type.operatorin');

        $filters['acy_group'] = new stdClass();
        $filters['acy_group']->name = acym_translation('ACYM_GROUP');
        $filters['acy_group']->option = '<div class="intext_select_automation cell">';
        $filters['acy_group']->option .= $operatorIn->display('acym_action[filters][__numor__][__numand__][acy_group][in]');
        $filters['acy_group']->option .= '</div>';
        $filters['acy_group']->option .= '<div class="intext_select_automation cell">';
        $filters['acy_group']->option .= acym_select($groups, 'acym_action[filters][__numor__][__numand__][acy_group][group]', null, 'class="acym__select"');
        $filters['acy_group']->option .= '</div>';

        if ('Joomla' == 'Joomla') {
            $filters['acy_group']->option .= '<div class="cell grid-x medium-3">';
            $filters['acy_group']->option .= acym_switch('acym_action[filters][__numor__][__numand__][acy_group][subgroup]', 1, acym_translation('ACYM_INCLUDE_SUB_GROUPS'));
            $filters['acy_group']->option .= '</div>';
        }


        $cmsFields = array();
        foreach (acym_getColumns('users', false) as $key => $column) {
            $cmsFields[$column] = $column;
        }

        if ('Joomla' == 'Joomla' && ACYM_J37) {
            $query = 'SELECT id, title 
						FROM #__fields 
						WHERE context = "com_users.user"
							AND state = 1
							AND type IN ("calendar", "checkboxes", "color", "integer", "list", "radio", "sql", "text", "url")
						ORDER BY title ASC';
            $customFields = acym_loadObjectList($query);
            foreach ($customFields as $oneCF) {
                $cmsFields['cf_'.$oneCF->id] = $oneCF->title;
            }
        }
        $excluded = array('password', 'params', 'activation', 'lastResetTime', 'resetCount', 'optKey', 'otep', 'requireReset', 'user_pass', 'user_activation_key');
        foreach ($excluded as $oneExcluded) {
            unset($cmsFields[$oneExcluded]);
        }

        $operator = acym_get('type.operator');

        $filters['acy_cmsfield'] = new stdClass();
        $filters['acy_cmsfield']->name = acym_translation('ACYM_ACCOUNT_USER_FIELD');
        $filters['acy_cmsfield']->option = '<div class="intext_select_automation cell">';
        $filters['acy_cmsfield']->option .= acym_select($cmsFields, 'acym_action[filters][__numor__][__numand__][acy_cmsfield][field]', null, 'class="acym__select"');
        $filters['acy_cmsfield']->option .= '</div>';
        $filters['acy_cmsfield']->option .= '<div class="intext_select_automation cell">';
        $filters['acy_cmsfield']->option .= $operator->display('acym_action[filters][__numor__][__numand__][acy_cmsfield][operator]');
        $filters['acy_cmsfield']->option .= '</div>';
        $filters['acy_cmsfield']->option .= '<input class="intext_input_automation cell" type="text" name="acym_action[filters][__numor__][__numand__][acy_cmsfield][value]">';
    }

    public function onAcymProcessCondition_acy_toss(&$query, $option, $num, &$conditionNotValid)
    {
        if (!mt_rand(0, 1)) $conditionNotValid++;
    }

    public function onAcymProcessCondition_acy_totaluser(&$query, $option, $num, &$conditionNotValid)
    {
        $numberUsers = $query->count();
        $res = false;

        switch ($option['operator']) {
            case '=' :
                $res = $numberUsers == $option['number'];
                break;
            case '>' :
                $res = $numberUsers > $option['number'];
                break;
            case '<' :
                $res = $numberUsers < $option['number'];
                break;
        }

        if (!$res) $conditionNotValid++;
    }

    public function onAcymProcessCondition_acy_group(&$query, $options, $num, &$conditionNotValid)
    {
        if ('Joomla' == 'Joomla') {
            $operator = (empty($options['in']) || $options['in'] == 'in') ? 'IS NOT NULL AND cmsuser'.$num.'.user_id != 0' : "IS NULL";

            if (empty($options['subgroup'])) {
                $value = ' = '.intval($options['group']);
            } else {
                $lftrgt = acym_loadObject('SELECT lft, rgt FROM #__usergroups WHERE id = '.intval($options['group']));
                $allGroups = acym_loadResultArray('SELECT id FROM #__usergroups WHERE lft > '.intval($lftrgt->lft).' AND rgt < '.intval($lftrgt->rgt));
                array_unshift($allGroups, $options['group']);
                $value = ' IN ('.implode(', ', $allGroups).')';
            }

            $query->leftjoin['cmsuser'.$num] = "#__user_usergroup_map AS cmsuser$num ON cmsuser$num.user_id = user.cms_id AND cmsuser$num.group_id".$value;
            $query->where[] = "cmsuser$num.user_id ".$operator;
        } else {
            $operator = (empty($options['in']) || $options['in'] == 'in') ? 'IS NOT NULL AND cmsuser'.$num.'.user_id != 0' : "IS NULL";

            $query->leftjoin['cmsuser'.$num] = '#__usermeta AS cmsuser'.$num.' ON cmsuser'.$num.'.user_id = user.cms_id AND cmsuser'.$num.'.meta_key = "#__capabilities" AND cmsuser'.$num.'.meta_value LIKE '.acym_escapeDB('%'.strlen($options['group']).':"'.$options['group'].'"%');
            $query->where[] = "cmsuser$num.user_id ".$operator;
        }
        $affectedRows = $query->count();
        if (empty($affectedRows)) $conditionNotValid++;
    }

    public function onAcymProcessFilter_acy_group(&$query, $options, $num)
    {
        if ('Joomla' == 'Joomla') {
            $operator = (empty($options['in']) || $options['in'] == 'in') ? 'IS NOT NULL AND cmsuser'.$num.'.user_id != 0' : "IS NULL";

            if (empty($options['subgroup'])) {
                $value = ' = '.intval($options['group']);
            } else {
                $lftrgt = acym_loadObject('SELECT lft, rgt FROM #__usergroups WHERE id = '.intval($options['group']));
                $allGroups = acym_loadResultArray('SELECT id FROM #__usergroups WHERE lft > '.intval($lftrgt->lft).' AND rgt < '.intval($lftrgt->rgt));
                array_unshift($allGroups, $options['group']);
                $value = ' IN ('.implode(', ', $allGroups).')';
            }

            $query->leftjoin['cmsuser'.$num] = "#__user_usergroup_map AS cmsuser$num ON cmsuser$num.user_id = user.cms_id AND cmsuser$num.group_id".$value;
            $query->where[] = "cmsuser$num.user_id ".$operator;
        } else {
            $operator = (empty($options['in']) || $options['in'] == 'in') ? 'IS NOT NULL AND cmsuser'.$num.'.user_id != 0' : "IS NULL";

            $query->leftjoin['cmsuser'.$num] = '#__usermeta AS cmsuser'.$num.' ON cmsuser'.$num.'.user_id = user.cms_id AND cmsuser'.$num.'.meta_key = "#__capabilities" AND cmsuser'.$num.'.meta_value LIKE '.acym_escapeDB('%'.strlen($options['group']).':"'.$options['group'].'"%');
            $query->where[] = "cmsuser$num.user_id ".$operator;
        }
    }

    public function onAcymProcessFilterCount_acy_group(&$query, $options, $num)
    {
        $this->onAcymProcessFilter_acy_group($query, $options, $num);

        return acym_translation_sprintf('ACYM_SELECTED_USERS', $query->count());
    }

    public function onAcymProcessCondition_acy_cmsfield(&$query, $options, $num, &$conditionNotValid)
    {
        if (empty($options['field'])) {
            return;
        }

        if (strpos($options['field'], 'cf_') !== false) {
            $query->leftjoin['cmsuserfields'.$num] = '#__fields_values AS cmsuserfields'.$num.' ON cmsuserfields'.$num.'.item_id = user.cms_id AND cmsuserfields'.$num.'.field_id = '.intval($options['field']);
            $query->where[] = $query->convertQuery('cmsuserfields'.$num, 'value', $options['operator'], $options['value'], '');
        } else {
            $type = '';
            $query->leftjoin['cmsuser'.$num] = '#__users AS cmsuser'.$num.' ON cmsuser'.$num.'.id = user.cms_id';

            if (in_array($options['field'], array('registerDate', 'lastvisitDate', 'user_registered'))) {
                $type = 'datetime';
                $options['value'] = acym_replaceDate($options['value']);

                if (!is_numeric($options['value']) && strtotime($options['value']) !== false) {
                    $options['value'] = strtotime($options['value']);
                }
                if (is_numeric($options['value'])) {
                    $options['value'] = strftime('%Y-%m-%d %H:%M:%S', $options['value']);
                }
            }

            $query->where[] = $query->convertQuery('cmsuser'.$num, $options['field'], $options['operator'], $options['value'], $type);
        }
        $affectedRows = $query->count();
        if (empty($affectedRows)) $conditionNotValid++;
    }

    public function onAcymProcessFilter_acy_cmsfield(&$query, $options, $num)
    {
        if (empty($options['field'])) {
            return;
        }

        if (strpos($options['field'], 'cf_') !== false) {
            $query->leftjoin['cmsuserfields'.$num] = '#__fields_values AS cmsuserfields'.$num.' ON cmsuserfields'.$num.'.item_id = user.cms_id AND cmsuserfields'.$num.'.field_id = '.intval($options['field']);
            $query->where[] = $query->convertQuery('cmsuserfields'.$num, 'value', $options['operator'], $options['value'], '');
        } else {
            $type = '';
            $query->leftjoin['cmsuser'.$num] = '#__users AS cmsuser'.$num.' ON cmsuser'.$num.'.id = user.cms_id';

            if (in_array($options['field'], array('registerDate', 'lastvisitDate', 'user_registered'))) {
                $type = 'datetime';
                $options['value'] = acym_replaceDate($options['value']);

                if (!is_numeric($options['value']) && strtotime($options['value']) !== false) {
                    $options['value'] = strtotime($options['value']);
                }
                if (is_numeric($options['value'])) {
                    $options['value'] = strftime('%Y-%m-%d %H:%M:%S', $options['value']);
                }
            }

            $query->where[] = $query->convertQuery('cmsuser'.$num, $options['field'], $options['operator'], $options['value'], $type);
        }
    }

    public function onAcymProcessFilterCount_acy_cmsfield(&$query, $options, $num)
    {
        $this->onAcymProcessFilter_acy_cmsfield($query, $options, $num);

        return acym_translation_sprintf('ACYM_SELECTED_USERS', $query->count());
    }

    public function onAcymDeclareSummary_conditions(&$automationCondition)
    {
        if (!empty($automationCondition['acy_group'])) {
            if ('joomla' === ACYM_CMS) {
                $allGroups = acym_getGroups();
                $groups = array();
                foreach ($allGroups as $group) {
                    if ($automationCondition['acy_group']['group'] == $group->id) $automationCondition['acy_group']['group'] = $group->text;
                    $groups[$group->id] = $group->text;
                }
            } else {
                $automationCondition['acy_group']['group'] = acym_translation('ACYM_'.strtoupper($automationCondition['acy_group']['group']));
            }
            $finalText = acym_translation_sprintf('ACYM_CONDITION_ACY_GROUP_SUMMARY', acym_translation($automationCondition['acy_group']['in'] == 'in' ? 'ACYM_IS_IN' : 'ACYM_IS_NOT_IN'), $automationCondition['acy_group']['group']);
            if ('joomla' === ACYM_CMS) {
                $finalText .= $automationCondition['acy_group']['subgroup'] == 1 ? '' : ' '.acym_translation('ACYM_FILTER_ACY_GROUP_SUBGROUP_SUMMARY');
            }
            $automationCondition = $finalText;
        }
        if (!empty($automationCondition['acy_cmsfield'])) {
            $automationCondition = acym_translation_sprintf('ACYM_CONDITION_ACY_CMS_FIELD_SUMMARY', $automationCondition['acy_cmsfield']['field'], $automationCondition['acy_cmsfield']['operator'], $automationCondition['acy_cmsfield']['value']);
        }

        if (!empty($automationCondition['acy_totaluser'])) {
            $operators = array('=' => acym_translation('ACYM_EXACTLY'), '>' => acym_translation('ACYM_MORE_THAN'), '<' => acym_translation('ACYM_LESS_THAN'));
            $automationCondition = acym_translation('ACYM_THERE_IS').' '.strtolower($operators[$automationCondition['acy_totaluser']['operator']]).' '.$automationCondition['acy_totaluser']['number'].' '.acym_translation('ACYM_ACYMAILING_USERS');
        }

        if (!empty($automationCondition['acy_toss'])) {
            $automationCondition = acym_translation('ACYM_TOSS_DESC');
        }
    }

    public function onAcymDeclareSummary_filters(&$automationFilter)
    {
        if (!empty($automationFilter['acy_group'])) {
            if ('joomla' === ACYM_CMS) {
                $allGroups = acym_getGroups();
                $groups = array();
                foreach ($allGroups as $group) {
                    if ($automationFilter['acy_group']['group'] == $group->id) $automationFilter['acy_group']['group'] = $group->text;
                    $groups[$group->id] = $group->text;
                }
            } else {
                $automationFilter['acy_group']['group'] = acym_translation('ACYM_'.strtoupper($automationFilter['acy_group']['group']));
            }
            $finalText = acym_translation_sprintf('ACYM_FILTER_ACY_GROUP_SUMMARY', acym_translation($automationFilter['acy_group']['in'] == 'in' ? 'ACYM_IN' : 'ACYM_NOT_IN'), $automationFilter['acy_group']['group']);
            if ('joomla' === ACYM_CMS) {
                $finalText .= $automationFilter['acy_group']['subgroup'] == 1 ? '' : ' '.acym_translation('ACYM_FILTER_ACY_GROUP_SUBGROUP_SUMMARY');
            }
            $automationFilter = $finalText;
        }
        if (!empty($automationFilter['acy_cmsfield'])) {
            $automationFilter = acym_translation_sprintf('ACYM_FILTER_ACY_CMS_FIELD_SUMMARY', $automationFilter['acy_cmsfield']['field'], $automationFilter['acy_cmsfield']['operator'], $automationFilter['acy_cmsfield']['value']);
        }
    }

    public function onAcymDeclareSummary_actions(&$automationAction)
    {
        if (!empty($automationAction['acy_add_queue'])) {
            $mailClass = acym_get('class.mail');
            $mail = $mailClass->getOneById($automationAction['acy_add_queue']['mail_id']);
            if (empty($mail)) {
                $automationAction = '<span class="acym__color__red">'.acym_translation('ACYM_SELECT_A_MAIL').'</span>';
            } else {
                if (strpos($automationAction['acy_add_queue']['time'], '{time}') !== false) {
                    $addedTime = str_replace('{time}', '', $automationAction['acy_add_queue']['time']);
                    $automationAction['acy_add_queue']['time'] = time() + intval($addedTime);
                }
                $automationAction = acym_translation_sprintf('ACYM_ACTION_ADD_QUEUE_SUMMARY', $mail->name, acym_date($automationAction['acy_add_queue']['time'], 'd M Y H:i'));
            }
        }
        if (!empty($automationAction['acy_remove_queue'])) {
            $mailClass = acym_get('class.mail');
            $mail = $mailClass->getOneById($automationAction['acy_remove_queue']['mail_id']);
            $automationAction = empty($mail) ? '<span class="acym__color__red">'.acym_translation('ACYM_SELECT_A_MAIL').'</span>' : acym_translation_sprintf('ACYM_ACTION_REMOVE_QUEUE_SUMMARY', $mail->name);
        }
    }
}

