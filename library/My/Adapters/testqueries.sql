SELECT 
	scoutit_materiaali.id,
	scoutit_materiaali.nimi, 
	(SELECT SUM(scoutit_ostot.ostomaara) FROM scoutit_ostot WHERE scoutit_ostot.tuote_id = scoutit_materiaali.id) AS ostot,
	(SELECT SUM(scoutit_varaukset.maara) FROM scoutit_varaukset WHERE scoutit_varaukset.tuote_id = scoutit_materiaali.id AND scoutit_varaukset.palautettu IS NULL) AS varaukset
FROM
	scoutit_materiaali
	
SELECT 
	scoutit_materiaali.id,
	scoutit_materiaali.nimi, 
	SUM(scoutit_ostot.ostomaara) AS ostot
FROM 
	`scoutit_materiaali`
LEFT JOIN
	`scoutit_ostot` ON scoutit_materiaali.id = scoutit_ostot.tuote_id
GROUP BY 
	scoutit_materiaali.id
	
SELECT 
	scoutit_materiaali.id,
	scoutit_materiaali.nimi, 
	IF (scoutit_varaukset.palautettu IS NULL, SUM(scoutit_varaukset.maara), NULL) AS varaukset 
FROM 
	`scoutit_materiaali`
LEFT JOIN
	`scoutit_varaukset` ON scoutit_varaukset.tuote_id = scoutit_materiaali.id 
GROUP BY 
	scoutit_materiaali.id

