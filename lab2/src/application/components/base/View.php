<?php

abstract class View 
{
    protected $pageTpl = null;

    abstract public function render($pageData);
}