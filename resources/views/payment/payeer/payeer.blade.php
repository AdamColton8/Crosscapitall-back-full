<?php
$m_shop = '8945305';
$m_orderid = '1';
$m_amount = number_format(100, 2, '.', '');
$m_curr = 'USD';
$m_desc = base64_encode('Test');
$m_key = 'Ваш секретный ключ';

$arHash = array(
    $m_shop,
    $m_orderid,
    $m_amount,
    $m_curr,
    $m_desc
);

$arHash[] = $m_key;

$sign = strtoupper(hash('sha256', implode(':', $arHash)));
?>
<form method="post" action="https://payeer.com/merchant/">
    <input type="hidden" name="m_shop" value="<?=$m_shop?>">
    <input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
    <input type="text" name="m_amount" value="<?=$m_amount?>">
    <input type="hidden" name="m_curr" value="<?=$m_curr?>">
    <input type="hidden" name="m_desc" value="<?=$m_desc?>">
    <input type="hidden" name="m_sign" value="<?=$sign?>">
    <?php /*
<input type="hidden" name="form[ps]" value="2609">
<input type="hidden" name="form[curr[2609]]" value="USD">
*/ ?>
    <?php /*
<input type="hidden" name="m_params" value="<?=$m_params?>">
*/ ?>
    <input type="submit" name="m_process" value="send" />
</form>