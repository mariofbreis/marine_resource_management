<html>
<body>
<?php
    // inicia sess�o para passar variaveis entre ficheiros php
    session_start();
    $username = $_SESSION['username'];
    $nif = $_SESSION['nif'];

    // Fun��o para limpar os dados de entrada
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    // Carregamento das vari�veis username e pin do form HTML atrav�s do metodo POST;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $leiloes = test_input($_POST["leiloes"]);
    }

    $leiloes = explode(" ", $leiloes);

    // Conex�o � BD
    $host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
    $user="ist167030"; // -> substituir pelo nome de utilizador
    $password="jegd1591"; // -> substituir pela password dada pelo mysql_reset
    $dbname = $user; // a BD tem nome identico ao utilizador
    $connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");

try{
	$connection->beginTransaction();
	foreach($leiloes as $leilao){
	    $sql = "INSERT INTO concorrente (pessoa,leilao) VALUES ($nif,$leilao)";
	    $result = $connection->query($sql);
	    if(!$result) {
		print("<p>falhou '$sql'</p>");
		throw new Exception($connection->error);
	    }
	}
	print("$username foi inscrito nos leiloes pedidos.");
	$connection->commit();
}catch(Exception $e){
	print("<p>rollbacking</p>");
	$connection->rollback();
}


?>

</body>
</html>
