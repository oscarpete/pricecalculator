<?php

declare(strict_types=1);

class CustomerLoader extends Connection
{
    private array $customers;

    public function loadCustomer(): void {

        $pdo = $this->openConnection();
        $statement = $pdo->prepare('SELECT * FROM customer');
        $statement->execute();
        $customers = $statement->fetchAll();
        $loader = new GroupLoader();
        foreach ($customers as $customer) {
            $group = $loader->getGroups()[$customer['group_id']];
            $this->customers[$customer['id']] = new Customer((int)$customer['id'], (string)$customer['firstname'], (string)$customer['lastname'], (int)$customer['fixed_discount'], (int)$customer['variable_discount'], $group);
        }
    }

    public function getCustomers(): array {
        $this->loadCustomer();
        return $this->customers;
    }
}