<?php

/**
 * Created by FsFlex.
 * User: VH
 * Date: 9/19/2017
 * Time: 10:27 AM
 */
class EF4VCGridBuilder
{
    public function do_shortcode($atts)
    {
        //var_dump($atts);die();
        if(is_callable(array($this,'content')))
        {
            ob_start();
            $callback_return = $this->content($atts);
            $content = ob_get_clean();
            $content = $content.$callback_return;
            return $content;
        }
    }
}

