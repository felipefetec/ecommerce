
<?php
$headers = apache_request_headers();

foreach ($headers as $header => $value) {
    echo "$header: $value <br />\n";
}



$rawData = file_get_contents("php://input");
echo '<pre>';
// this returns null if not valid json
print_r( json_decode($rawData) );


print_r($_POST);