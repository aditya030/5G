<?php
class MyDB extends SQLite3 {
    function __construct() {
       $this->open('nokia_model_level.db');
    }
}

$db = new MyDB();
$viewType = $_GET['view'] ?? 'real'; // Default to 'real' view if not specified
$faultId = $_GET['faultId'] ?? '';



switch ($viewType) {
    case 'real':
        $columnName = 'full_view';
        break;
    case 'schematic':
        $columnName = 'schematic_view'; 
    case 'description':
        $columnName = 'description_view'; 
        break;
    default:
        $columnName = 'full_view'; 
        break;
}

$sql =<<<EOF
    SELECT $columnName FROM faulty_id WHERE failure_id_model = '$faultId'
EOF;
$result = $db->querySingle($sql,true);

if ($result) {
    $data = base64_encode($result[$columnName]);
    echo "<img src=\"data:image/png;base64,$data\"/>";
    echo "<br>";
    echo "Website URL: <a href=\"$websiteUrl\" target=\"_blank\">$websiteUrl</a>"; // Display the website URL as a link
} else {
    echo "View not found for the specified fault ID.";
}
?>
