<?php


class ProductLoader extends Connection
{
    private array $products;


    public function loadProduct() :void {
        $pdo = $this->openConnection();
        $productsData = $pdo->prepare('SELECT * FROM product');
        $productsData->execute();
        $products = $productsData->fetchAll();
        foreach ($products as $product) {
            $this->products[$product['id']] = new Product((int)$product['id'], (string)$product['name'], (string)$product['price']);
        }
    }

    public function getProducts(): array {
        $this->loadProduct();
        return $this->products;
    }
}