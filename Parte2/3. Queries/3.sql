DELIMITER ;

-- 3. Qual o leilão com o maior rácio (valor do melhor lance)/(valor base)?
SELECT K.lid, K.nome, K.valorbase, K.valor, max(K.ratio) AS ratio
FROM(	SELECT L.lid, L3.nome, L3.valorbase, L2.valor, L2.valor/L3.valorbase AS ratio
        FROM leilao L3 NATURAL JOIN leilaor L RIGHT JOIN (SELECT pessoa, leilao, max(valor) AS valor FROM lance GROUP BY leilao) L2
        ON L.lid=L2.leilao) K;
