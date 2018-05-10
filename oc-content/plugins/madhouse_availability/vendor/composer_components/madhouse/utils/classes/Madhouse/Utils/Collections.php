<?php

class Madhouse_Utils_Collections
{

    /**
     * Returns the (first) element in list $list that have the same id as $id.
     * @param $list (Array<Any>) list of element to inspect. Elements must have public method 'getId()'.
     * @param $id (Int) id of the element to find.
     * @return an element of $list or null.
     * @since 1.10
     */
    public static function findById($list, $id)
    {
    	foreach ($list as $i) {
    		if($i->getId() == $id) {
    			return $i;
    		}
    	}
    	return null;
    }

    /**
     * Returns the (first) element in list $list where field $field matches $value.
     * @param  Array<String, Any>   $list list of elements to inspect.
     * @param  String $field        field name to look for.
     * @param  Any $value           field value to look for.
     * @return Any                  the found element or null if not found.
     */
    public static function findByField($list, $field, $value)
    {
        foreach ($list as $i) {
            if($i[$field] == $value) {
                return $i;
            }
        }
        return null;
    }

    /**
     * TODO
     * @param  [type] $list [description]
     * @param  [type] $id   [description]
     * @return [type]       [description]
     */
    public static function filterById($list, $id)
    {
        return array_filter($list, function($v) use ($id) {
           if($v->getId() == $id) {
               return false;
           }
           return true;
        });
    }

    /**
     * Returns the list of the identifiers of elements of list $list.
     * @param $list (Array<Any>) list of element to inspect. Elements must have public method 'getId()'.
     * @return an array of integers.
     * @since 1.10
     */
    public static function getIdsFromList($list)
    {
        return array_map(
            function($v) {
                return $v->getId();
            },
            $list
        );
    }

    /**
     * Gets a particular field $field in the $list.
     * @param  Array<Any> $list  list of element to inspect.
     * @param  String $field     field name to get.
     * @return Array<Any>    array of $field values.
     */
    public static function getFieldsFromList($list, $field)
    {
        return array_map(
            function($v) use ($field) {
                return $v[$field];
            },
            $list
        );
    }

    /**
     * Sorts a list by a particular field.
     * @param  Array<Any> $list  list of element to inspect.
     * @param  String $field     field name to sort by.
     * @return Array<Any>    the list sorted.
     */
    public static function sortListByField($list, $field)
    {
        usort(
            $list,
            function($a, $b) use ($field) {
                if($a[$field] == $b[$field]) {
                    return 0;
                }
                return ($a[$field] < $b[$field]) ? -1 : 1;
            }
        );
        return $list;
    }
}

?>