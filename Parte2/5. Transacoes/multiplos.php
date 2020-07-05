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
        $data = test_input($_POST["data"]);
    }

    // Conexão à BD
    $host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
    $user="ist167030"; // -> substituir pelo nome de utilizador
    $password="jegd1591"; // -> substituir pela password dada pelo mysql_reset
    $dbname = $user; // a BD tem nome identico ao utilizador
    $connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");

    $date1 = date('Y-m-d', time());

// Apresenta os leilões
    $sql = "SELECT L.*, LR.nrdias FROM leilao L natural join leilaor LR where L.dia='$data'";
    $result = $connection->query($sql);
    echo("<table border=\"1\">\n");
    echo("<tr><td>ID</td><td>nif</td><td>dia</td><td>NrDoDia</td><td>nome</td><td>tipo</td><td>valo
rbase</td></tr>\n");
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

<form action="inscricao_multiplos.php" method="post">
<h2>Insira os leiloes em que se pretende inscrever.</h2>
IDs:
<input type="text" name="leiloes" value="IDs separados por espaço."> 
<input type="submit" VALUE = "Inscrever" username=$username nif=$nif />
</form>


<form action="multiplos.php" method="post">
<h2>Ver outro dia.</h2>
Escolha a data:
<input type="text" name="data" value="YYYY-MM-DD"> 
<input type="submit" VALUE = "Listar" username=$username nif=$nif />
</form>

</body>
</html>
