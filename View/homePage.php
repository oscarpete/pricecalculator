<?php require 'includes/header.php' ?>
<form action="" method="post" class="">
    
    <select name="customer">
        <?php foreach ($customers as $customer) :?>
            <option value="<?php echo $customer->getId()?>"><?php echo $customer->getFirstName()." ".$customer->getLastName()?></option>
        <?php endforeach;?>
    </select>

    <select name="product">
        <?php foreach ($products as $product) :?>
            <option value="<?php echo $product->getId()?>"><?php echo $product->getName()?> - <?php echo $product->getPrice()?> </option>
        <?php endforeach;?>
    </select>
    <input type="submit" value="Check"> 
</form>


<?php if (isset($myCustomer) && isset($myProduct) && isset($data)):?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Product</th>
            <th scope="col">Price</th>
            <th scope="col">Discount</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row"><?php echo $myCustomer->getFirstName()?></th>
            <th scope="row"><?php echo $myCustomer->getLastName()?></th>
            <th scope="row"><?php echo $myProduct->getName()?></th>
            <th scope="row"><?php echo ($myProduct->getPrice()/100)." €"?></th>
            <th scope="row"><?php echo (($myProduct->getPrice()-$data[0]['finalPrice'])/100)." €"?></th>
        </tr>
        <tr>
            <td colspan="5" class="text-center"><h2>Discounts</h2></td>
        </tr>
        <tr>
            <td scope="row">Final Price</td>
            <td scope="row">Variable Group Detail</td>
            <td scope="row">Fix Group Detail</td>
            <td scope="row" colspan="2">Customer Discount Detail</td>
        </tr>
        <tr>
            <th scope="row"><?php echo ($data[0]['finalPrice']/100)." €"?></th>
            <th scope='row'>
                <?php if ($data[0]['varGroup'] !== []): ?>
                    <?php foreach ($data[0]['varGroup'] as $varGroup): ?>
                        <li><?php echo "VarGroup :".$varGroup->getName()?></li>
                        <li><?php echo "Var Discount(%) :".$varGroup->getVarDiscount()?></li>
                    <?php endforeach;?>
                <?php endif;?>
            </th>
            <th scope='row'>
                <?php if ($data[0]['fixGroup'] !== []): ?>
                    <?php foreach ($data[0]['fixGroup'] as $fixGroup): ?>
                        <li><?php echo "FixGroup :".$fixGroup->getName()?></li>
                        <li><?php echo "Fix Discount :".($fixGroup->getFixDiscount()/100)." €"?></li>
                    <?php endforeach;?>
                <?php endif;?>
            </th>
            <th scope="row" colspan="2">
                <li><?php echo $data[0]['customer']->getFixDiscount()." €"?></li>
                <li><?php echo $data[0]['customer']->getVarDiscount()." %"?></li>
            </th>
        </tr>
        </tbody>
    </table>
<?php endif;?>

<?php require 'includes/footer.php' ?>