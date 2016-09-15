<?php

class TakebackCustomizationsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'items_browse_sql',
        'collections_browse_sql',
        'tags_browse_sql'
    );

    /**
     * Sets the default sort field to DC:Date, and the default order to ASC.
     */
    public function hookItemsBrowseSql($args) {

        if((is_admin_theme()) || (isset($_REQUEST['sort_field']))) {
            return;
        }

        $select = $args['select'];
        $select->reset('order');

        $sort_field = 'Dublin Core,Date';
        $sort_dir = 'ASC';

        get_db()->getTable('Item')->applySorting($select, $sort_field, $sort_dir);

    }

    /**
     * Order Collections
     */
    public function hookCollectionsBrowseSql($args) {

        if((is_admin_theme()) || (isset($_REQUEST['sort_field']))) {
            return;
        }

        $select = $args['select'];
        $select->reset(Zend_Db_Select::ORDER);

        $sort_string = "CASE WHEN et_sort.text REGEXP '<[^>]*>' = 1 THEN TRIM(SUBSTR(et_sort.text, INSTR(et_sort.text ,' '))) ELSE et_sort.text END ASC";
        $sort_string = "FIELD(collections.id,12,15,19,20,6,11,18,17,16,13,14,21)";
        $sort_order = new Zend_Db_Expr($sort_string);
        $select->order($sort_order);

        get_db()->getTable('Collection')->applySorting($select, 'Dublin Core,Title', 'ASC');

    }

    /**
     * Hijack the tags query.
     */
    public function hookTagsBrowseSql($args) {
        if(is_admin_theme()) {
            return;
        }

        $select = $args['select'];
        $select = $select->where("tags.name NOT IN('institutional racism','semantics')");
    }

}

