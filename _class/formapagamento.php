<?php

include 'sql.php';

$SELECT = 'SELECT * FROM forma_pagamento';

$INSERT = 'INSERT INTO forma_pagamento (nome) VALUES (%s)';

$UPDATE = 'UPDATE forma_pagamento SET nome = %s WHERE id_forma_pagamento = %d';

$DELETE = 'DELETE from forma_pagamento where id_forma_pagamento = %d';


$methodToCall = $_POST['methodToCall'];

if ($methodToCall == 'loadData'){
    $rows = DB::get_rows(DB::query($SELECT." ORDER BY id_forma_pagamento ASC"));

    echo json_encode($rows);
}

if ($methodToCall == 'save'){
    
    $id = $_POST['dataset']['id_forma_pagamento'];  
    $nome = $_POST['dataset']['nome'];
    $state = $_POST['dataset']['_STATE'];

    if($state == 'I')  {

         DB::query($INSERT, $nome);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro efetuado com sucesso';
        echo json_encode($response);

    } else if($state == 'U') {
       
        DB::query($UPDATE, $nome, $id);

        $response['type'] = 'success';
        $response['message'] = 'Cadastro editado com sucesso';
        echo json_encode($response);

    } else {

        $response['type'] = 'error';
        $response['message'] = 'Ocorreu algum erro.';
        echo json_encode($response);
        
    }
}

if($methodToCall == 'delete'){
    $id_forma_pagamento = $_POST['dataset']['id_forma_pagamento'];
    DB::query($DELETE, $id_forma_pagamento);

    $response['type'] = 'success';
    $response['message'] = 'Excluido com sucesso!';
    echo json_encode($response);
}

if($methodToCall == 'loadSelects'){
    echo json_encode(array());
}
?>