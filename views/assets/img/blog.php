<?php
	include("connect.php");
	$strTitle 	= "Articles";
	$strPage	= "blog";
	include("header.php");
	
	// Début de la requête
	$strRq 		= "	SELECT articles.*, user_firstname 
					FROM articles
						INNER JOIN users ON article_creator = user_id ";
	$strWhere	= " WHERE ";
	
	// Traitement des mots clés (dans le titre ou le contenu)
	$strKeywords 	= $_POST['keywords']??'';
	if ($strKeywords != ''){
		$strRq 		.= $strWhere." (article_title LIKE '%".$strKeywords."%' 
							OR article_content LIKE '%".$strKeywords."%' )";
		$strWhere	= " AND ";
	}
	// Traitement date exacte
	$boolPeriod 	= $_POST['period']??0;
	$strDate 		= $_POST['date']??'';
	if ($boolPeriod == 0 && $strDate != ''){
		$strRq 		.= $strWhere." article_createdate = '".$strDate."' ";
		$strWhere	= " AND ";
	}
	
	// Traitement par période de dates
	$strStartDate 		= $_POST['startdate']??'';
	$strEndDate 		= $_POST['enddate']??'';
	if ($boolPeriod == 1 && $strStartDate != '' && $strEndDate != ''){
		$strRq 		.= $strWhere." article_createdate BETWEEN '".$strStartDate."' AND '".$strEndDate."' ";
		$strWhere	= " AND ";
	}
	
	// Traitement des auteurs
	$intAuthor	= $_POST['author']??'';
	if ($intAuthor != ''){
		$strRq 		.= $strWhere." article_creator = ".$intAuthor;
	}

	$arrArticles 	= $db->query($strRq)->fetchAll();
	
	// Liste des auteurs
	$strRqUsers = "SELECT user_id, user_firstname FROM users;";
	$arrUsers 	= $db->query($strRqUsers)->fetchAll();
	
?>
			<h2>Les articles</h2>
			<p>Page affichant tous les articles, avec une zone de recherche sur les articles</p>
			<form name="formSearch" method="post" action="#">
				<fieldset>
					<legend>Rechercher des articles</legend>
					<p><label for="keywords">Mots clés</label>
						<input id="keywords" type="text" name="keywords" value="<?php echo $strKeywords; ?>" /></p>
					<p>	<input type="radio" name="period" <?php if ($boolPeriod == 0) { echo "checked"; } ?> value="0" onclick="changePeriod()" /> Par date exacte
						<input type="radio" name="period" <?php if ($boolPeriod == 1) { echo "checked"; } ?> value="1" onclick="changePeriod()" /> Par période
					</p>
					<p id="uniquedate">
						<label for="date">Date</label>
						<input id="date" type="date" name="date" value="<?php echo $strDate; ?>" />
					</p>
					<p id="period">
						<label for="startdate">Date de début</label>
						<input id="startdate" type="date" name="startdate"  value="<?php echo $strStartDate; ?>"/>
						<label for="enddate">Date de fin</label>
						<input id="enddate" type="date" name="enddate"  value="<?php echo $strEndDate; ?>"/>
					</p>
					<p>
						<label for="author">Auteur</label>
						<select id="author" name="author">
							<option <?php if ($intAuthor == ''){ echo "selected"; } ?> value=''>--</option>
						<?php
							foreach($arrUsers as $arrDetUser){
								/*if ($intAuthor == $arrDetUser['user_id']){ 
									$strSelected = "selected"; 
								}else{
									$strSelected = ""; 
								}*/
								$strSelected = ($intAuthor == $arrDetUser['user_id'])?"selected":"";
								echo "<option ".$strSelected." value='".$arrDetUser['user_id']."'>".$arrDetUser['user_firstname']."</option>";
							}
						?>
							<!--option value="2">test</option-->
						</select>
					</p>
					<p><input type="submit" value="Rechercher" /> <input type="reset" value="Réinitialiser" />
				</fieldset>
			</form>
			<?php
				foreach($arrArticles as $arrDetArticle){
					include("article.php");
				}
		
	include("footer.php");
?>