<?php
// Database credentials
$hostname = "localhost";
$username = "bearnett_bot";
$password = "12345";
$database = "bearnett_bot";

// Connecting to the database
$conn = mysqli_connect($hostname, $username, $password, $database) or die("Database Error");

// Getting user message through AJAX
$getMesg = mysqli_real_escape_string($conn, $_POST['text']);

// Exploding user message into keywords
$keywords = explode(" ", $getMesg);

// Constructing a WHERE clause to check for matches with each keyword
$whereClause = '';
foreach ($keywords as $keyword) {
    $whereClause .= " OR queries LIKE '%$keyword%'";
}
$whereClause = ltrim($whereClause, " OR");

// Checking user query against the database with the updated WHERE clause
$check_data = "SELECT replies FROM chatbot WHERE $whereClause";
$run_query = mysqli_query($conn, $check_data) or die("Error");

// If user query matches a database entry, show the reply; otherwise, display a default message
if (mysqli_num_rows($run_query) > 0) {
    // Fetching reply from the database according to the user query
    $fetch_data = mysqli_fetch_assoc($run_query);
    // Storing reply to a variable which we'll send to AJAX
    $reply = $fetch_data['replies'];
    echo $reply;
} else {
    echo "I apologize for any confusion. I'm currently in the learning phase, working on improving my skills. Feel free to try asking me in a different way.";
}

// Close the database connection
mysqli_close($conn);
?>
