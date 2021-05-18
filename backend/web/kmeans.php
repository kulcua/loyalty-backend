<?php

require "vendor/autoload.php";

$points = array();
$customerIds = array();
$credentials;
$description = "automatically made by Kmeans";
$list_segments = array();
$segmentId_index = array();

Login();
ReadSegmentData();
ReadCustomerData();

function ReadCustomerData()
{
    $url = '127.0.0.1/api/admin/customer';

    $result = GetData($url);

    CreatePoints($result);
}

function ReadSegmentData()
{
    global $description;
    global $list_segments;
    global $segmentId_index;

    $index = 0;

    $url = '127.0.0.1/api/segment';

    $result = GetData($url)->segments;

    foreach ($result as $s)
    {
        if ($s->description == $description){
            $list_segments = array_push_assoc($list_segments, $s->segmentId, $s->name);
            $segmentId_index = array_push_assoc($segmentId_index, $index, $s->segmentId);
            $index++;
        }
    }

    // var_dump($segmentId_index);
}

function GetData($url)
{
    global $credentials;

    $headers = [];
    $headers[] = "Authorization: Bearer {$credentials}";
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $headers[] = 'Cache-Control: no-cache';
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    
    // Debug the result
    // var_dump($result);
    return $result = json_decode($result);
}

function CreatePoints($result)
{
    global $points;
    global $customerIds;

    $current_year = date("Y");
    foreach ($result->customers as $c)
    {
        if ($c->gender == "male")
            $gender = 1;
        else $gender = 2;

        $transactionsAmount = rand(10,1000);
        $customerId = $c->customerId;

        $year = $current_year - date('Y', strtotime($c->birthDate));

        array_push($points, [$gender, $transactionsAmount, $year]);

        $key = (string)$gender.(string)$year.(string)$transactionsAmount;

        $customerIds = array_push_assoc($customerIds, $key, $customerId);
    }
}

// create a 2-dimentions space
$space = new KMeans\Space(3);

// add points to space
foreach ($points as $i => $coordinates) {
    $space->addPoint($coordinates);
}

// cluster these points in 3 clusters
$clusters = $space->solve(3);

printf("Segment result:\n");
// display the cluster centers and attached points
foreach ($clusters as $num => $cluster) {
    $coordinates = $cluster->getCoordinates();
    $customers = array();

    printf(
        "Cluster %s [%d,%d,%d]: %d points\n",
        $num,
        $coordinates[0],
        $coordinates[1],
        $coordinates[2],
        count($cluster)
    );

    foreach ($cluster as $point) {
        $gender = $point[0];
        $transactionsAmount = $point[1];
        $year = $point[2];
        printf("[%d,%d,%d]\n", $gender, $transactionsAmount, $year);
        $key = (string)$gender.(string)$year.(string)$transactionsAmount;
        $point = $space->addPoint([$gender, $transactionsAmount, $year], $customerIds[$key]);
        $data = $space[$point];
        array_push($customers, $data);
    }

    HandleSegment($num, $customers);
}

function array_push_assoc($array, $key, $value)
{
    $array[$key] = $value;
    return $array;
}

function Update($url, $data)
{
    global $credentials;
    $authorization = "Authorization: Bearer ".$credentials;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization));
    $json_response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($json_response);
    var_dump($response);
    return $response;
}

function HandleSegment($num, array $customers)
{
    global $description;
    global $list_segments;
    global $segmentId_index;

    if ($num == 0)
        $name = "Regular Customer";
    else if ($num == 1)
        $name = "Potential Customer";
    else $name = "VIP Customer";

    if (sizeof($list_segments) != 0)
    {
        $segmentId = $segmentId_index[$num];
        $name = $list_segments[$segmentId];
    }

    $data = array(
        "segment" => array (
            "name" => $name,
            "active" => "1",
            "description" => $description,
            "parts" =>  array ([
                "criteria" => array ([
                    "type" => "customer_list",
                    "customers" => $customers
                ])
            ])
        )
    );
    
    $data = json_encode($data);

    if (sizeof($list_segments) == 0)
    {
        $url = "127.0.0.1/api/segment";
        Post($url, $data);
    }
    else {
        $url = "127.0.0.1/api/segment/$segmentId";
        Update($url, $data);
    }
}

function Login()
{
    $data = array(
        "_username" => "admin",
        "_password" => "open",
    );
    
    $data = json_encode($data);
    $url = "127.0.0.1/api/admin/login_check";
    $response = Post($url, $data);

    global $credentials;
    $credentials = $response->token;
}

function Post($url, $data)
{
    global $credentials;
    $authorization = "Authorization: Bearer ".$credentials;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $json_response = curl_exec($curl);
    
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if ( $status != 200 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
    curl_close($curl);
    $response = json_decode($json_response);

    return $response;
}