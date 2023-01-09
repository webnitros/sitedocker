<?php

class bxUrl extends xPDOSimpleObject
{
    /**
     * Редирект на страницу
     */
    public function redirect()
    {
        $url = $this->get('url');
        $this->xpdo->sendRedirect($url);
    }
}