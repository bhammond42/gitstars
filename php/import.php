<?php
	include('dbconn.php');
	
	$url = 'https://api.github.com/search/repositories?q=stars&sort=stars&order=desc';
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_USERAGENT,$useragent);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	$items = json_decode(curl_exec($ch), true);
	curl_close($ch);								
	
	$dbh = getDBConn();
	$null_variable = null;
	$text = '';
	$imported = 0;
	$updated = 0;
	$errored = 0;
	foreach($items as $item){
		foreach($item as $i){
			$id = $i['id'];
			$name = $i['full_name'];
			$url = $i['html_url'];
			$createdt = $i['created_at'];
			$pushdt = $i['pushed_at'];
			$description = $i['description'];
			$stars = $i['stargazers_count'];
			
			$query = "select id from repos where id = :id";
			$stmt = $dbh->prepare($query);
			$stmt->bindParam(":id", $id);
			$stmt->execute();
			$result = $stmt->fetchALL(PDO::FETCH_ASSOC);
			$match = count($result);
			
			if($match > 0){
				$query = "update repos set name = :name, url = :url, createdt = :createdt, pushdt = :pushdt, 
					description = :description, stars = :stars, lastmoddt = now()
					where id = :id";
				$stmt = $dbh->prepare($query);
				$stmt->bindParam(":id", $id);
				$stmt->bindParam(":name", $name);
				$stmt->bindParam(":url", $url);
				$stmt->bindParam(":createdt", $createdt);
				$stmt->bindParam(":pushdt", $pushdt);
				$stmt->bindParam(":description", $description);
				$stmt->bindParam(":stars", $stars);
				$result = $stmt->execute();
				if($result==1){
					$updated++;
					$text .= "$id - $name - UPDATED.<br>";
				}else{
					$errored++;
					$text .= "$id - $name - ERRORED UPDATE.<br>";
				}
			}else{
				$query = "insert into repos (id, name, url, createdt, pushdt, description, stars, importdt, lastmoddt)
					values (:id,:name,:url,:createdt,:pushdt,:description,:stars,now(),now())";
				$stmt = $dbh->prepare($query);
				$stmt->bindParam(":id", $id);
				$stmt->bindParam(":name", $name);
				$stmt->bindParam(":url", $url);
				$stmt->bindParam(":createdt", $createdt);
				$stmt->bindParam(":pushdt", $pushdt);
				$stmt->bindParam(":description", $description);
				$stmt->bindParam(":stars", $stars);
				$result = $stmt->execute();
				if($result==1){
					$imported++;
					$text .= "$id - $name - IMPORTED.<br>";
				}else{
					$errored++;
					$text .= "$id - $name - ERRORED IMPORT.<br>";
				}
			}
		}
	}
	$text .= "$imported rows imported.<br>";
	$text .= "$updated rows updated.<br>";
	$text .= "$errored errors.<br>";
	echo $text;
?>