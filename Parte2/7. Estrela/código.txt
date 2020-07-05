DROP TABLE IF EXISTS tempo_dim;
CREATE TABLE tempo_dim(
  tempo_id INT NOT NULL AUTO_INCREMENT,
  data DATE,
  PRIMARY KEY (tempo_id)
);

DROP TABLE IF EXISTS localizacao_dim;
CREATE TABLE localizacao_dim(
  localizacao_id INT NOT NULL AUTO_INCREMENT,
  concelho VARCHAR(80),
  regiao VARCHAR(80),
  PRIMARY KEY (localizacao_id)
);

DROP TABLE IF EXISTS maior_lance;
CREATE TABLE maior_lance(
  localizacao_id INT NOT NULL REFERENCES tempo(localiacao_id),
  tempo_id INT NOT NULL REFERENCES localizacao(tempo_id),
  lid INT NOT NULL REFERENCES lance(leilao),
  lance_maximo INT NOT NULL
);


INSERT INTO localizacao_dim(concelho, regiao) SELECT concelho, regiao
from (select L.dia, LR.lid, L2.valor, P.concelho, P.regiao
from Leilao L natural join leiloeira P natural join leilaor LR left join (
select pessoa, leilao, max(valor) as valor from lance) L2
on LR.lid=l2.leilao) K;

INSERT INTO tempo_dim(data) SELECT dia
from (select L.dia, LR.lid, L2.valor, P.concelho, P.regiao
from leilao L natural join leiloeira P natural join leilaor LR left join (
select pessoa, leilao, max(valor) as valor from lance) L2
on LR.lid=L2.leilao) K;