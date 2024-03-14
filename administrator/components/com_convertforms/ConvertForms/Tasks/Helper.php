<?php

/**
 * @package         Convert Forms
 * @version         4.3.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace ConvertForms\Tasks;

defined('_JEXEC') or die('Restricted access');

class Helper
{
    public static function readRepeatSelect($items)
    {
        if (!$items)
        {
            return;
        }

        return array_filter(array_map(function($item)
        {
            if (isset($item['value']))
            {
                return $item['value'];
            }
        }, $items));
    }
}

?>