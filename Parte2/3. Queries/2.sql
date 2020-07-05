DELIMITER ;

-- 2. Qual o nome das pessoas coletivas com exatamente duas inscrições em leilões?
SELECT K.nome
FROM (select *
	from pessoac P natural join concorrente C natural join pessoa P2
	where C.pessoa=P.nif) K
GROUP BY K.pessoa
HAVING COUNT(*) = 2;
