<?php

declare(strict_types=1);

class Customer
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private int $fixDiscount;
    private int $varDiscount;
    private Group $group;


    public function __construct(int $id, string $firstName, string $lastName, int $fixDiscount, int $varDiscount, Group $group)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->fixDiscount = $fixDiscount;
        $this->varDiscount = $varDiscount;
        $this->group = $group;
    }

    public function getId():int {
        return $this->id;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFixDiscount(): int
    {
        return $this->fixDiscount;
    }

    public function getVarDiscount(): int
    {
        return $this->varDiscount;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }


    // -------------------check if var or fix discount of group is valuable---------------//

    private array $fixGroup = [];
    private array $varGroup = [];
    private int $totalFixDiscount = 0;
    private int $maxVarDiscount = 0;

    public function getAllGroups() {
        $groupLoader =  new GroupLoader();
        $this->addToFixVarArray($this->group);
        if ($this->group->getParentId() !== 0) {
            $parentGroup = $groupLoader->getGroups()[$this->group->getParentId()];
            while ($parentGroup->getParentId() !== 0) {
                $this->addToFixVarArray($parentGroup);
                $parentGroup = $groupLoader->getGroups()[$parentGroup->getParentId()];
            }
        }
    }

    public function valuableDiscount($varDiscount = []) {
        $this->getAllGroups();
        foreach ($this->fixGroup as $group) {
            $this->totalFixDiscount += $group->getFixDiscount();
        }
        foreach ($this->varGroup as $group) {
            array_push($varDiscount, $group->getVarDiscount());
        }
        if (count($varDiscount) > 0) {
            $this->maxVarDiscount = max($varDiscount);
        }

    }

    public function addToFixVarArray(Group $group) {
        if ($group->getFixDiscount()) {
            array_push($this->fixGroup, $group);
        } else {
            array_push($this->varGroup, $group);
        }
    }

    public function customerDiscount(Product $product, $result = []) {
        $this->valuableDiscount();

        if (($product->getPrice() - $this->totalFixDiscount) > ($product->getPrice() - $product->getPrice()*$this->maxVarDiscount/100)) {
            if ($this->getVarDiscount() && ($this->getVarDiscount() > $this->maxVarDiscount)) {
                $finalPrice = $product->getPrice() - $product->getPrice()*$this->getVarDiscount()/100;
            } elseif($this->getVarDiscount() && ($this->getVarDiscount() < $this->maxVarDiscount)) {
                $finalPrice = $product->getPrice() - $product->getPrice()*$this->maxVarDiscount/100;
            } else {
                $finalPrice = $product->getPrice() - $this->getFixDiscount() - $product->getPrice()*$this->maxVarDiscount/100;
            }
        } else {
            if ($this->getVarDiscount()) {
                $finalPrice = $product->getPrice() - $this->totalFixDiscount - $product->getPrice()*$this->getVarDiscount()/100;
            } else {
                $finalPrice = $product->getPrice() - $this->getFixDiscount() - $this->totalFixDiscount;
            }
        }
        $result[] = array('finalPrice' => $finalPrice, 'fixGroup' => $this->fixGroup, 'varGroup' => $this->varGroup, "customer" => $this);

        return $result;
    }

}