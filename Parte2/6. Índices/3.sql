DELIMITER ;

-- Indices para a query 3.3
CREATE INDEX LeilaoIndice
ON leilao (nome, valorbase);

CREATE INDEX LeilaorIndice
ON leilao (lid);

CREATE INDEX LanceIndice
ON leilao (pessoa, leilao, valor);
