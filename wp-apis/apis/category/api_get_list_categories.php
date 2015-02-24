<?php

/**
 * Get list categories
 * Used for display list category with articles count
 * @author NTQ-SOFT
 */
class api_get_list_categories extends api_response {
            
    protected function execute() {
        $args = array(
                    'hide_empty'         => 1,
                    'exclude'           => array(self::$UNCATEGORY,self::$RECOMMENDATION_CATE
            )
        );
        $categories = get_categories($args);
        //var_dump($categories_list);
        $arr_categories = array();
        foreach ($categories as $category) {
            $arr_categories[] = array (
                            'id' => $category->term_id,
                            'name' => $category->name,
                            'article_count' => $category->count
            );
        }
        $this->code = self::$CODES['common']['success'];
        $this->data = $arr_categories;
    }
}