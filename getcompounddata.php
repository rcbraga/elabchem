<?php
/********************************************************************************
*  This file is part of eLabChem (http://github.com/martinp23/elabchem)         *
*  Copyright (c) 2013 Martin Peeks (martinp23@googlemail.com)                   *
*                                                                               *
*    eLabChem is free software: you can redistribute it and/or modify           *
*    it under the terms of the GNU Affero General Public License as             *
*    published by the Free Software Foundation, either version 3 of             *
*    the License, or (at your option) any later version.                        *
*                                                                               *
*    eLabChem is distributed in the hope that it will be useful,                *
*    but WITHOUT ANY WARRANTY; without even the implied                         *
*    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR                    *
*    PURPOSE.  See the GNU Affero General Public License for more details.      *
*                                                                               *
*    You should have received a copy of the GNU Affero General Public           *
*    License along with eLabChem.  If not, see <http://www.gnu.org/licenses/>.  *
*                                                                               *
*   eLabChem is a fork of elabFTW.                                              *                                                               
*                                                                               *
********************************************************************************/
	require_once('inc/connect.php');
    require_once('inc/functions.php');
	
	$reactants = $_REQUEST['reactants'];
	$products = $_REQUEST['products'];
	
	$reactant_results = array();
	$product_results = array();
	
	foreach ($reactants as $molecule) {
		$inchi = getInChI($molecule,$bdd);
		$compoundId = findInChI($inchi, $bdd);
		if ($compoundId) {
			$sql = "SELECT c.id, c.name as cpd_name, c.iupac_name, c.cas_number, cp.mwt, cp.formula, cp.density 
			FROM compounds c INNER JOIN compound_properties cp 
			ON c.id = cp.compound_id 
			WHERE c.id= :cid ;";
	        $req = $bdd->prepare($sql, array(PDO::ATTR_EMULATE_PREPARES => false));
	        $req->execute( array('cid' => $compoundId));
			$result = $req->fetch(PDO::FETCH_ASSOC);
			$result['inchi'] = $inchi;
            $result['mwt_units'] = 'g/mol';
			$result = array_filter($result);
			$reactant_results[] = $result;			
		} else {
			$moleculeJson = json_encode($molecule);
			$sql = "SELECT MOLWEIGHT(:molecule), MOLFORMULA(:molecule);";
	        $req = $bdd->prepare($sql);
	        $req->execute(array('molecule' => $molecule));
			$data = $req->fetch();
			$result = array();
			$result['mwt'] = $data[0];
			$result['formula'] = $data[1];
			$result['inchi'] = $inchi;
			$result['cpd_name'] = null;
			$result['density'] = null;
			$result['cas_number'] = null;
            $result['mwt_units'] = 'g/mol';
			$reactant_results[] = $result;		
		};
	};
	
	
	foreach ($products as $molecule) {
		$inchi = getInChI($molecule,$bdd);
		$compoundId = findInChI($inchi, $bdd);
		if ($compoundId) {
			$sql = "SELECT c.id, c.name, c.iupac_name, c.cas_number, cp.mwt, cp.formula, cp.density 
			FROM compounds c INNER JOIN compound_properties cp 
			ON c.id = cp.compound_id 
			WHERE c.id= :cid;";
	        $req = $bdd->prepare($sql, array(PDO::ATTR_EMULATE_PREPARES => false));
	        $req->execute( array('cid' => $compoundId));
			$result = $req->fetch(PDO::FETCH_ASSOC);
			$result['inchi'] = $inchi;
            $result['mwt_units'] = 'g/mol';
			$product_results[] = $result;			
		} else {
			$moleculeJson = json_encode($molecule);
			$sql = "SELECT MOLWEIGHT(:molecule), MOLFORMULA(:molecule);";
	        $req = $bdd->prepare($sql);
	        $req->execute( array('molecule' => $molecule));
			$data = $req->fetch();
			$result = array();
			$result['mwt'] = $data[0];
			$result['formula'] = $data[1];
			$result['inchi'] = $inchi;
            $result['mwt_units'] = 'g/mol';
			$product_results[] = $result;		
		};
	};	
	
	
	echo json_encode(array("reactants" => $reactant_results, "products"=>$product_results));
	
	

?>
