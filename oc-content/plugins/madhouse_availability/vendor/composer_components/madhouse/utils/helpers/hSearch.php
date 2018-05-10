<?php 
	/**
     * Gets current search category id
     *
     * @return int
     */
    function mdh_search_category_id($c) {
        $categories = $c;
        $category   = array();
        $where      = array();

        foreach($categories as $cat) {
            if( is_numeric($cat) ) {
                $where[] = "a.pk_i_id = " . $cat;
            } else {
                $slug_cat = explode( "/", trim($cat, "/") );
                $where[]  = "b.s_slug = '" . addslashes( $slug_cat[count($slug_cat)-1] ) . "'";
            }
        }

        if( empty($where) ) {
            return null;
        }

        // TODO: not the best way to do it
        $categories = Category::newInstance()->listWhere( implode(" OR ", $where) );
        foreach($categories as $cat) {
            $category[] = $cat['pk_i_id'];
        }

        return $category;
    }
?>