DELIMITER ;

-- 4. Quais as pessoas coletivas com o mesmo capital social?
SELECT P.nif, P.capitalsocial
FROM pessoac P,
    (SELECT nif, capitalsocial, count(capitalsocial)
    FROM pessoac
    GROUP BY capitalsocial
    HAVING count(capitalsocial)>1) K
WHERE P.capitalsocial=K.capitalsocial
ORDER BY P.capitalsocial;
