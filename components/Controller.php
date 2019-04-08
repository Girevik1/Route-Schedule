<?php

class Controller
{

    /**
     * перенаправление $url
     * @param $url
     * @return bool
     */
    public function redirect($url)
    {
        header('Location: ' . $url);

        return true;
    }

}