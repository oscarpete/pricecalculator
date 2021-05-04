<?php

class HomepageController
{
    public function displayData() {

        $cusLoader = new CustomerLoader();
        $customers = $cusLoader->getCustomers();
        $proLoader = new ProductLoader();
        $products = $proLoader->getProducts();

        if (isset($_POST['customer']) && isset($_POST['product'])) {
            $myCustomer = $customers[$_POST['customer']];
            $myProduct = $products[$_POST['product']];

            $data = $myCustomer->customerDiscount($myProduct);
        }

        require 'View/homePage.php';
    }

}