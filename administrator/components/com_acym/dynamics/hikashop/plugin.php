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

class plgAcymHikashop extends acymPlugin
{
    var $hikaConfig;
    var $currencyClass;
    var $imageHelper;
    var $productClass;
    var $translationHelper;

    public function __construct()
    {
        parent::__construct();
        $this->cms = 'Joomla';
        if (!defined('JPATH_ADMINISTRATOR') || !file_exists(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.'com_hikashop'.DS)) {
            $this->installed = false;
        }
        $this->name = 'hikashop';
    }

    public function insertOptions()
    {
        $plugin = new stdClass();
        $plugin->name = 'HikaShop';
        $plugin->icon = ACYM_DYNAMICS_URL.basename(__DIR__).'/icon.ico';
        $plugin->plugin = __CLASS__;

        return $plugin;
    }

    public function contentPopup()
    {
        acym_loadLanguageFile('com_hikashop', JPATH_SITE);

        $this->categories = acym_loadObjectList("SELECT category_id AS id, category_parent_id AS parent_id, category_name AS title FROM `#__hikashop_category` WHERE category_type = 'product'", 'id');

        $tabHelper = acym_get('helper.tab');
        $tabHelper->startTab(acym_translation('ACYM_ONE_BY_ONE'));

        $displayOptions = array(
            array(
                'title' => 'ACYM_DISPLAY',
                'type' => 'radio',
                'name' => 'type',
                'options' => array(
                    'title' => 'ACYM_TITLE_ONLY',
                    'intro' => 'ACYM_INTRO_ONLY',
                    'full' => 'ACYM_FULL_TEXT',
                ),
                'default' => 'full',
            ),
            array(
                'title' => 'ACYM_PRICE',
                'type' => 'radio',
                'name' => 'price',
                'options' => array(
                    'full' => 'ACYM_APPLY_DISCOUNTS',
                    'no_discount' => 'ACYM_NO_DISCOUNT',
                    'none' => 'ACYM_NO',
                ),
                'default' => 'full',
            ),
        );

        echo $this->acympluginHelper->displayOptions($displayOptions, $this->name);

        echo $this->getFilteringZone();

        $this->displayListing();

        $tabHelper->endTab();
        $tabHelper->startTab(acym_translation('ACYM_BY_CATEGORY'));

        $catOptions = array(
            array(
                'title' => 'ACYM_ORDER_BY',
                'type' => 'select',
                'name' => 'order',
                'options' => array(
                    'product_id' => 'ACYM_ID',
                    'product_created' => 'ACYM_DATE_CREATED',
                    'product_modified' => 'ACYM_MODIFICATION_DATE',
                    'product_name' => 'ACYM_TITLE',
                    'rand' => 'ACYM_RANDOM',
                ),
            ),
            array(
                'title' => 'ACYM_COLUMNS',
                'type' => 'text',
                'name' => 'cols',
                'default' => 1,
            ),
            array(
                'title' => 'ACYM_MAX_NB_ELEMENTS',
                'type' => 'text',
                'name' => 'max',
                'default' => 20,
            ),
        );

        $displayOptions = array_merge($displayOptions, $catOptions);

        echo $this->acympluginHelper->displayOptions($displayOptions, 'auto'.$this->name, 'grouped');

        echo $this->getCategoryListing();

        $tabHelper->endTab();
        $tabHelper->startTab(acym_translation('HIKA_ABANDONED_CART'));

        $methods = acym_loadObjectList('SELECT payment_id, payment_name FROM #__hikashop_payment', 'payment_id');

        $paymentMethods = array('' => 'ALL_PAYMENT_METHODS');
        foreach ($methods as $method) {
            $paymentMethods[$method->payment_id] = $method->payment_name;
        }

        $displayOptions = array(
            array(
                'title' => 'ACYM_DISPLAY',
                'type' => 'radio',
                'name' => 'type',
                'options' => array(
                    'title' => 'ACYM_TITLE_ONLY',
                    'intro' => 'ACYM_INTRO_ONLY',
                    'full' => 'ACYM_FULL_TEXT',
                ),
                'default' => 'full',
            ),
            array(
                'title' => 'PAYMENT_METHOD',
                'type' => 'select',
                'name' => 'paymentcart',
                'options' => $paymentMethods,
            ),
            array(
                'title' => 'ACYM_DATE_CREATED',
                'type' => 'intextfield',
                'name' => 'nbdayscart',
                'text' => 'DAYS_AFTER_ORDERING',
                'default' => 1,
            ),
        );

        echo $this->acympluginHelper->displayOptions($displayOptions, 'hikashop_abandonedcart', 'simple');

        $tabHelper->endTab();
        $tabHelper->startTab(acym_translation('ACYM_COUPON'));

        $query = "SELECT `product_id`, CONCAT(product_name, ' ( ', product_code, ' )') AS `title` 
                            FROM #__hikashop_product 
                            WHERE `product_type`='main' AND `product_published` = 1  
                            ORDER BY `product_code` ASC";
        $results = acym_loadObjectList($query);

        $products = array(0 => 'ACYM_NONE');
        foreach ($results as $result) {
            $products[$result->product_id] = $result->title;
        }

        $parent = acym_loadResult('SELECT category_id FROM #__hikashop_category WHERE category_parent_id = 0');

        $query = 'SELECT a.category_id, a.category_name  
                    FROM #__hikashop_category AS a 
                    WHERE a.category_type = "tax" 
                        AND a.category_published = 1 
                        AND a.category_parent_id != '.intval($parent).' 
                    ORDER BY a.category_ordering ASC';

        $results = acym_loadObjectList($query);

        $taxes = array(0 => 'ACYM_NONE');
        foreach ($results as $result) {
            $taxes[$result->category_id] = $result->category_name;
        }

        $query = 'SELECT currency_id AS value, CONCAT(currency_symbol, " ", currency_code) AS text FROM #__hikashop_currency WHERE currency_published = 1';
        $currencies = acym_loadObjectList($query);

        $displayOptions = array(
            array(
                'title' => 'DISCOUNT_CODE',
                'type' => 'text',
                'name' => 'code',
                'default' => '[name][key][value]',
                'class' => 'acym_plugin__larger_text_field',
                'large' => true,
            ),
            array(
                'title' => 'DISCOUNT_FLAT_AMOUNT',
                'type' => 'custom',
                'name' => 'flat',
                'output' => '<input type="text" name="flathikashop_coupon" id="flat" onchange="updateDynamichikashop_coupon();" value="0" class="acym_plugin_text_field" style="display: inline-block;" />
                            '.acym_select($currencies, 'currencyhikashop_coupon', null, 'onchange="updateDynamichikashop_coupon();" style="width: 80px;"'),
                'js' => 'otherinfo += "| flat:" + jQuery(\'input[name="flathikashop_coupon"]\').val();
                        otherinfo += "| currency:" + jQuery(\'[name="currencyhikashop_coupon"]\').val();',
            ),
            array(
                'title' => 'DISCOUNT_PERCENT_AMOUNT',
                'type' => 'text',
                'name' => 'percent',
                'default' => '0',
            ),
            array(
                'title' => 'DISCOUNT_START_DATE',
                'type' => 'date',
                'name' => 'start',
                'default' => '',
            ),
            array(
                'title' => 'DISCOUNT_END_DATE',
                'type' => 'date',
                'name' => 'end',
                'default' => '',
            ),
            array(
                'title' => 'MINIMUM_ORDER_VALUE',
                'type' => 'text',
                'name' => 'min',
                'default' => '0',
            ),
            array(
                'title' => 'DISCOUNT_QUOTA',
                'type' => 'text',
                'name' => 'quota',
                'default' => '',
            ),
            array(
                'title' => 'PRODUCT',
                'type' => 'select',
                'name' => 'product',
                'options' => $products,
                'default' => '0',
            ),
            array(
                'title' => 'TAXATION_CATEGORY',
                'type' => 'select',
                'name' => 'tax',
                'options' => $taxes,
                'default' => '0',
            ),
        );

        echo $this->acympluginHelper->displayOptions($displayOptions, 'hikashop_coupon', 'simple');

        $tabHelper->endTab();

        $tabHelper->display('plugin');
    }

    public function displayListing()
    {
        $query = 'SELECT SQL_CALC_FOUND_ROWS a.* FROM #__hikashop_product AS a ';
        $filters = [];

        $this->pageInfo = new stdClass();
        $this->pageInfo->limit = acym_getCMSConfig('list_limit');
        $this->pageInfo->page = acym_getVar('int', 'pagination_page_ajax', 1);
        $this->pageInfo->start = ($this->pageInfo->page - 1) * $this->pageInfo->limit;
        $this->pageInfo->search = acym_getVar('string', 'plugin_search', '');
        $this->pageInfo->filter_cat = acym_getVar('int', 'plugin_category', 0);
        $this->pageInfo->order = 'a.product_id';
        $this->pageInfo->orderdir = 'DESC';

        $searchFields = array('a.product_id', 'a.product_name', 'a.product_code');
        if (!empty($this->pageInfo->search)) {
            $searchVal = '%'.acym_getEscaped($this->pageInfo->search, true).'%';
            $filters[] = implode(" LIKE ".acym_escapeDB($searchVal)." OR ", $searchFields)." LIKE ".acym_escapeDB($searchVal);
        }
        if (!empty($this->pageInfo->filter_cat)) {
            $query .= 'JOIN #__hikashop_product_category AS b ON a.product_id = b.product_id';
            $filters[] = "b.category_id = ".intval($this->pageInfo->filter_cat);
        }
        if (!empty($filters)) {
            $query .= ' WHERE ('.implode(') AND (', $filters).')';
        }
        if (!empty($this->pageInfo->order)) {
            $query .= ' ORDER BY '.acym_secureDBColumn($this->pageInfo->order).' '.acym_secureDBColumn($this->pageInfo->orderdir);
        }

        $rows = acym_loadObjectList($query, '', $this->pageInfo->start, $this->pageInfo->limit);
        $this->pageInfo->total = acym_loadResult('SELECT FOUND_ROWS()');


        $listingOptions = [
            'header' => [
                'product_name' => [
                    'label' => 'ACYM_TITLE',
                    'size' => '7',
                ],
                'product_created' => [
                    'label' => 'ACYM_DATE_CREATED',
                    'size' => '4',
                    'type' => 'date',
                ],
                'product_id' => [
                    'label' => 'ACYM_ID',
                    'size' => '1',
                    'class' => 'text-center',
                ],
            ],
            'id' => 'product_id',
            'rows' => $rows,
        ];

        echo $this->getElementsListing($listingOptions);
    }

    public function replaceContent(&$email)
    {
        $this->_replaceAuto($email);
        $this->_replaceOne($email);
    }

    public function _replaceAuto(&$email)
    {
        $this->generateByCategory($email);
        if (empty($this->tags)) {
            return;
        }
        $this->acympluginHelper->replaceTags($email, $this->tags, true);
    }

    public function generateByCategory(&$email)
    {
        $tags = $this->acympluginHelper->extractTags($email, 'auto'.$this->name);
        $return = new stdClass();
        $return->status = true;
        $return->message = '';
        $this->tags = array();

        if (empty($tags)) {
            return $return;
        }

        foreach ($tags as $oneTag => $parameter) {
            if (isset($this->tags[$oneTag])) continue;

            $allcats = explode('-', $parameter->id);
            $selectedArea = array();
            foreach ($allcats as $oneCat) {
                if (empty($oneCat)) continue;
                $selectedArea[] = intval($oneCat);
            }

            $query = 'SELECT DISTINCT b.`product_id` FROM #__hikashop_product_category AS a 
                    LEFT JOIN #__hikashop_product AS b ON a.product_id = b.product_id';

            $where = array();

            if (!empty($selectedArea)) {
                $where[] = 'a.category_id IN ('.implode(',', $selectedArea).')';
            }

            $where[] = "b.`product_published` = 1";

            if (!empty($parameter->filter) && !empty($email->params['lastgenerateddate'])) {
                $condition = 'b.`product_created` > '.acym_escapeDB($email->params['lastgenerateddate']);
                if ($parameter->filter == 'modify') {
                    $condition .= ' OR b.`product_modified` > '.acym_escapeDB($email->params['lastgenerateddate']);
                }
                $where[] = $condition;
            }

            $query .= ' WHERE ('.implode(') AND (', $where).')';

            if (!empty($parameter->order)) {
                $ordering = explode(',', $parameter->order);
                if ($ordering[0] == 'rand') {
                    $query .= ' ORDER BY rand()';
                } else {
                    $query .= ' ORDER BY b.`'.acym_secureDBColumn(trim($ordering[0])).'` '.acym_secureDBColumn(trim($ordering[1]));
                }
            }

            if (!empty($parameter->max)) {
                $query .= ' LIMIT '.intval($parameter->max);
            }
            $allArticles = acym_loadResultArray($query);

            if (!empty($parameter->min) && count($allArticles) < $parameter->min) {
                $return->status = false;
                $return->message = 'Not enough products for the tag '.$oneTag.' : '.count($allArticles).' / '.$parameter->min;
            }

            $this->tags[$oneTag] = $this->finalizeCategoryFormat($this->name, $allArticles, $parameter);
        }

        return $return;
    }

    private function _replaceOne(&$email)
    {
        $tags = $this->acympluginHelper->extractTags($email, $this->name);
        if (empty($tags)) return;

        $this->readmore = empty($email->template->readmore) ? JText::_('ACYM_READ_MORE') : '<img src="'.ACYM_LIVE.$email->template->readmore.'" alt="'.JText::_('ACYM_READ_MORE', true).'" />';

        if (!include_once(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.'com_hikashop'.DS.'helpers'.DS.'helper.php')) return;

        $this->hikaConfig = hikashop_config();
        $this->productClass = hikashop_get('class.product');
        $this->imageHelper = hikashop_get('helper.image');
        $this->currencyClass = hikashop_get('class.currency');
        $this->translationHelper = hikashop_get('helper.translation');

        $tagsReplaced = array();
        foreach ($tags as $i => $oneTag) {
            if (isset($tagsReplaced[$i])) continue;
            $tagsReplaced[$i] = $this->_replaceContent($oneTag, $email);
        }

        $this->acympluginHelper->replaceTags($email, $tagsReplaced, true);
    }

    public function _replaceContent($tag, &$email)
    {
        if (empty($tag->lang) && !empty($email->language)) {
            $tag->lang = $email->language;
        }

        $query = 'SELECT b.*, a.*
                    FROM #__hikashop_product AS a
                    LEFT JOIN #__hikashop_file AS b ON a.product_id = b.file_ref_id AND file_type = "product"
                    WHERE a.product_id = '.intval($tag->id).'
                    ORDER BY b.file_ordering ASC, b.file_id ASC';

        $product = acym_loadObject($query);

        if (empty($product)) {
            if (acym_isAdmin()) {
                acym_enqueueMessage('The product "'.$tag->id.'" could not be loaded', 'notice');
            }

            return '';
        }

        if ($product->product_type == 'variant') {
            $query = 'SELECT * 
                        FROM #__hikashop_variant AS a 
                        LEFT JOIN #__hikashop__characteristic AS b ON a.variant_characteristic_id = b.characteristic_id 
                        WHERE a.variant_product_id = '.intval($tag->id).' 
                        ORDER BY a.ordering';
            $product->characteristics = acym_loadObjectList($query);

            $query = 'SELECT b.*, a.*
                        FROM #__hikashop_product AS a
                        LEFT JOIN #__hikashop_file AS b ON a.product_id = b.file_ref_id AND file_type = "product"
                        WHERE a.product_id = '.intval($product->product_parent_id).'
                        ORDER BY b.file_ordering ASC, b.file_id ASC';
            $parentProduct = acym_loadObject($query);

            $this->productClass->checkVariant($product, $parentProduct);
        }

        if ($this->translationHelper->isMulti(true, false)) {
            $this->acympluginHelper->translateItem($product, $tag, 'hikashop_product');
        }

        $varFields = array();
        foreach ($product as $fieldName => $oneField) {
            $varFields['{'.$fieldName.'}'] = $oneField;
        }

        $tag->itemid = 0;
        $main_currency = $currency_id = (int)$this->hikaConfig->get('main_currency', 1);
        $zone_id = explode(',', $this->hikaConfig->get('main_tax_zone', 0));

        $zone_id = count($zone_id) ? array_shift($zone_id) : 0;

        $ids = array($product->product_id);
        $discount_before_tax = (int)$this->hikaConfig->get('discount_before_tax', 0);
        $this->currencyClass->getPrices($product, $ids, $currency_id, $main_currency, $zone_id, $discount_before_tax);
        $finalPrice = '';
        if (empty($tag->price) || $tag->price == 'full') {
            $finalPrice = @$this->currencyClass->format($product->prices[0]->price_value_with_tax, $product->prices[0]->price_currency_id);
            if (!empty($product->discount)) {
                $finalPrice = '<span style="text-decoration: line-through;">'.$this->currencyClass->format($product->prices[0]->price_value_without_discount_with_tax, $product->prices[0]->price_currency_id).'</span> '.$finalPrice;
            }
        } elseif ($tag->price == 'no_discount') {
            $finalPrice = $this->currencyClass->format($product->prices[0]->price_value_without_discount_with_tax, $product->prices[0]->price_currency_id);
        }
        $varFields['{finalPrice}'] = $finalPrice;

        if (empty($tag->type) || $tag->type == 'full') {
            $description = $product->product_description;
        } else {
            $pos = strpos($product->product_description, '<hr id="system-readmore"');
            if ($pos !== false) {
                $description = substr($product->product_description, 0, $pos);
            } else {
                $description = substr($product->product_description, 0, 100).'...';
            }
        }

        $link = 'index.php?option=com_hikashop&ctrl=product&task=show&cid='.$product->product_id;
        if (!empty($tag->lang)) {
            $link .= '&lang='.substr($tag->lang, 0, strpos($tag->lang, ','));
        }
        if (!empty($tag->itemid)) {
            $link .= '&Itemid='.$tag->itemid;
        }
        if (!empty($product->product_canonical)) {
            $link = $product->product_canonical;
        }
        $link = acym_frontendLink($link, false);
        $varFields['{link}'] = $link;

        $varFields['{pictHTML}'] = '';
        if (!empty($product->file_path)) {
            $img = $this->imageHelper->getThumbnail($product->file_path, null);
            if ($img->success) {
                $varFields['{pictHTML}'] = $img->url;
            } else {
                $varFields['{pictHTML}'] = $this->imageHelper->display($product->file_path, false, $product->product_name);
            }
        }
        $varFields['{pictHTML}'] = ltrim($varFields['{pictHTML}'], './');

        $title = $product->product_name;
        if (!empty($finalPrice)) {
            $title .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$finalPrice;
        }

        $picture = '';
        $contentText = '';
        if (empty($tag->type) || $tag->type != 'title') {
            $picture = $varFields['{pictHTML}'];
            $contentText = $description;
        }

        $format = new stdClass();
        $format->tag = $tag;
        $format->title = $title;
        $format->afterTitle = '';
        $format->afterArticle = '';
        $format->imagePath = $picture;
        $format->description = $contentText;
        $format->link = $link;
        $format->cols = empty($tag->nbcols) ? 1 : intval($tag->nbcols);
        $format->customFields = [];
        $result = '<div class="acym_product">'.$this->acympluginHelper->getStandardDisplay($format).'</div>';

        return $this->finalizeElementFormat($this->name, $result, $tag, $varFields);
    }

    public function replaceUserInformation(&$email, &$user, $send = true)
    {
        if (!include_once(rtrim(JPATH_ADMINISTRATOR, DS).DS.'components'.DS.'com_hikashop'.DS.'helpers'.DS.'helper.php')) return;

        $this->hikaConfig = hikashop_config();

        $this->_replaceAbandonedCarts($email, $user);
        $this->_replaceCoupons($email, $user, $send);
    }

    public function _replaceAbandonedCarts(&$email, &$user)
    {
        $tags = $this->acympluginHelper->extractTags($email, 'hikashop_abandonedcart');
        if (empty($tags)) {
            return;
        }

        $tagsReplaced = array();
        foreach ($tags as $i => $oneTag) {
            if (isset($tagsReplaced[$i])) {
                continue;
            }
            $tagsReplaced[$i] = $this->_replaceAbandonedCart($oneTag, $user);
        }

        $this->acympluginHelper->replaceTags($email, $tagsReplaced, true);

        $this->_replaceOne($email);
    }

    public function _replaceAbandonedCart($oneTag, $user)
    {
        if (empty($user->cms_id)) {
            return '';
        }

        $delay = 0;
        if (!empty($oneTag->nbdayscart)) {
            $delay = ($oneTag->nbdayscart * 86400);
        }

        $senddate = time() - intval($delay);

        $createdstatus = $this->hikaConfig->get('order_created_status', 'created');

        $myquery = 'SELECT c.product_id
					FROM #__hikashop_order AS a
					LEFT JOIN #__hikashop_order AS b
						ON a.order_user_id = b.order_user_id
						AND b.order_id > a.order_id
					JOIN #__hikashop_order_product AS c
						ON a.order_id = c.order_id
					JOIN #__hikashop_user AS hikauser
						ON a.order_user_id = hikauser.user_id ';

        if (!empty($oneTag->paymentcart)) {
            $myquery .= 'JOIN #__hikashop_payment AS payment
                            ON payment.payment_type = a.order_payment_method
                            AND payment.payment_id = '.intval($oneTag->paymentcart);
        }

        $myquery .= ' WHERE hikauser.user_cms_id = '.intval($user->cms_id).' AND a.order_status = '.acym_escapeDB($createdstatus).' AND b.order_id IS NULL ';
        $myquery .= ' AND FROM_UNIXTIME(a.order_created,"%Y %d %m") = FROM_UNIXTIME('.$senddate.',"%Y %d %m")';

        $allArticles = acym_loadResultArray($myquery);

        return $this->finalizeCategoryFormat($this->name, $allArticles, $oneTag);
    }

    public function _replaceCoupons(&$email, &$user, $send = true)
    {
        $tags = $this->acympluginHelper->extractTags($email, 'hikashop_coupon');
        if (empty($tags)) {
            return;
        }

        $tagsReplaced = array();
        foreach ($tags as $i => $oneTag) {
            if (isset($tagsReplaced[$i])) {
                continue;
            }
            if (!$send || empty($user->id)) {
                $tagsReplaced[$i] = '<i>'.acym_translation('ACYM_CHECK_EMAIL_COUPON').'</i>';
            } else {
                $tagsReplaced[$i] = $this->generateCoupon($oneTag, $user, $i);
            }
        }

        $this->acympluginHelper->replaceTags($email, $tagsReplaced, true);
    }

    public function generateCoupon($tag, $user, $raw)
    {
        if (empty($tag->code)) {
            list($minimum_order, $quota, $start, $end, $percent_amount, $flat_amount, $currency_id, $code, $product_id, $tax_id) = explode('|', $raw);
            $minimum_order = substr($minimum_order, strpos($minimum_order, ':') + 1);
            $tax_id = intval($tax_id);
        } else {
            $minimum_order = $tag->min;
            $quota = $tag->quota;
            $start = $tag->start;
            $end = $tag->end;
            $percent_amount = $tag->percent;
            $flat_amount = $tag->flat;
            $currency_id = $tag->currency;
            $code = $tag->code;
            $product_id = $tag->product;
            $tax_id = $tag->tax;
        }

        $key = acym_generateKey(5);

        if ($percent_amount > 0) {
            $value = $percent_amount;
        } else {
            $value = $flat_amount;
        }

        $value = str_replace(',', '.', $value);

        if ($start) {
            $start = hikashop_getTime($start);
        }
        if ($end) {
            $end = hikashop_getTime($end);
        }

        $clean_name = strtoupper($user->name);
        $space = strpos($clean_name, ' ');
        if (!empty($space)) {
            $clean_name = substr($clean_name, 0, $space);
        }

        $code = str_replace(
            array(
                '[name]',
                '[clean_name]',
                '[subid]',
                '[email]',
                '[key]',
                '[flat]',
                '[percent]',
                '[value]',
                '[prodid]',
            ),
            array(
                $user->name,
                $clean_name,
                $user->id,
                $user->email,
                $key,
                $flat_amount,
                $percent_amount,
                $value,
                $product_id,
            ),
            $code
        );

        $query = 'INSERT IGNORE INTO #__hikashop_discount (
            `discount_code`,
            `discount_percent_amount`,
            `discount_flat_amount`,
            `discount_type`,
            `discount_start`,
            `discount_end`,
            `discount_minimum_order`,
            `discount_quota`,
            `discount_currency_id`,
            `discount_product_id`,
            `discount_tax_id`,
            `discount_published`
		) VALUES (
		    '.acym_escapeDB($code).',
		    '.acym_escapeDB($percent_amount).',
		    '.acym_escapeDB($flat_amount).',
		    "coupon",
		    '.acym_escapeDB($start).',
		    '.acym_escapeDB($end).',
		    '.acym_escapeDB($minimum_order).',
		    '.acym_escapeDB($quota).',
		    '.acym_escapeDB($currency_id).',
		    '.acym_escapeDB($product_id).',
		    '.acym_escapeDB($tax_id).',
		    1
        )';

        acym_query($query);

        return $code;
    }

    public function searchProduct()
    {
        $return = [];
        $search = acym_getVar('cmd', 'search', '');
        $products = acym_loadObjectList('SELECT `product_id`, `product_name` FROM `#__hikashop_product` WHERE `product_name` LIKE '.acym_escapeDB('%'.$search.'%').' ORDER BY `product_name`');

        foreach ($products as $oneProduct) {
            $return[] = [$oneProduct->product_id, $oneProduct->product_name];
        }

        echo json_encode($return);
    }

    public function onAcymDeclareConditions(&$conditions)
    {
        $categories = array(
            'any' => acym_translation('ACYM_ANY_CATEGORY'),
        );
        $cats = acym_loadObjectList('SELECT `category_id`, `category_name` FROM #__hikashop_category WHERE `category_type` = "product" ORDER BY `category_name`');
        foreach ($cats as $oneCat) {
            $categories[$oneCat->category_id] = $oneCat->category_name;
        }

        $conditions['user']['hikapurchased'] = new stdClass();
        $conditions['user']['hikapurchased']->name = acym_translation_sprintf('ACYM_COMBINED_TRANSLATIONS', 'HikaShop', acym_translation('ACYM_PURCHASED'));
        $conditions['user']['hikapurchased']->option = '<div class="cell grid-x grid-margin-x">';

        $conditions['user']['hikapurchased']->option .= '<div class="cell acym_vcenter shrink">'.acym_translation('ACYM_BOUGHT').'</div>';

        $conditions['user']['hikapurchased']->option .= '<div class="intext_select_automation cell">';
        $ajaxParams = json_encode(
            [
                'plugin' => __CLASS__,
                'trigger' => 'searchProduct',
            ]
        );
        $conditions['user']['hikapurchased']->option .= acym_select(
            [],
            'acym_condition[conditions][__numor__][__numand__][hikapurchased][product]',
            null,
            'class="acym__select acym_select2_ajax" data-placeholder="'.acym_translation('ACYM_AT_LEAST_ONE_PRODUCT', true).'" data-params="'.acym_escape($ajaxParams).'"'
        );
        $conditions['user']['hikapurchased']->option .= '</div>';

        $conditions['user']['hikapurchased']->option .= '<div class="intext_select_automation cell">';
        $conditions['user']['hikapurchased']->option .= acym_select($categories, 'acym_condition[conditions][__numor__][__numand__][hikapurchased][category]', 'any', 'class="acym__select"');
        $conditions['user']['hikapurchased']->option .= '</div>';

        $conditions['user']['hikapurchased']->option .= '</div>';

        $conditions['user']['hikapurchased']->option .= '<div class="cell grid-x grid-margin-x">';
        $conditions['user']['hikapurchased']->option .= acym_dateField('acym_condition[conditions][__numor__][__numand__][hikapurchased][datemin]', '', 'cell shrink');
        $conditions['user']['hikapurchased']->option .= '<span class="acym__content__title__light-blue acym_vcenter margin-bottom-0 cell shrink"><</span>';
        $conditions['user']['hikapurchased']->option .= '<span class="acym_vcenter">'.acym_translation('ACYM_DATE_CREATED').'</span>';
        $conditions['user']['hikapurchased']->option .= '<span class="acym__content__title__light-blue acym_vcenter margin-bottom-0 cell shrink"><</span>';
        $conditions['user']['hikapurchased']->option .= acym_dateField('acym_condition[conditions][__numor__][__numand__][hikapurchased][datemax]', '', 'cell shrink');
        $conditions['user']['hikapurchased']->option .= '</div>';


        $orderStatuses = acym_loadObjectList('SELECT `orderstatus_id` AS value, `orderstatus_name` AS text FROM #__hikashop_orderstatus ORDER BY `orderstatus_name`');

        $paymentMethods = array('any' => acym_translation('ACYM_ANY_PAYMENT_METHOD'));
        $payments = acym_loadObjectList('SELECT `payment_id`, `payment_name` FROM #__hikashop_payment ORDER BY `payment_name`');
        foreach ($payments as $oneMethod) {
            $paymentMethods[$oneMethod->payment_id] = $oneMethod->payment_name;
        }

        $conditions['user']['hikareminder'] = new stdClass();
        $conditions['user']['hikareminder']->name = acym_translation_sprintf('ACYM_COMBINED_TRANSLATIONS', 'HikaShop', acym_translation('ACYM_REMINDER'));
        $conditions['user']['hikareminder']->option = '<div class="cell">';
        $conditions['user']['hikareminder']->option .= acym_translation_sprintf(
            'ACYM_ORDER_WITH_STATUS',
            '<input type="number" name="acym_condition[conditions][__numor__][__numand__][hikareminder][days]" value="1" min="1" class="intext_input"/>',
            '<div class="intext_select_automation cell margin-right-1">'.acym_select(
                $orderStatuses,
                'acym_condition[conditions][__numor__][__numand__][hikareminder][status]',
                null,
                'class="acym__select"'
            ).'</div>'
        );
        $conditions['user']['hikareminder']->option .= '<div class="intext_select_automation cell">';
        $conditions['user']['hikareminder']->option .= acym_select(
            $paymentMethods,
            'acym_condition[conditions][__numor__][__numand__][hikareminder][payment]',
            'any',
            'class="acym__select"'
        );
        $conditions['user']['hikareminder']->option .= '</div>';
        $conditions['user']['hikareminder']->option .= '</div>';
    }

    public function onAcymProcessCondition_hikapurchased(&$query, $options, $num, &$conditionNotValid)
    {
        $this->processConditionFilter_hikapurchased($query, $options, $num);
        $affectedRows = $query->count();
        if (empty($affectedRows)) $conditionNotValid++;
    }

    private function processConditionFilter_hikapurchased(&$query, $options, $num)
    {
        $query->join['hikapurchased_order'.$num] = '#__hikashop_order AS order'.$num.' ON order'.$num.'.order_user_id = user.cms_id';

        $query->where[] = 'order'.$num.'.order_user_id != 0';
        $query->where[] = 'order'.$num.'.order_type = "sale"';
        $query->where[] = 'order'.$num.'.order_status = "confirmed"';

        if (!empty($options['datemin'])) {
            $options['datemin'] = acym_replaceDate($options['datemin']);
            if (!is_numeric($options['datemin'])) $options['datemin'] = strtotime($options['datemin']);
            if (!empty($options['datemin'])) {
                $query->where[] = 'order'.$num.'.order_created > '.acym_escapeDB($options['datemin']);
            }
        }

        if (!empty($options['datemax'])) {
            $options['datemax'] = acym_replaceDate($options['datemax']);
            if (!is_numeric($options['datemax'])) $options['datemax'] = strtotime($options['datemax']);
            if (!empty($options['datemax'])) {
                $query->where[] = 'order'.$num.'.order_created < '.acym_escapeDB($options['datemax']);
            }
        }

        if (!empty($options['product'])) {
            $query->join['hikapurchased_order_product'.$num] = '#__hikashop_order_product AS hikaop'.$num.' ON order'.$num.'.order_id = hikaop'.$num.'.order_id';
            $query->where[] = 'hikaop'.$num.'.product_id = '.intval($options['product']);
        } elseif (!empty($options['category']) && $options['category'] != 'any') {
            $query->join['hikapurchased_order_product'.$num] = '#__hikashop_order_product AS hikaop'.$num.' ON order'.$num.'.order_id = hikaop'.$num.'.order_id';
            $query->join['hikapurchased_order_cat'.$num] = '#__hikashop_product_category AS hikapc'.$num.' ON hikaop'.$num.'.product_id = hikapc'.$num.'.product_id';
            $query->where[] = 'hikapc'.$num.'.category_id = '.intval($options['category']);
        }
    }

    public function onAcymProcessCondition_hikareminder(&$query, $options, $num, &$conditionNotValid)
    {
        $this->processConditionFilter_hikareminder($query, $options, $num);
        $affectedRows = $query->count();
        if (empty($affectedRows)) $conditionNotValid++;
    }

    private function processConditionFilter_hikareminder(&$query, $options, $num)
    {
        $options['days'] = intval($options['days']);

        $query->join['hikareminder_order'.$num] = '#__hikashop_order AS order'.$num.' ON order'.$num.'.order_user_id = user.cms_id';
        $query->where[] = 'order'.$num.'.order_user_id != 0';
        $query->where[] = 'order'.$num.'.order_type = "sale"';
        $query->where[] = 'order'.$num.'.order_status = '.acym_escapeDB($options['status']);

        $query->where[] = 'FROM_UNIXTIME(order'.$num.'.order_created, "%Y-%m-%d") = '.acym_escapeDB(date('Y-m-d', time() - ($options['days'] * 86400)));

        if (!empty($options['payment']) && $options['payment'] != 'any') {
            $query->where[] = 'order'.$num.'.order_payment_id = '.intval($options['payment']);
        }
    }

    public function onAcymDeclareSummary_conditions(&$automationCondition)
    {
        $this->summaryConditionFilters($automationCondition);
    }

    private function summaryConditionFilters(&$automationCondition)
    {
        if (!empty($automationCondition['hikapurchased'])) {
            if (empty($automationCondition['hikapurchased']['product'])) {
                $product = acym_translation('ACYM_AT_LEAST_ONE_PRODUCT');
            } else {
                $product = acym_loadResult('SELECT `product_name` FROM #__hikashop_product WHERE `product_id` = '.intval($automationCondition['hikapurchased']['product']));
            }

            $cats = acym_loadObjectList('SELECT `category_id`, `category_name` FROM #__hikashop_category WHERE `category_type` = "product"', 'category_id');
            $category = empty($cats[$automationCondition['hikapurchased']['category']]) ? acym_translation('ACYM_ANY_CATEGORY') : $cats[$automationCondition['hikapurchased']['category']]->category_name;

            $finalText = acym_translation_sprintf('ACYM_CONDITION_PURCHASED', $product, $category);

            $dates = [];
            if (!empty($automationCondition['hikapurchased']['datemin'])) {
                $dates[] = acym_translation('ACYM_AFTER').' '.acym_replaceDate($automationCondition['hikapurchased']['datemin'], true);
            }

            if (!empty($automationCondition['hikapurchased']['datemax'])) {
                $dates[] = acym_translation('ACYM_BEFORE').' '.acym_replaceDate($automationCondition['hikapurchased']['datemax'], true);
            }

            if (!empty($dates)) {
                $finalText .= ' '.implode(' '.acym_translation('ACYM_AND').' ', $dates);
            }

            $automationCondition = $finalText;
        }

        if (!empty($automationCondition['hikareminder'])) {

            $orderStatuses = acym_loadObjectList('SELECT `orderstatus_id`, `orderstatus_name` FROM #__hikashop_orderstatus', 'orderstatus_id');
            $paymentMethods = acym_loadObjectList('SELECT `payment_id`, `payment_name` FROM #__hikashop_payment', 'payment_id');

            $automationCondition = acym_translation_sprintf(
                'ACYM_CONDITION_ECOMMERCE_REMINDER',
                intval($automationCondition['hikareminder']['days']),
                $orderStatuses[$automationCondition['hikareminder']['status']]->orderstatus_name,
                empty($paymentMethods[$automationCondition['hikareminder']['payment']]) ? acym_translation('ACYM_ANY_PAYMENT_METHOD') : $paymentMethods[$automationCondition['hikareminder']['payment']]
            );
        }
    }

    public function onAcymDeclareFilters(&$filters)
    {
        $newFilters = [];

        $this->onAcymDeclareConditions($newFilters);
        foreach ($newFilters as $oneType) {
            foreach ($oneType as $oneFilterName => $oneFilter) {
                if (!empty($oneFilter->option)) $oneFilter->option = str_replace(['acym_condition', '[conditions]'], ['acym_action', '[filters]'], $oneFilter->option);
                $filters[$oneFilterName] = $oneFilter;
            }
        }
    }

    public function onAcymProcessFilterCount_hikapurchased(&$query, $options, $num)
    {
        $this->onAcymProcessFilter_hikapurchased($query, $options, $num);

        return acym_translation_sprintf('ACYM_SELECTED_USERS', $query->count());
    }

    public function onAcymProcessFilter_hikapurchased(&$query, $options, $num)
    {
        $this->processConditionFilter_hikapurchased($query, $options, $num);
    }

    public function onAcymProcessFilterCount_hikareminder(&$query, $options, $num)
    {
        $this->onAcymProcessFilter_hikareminder($query, $options, $num);

        return acym_translation_sprintf('ACYM_SELECTED_USERS', $query->count());
    }

    public function onAcymProcessFilter_hikareminder(&$query, $options, $num)
    {
        $this->processConditionFilter_hikareminder($query, $options, $num);
    }

    public function onAcymDeclareSummary_filters(&$automationFilter)
    {
        $this->summaryConditionFilters($automationFilter);
    }
}

