<?php
/**
 * This file is essentially a stripped down version of /views/invoices/view.php
 * Any changes you make to that formatting, you may consider adding to this.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?php echo $page_title; ?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <style type="text/css">
<?php echo file_get_contents("css/print" . $this->settings_model->getSettingsID() . ".css"); ?>
        </style>
    </head>
    <body>

        <table>
            <tr>
                <td width="60%">
                </td>
                <td>

                    <h2>
			<div class="logo_holder">
                            <?php if (isset($company_logo)) {
                                echo $company_logo;
                            } ?>
                        </div>
                        <br />

                            <?php echo $companyInfo->company_name; ?>
                            <!--span><?php echo $this->lang->line('invoice_invoice'); ?></span-->
                    </h2>
                    <p>
                        <?php echo $companyInfo->address1; ?>
                        <?php if ($companyInfo->address2 != '') {
                            echo '<br />' . $companyInfo->address2;
                        } ?><br />
                        <?php echo $companyInfo->postal_code . ' ' . $companyInfo->city; ?>
                        <?php echo $companyInfo->province; ?><br />
                        <?php echo $companyInfo->country; ?><br />
                        <!--?php echo auto_link(prep_url($companyInfo->website)); ?-->
                    </p>
                </td>
            </tr>
        </table>

        <h3><?php /*echo $this->lang->line('invoice_bill_to');*/ ?>
            <?php echo $row->name; ?>
        </h3>

        <p>
            <?php if ($row->address1 != '') {
                echo $row->address1 . '<br />';
            } ?>
            <?php if ($row->address2 != '') {
                echo $row->address2 . '<br />';
            } ?>
            <?php if ($row->postal_code != '') {
                echo $row->postal_code . ' ';
            } ?>
            <?php if ($row->city != '') {
                echo $row->city;
            } ?>
            <?php if ($row->province != '') {
                if ($row->city != '') {
                    echo ', ';
                } echo $row->province;
            } ?><br />
            <?php if ($row->country != '') {
                echo $row->country;
            } ?><br />
            <!--?php echo auto_link(prep_url($row->website)); ?-->
            <br /><br />
            <?php if ($row->tax_code != '') {
                echo '<br />' . $this->lang->line('settings_tax_code') . ': ' . $row->tax_code;
            } ?>
        </p>
        <br />
            <p>
                <strong>
                    <?php echo $this->lang->line('invoice_invoice'); ?> <?php echo $row->invoice_number; ?><br />
                    <?php echo $date_invoice_issued; ?>
                </strong>
            </p>
        <br />
        <table class="invoice_items stripe">
            <tr>
                <th width="13%"><?php echo $this->lang->line('invoice_quantity'); ?></th>
                <th width="50%"><?php echo $this->lang->line('invoice_work_description'); ?></th>
                <th width="27%"><?php echo $this->lang->line('invoice_amount_item'); ?></th>
                <th width="20%"><?php echo $this->lang->line('invoice_total'); ?></th>
            </tr>
            <?php foreach ($items->result() as $item): ?>
                <tr valign="top">
                    <td><p><?php echo str_replace('.00', '', $item->quantity); ?></p></td>
                    <td><?php echo nl2br(str_replace(array('\n', '\r'), "\n",
                            $item->work_description)); ?></td>
                    <td><p><?php echo $currency_symbol . " " . str_replace('.',
                    $this->config->item('currency_decimal'), $item->amount); ?> <?php if ($item->taxable == 0) {
                    /*echo '(' . $this->lang->line('invoice_not_taxable') . ')'*/;
            } ?></p></td>
                    <td><p><?php echo $currency_symbol . " " . number_format($item->quantity * $item->amount,
                    2, $this->config->item('currency_decimal'),
                    $this->config->item('currency_thousands')); ?></p></td>
                </tr>
            <?php endforeach; ?>

            <?php if ( ($total_tax1 > 0 || $total_tax2 > 0) ||
                       ($total_tax1 == 0 && $total_tax2 == 0 && $this->settings_model->get_setting('display_zero_tax') == "y")) {?>

                <tr>
                    <td><p></p></td>
                    <td><p></p></td>
                    <td><p><?php echo $this->lang->line('invoice_net_amount'); ?></p></td>
                    <td><p><?php echo $currency_symbol . " " . $total_notax_number; ?></p></td>
                </tr>
                <tr>
                    <td><p></p></td>
                    <td><p></p></td>
                    <td><p><?php echo $tax1_desc . " (" . $tax1_rate . "%)"; ?></p></td>
                    <td><p><?php echo $currency_symbol . " " . $total_tax1; ?></p></td>
                </tr>
                <?php
                if ($total_tax2 > 0) {
                    ?>
                    <tr>
                        <td><p></p></td>
                        <td><p></p></td>
                        <td><p><?php echo $tax2_desc . " (" . $tax2_rate . "%)"; ?></p></td>
                        <td><p><?php echo $currency_symbol . " " . $total_tax2; ?></p></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td><p></p></td>
                <td><p></p></td>
                <td><p><?php echo $this->lang->line('invoice_total'); ?></p></td>
                <td><p><strong><?php echo $currency_symbol . " " . $total_with_tax_number; ?></strong></p></td>
            </tr>            
        </table>

        <p>
            <span class="totalPaid">
        <?php echo $total_paid; ?>
            </span>
            <span class="totalOutstanding">
        <?php echo $total_outstanding; ?>
            </span>
        </p>


        <p>
            <strong><?php echo $this->lang->line('invoice_payment_term'); ?>: <?php echo $days_payment_due; ?> <?php echo $this->lang->line('date_days'); ?></strong> 
            (<?php echo $date_invoice_due; ?>)
        </p>

<?php if ($companyInfo->tax_code != ''): ?>
            <p><?php echo $companyInfo->tax_code; ?></p>
<?php endif; ?>

        <p><?php $str = convertToHtmlSpecialChars($row->invoice_note);
echo $str;
?></p>

<?php if ($this->config->item('show_client_notes') === TRUE): ?>
            <p>
    <?php echo auto_typography($client_note) ?>
            </p>
<?php endif; ?>
<?php if ($this->settings_model->get_setting('currency_type_accounting') != $currency_type) {
    ?>
            <p class='AccountingAmount'>(<?php echo $this->lang->line('invoice_accounting_purpose') . " " . $this->settings_model->get_setting('currency_type_accounting') . ": <strong>" . $total_accounting_amount . " / " . $accounting_invoice_exchange_rate . "</strong>"; ?>)</p>
<?php }
?>

        <div id="footer">
<?php if ($this->settings_model->get_setting('display_branding') == 'y'): ?>
                <p>
    <?php echo $this->lang->line('invoice_generated_by'); ?> 
    <?php echo $this->lang->line('bambooinvoice_logo'); ?><br />
                    <a href="http://www.bambooinvoice.net/">http://www.bambooinvoice.net</a>
                </p>
<?php endif; ?>
        </div>

    </body>
</html>