<?php
  namespace MT\UI;
  
  use Nette\Application\UI\Control,
   Nette\Object;
  
  class Menu extends Control
  { 
    public $presenter;
    public $config;
    public $type;
    
    public function initialize($menu, $presenter, $type)
    {
      $this->presenter=$presenter;
      $this->config=$menu;
      $this->type=$type;
    }
    
    public function render()
    {
      $template = $this->template;
      $template->setFile(dirname(__FILE__).'/menu.'.$this->type.'.latte');
      
      $template->presenter=$this->presenter;
      $template->config=$this->config;
      
      $template->render();
    }
  }
  
  class MenuGroup extends Object
  {
    public $target;
    public $text;
    public $items;
    
    public function __construct($target, $text="New link")
    {
      $this->target = $target;
      $this->text = $text;
      $this->items = array();
    }
    
    public function addItem($item)
    {
      $this->items[]=$item;
    }
  }
  
  class MenuItem extends Object
  {
    public $target;
    public $text;
    
    public function __construct($target, $text="New link")
    {
      $this->target = $target;
      $this->text = $text;
    }
  }