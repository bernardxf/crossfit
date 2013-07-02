<?php

class DB {

    protected $query;

    public function connect($server, $usuario, $password, $dbname) {

        if (!($id = mysql_connect($server, $usuario, $password))) {
            echo "Não foi possível estabelecer uma conexão com o gerenciador MySQL. Favor Contactar o Administrador.";
            exit;
        }

        if (!($con = mysql_select_db($dbname))) {
            echo "Não foi possível selecionar um Banco de Dados. Favor Contactar o Administrador.";
            exit;
        }
    }

   public static function query($query) {
        $args = func_get_args();

        $query = array_shift($args);

        if(count($args) > 0)
        {
            if (is_array($args[0])) {
                $args = $args[0];
            }

            $query = str_replace("'%s'", '%s', $query);

            $query = str_replace('"%s"', '%s', $query);

            $query = str_replace('%s', "'%s'", $query);

            foreach ($args as &$arg) {
                $arg = mysql_real_escape_string($arg);
            }

            $query = @vsprintf($query, $args);
        }
// echo $query;
        return mysql_query($query);
    }

    public static function get_row($query) {
        return mysql_fetch_assoc($query);
    }

    public static function erro() {
        return mysql_error();
    }

    public function get_rows($query){
        $rows = array();
        while($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }

}

/* conectando ao banco */

@DB::connect('localhost','root','123456','crossfit');

?>
