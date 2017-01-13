<?php
include_once("connection.php");

// This first query is just to get the total count of rows
$sql = "SELECT COUNT(id) FROM people";
$query = mysqli_query($connection, $sql);
$row = mysqli_fetch_row($query);

// Here we have the total row count
$rows = $row[0];
// This is the number of results we want displayed per page
$page_rows = 10;

// This tells us the page number of our last page
$last = ceil($rows/$page_rows);
// This makes sure $last cannot be less than 1
if($last < 1) {
    
	$last = 1;
    
}


// Get pagenum from URL vars if it is present, else it is = 1

if (isset($_GET['pn']) && is_numeric($_GET['pn'])) {
    
	$pagenum = floor($_GET['pn']);
    
} else {
    
    $pagenum = 1;
    
}

// This makes sure the page number isn't below 1, or more than our $last page
if ($pagenum < 1) {
    
    $pagenum = 1; 
    
} else if ($pagenum > $last) { 
    
    $pagenum = $last;
    
}

// This sets the range of rows to query for the chosen $pagenum
$limit = 'LIMIT ' . ($pagenum - 1) * $page_rows . ',' . $page_rows;
// This is your query again, it is for grabbing just one page worth of rows by applying $limit
$sql = "SELECT id, username, country, gender FROM people ORDER BY id DESC $limit";
$query = mysqli_query($connection, $sql);

// This shows the user what page they are on, and the total number of pages
$textline1 = "Testimonials <b>$rows</b>";
$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";


// Establish the $paginationCtrls variable
$paginationCtrls = '';


// If there is more than 1 page worth of results
if($last != 1) {

/* First we check if we are on page one. If we are then we don't need a link to 
	   the previous page or the first page so we do nothing. If we aren't then we
	   generate links to the first page, and to the previous page. */
	if ($pagenum > 1) {
        
        $previous = $pagenum - 1;
        
		$paginationCtrls .= '<a class="btn btn-primary" href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '">Previous</a> &nbsp; &nbsp; ';
        
        
		// Render clickable number links that should appear on the left of the target page number
        for($i = $pagenum - 4; $i < $pagenum; $i++) {
            
			if($i > 0) {
                
		        $paginationCtrls .= '<a class="btn btn-primary" href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '">' . $i . '</a> &nbsp; ';
			}
	    }
    }
    
	// Render the target page number, but without it being a link
	$paginationCtrls .= '' . $pagenum . ' &nbsp; ';
	
    // Render clickable number links that should appear on the right of the target page number
	for($i = $pagenum + 1; $i <= $last; $i++) {
        
		$paginationCtrls .= '<a class="btn btn-primary" href="' . $_SERVER['PHP_SELF'] . '?pn=' . $i . '">' . $i . '</a> &nbsp; ';
        
		if($i >= $pagenum + 4) {
            
			break;
            
		}
	}
	// This does the same as above, only checking if we are on the last page, and then generating the "Next"
    if ($pagenum != $last) {
        
        $next = $pagenum + 1;
        $paginationCtrls .= ' &nbsp; &nbsp; <a class="btn btn-primary" href="' . $_SERVER['PHP_SELF'] . '?pn=' . $next . '">Next</a> ';
    }
}
$list = '';
while ($row = mysqli_fetch_assoc($query)) {
    
	$id = $row["id"];
	$username = $row["username"];
	$gender = $row["gender"];
	$country = $row["country"];
	
	$list .= "<p>$id $username $gender $country</p>";
}

mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Pagination</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        #container {
            width: 764px;
            margin: 0 auto;
            padding-top: 50px;
        }
        #container > h2, h4 {
            text-align: center;
        }
        #pagination_controls {
            text-align: center;
            font-size: 21px;
            width:90%;
            margin: 0 auto;
        }
        #pagination_controls > a { 
            color: whitesmoke; 
        }
        #pagination_controls > a:visited { 
            color: whitesmoke; 
        }
    </style>
</head>

<body>
<div id="container">
    <h2><?php echo $textline1; ?> results</h2>
    <p><?php echo $list; ?></p>
    <h4><?php echo $textline2; ?></h4>
    <div id="pagination_controls"><?php echo $paginationCtrls; ?></div>
</div>
</body>
</html>