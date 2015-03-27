<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
	parent::__construct();
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
	// Build a list of orders
        /* Load Directory Helper */
	$this->load->helper('directory');

        
        /* Get all files in directory */
        $files = directory_map('./data');
        
        /* Filter files to get "order~.xml" */
        $filesFiltered = array();
        foreach($files as $file)
        {
            $test = ".xml";
            $fileStart = "order";
            if ((substr_compare($file, $test, strlen($file)-strlen($test), strlen($test)) === 0)
                    and (substr_compare($file, $fileStart, 0, 5) === 0) )
            {
                array_push($filesFiltered, $file);
            }
        }   
        
        /* Create order associative array */
        $this->data['orders'] = array();
        foreach ($filesFiltered as $file)
        {
            $xml = simplexml_load_file(DATAPATH . $file);
            
            $order = array();
            $order['orderName'] = substr($file, 0, strlen($file) - 4) . ' (' . $xml->customer . ')';
            $order['fileName'] = $file;
            
            array_push($this->data['orders'], $order);
        }
        
        
	// Present the list to choose from
	$this->data['pagebody'] = 'homepage';
	$this->render();
    }
    
    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
	// Build a receipt for the chosen order
	/* Load orders model */
        $this->load->model('orders');
        
        $this->data['customer'] = $this->orders->GetCustomerName($filename);
        $this->data['order_type'] = $this->orders->GetOrderType($filename);
        $this->data['burgers'] = $this->orders->GetBurgers($filename);
        $this->data['total'] = $this->orders->GetOrderTotal($filename);
        
        //print_r($this->data['burgers']);
        
	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
	$this->render();
    }
    

}
