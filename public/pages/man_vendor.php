<?php if (isset($_REQUEST["name"]) || isset($_REQUEST["mail"])) {
    $name = filter_var($_REQUEST['name']);
    $mail = filter_var($_REQUEST["mail"], FILTER_VALIDATE_EMAIL);

    if (empty($name) || empty($mail)) {
        $error = "O nome e o email são campos obrigatórios";
    } else {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'nginx/vendor',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "name": "'.$name.'",
                "mail": "'.$mail.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $resApi = json_decode($response, true);

        if (count($resApi) > 0) {
            $status = ($resApi["status"] == "error") ? "danger" : $resApi["status"];
            $msg = $resApi["msg"];
        } else {
            $status = "error";
            $msg = "Ocorreu um erro de comunicação, tente novamente mais tarde";
        }
    }
}
?>
<br />
<h3>Cadastrar um novo vendedor</h3>
<br />
<?php if (!empty($msg)) { ?>
    <div class="alert alert-<?=$status?>"><?=$msg?></div>
<?php } ?>
<form name="formManVendor">
    <input type="hidden" name="page" value="<?=$page?>">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="name">Nome</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nome">
        </div>
        <div class="form-group col-md-3">
            <label for="mail">Email</label>
            <input type="email" class="form-control" id="mail" name="mail" placeholder="Email">
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Gravar</button>
</form>