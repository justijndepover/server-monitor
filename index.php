<?php

if ($_SERVER['HTTP_ACCESS_TOKEN'] != 'TOKEN') {
    header("HTTP/1.1 401 Unauthorized");
    die();
}

header('Content-Type: application/json');

$stats = array();
$stats['data']['ip'] = $_SERVER['SERVER_ADDR'];
$stats['data']['ram'] = get_server_memory_usage();
$stats['data']['cpu'] = get_server_cpu_usage();
$stats['data']['diskspace'] = get_server_diskspace();

echo(json_encode($stats));

function get_server_memory_usage()
{
    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    $memory_usage = $mem[2]/$mem[1]*100;

    return $memory_usage;
}

function get_server_cpu_usage()
{
    $load = sys_getloadavg();
    return $load[0];
}

function get_server_diskspace()
{
    $output = exec('df -P .');
    preg_match('/(\d?\d)%/', $output, $matches);
    return $matches[1];
}
