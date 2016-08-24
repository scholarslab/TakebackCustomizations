<?php

class TakebackCustomizationsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'items_browse_sql'
    );

    /**
     * Sets the default sort field to DC:Date, and the default order to ASC.
     */
    public function hookItemsBrowseSql($args) {
        $select = $args['select'];
        $params = $args['params'];

        if (!isset($_REQUEST['sort_field'])) {
            $sort_field = 'Dublin Core,Date';
            $sort_dir = 'ASC';
            $select->reset('order');
            get_db()->getTable('Item')->applySorting($select, $sort_field, $sort_dir);
        }
    }
}

