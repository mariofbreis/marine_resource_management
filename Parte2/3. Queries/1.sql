DELIMITER ;

-- 1. Quais os participantes inscritos em leilões mas sem lances até à data?
SELECT *
FROM concorrente C
WHERE C.pessoa NOT IN(
	SELECT pessoa
	FROM concorrente C NATURAL JOIN lance L);
