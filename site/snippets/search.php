<?php
    $conn = new PDO('sqlite:' . dirname(__DIR__, 2) . '/efs/data.db');

	$with_any_one_of = "";
	$with_the_exact_of = "";
	$without = "";
	$starts_with = "";
	$search_in = "";
	$advance_search_submit = "";
	$statusResult = $conn->query("SELECT * FROM Status", PDO::FETCH_ASSOC);
	
	$queryCondition = "";
	if(!empty($_POST["search"])) {
		$advance_search_submit = $_POST["advance_search_submit"];
		foreach($_POST["search"] as $k=>$v){
			if(!empty($v)) {
				$queryCases = array("with_any_one_of","with_the_exact_of","without","starts_with");
				if(in_array($k,$queryCases)) {
					if(!empty($queryCondition)) {
						$queryCondition .= " AND ";
					} else {
						$queryCondition .= " WHERE ";
					}
				}
				switch($k) {
					case "with_any_one_of":
						$with_any_one_of = $v;
						$wordsAry = explode(" ", $v);
						$wordsCount = count($wordsAry);
						for($i=0;$i<$wordsCount;$i++) {
							if(!empty($_POST["search"]["search_in"])) {
								$queryCondition .= $_POST["search"]["search_in"] . " LIKE '%" . $wordsAry[$i] . "%'";
							} else {
								$queryCondition .= "Titel LIKE '" . $wordsAry[$i] . "%' OR Projektbeschreibung LIKE '" . $wordsAry[$i] . "%'";
							}
							if($i!=$wordsCount-1) {
								$queryCondition .= " OR ";
							}
						}
						break;
					case "with_the_exact_of":
						$with_the_exact_of = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " LIKE '%" . $v . "%'";
						} else {
							$queryCondition .= "Titel LIKE '%" . $v . "%' OR Projektbeschreibung LIKE '%" . $v . "%'";
						}
						break;
					case "without":
						$without = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " NOT LIKE '%" . $v . "%'";
						} else {
							$queryCondition .= "Titel NOT LIKE '%" . $v . "%' AND Projektbeschreibung NOT LIKE '%" . $v . "%'";
						}
						break;
					case "starts_with":
						$starts_with = $v;
						if(!empty($_POST["search"]["search_in"])) {
							$queryCondition .= $_POST["search"]["search_in"] . " LIKE '" . $v . "%'";
						} else {
							$queryCondition .= "Titel LIKE '" . $v . "%' OR Projektbeschreibung LIKE '" . $v . "%'";
						}
						break;
					case "search_in":
						$search_in = $_POST["search"]["search_in"];
						break;
				}
			}
		}
	}
	$orderby = " ORDER BY id asc"; 
	$sql = "SELECT * FROM vakanzengrabber " . $queryCondition . $orderby;
	$result = $conn->query($sql);
	
?>
<html>
	<head>
	<style>
		h2 {
			font-size:38px;
		}
		.frmSearch{
			width: 800px;
			font-family: "Segoe UI",Optima,Helvetica,Arial,sans-serif;
			line-height: 25px;
		}
		.search-box {
			padding: 50px;
			background-color:#C8EEFD;
		}
		.search-label{
			margin:2px;
			font-weight: bold;
		}
		#advanced-search-box-left{
			float: left;
		}
		#advanced-search-box-right{
			float: left;
		}
		#advanced-search-box{
			display: none;
		}
		.SearchInputBox, .status{   
			font-style: italic;
			color: #505050;
			padding: 10px;
			border: 0;
			border-radius: 4px;
			margin: 10px 10px 5px 15px;
		}
		.SearchInputBox {
			width: 350px;
		}
		.staus{
			width: 80px;
		}
		#search-btn-box{
			float:none;
		}
		.btnSearch{    
			padding: 5px;
			background: #84D2A7;
			font-size: 24px;
			border: 0;
			float: right;
			border-radius: 4px;
			margin: 0px 5px;
			color: #FFF;
			width: 200px;
			cursor: hand;
		}
		#advance_search_link {
			background: #84D2A7;
			float: right;
			cursor: hand;
			padding: 10px;
			background: #84D2A7;
			border: 0;
			float: right;
			border-radius: 4px;
			margin: 0px 5px;
			color: #FFF;
			width: 150px;
		}
		.result-description{
			margin: 5px 0px 15px;
		}
		#search_results{
			cellpadding: 10px;
			cellspacing: 1px;
			border: 1px;
		}
		#search_results th{
			background-color: #C8EEFD;
			font-size:20px;
			font-weight: bold;
			padding-left: 5px;
			text-align: left;
			color: black;
		}
		tr:nth-child(even) {background: #f2f6fa}
		tr:nth-child(odd) {background: #bcd1e3}
		
		#search_results td{
			padding-left: 10px;
			padding-right: 10px;
		}
		#no_results
		{
			padding-left: 25px;
			font-style: italic;
			font-size: 20px;
			float: center;
		}
		
		a {
			font-style: italic;
			text-decoration: underline;
		}
	</style>
	<script>
		function showHideAdvanceSearch() {
			if(document.getElementById("advanced-search-box").style.display=="none") {
				document.getElementById("advanced-search-box").style.display = "inline-block";
				document.getElementById("advanced-search-box-left").style.display = "inline-block";
				document.getElementById("advanced-search-box-right").style.display = "inline-block";
				document.getElementById("advance_search_submit").value= "1";
			} else {
				document.getElementById("advanced-search-box").style.display = "none";
				document.getElementById("advanced-search-box-left").style.display = "none";
				document.getElementById("advanced-search-box-right").style.display = "none";
				document.getElementById("with_the_exact_of").value= "";
				document.getElementById("without").value= "";
				document.getElementById("starts_with").value= "";
				document.getElementById("search_in").value= "";
				document.getElementById("advance_search_submit").value= "";
			}
		}
	</script>
	</head>
	<body>
		<h2>Suche</h2>
    	<div id="search_window">      
			<form name="frmSearch" method="post">
			<input type="hidden" id="advance_search_submit" name="advance_search_submit" value="<?php echo $advance_search_submit; ?>">
			<div class="search-box">
				<label class="search-label">Suchbegriff</label>
				<div>
					<input type="text" name="search[with_any_one_of]" class="SearchInputBox" value="<?php echo $with_any_one_of; ?>"	/>
					<span id="advance_search_link" onClick="showHideAdvanceSearch()">Erweiterte Suche</span>
				</div>				
				<div id="advanced-search-box" <?php if(empty($advance_search_submit)) { ?>style="display:none;"<?php } ?>>
					<div>
					<div id="advanced-search-box-left">
						<label class="search-label">Exakter Suchbegriff:</label>
						<div>
							<input type="text" name="search[with_the_exact_of]" id="with_the_exact_of" class="SearchInputBox" value="<?php echo $with_the_exact_of; ?>"	/>
						</div>
						<label class="search-label">Ohne:</label>
						<div>
							<input type="text" name="search[without]" id="without" class="SearchInputBox" value="<?php echo $without; ?>"	/>
						</div>
						<label class="search-label">Beginn mit:</label>
						<div>
							<input type="text" name="search[starts_with]" id="starts_with" class="SearchInputBox" value="<?php echo $starts_with; ?>"	/>
						</div>
						<label class="search-label">Suche in Spalte:</label>
						<div>
							<select name="search[search_in]" id="search_in" class="SearchInputBox">
								<option value="">Suche in Spalte</option>
								<option value="title" <?php if($search_in=="title") { echo "selected"; } ?>>Titel</option>
								<option value="description" <?php if($search_in=="description") { echo "selected"; } ?>>Beschreibung</option>
							</select>
						</div>
					</div>
					<div id="advanced-search-box-right">
						<label class="search-label">Status:</label>
						<select id="status" name="status[]" multiple="multiple" class="SearchInputBox">
							<option value="0" selected="selected">Alle</option>
							<?php
								if (!empty($statusResult)) {
									foreach($statusResult as $row) {
										 echo '<option value="' . $row['Beschreibung'] . '">' . $row['Beschreibung'] . '</option>';
									}
								 }
								 else
								 {
									 echo '<option value="None">Keine Einträge gefunden</option>';
								 }
							?>
						</select>
					</div>
					</div>
				</div>
				<div id="search-btn-box">
					<button type="submit" class="btnSearch" action="search.php">Suchen</button>
				</div>
			</div>
			</form>
		<h2>Suchergebnisse</h2>
    	<?php
		if (! empty($result)) {
		?>
		<table id="search_results">
		    <thead>
		        <tr>
		            <th><strong>ID</strong></th>
		            <th><strong>Titel</strong></th>
		            <th><strong>Ort</strong></th>
		            <th><strong>Start</strong></th>
		            <th><strong>Ende</strong></th>
		            <th><strong>Status</strong></th>
		        </tr>
		    </thead>
		    <tbody>
	        	<?php
			    foreach($result as $row) {
			    ?>
			        <tr>
			            <td><div class="col" id="ID">
			                    <?php echo $row['ID']; ?>
			                </div></td>
			            <td><div class="col" id="title">
			                    <a href=""><?php echo $row['Titel']; ?></a>
			                </div></td>
			            <td><div class="col" id="description">
			                    <?php echo $row['Ort']; ?>
			                </div></td>
			            <td><div class="col" id="description">
			                    <?php echo $row['Start']; ?>
			                </div></td>
			            <td><div class="col" id="description">
			                    <?php echo $row['Ende']; ?>
			                </div></td>
			            <td><div class="col" id="description">
								<select id="status" name="status" class="SearchInputBox">
									<?php
										$statusResult = $conn->query("SELECT * FROM Status", PDO::FETCH_ASSOC);
										if (!empty($statusResult)) {
											foreach($statusResult as $row) {
												 echo '<option value="' . $row['Beschreibung'] . '">' . $row['Beschreibung'] . '</option>';
											}
										 }
										 else
										 {
											 echo '<option value="None">Keine Einträge gefunden</option>';
										 }
									?>
								</select>
			                </div></td>
			        </tr>
			    <?php
			    	} # Ende For-Loop
			    ?>
		    </tbody>
		</table>
	    <?php
		} # Ende If
		else {
	    ?>
		<div class="col" id="no_results">Keine Resultate gefunden</div></td>
	    <?php
	    }
	    ?>
	</body>
</html>