<?php

// require_once 'config.php';

$host= 'localhost';
$db = 'blue_app';
$user = 'postgres';
$password = 'killer'; // change to your password
try {
	$dsn = "pgsql:host=$host;port=5432;dbname=$db;";
	// make a database connection
	$pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

	if ($pdo) {
		echo "Connected to the $db database successfully!";
	}
	
	
	 $stmt = $pdo->query('SELECT * '
                . 'FROM t_utilisateurs '
                . 'ORDER BY nom_utilisateur');
        $stocks = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = $row;
			/*[
                'id' => $row['id'],
                'symbol' => $row['symbol'],
                'company' => $row['company']
            ];*/
        }
        // var_dump($stocks);
		
		
		$id = "6d9dbc934962a351965da07efe5c27d9";
		// prepare SELECT statement
        $stmt = $pdo->prepare('SELECT *
                                       FROM t_utilisateurs
                                      WHERE code_utilisateur=:id');
        // bind value to the :id parameter
        $stmt->bindValue(':id', $id);
        
        // execute the statement
        $stmt->execute();

        // return the result set as an object
        $stock = $stmt->fetchObject(); 
    
    var_dump($stock);
		
		
} catch (PDOException $e) {
	die($e->getMessage());
} finally {
	if ($pdo) {
		$pdo = null;
	}
}