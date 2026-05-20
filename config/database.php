<?php
//database configuration
$LOCAL_HOST = 'localhost'; // 127.0.0.1
$LOCAL_DBNAME = 'app-beta';
$LOCAL_USERNAME = 'postgres';
$LOCAL_PASSWORD = 'unicesmag';
$LOCAL_PORT = '5432';

// Supabase PostgreSQL configuration
$SUPA_HOST = 'aws-0-us-west-2.pooler.supabase.com';
$SUPA_DBNAME = 'postgres';
$SUPA_USERNAME = 'postgres.zwustwrpovllqglqmkzu';
$SUPA_PASSWORD = 'Perry.1004634903';
$SUPA_PORT = '6543';

$local_data_connection = "
    host=$LOCAL_HOST
    dbname=$LOCAL_DBNAME
    user=$LOCAL_USERNAME
    password=$LOCAL_PASSWORD
    port=$LOCAL_PORT
    ";

$supa_data_connection = "
    host = $SUPA_HOST
    dbname = $SUPA_DBNAME
    user = $SUPA_USERNAME
    password = $SUPA_PASSWORD
    port = $SUPA_PORT
    ";
//local conncetion
    $local_conn = pg_connect($local_data_connection);

    if(!$local_conn){
        echo "  Error: Unable to connect to local database. ";
        exit();
    }else{
        echo "Success local Connection !!!";
    }
//supa conection
    $supa_conn = pg_connect($supa_data_connection);

    if(!$supa_conn){
        echo "Error: Unable to connect to supa database";
        exit();
    }else{
        echo "<br>supa succes connection ";
    }
    
?>