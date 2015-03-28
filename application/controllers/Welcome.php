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
        $this->load->helper('directory');
    }

    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
        // Build a list of orders
        // Get all files in data folder
        $files = directory_map('./data');

        // filter out orders
        $orders = array();
        preg_match_all('/(?<=order).*(?=\.xml*)/', implode("\n", $files), $orders);

        $orderlinks = array();
        foreach($orders[0] as $order)
        {
            $orderlink = array(
                'filenumber'   => $order,
            );
            $orderlinks[] = $orderlink;
        }

        // Present the list to choose from
        $this->data['pagebody'] = 'homepage';
        $this->data['orders'] =  $orderlinks;

        $this->render();
    }

    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
	// Build a receipt for the chosen order

	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
	$this->render();
    }


}
