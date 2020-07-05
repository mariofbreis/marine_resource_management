-- valor minimo de um lance num leilao seja o valor base desase leilao
-- valor minimo de um lance num leilao nao seja menor que o valor base
DELIMITER //
CREATE TRIGGER triggerlance
BEFORE INSERT ON lance
FOR EACH ROW
BEGIN
	IF ((SELECT valorbase FROM leilao NATURAL JOIN leilaor WHERE lid=NEW.leilao) > NEW.valor
	OR (SELECT max(valor) from lance NATURAL JOIN leilaor WHERE lid=NEW.leilao) > NEW.valor)
	THEN UPDATE `Error: invalid_bid` SET x=1;
END IF;
END;//
DELIMITER ;
DELIMITER //
DROP TRIGGER IF EXISTS lancemin;
CREATE TRIGGER lancemin
BEFORE INSERT ON lance
FOR EACH ROW
BEGIN
	  IF NEW.valor >  SELECT valorbase FROM leilao NATURAL JOIN leilaor
	  WHERE lid=NEW.leilao THEN
	  	CALL Erro('valor do lance mais baixo que o'); END IF; END//  DELIMITER ;