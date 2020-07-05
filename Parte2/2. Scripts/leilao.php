<html>
<body>
<?php
    // inicia sessão para passar variaveis entre ficheiros php
    session_start();
    $username = $_SESSION['username'];
    $nif = $_SESSION['nif'];

    // Função para limpar os dados de entrada
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    // Carregamento das variáveis username e pin do form HTML através do metodo POST;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $lid = test_input($_POST["lid"]);
    }

    // Conexão à BD
    $host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
    $user="ist167030"; // -> substituir pelo nome de utilizador
    $password="jegd1591"; // -> substituir pela password dada pelo mysql_reset
    $dbname = $user; // a BD tem nome identico ao utilizador
    $connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");

    //regista a pessoa no leilão. Exemplificativo apenas.....

	$date1 = date('Y-m-d', time());
	print("<p>Data actual: $date1</p>");

    $sql = "select dia, nrdias from leilaor where lid=$lid";
    $result = $connection->query($sql);
    if (!$result) {
        echo("<p> Falha ao ir buscar a data limite do leilao: Erro na Query:($sql) <p>");
        exit();
    }

    foreach($result as $row){
	$date2 = $row["dia"];
	$duracao=$row["nrdias"];
	while($duracao >= 1){
	    $date2 = date('Y-m-d', strtotime($date2. ' + 1 days'));
	    $duracao = $duracao - 1;
	}
    }	

    if ($date1 > $date2) {
	print("Leilao expirado. A pessoa não pode ser registada neste leilao");
	exit();
    }else{
	$sql = "INSERT INTO concorrente (pessoa,leilao) VALUES ($nif,$lid)";
	$result = $connection->query($sql);
	if (!$result) {
	    echo("<p> Pessoa nao registada: Erro na Query:($sql) <p>");
	    exit();
	}
    	echo("<p> Pessoa ($username), nif ($nif) Registada no leilao ($lid)</p>\n");
    }

    //termina a sessão
    session_destroy();
?>
</body>
</html>
