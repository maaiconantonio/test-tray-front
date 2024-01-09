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
            </tr>
        </thead>
        <tbody>
            <?php foreach($resApi as $dataVendors) { ?>
                <tr>
                    <th scope="row"><?=$dataVendors["id"]?></th>
                    <td><?=$dataVendors["name"]?></td>
                    <td><?=$dataVendors["mail"]?></td>
                    <td><?=number_format($dataVendors["commission"], 2, ",", ".")?>%</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>