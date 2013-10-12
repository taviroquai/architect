<div class="well">
    <label>Shopping Cart Demo</label>
    <div id="cart">
        <form action="<?=$checkoutUrl?>" method="post">
            <div class="pull-right">
                <label>Currency</label>
                <select name="currency">
                    <?php foreach ($this->model->currency_options as $opt) { ?>
                    <option <?=$cart->currency == $opt ? 'selected' : ''?> 
                        value="<?=$opt?>"><?=$opt?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="clearfix"></div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th class="span3">
                            Cost
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart->items as $i => $item) { ?>
                    <tr>
                        <td>
                            <a href="<?=$checkoutUrl?>?del=<?=$i?>">
                                <span class="label-important label">
                                    <i class="icon-white icon-remove"></i>
                                </span>
                            </a>
                            <?=$item->product->name?></td>
                        <td><?=$item->product->price?></td>
                        <td>
                            <select name="quantity[<?=$i?>]" class="span1">
                                <?php foreach ($this->model->quantity_options as $opt) { ?>
                                <option <?=$item->quantity == $opt ? 'selected' : ''?> 
                                    value="<?=$opt?>"><?=$opt?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><?=round($item->quantity * $item->product->price, 2)?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>

                    <tr>
                        <th>Shipping</th>
                        <td colspan="2">
                            <select name="shipping">
                                <?php foreach ($this->model->shipping_options as $opt) { ?>
                                <option <?=$cart->shipping == $opt ? 'selected' : ''?> 
                                    value="<?=$opt?>"><?=$opt?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td  class="span3"><?=$cart->shipping_cost?></td>
                    </tr>
                    <tr>
                        <th colspan="3">Tax</th>
                        <td><?=$cart->tax_cost?></td>
                    </tr>
                    <tr>
                        <th colspan="3">Total</th>
                        <td><strong><?=$cart->total_cost?></strong></td>
                    </tr>
                    <tr>
                        <th>Payment</th>
                        <td colspan="2">
                            <select name="payment">
                                <?php foreach ($this->model->payment_options as $opt) { ?>
                                <option <?=$cart->payment == $opt ? 'selected' : ''?> 
                                    value="<?=$opt?>"><?=$opt?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <div class="text-center">
                                <input type="submit" name="pay" value="Checkout &gt;&gt;&gt;" class="btn-primary btn-large" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
<script type="text/javascript">
    $('#cart select').on('change', function() {
        $('#cart form').submit();
    });
</script>