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
        
        $count = 1;
        foreach ($burgers_objs as $obj)
        {
            $newburger['count'] = $count;
            $newburger['patty'] = $obj->patty['type'];
            
            /* Get top cheese */                        
            $newburger['cheeses'] = '';
            if (isset($obj->cheeses['top']))
            {
                $newburger['cheeses'] .= $obj->cheeses['top'] . ' (top) ';
            }
            if (isset($obj->cheeses['bottom']))
            {
                $newburger['cheeses'] .= $obj->cheeses['bottom'] . ' (bottom)';
            }
            
            /* Get Toppings */
            $newburger['toppings'] = '';
            if (isset($obj->topping))
            {
                $newburger['toppings'] = 'Topping: ';
                $moreThanTwo = FALSE;
                foreach ($obj->topping as $topping)
                {
                    if ($moreThanTwo == TRUE)
                    {
                        $newburger['toppings'] .= ', ';
                    }
                    $newburger['toppings'] .= $topping['type'];
                    $moreThanTwo = TRUE;
                }
                $newburger['toppings'] .= '</br>';
            }            

            /* Get Sauces */
            $newburger['sauce'] = '';
            if (isset($obj->sauce))
            {
                $newburger['sauce'] .= "Sauce: ";
                $moreThanTwo = FALSE;
                foreach ($obj->sauce as $sauce)
                {
                    if ($moreThanTwo == TRUE)
                    {
                        $newburger['sauce'] .= ', ';
                    }
                    $newburger['sauce'] .= $sauce['type'] . ' ';
                    $moreThanTwo = TRUE;
                }
                $newburger['sauce'] .= '</br>';
            }
            
            /* Get burger price */
            $newburger['price'] = $this->GetBurgerPrice($obj);
            
            $count++;
            
            array_push($burgers, $newburger);
        }
        
        return $burgers;
    }
    
    /* Get Burger Price */
    private function GetBurgerPrice($burger)
    {
        $this->load->model('menu');
        
        $price = 0;
        $price += $this->menu->GetPattyPrice((string)$burger->patty['type']);
        $price += $this->menu->GetCheesePrice((string)$burger->cheeses['top']);
        $price += $this->menu->GetCheesePrice((string)$burger->cheeses['bottom']);
        foreach ($burger->topping as $topping)
        {
            $price += $this->menu->GetToppingPrice((string)$topping['type']);
        }
        foreach ($burger->sauce as $sauce)
        {
            $price += $this->menu->GetSaucePrice((string)$sauce['type']);
        }

        
        
        return $price;
    }
    
    /* Get order total */
    public function GetOrderTotal($filename)
    {
        $burgers = $this->GetBurgers($filename);
        
        $total = 0;
        foreach ($burgers as $burger)
        {
            $total += $burger['price'];
        }
        
        return $total;
    }
}

