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
$date1 = date('Y-m-d', time());
print("<p>Data actual: $date1</p>");

    // Conexão à BD
    $host="db.ist.utl.pt"; // o MySQL esta disponivel nesta maquina
    $user="ist167030"; // -> substituir pelo nome de utilizador
    $password="jegd1591"; // -> substituir pela password dada pelo mysql_reset
    $dbname = $user; // a BD tem nome identico ao utilizador
    $connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");

    //Mostra os leiloes em que a pessoa esta inscrita.

    $sql = "SELECT DISTINCT C.leilao, L.tipo, L.dia, LR.nrdias, L.nome, LR.nif, L.valorbase, L3.valor FROM concorrente C natural join leilaor LR natural join leilao L left join lance L3 ON LR.lid=L3.leilao WHERE C.pessoa=$username and C.leilao=LR.lid and LR.nif=L.nif";
    $result = $connection->query($sql);
    if (!$result) {
        echo("<p> Pessoa nao registada: Erro na Query:($sql) <p>");
        exit();
    }else{
	echo("<p> Leiloes em que $username participa: <p>");
	echo("<table border=\"1\">\n");
	echo("<tr><td>ID</td><td>tipo</td><td>dia</td><td>Duracao</td><td>nome</td><td>Nif</td><td>valorbase</td><td>Valor actual</td><td>dias restantes</td</tr>\n");
	foreach($result as $row){

		$date2 = $row["dia"];
		$duracao=$row["nrdias"];
		while($duracao >= 1){
			$date2 = date('Y-m-d', strtotime($date2. ' + 1 days'));
			$duracao = $duracao - 1;
		}

	if ($date1 <= $date2) {
	        echo("<tr><td>");
        	echo($row["leilao"]); echo("</td><td>");
		echo($row["tipo"]); echo("</td><td>");
		echo($row["dia"]); echo("</td><td>");
		echo($row["nrdias"]); echo("</td><td>");
		echo($row["nome"]); echo("</td><td>");
		echo($row["nif"]); echo("</td><td>");
		echo($row["valorbase"]); echo("</td><td>");
		if (!$row["valor"]) {
			echo("N/A"); echo("</td><td>");
		}else{
			echo($row["valor"]); echo("</td><td>");
		}
		$diff = abs(strtotime($date2) - strtotime($date1));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff + $years*365 + $months*30)/ (60*60*24));
		print("$days");
	}


    }
    echo("</table>\n");
}
?>

<form action="lance.php" method="post">
<h2>Insira o ID do leilao e o lance que pretende efectuar.</h2>
	<p>ID : <input type="text" name="lid" </p>
	<p>Valor : <input type="text" name="val" /></p>
<p><input type="submit" username=$username/></p>
</form>

</body>
</html>
