<?php

declare(strict_types=1);

class GroupLoader extends Connection
{
    private array $groups;

    public function loadGroup(): void {
        $pdo = $this->openConnection();
        $groupsData = $pdo->prepare('SELECT * FROM customer_group');
        $groupsData->execute();
        $groups = $groupsData->fetchAll();
        foreach ($groups as $group) {
            $this->groups[$group['id']] = new Group((int)$group['id'], (string)$group['name'], (int)$group['parent_id'], 
            (int)$group['fixed_discount'], (int)$group['variable_discount']);
        }
    }

    public function getGroups() :array {
        $this->loadGroup();
        return $this->groups;
    }
}