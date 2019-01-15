	<?php
	$servername = "localhost";
	$username = "root";
	$password = "smgtLLC123";
	$dbname = "uaet10_dynamic_display_mediclinic";
	$dbname3 = "dbupgrade";
	$dbname2 = "uaet10_dynamic_display_latest";
	$Tables_in_dbname = "Tables_in_".$dbname;
	$Tables_in_dbname2 = "Tables_in_".$dbname2;

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	// Create connection
	$conn3 = new mysqli($servername, $username, $password, $dbname3);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	
	$sql = "Truncate table tables";
	$result = $conn3->query($sql);

	$sql = "SHOW TABLES FROM $dbname";
	$result = $conn->query($sql);
	$db1 = array();
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$db1[] = $row[$Tables_in_dbname];			
			
		}
	} else {
		echo "0 results";
	}
	// $conn->close();
	
	
	$sql2 = "SHOW TABLES FROM $dbname2 ";
	$result2 = $conn->query($sql2);
	$db2 = array();
	if ($result2->num_rows > 0) {
		// output data of each row
		while($row = $result2->fetch_assoc()) {
			$db2[] = $row[$Tables_in_dbname2];			
			
		}
	} else {
		echo "0 results";
	}
	$conn->close();
	
	
	define('MYSQL_SERVER', 'localhost');
	define('MYSQL_DATABASE_NAME', $dbname);
	define('MYSQL_USERNAME', 'root');
	define('MYSQL_PASSWORD', 'smgtLLC123');
	
	define('MYSQL_SERVER2', 'localhost');
	define('MYSQL_DATABASE_NAME2', $dbname2);
	define('MYSQL_USERNAME2', 'root');
	define('MYSQL_PASSWORD2', 'smgtLLC123');

	$database1 = array();
	$database2 = array();
	//iterate the database structure of the database(the one which is needed to be update the structure)
	foreach($db1 as $row){
		
		$fieldnames = array();

			//Instantiate the PDO object and connect to MySQL.
			$pdo = new PDO(
					'mysql:host=' . MYSQL_SERVER . ';dbname=' . MYSQL_DATABASE_NAME, 
					MYSQL_USERNAME, 
					MYSQL_PASSWORD
			);

					
			$tableToDescribe = $row;
			$statement = $pdo->query('DESCRIBE ' . $tableToDescribe);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $column){
				//echo '&emsp; '. $column['Field'] . ' - ' . $column['Type'].'<br>';//////////////////////////////////////////////////////
				$fieldnames[] = array(
								"fieldname" => $column['Field'],
								"type" => $column['Type']
								);
			}
		
		$database1[] = array(
						$row =>  $fieldnames
						);

	}	
	//iterate the database structure of the database(the one where to get the latest  database structure)
	foreach($db2 as $row2){
		
		
		$fieldnames2 = array();
			$pdo = new PDO(
					'mysql:host=' . MYSQL_SERVER2 . ';dbname=' . MYSQL_DATABASE_NAME2, 
					MYSQL_USERNAME2, 
					MYSQL_PASSWORD2
			);
					
			$tableToDescribe = $row2;
			$statement = $pdo->query('DESCRIBE ' . $tableToDescribe);
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $column){
				//echo '&emsp; '. $column['Field'] . ' - ' . $column['Type'].'<br>';//////////////////////////////////////////////////////
				$fieldnames2[] = array(
								"fieldname" => $column['Field'],
								"type" => $column['Type']
								);
			}
		
		$database2[] = array(
						$row2 =>  $fieldnames2
						);

	}

		$keys = array_keys($database1);
		$keys2 = array_keys($database2);
		
		//save the database structure of the first defined database in the  database
		for($i = 0; $i < count($database1); $i++) {
			foreach($database1[$keys[$i]] as $key => $value) {
				// echo $key."<br/>";
				foreach($value as $asd => $qwe){
					$arr = array();
					$x = 0;
					foreach($qwe as $weq => $qqq){
						$arr[$x] = $qqq;
						$x++;
					}
					$arrs = array();
					// echo $arr[0];
					// echo $arr[1];
					$arrs = explode("(", $arr[1]);
					// print_r($arrs);
					// echo "<br/>";
					if(count($arrs)>1){
						$var= str_replace(")","",$arrs[1]);
					}else{
						$var="";
					}
					
					// Create connection
					$conn3 = new mysqli($servername, $username, $password, $dbname3);
					// Check connection
					if ($conn3->connect_error) {
						die("Connection failed: " . $conn3->connect_error);
					} 
					
					$sql = "INSERT INTO tables (table_name, column_name, datatype,value,database_id)
						VALUES ('$key', '$arr[0]', '$arrs[0]', '$var','$dbname')";

						if ($conn3->query($sql) === TRUE) {
						} else {
						}
					$conn3->close();
				}
			}
		}
		
		
		//iterate the second database and create the script to update the database
		for($i = 0; $i < count($database2); $i++) {
			foreach($database2[$keys2[$i]] as $key2 => $value2) {
				// echo $key2."<br/>";
				
					$table_create_status = true;
					foreach($value2 as $asd => $qwe2){
						$arr = array();
						$x = 0;
						foreach($qwe2 as $weq => $qqq){
							$arr[$x] = $qqq;
							$x++;
						}
						$arrs = array();
						// echo $arr[0];
						// echo $arr[1];
						$arrs = explode("(", $arr[1]);
						// print_r($arrs);
						// echo "<br/>";
						
						// $var= str_replace(")"," ",$arrs[1]);
						
						if(count($arrs)>1){
							$var= str_replace(")","",$arrs[1]);
						}else{
							$var="";
						}
						
						
						// Create connection
						$conn3 = new mysqli($servername, $username, $password, $dbname3);
						// Check connection
						if ($conn3->connect_error) {
							die("Connection failed: " . $conn3->connect_error);
						} 
						
						$sql = "SELECT * FROM tables where table_name = '$key2'";
						$result = $conn3->query($sql);

						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								if($row["column_name"]== $arr[0] && $row["datatype"]!= $arrs[0]){
									 // echo  $arr[0].' '.$arrs[0].' '.$var;
									if(count($arrs)>1){
										echo "ALTER TABLE ".$key2." CHANGE ".$arr[0]." ".$arr[0]." ".$arrs[0]." (".$arrs[1].";";
									}else{
										echo "ALTER TABLE ".$key2." CHANGE ".$arr[0]." ".$arr[0]." ".$arrs[0].";";
									}
								}else if($row["column_name"]== $arr[0] &&$row["datatype"]== $arrs[0] && $row["value"]!= $var){
									 // echo  $arr[0].' '.$arrs[0].'>'.$var;
									if(count($arrs)>1){
										echo "ALTER TABLE ".$key2." CHANGE ".$arr[0]." ".$arr[0]." ".$arrs[0]." (".$arrs[1].";";
									}else{
										echo "ALTER TABLE ".$key2." CHANGE ".$arr[0]." ".$arr[0]." ".$arrs[0].";";
									}
								}
								  // echo "<br/>";
							}
						} else {
							 // echo "0 results";
							 // echo "create table" .$key2.' '.$arr[0].' '.$arrs[0].' '.$arrs[1];
							 // echo "<br/>";
							 
						
						//////////////////////////////////////////////////////
						/* if($table_create_status==false){
							 $sql = "SELECT * FROM tables where table_name = '$key2' and column_name= '$arr[0]' ";
							$result = $conn3->query($sql);

							if ($result->num_rows > 0) {
								
							} else {
								// echo  $arr[0].' '.$arrs[0].' '.$var;
								if(count($arrs)>1){
									echo "ALTER TABLE ".$key2." ADD COLUMN IF NOT EXISTS ".$arr[0]." ".$arrs[0]." (".$arrs[1].";";
								}else{
									echo "ALTER TABLE ".$key2." ADD COLUMN IF NOT EXISTS ".$arr[0]." ".$arrs[0].";";
								}
								echo "<br/>";
							} 
						} */
						
						//////////////////////////////////////////////////////
						
							 
							if($table_create_status==true){
							if(count($arrs)>1){
							echo "CREATE TABLE IF not EXISTs ".$key2." (".$arr[0]." ".$arrs[0]."(".$arrs[1].");";
							}else{
							echo "CREATE TABLE IF not EXISTs ".$key2." (".$arr[0]." ".$arrs[0].");";
							}
							echo "<br/>";
							$table_create_status=false;
							}else {
								// echo  $arr[0].' '.$arrs[0].' '.$var;
								if(count($arrs)>1){
									echo "ALTER TABLE ".$key2." ADD COLUMN ".$arr[0]." ".$arrs[0]." (".$arrs[1].";";
								}else{
									echo "ALTER TABLE ".$key2." ADD COLUMN ".$arr[0]." ".$arrs[0].";";
								}
								// echo "<br/>";
							} 
						}
						
						
						
						$sql = "SELECT * FROM tables where table_name = '$key2' and column_name = '$arr[0]'";
						$result = $conn3->query($sql);

						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								// echo $qwe = $row["column_name"];
							}
						} else {
							
							if($arr[0]=="_id"){
								if(count($arrs)>1){
										echo "ALTER TABLE ".$key2." CHANGE id ".$arr[0]." ".$arrs[0]." (".$arrs[1].";";
									}else{
										echo "ALTER TABLE ".$key2." CHANGE id ".$arr[0]." ".$arrs[0].";";
									}
							}else{
								if(count($arrs)>1){
										echo "ALTER TABLE ".$key2." ADD COLUMN ".$arr[0]." ".$arrs[0]." (".$arrs[1].";";
									}else{
										echo "ALTER TABLE ".$key2." ADD COLUMN ".$arr[0]." ".$arrs[0].";";
									}
							}
						}
						
						$conn3->close();
						
						//////////////////////////////////////////////////////
						/* if($table_create_status==false){
							 $sql = "SELECT * FROM tables where table_name = '$key2' and column_name= '$arr[0]' ";
							$result = $conn3->query($sql);

							if ($result->num_rows > 0) {
								
							} else {
								// echo  $arr[0].' '.$arrs[0].' '.$var;
								if(count($arrs)>1){
									echo "ALTER TABLE ".$key2." ADD COLUMN IF NOT EXISTS ".$arr[0]." ".$arrs[0]." (".$arrs[1].";";
								}else{
									echo "ALTER TABLE ".$key2." ADD COLUMN IF NOT EXISTS ".$arr[0]." ".$arrs[0].";";
								}
								echo "<br/>";
							} 
						} */
						
						//////////////////////////////////////////////////////
						
						
					}
			}
			echo "<br>";
		}
			
		?>