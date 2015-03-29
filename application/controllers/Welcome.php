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
        $this->load->model('order');
        $this->load->model('menu');
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
        //Extract number from order<num>.xml files
        //preg_match_all('/(?<=order).*(?=\.xml*)/', implode("\n", $files), $orders);

        preg_match_all('/order.*\.xml/', implode("\n", $files), $orders);

        $orderlinks = array();
        foreach($orders[0] as $order)
        {
            $orderlink = array(
                'file'   => $order,
                'order'  => preg_split("/\./", $order)[0],
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

        $order = new Order($filename);

    	// Build a receipt for the chosen order
        $this->data['order'] = preg_split("/\./", $filename)[0];
        $this->data['customer'] = $order->customer;
        $this->data['type'] = $order->type;
        $this->data['burgers'] = $order->burgers;
        $this->data['total'] = $order->total;
        $this->data['special'] = $order->orderInstructions;

        //var_dump($order);
        // Present the list to choose from
        $this->data['pagebody'] = 'justone';
        $this->render();
    }


}
