<br />
<form name="formSearchVendor">
    <input type="hidden" name="page" id="page" value="<?=$page?>">
    <div class="form-group row">
        <label for="vendor" class="col-sm-1 col-form-label">Vendedor:</label>
        <div class="col-sm-2">
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
                        $selected = ($_REQUEST["vendor"] == $dataVendors["id"]) ? "selected" : ""; ?>
                        <option value="<?=$dataVendors["id"]?>" <?=$selected?>><?=$dataVendors["name"]?></option>
                    <?php }
                } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Pesquisar</button>
    </div>
</form>
<br />
<?php if (isset($_REQUEST['vendor'])) {
    $vendor = filter_var($_REQUEST["vendor"], FILTER_VALIDATE_INT);
    if (!empty($vendor)) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'nginx/sale/vendor/'.$vendor,
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

        if (count($resApi) == 0) { ?>
            <div class="alert alert-info">Nenhum resultado encontrado</div>
        <?php } else { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Comissão</th>
                        <th scope="col">Valor da Venda</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($resApi as $dataVendors) { ?>
                        <tr>
                            <th scope="row"><?=$dataVendors["id"]?></th>
                            <td><?=$dataVendors["name"]?></td>
                            <td><?=$dataVendors["mail"]?></td>
                            <td>R$ <?=number_format($dataVendors["commission_value"], 2, ",", ".")?></td>
                            <td>R$ <?=number_format($dataVendors["sale_value"], 2, ",", ".")?></td>
                            <td><?=date("m/d/Y", strtotime($dataVendors["created_at"]))?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php }
    }
} ?>