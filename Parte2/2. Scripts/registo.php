<html>
<body>
<?php
    // inicia sessão para passar variaveis entre ficheiros php
    session_start();

    // Função para limpar os dados de entrada
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Carregamento das variáveis username e pin do form HTML através do metodo POST;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = test_input($_POST["username"]);
        $pin = test_input($_POST["pin"]);
    }

    // Carregamento das variáveis username e pin do form HTML através do metodo GET;
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
	print ("GET!\n");
        $username = test_input($_GET["username"]);
        $pin = test_input($_GET["pin"]);
    }
    echo("<p>Valida Pin da Pessoa $username</p>\n");

    // Variáveis de conexão à BD
    $host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
    $user="ist167030"; // -> substituir pelo nome de utilizador
    $password="jegd1591"; // -> substituir pela password dada pelo mysql_reset
    $dbname = $user; // a BD tem nome identico ao utilizador
    echo("<p>Projeto Base de Dados Parte II</p>\n");
    $connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");


    // obtem o pin da tabela pessoa
    $sql = "SELECT * FROM pessoa WHERE nif=" . $username;
    $result = $connection->query($sql);
    if (!$result) {
        echo("<p> Erro na Query:($sql)<p>");
        exit();
    }

    foreach($result as $row){
        $safepin = $row["pin"];
        $nif = $row["nif"];
    }
    if ($safepin != $pin ) {
        echo "<p>Pin Invalido! Exit!</p>\n";
        $connection = null;
        exit;
    }
echo "<p>Pin Valido! </p>\n";

    // passa variaveis para a sessao;
    $_SESSION['username'] = $username;
    $_SESSION['nif'] = $nif;


    // Apresenta os leilões
    $sql = "SELECT L.*, LR.nrdias FROM leilao L natural join leilaor LR";
    $result = $connection->query($sql);
if (!$result) {print("query falhou");}

    echo("<table border=\"1\">\n");
    echo("<tr><td>ID</td><td>nif</td><td>dia</td><td>NrDoDia</td><td>nome</td><td>tipo</td><td>valo
rbase</td></tr>\n");

    $date1 = date('Y-m-d', time());
    print("<p>Data actual: $date1</p>");
print("<h2>Leiloes a decorrer ou a iniciar no futuro:</h2>");
    $idleilao = 0;
    foreach($result as $row){
        $idleilao = $idleilao +1;
	$date2 = $row["dia"];
	$duracao=$row["nrdias"];
	while($duracao >= 1){
		$date2 = date('Y-m-d', strtotime($date2. ' + 1 days'));
		$duracao = $duracao - 1;
	}

if ($date1 <= $date2) {
        echo("<tr><td>");
        echo($idleilao); echo("</td><td>");
        echo($row["nif"]); echo("</td><td>");
        echo($row["dia"]); echo("</td><td>");
        echo($row["nrleilaonodia"]); echo("</td><td>");
        echo($row["nome"]); echo("</td><td>");
        echo($row["tipo"]); echo("</td><td>");
        echo($row["valorbase"]); echo("</td><td>");
        $leilao[$idleilao]= array($row["nif"],$row["diahora"],$row["nrleilaonodia"]);
    }
}
    echo("</table>\n");
?>

<form action="leilao.php" method="post">
<h2>Escolha o ID do leilão que pretende concorrer</h2>
ID : <input type="text" name="lid" />
<input type="submit" />
</form>

<form action="multiplos.php" method="post">
<h2>Inscrever-se em varios leiloes em simultaneo.</h2>
Escolha a data:
<input type="text" name="data" value="YYYY-MM-DD"> 
<input type="submit" VALUE = "Listar" username=$username nif=$nif />
</form>

<form action="listagem.php" method="post">
<h2>Listar leiloes em que esta a concorrer. / Efetuar Lance. </h2>
<input type="submit" VALUE = "Listar" username=$username nif=$nif />

</form>

</body>
</html>
