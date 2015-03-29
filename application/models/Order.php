<?php

/**
 * This is a "CMS" model for quotes, but with bogus hard-coded data.
 * This would be considered a "mock database" model.
 *
 * @author jim
 */
class Order extends CI_Model {

    protected $xml = null;
    public $customer;
    public $type;
    public $orderInstructions = "";
    public $burgers = array();
    public $total = 0.00;

    // Constructor
    public function __construct($filename="") {
        parent::__construct();

        if(!empty($filename))
        {
            $this->xml = simplexml_load_file(DATAPATH . $filename);

            // Get order details
            $this->customer = (string) $this->xml->customer;
            $this->type = (string) $this->xml['type'];
            if (isset($this->xml->special))
            {
                $this->orderInstructions = (string) $this->xml->special;
            }

            // Create burger
            $burgernum = 1;

            foreach ($this->xml->burger as $burger)
            {
                // reset options for new burger
                $cheeses = "";
                $sauces = "";
                $toppings = "";

                // Create burger detail
                $burger_detail = array();

                // burger number
                $burger_detail['num'] = $burgernum++;
                // burger patty
                $burger_detail['patty'] = $burger->patty['type'];

                // burger cheeses
                if (isset($burger->cheeses['top']))
                {
                    $cheeses .= $burger->cheeses['top'] . "(top), ";
                }

                if (isset($burger->cheeses['bottom']))
                {
                    $cheeses .= $burger->cheeses['bottom'] . "(bottom)";
                }

                $burger_detail['cheese'] = $cheeses;

                // burger toppings
                if (!isset($burger->topping))
                {
                    $toppings .= "none";
                }
                else
                {
                    foreach($burger->topping as $topping)
                    {
                        $toppings .= $topping['type'] . ", ";
                    }
                }

                $burger_detail['toppings'] = $toppings;

                // burger sauces
                if (!isset($burger->sauce))
                {
                    $sauces .= "none";
                }
                else
                {
                    foreach($burger->sauce as $sauce)
                    {
                        $sauces .= $sauce['type'] . ", ";
                    }
                }
                $burger_detail['sauces'] = $sauces;

                // burger instructions
                if (isset($burger->instructions))
                {
                    $burger_detail['instructions'] = (string) $burger->instructions;
                }
                else
                {
                    $burger_detail['instructions'] = "";
                }

                // Compute cost
                $cost = $this->getBurgerCost($burger);

                $burger_detail['cost'] = $cost;
                $this->total += $cost;

                // Add burger to order
                $this->burgers[] = $burger_detail;
            }
        }
    }

    private function getBurgerCost($burger)
    {
        $burgerTotal = 0.00;

        // Add the patty price to the total
        $burgerTotal += $this->menu->getPatty((string) $burger->patty['type'])->price;

        // Add the cheeses price to the total
        if (isset($burger->cheeses['top']))
        {
            $burgerTotal += $this->menu->getCheese((string) $burger->cheeses['top'])->price;
        }

        if (isset($burger->cheeses['bottom']))
        {
            $burgerTotal += $this->menu->getCheese((string) $burger->cheeses['bottom'])->price;
        }

        // Add the toppings price to the total
        foreach ($burger->topping as $topping)
        {
            $burgerTotal += $this->menu->getTopping((string) $topping['type'])->price;
        }

        // Add the sauces price to the total
        foreach ($burger->sauce as $sauce)
        {
            $burgerTotal += $this->menu->getSauce((string) $sauce['type'])->price;
        }

        return $burgerTotal;
    }
}
