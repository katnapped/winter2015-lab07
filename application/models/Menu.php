<?php

/**
 * This is a "CMS" model for quotes, but with bogus hard-coded data.
 * This would be considered a "mock database" model.
 *
 * @author jim
 */
class Menu extends CI_Model {

    protected $xml = null;
    protected $patty_names = array();
    protected $patties = array();
    protected $cheeses = array();
    protected $toppings = array();
    protected $sauces = array();

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->xml = simplexml_load_file(DATAPATH . 'menu.xml');

        // build the list of patties - approach 1
        foreach ($this->xml->patties->patty as $patty) {
            $this->patty_names[(string)$patty['code']] = (string)$patty;
        }

        // build a full list of patties - approach 2
        foreach ($this->xml->patties->patty as $patty) {
            $record = new stdClass();
            $record->code = (string)$patty['code'];
            $record->name = (string)$patty;
            $record->price = (float) $patty['price'];
            $this->patties[(string)$record->code] = $record;
        }
        
        /* Build the list of cheeses */
        foreach ($this->xml->cheeses->cheese as $cheese)
        {
            $record = new stdClass();
            $record->code = (string)$cheese['code'];
            $record->name = (string)$cheese;
            $record->price = (float) $cheese['price'];
            $this->cheeses[(string)$record->code] = $record;
        }
        
        /* Build the list of toppings */
        foreach ($this->xml->toppings->topping as $topping)
        {
            $record = new stdClass();
            $record->code = (string)$topping['code'];
            $record->name = (string)$topping;
            $record->price = (float) $topping['price'];
            $this->toppings[(string)$record->code] = $record;
        }
        
        /* Build the list of sauces */
        /* Build the list of toppings */
        foreach ($this->xml->sauces->sauce as $sauce)
        {
            $record = new stdClass();
            $record->code = (string)$sauce['code'];
            $record->name = (string)$sauce;
            $record->price = (float) $sauce['price'];
            $this->sauces[(string)$record->code] = $record;
        }
    }

    // retrieve a list of patties, to populate a dropdown, for instance
    function patties() {
        return $this->patty_names;
    }

    // retrieve a patty record, perhaps for pricing
    function GetPattyPrice($code) {
        if (isset($this->patties[$code]))
        {
            return $this->patties[(string)$code]->price;
        }
        else
        {
            return null;
        }
    }
    
    /* Retreive Cheese Price */
    function GetCheesePrice($code)
    {
        if (isset($this->cheeses[$code]))
        {
            return $this->cheeses[(string)$code]->price;
        }
        else
        {
            return null;
        }
    }

    /* Retreive Topping Price */
    function GetToppingPrice($code)
    {
        if (isset($this->toppings[$code]))
        {
            return $this->toppings[(string)$code]->price;
        }
        else
        {
            return null;
        }
    }
    
    /* Retreive Sauces Price */
    function GetSaucePrice($code)
    {
        if (isset($this->sauces[$code]))
        {
            return $this->sauces[(string)$code]->price;
        }
        else
        {
            return null;
        }
    }
}
