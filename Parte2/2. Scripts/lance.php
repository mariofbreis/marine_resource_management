<html>
<body>
<?php
    // inicia sess�o para passar variaveis entre ficheiros php
    session_start();
    $username = $_SESSION['username'];

    // Carregamento das vari�veis username e pin do form HTML atrav�s do metodo POST;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $lid = test_input($_POST["lid"]);
        $val = test_input($_POST["val"]);
    }

    // Fun��o para limpar os dados de entrada
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    // Carregamento das vari�veis username e pin do form HTML atrav�s do metodo POST;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $lid = test_input($_POST["lid"]);
    }

    // Conex�o � BD
    $host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
    $user="ist167030"; // -> substituir pelo nome de utilizador
    $password="jegd1591"; // -> substituir pela password dada pelo mysql_reset
    $dbname = $user; // a BD tem nome identico ao utilizador
    $connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");

    $sql = "INSERT INTO lance (pessoa,leilao, valor) VALUES ($username,$lid, $val)";
    $result = $connection->query($sql);
    if (!$result) {
        echo("<p> Lance nao registado: Erro na Query:($sql) <p>");
	exit();
    }

    echo("<p> Pessoa ($username) registou um lance no valor ($val) no leilao ($lid).</p>\n");


    //termina a sess�o
    session_destroy();
?>
</body>
</html>
