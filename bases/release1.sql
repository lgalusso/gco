ALTER TABLE `gerenciador`.`parcelas_orcamento` 
ADD COLUMN `debit` FLOAT NULL COMMENT '' AFTER `datapgto`;

DROP VIEW gerenciador.v_orcamento;

CREATE VIEW v_orcamento AS ( SELECT tpo.codigo_orcamento AS codigo_orcamento, 
									tor.parcelas AS parcelas, 
									tor.confirmado AS confirmado, 
									tor.baixa AS baixa, 
									tpo.codigo AS codigo_parcela, 
									tpo.datavencimento AS data, 
									tpo.valor AS valor, 
									tpo.debit AS debit
									tpo.pago AS pago, 
									tpo.datapgto AS datapgto, 
									tp.codigo AS codigo_paciente, 
									tp.nome AS paciente, 
									td.nome AS dentista, 
									td.sexo AS sexo_dentista
							FROM parcelas_orcamento tpo INNER JOIN orcamento tor ON 
							tpo.codigo_orcamento = tor.codigo INNER JOIN pacientes tp ON 
							tor.codigo_paciente = tp.codigo JOIN dentistas td ON tor.codigo_dentista = td.codigo );