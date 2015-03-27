<?php

/* Responsible for retrieving orders from xml files */
class Orders extends CI_Model
{
    protected $xml = null;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /* Gets and returns the customer data from specified xml file */
    public function GetCustomerName($filename)
    {
        $this->xml = simplexml_load_file(DATAPATH . $filename);
        
        return $this->xml->customer;
    }
    
    /* Gets and returns the order type from specified xml file */
    public function GetOrderType($filename)
    {
        $this->xml = simplexml_load_file(DATAPATH . $filename);
        
        return $this->xml['type'];
    }
    
    /* Gets and returns an array of burgers */
    public function FetchBurgers($filename)
    {
        $this->xml = simplexml_load_file(DATAPATH . $filename);
        
        $burgers = array();
        
        foreach ($this->xml->burger as $burger)
        {
            array_push($burgers, $burger);
        }

        return $burgers;
    }
    
    /* Populate the Burgers */
    public function GetBurgers($filename)
    {
        $burgers_objs = $this->FetchBurgers($filename);
        
        $burgers = array();
        
        $count = 0;
        foreach ($burgers_objs as $obj)
        {
            $newburger['count'] = $count;
            $newburger['patty'] = $obj->patty['type'];
            
            /* Get top cheese */                        
            $newburger['cheeses'] = $obj->cheeses['top'] . ' (top) ' . $obj->cheeses['bottom'] . ' (bottom)';
            
            /* Get Toppings */
            $newburger['toppings'] = '';
            foreach ($obj->topping as $topping)
            {
                $newburger['toppings'] .= $topping['type'] . ' ';
            }

            /* Get Sauces */
            $newburger['sauce'] = '';
            if (isset($obj->sauce['type']))
            {
                $newburger['sauce'] .= "Sauce: " . $obj->sauce['type'];
            }
            
            $count++;
            
            array_push($burgers, $newburger);
        }
        
        return $burgers;
    }
}

