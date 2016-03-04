<?php
$pathImagem1 = "C:/Users/Public/Pictures/Sample Pictures/Desert.jpg";
//$pathImagem2 = "C:/Users/Public/Pictures/Sample Pictures/Chrysanthemum.jpg";
//$pathImagem3 = "C:/Users/Public/Pictures/Sample Pictures/Chrysanthemum.jpg";
$restUrl = "http://reunimotors.com.br/ws/anuncio/cadastrar";
#$restUrl = "http://localhost/dump-request.php";
$params = array (
  'config' => array (
    'key' => '6364d3f0f495b6ab9dcf8d3b5c6e0b01',
  ),
  'anuncios' => 
  array ( 
		array (
      'id' => '4234324',
      'veiculo_cor' => 'Vermelho',
      'cidade_codigo' => '0005',
      'veiculo_modelo' => '011027-2',
      'descricao' => 'string',
      'veiculo_ano' => 2010,
      'veiculo_preco' => 'float',
      'veiculo_km_rodados' => 10000,
      'veiculo_ano_modelo' => 2011,
      'veiculo_numero_portas' => 4,
      'veiculo_combustivel' => 'Gasolina',
      'veiculo_transmissao' => 'Manual',
      'veiculo_estado' => 'Novo',
      'opcionais' => array ()
    )
  )
);
$defaults = array(
	CURLOPT_URL => $restUrl, 
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => array(
		rsq => json_encode($params),
		veiculo_imagem_1 => '@' . $pathImagem1
//		veiculo_imagem_2 => '@' . $pathImagem2,
//		veiculo_imagem_3 => '@' . $pathImagem3
	),
	CURLOPT_RETURNTRANSFER    => true
);
$ch = curl_init();
curl_setopt_array($ch, $defaults);
$response = curl_exec($ch);

header('Content-Type: text/plain');
echo $response;