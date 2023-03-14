<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Content-Type: application/json');





require_once "../models/Categoria.php";

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($_GET["send"]) {
            case '':
                http_response_code(400);
                print_r(json_encode(array("status" => "error", "message" => "Falta parametros")));
                break;
                // EndPoint UsuariosVerificaciones
            case 'message':
                // Codigo
                $DesdeLetra = "a";
                $HastaLetra = "z";
                $DesdeNumero = 1;
                $HastaNumero = 10000;
                $letraAleatoria = chr(rand(ord($DesdeLetra), ord($HastaLetra)));
                $numeroAleatorio = rand($DesdeNumero, $HastaNumero);
                $code = $letraAleatoria . $numeroAleatorio;
                // end Codigo
                $cellphone = $_GET["number"];

                $cellphonemodify = substr($cellphone, 1);

                $cellphonefinal = "593" . $cellphonemodify;

                $url = "https://api2.massend.com/enviosms";

                //Execute 
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


                $headers = array(
                    "Authorization: aWNlc3NhLWFwaUBtYXNzZW5kLmNvbQ==",
                    "Content-Type: application/x-www-form-urlencoded",
                );

                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                $data = "username=icessa-api&mensajeid=32743&telefono=$cellphonefinal&tipo=1&datos=$code";

                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                $resp = curl_exec($curl);
                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                if ($status == 201) {
                    print_r(json_encode(array("status" => "OK", "message" => "Mensaje enviado, espere unos segundos, por favor...", "code"=> $code)));
                } else {
                    print_r(json_encode(array("status" => "error", "message" => "El mensaje no se pudo enviar")));
                }
                break;
        }
        break;
    case 'POST':
        http_response_code(401);
        print_r(json_encode(array("status" => "error", "message" => "Método incorrecto")));
        break;
    default:
        http_response_code(401);
        print_r(json_encode(array("status" => "error", "message" => "Ingresar correctamente los datos")));
        break;
}
