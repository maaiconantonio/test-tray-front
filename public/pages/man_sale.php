<?php
$vendor = null;
$value = null;
if (isset($_REQUEST["vendor"]) || isset($_REQUEST["value"])) {
    $vendor = filter_var($_REQUEST['vendor'], FILTER_VALIDATE_INT);
    $value = filter_var($_REQUEST["value"], FILTER_VALIDATE_FLOAT);

    if ($value === false) {
        $status = "danger";
        $msg = "O campo de valor não foi preenchido com um valor válido";
    } else {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'nginx/sale',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "vendor_id": "'.$vendor.'",
                "sale_value": "'.$value.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $resApi = json_decode($response, true);

        if (isset($resApi["status"])) {
            $status = "danger";
            $msg = $resApi["msg"];
        } else if (count($resApi)) {
            $commission = number_format($resApi["commission"], 2, ",", ".");
            $status = "success";
            $msg = "Venda lançada com sucesso, sua comissão foi de: R$ {$commission}. Parabéns!";
        } else {
            $status = "error";
            $msg = "Ocorreu um erro de comunicação, tente novamente mais tarde";
        }
    }
}
?>
<br />
<h3>Cadastrar uma nova venda</h3>
<br />
<?php if (!empty($msg)) { ?>
    <div class="alert alert-<?=$status?>"><?=$msg?></div>
<?php } ?>
<form name="formManVendor">
    <input type="hidden" name="page" value="<?=$page?>">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="vendor">Vendedor</label>
            <div>
                <select class="form-control col" id="vendor" name="vendor">
                    <option value="">Selecione</option>
                    <?php
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'nginx/vendor',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    $resApi = json_decode($response, true);

                    if (count($resApi) > 0) {
                        foreach($resApi as $dataVendors) {
                            $selected = ($vendor == $dataVendors["id"]) ? "selected" : ""; ?>
                            <option value="<?=$dataVendors["id"]?>" <?=$selected?>><?=$dataVendors["name"]?></option>
                        <?php }
                    } ?>
                </select>
            </div>
        </div>
        <div class="form-group col-md-3">
            <label for="value">Valor (R$)</label>
            <input type="number" min="0.00" max="100000.00" step="0.01" class="form-control" id="value" name="value" placeholder="Valor" value="<?=$value?>">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Gravar</button>
</form>