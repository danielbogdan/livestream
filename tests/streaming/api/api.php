<?php
if ($_POST['action'] == 'start_service') {
    $userId = $_POST['name'];
    $service = $_POST['service']; // facebook, youtube, hls
    $containerName = "{$userId}_{$service}";
 $dockerImage = "{$service}:latest";
//$dockerImage = "rtmp-server-rtmp:latest";


    $envVars = [
        "STREAM_NAME={$userId}",
        "FB_URL={$_POST['fb_url']}",
        "YT_KEY={$_POST['yt_key']}"
    ];

    // Convert environment variables to Docker's --env flag
    $envOptions = implode(' ', array_map(fn($env) => "--env $env", $envVars));

    // Start a new container
    $command = "docker run -d --name $containerName " .
               "--network shared-network " .
               "-v /path/to/shared-data:/mnt " .
               "$envOptions $dockerImage";
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to start container']);
        exit;
    }

    echo json_encode(['container_id' => $output[0]]);
    exit;
}

if ($_POST['action'] == 'stop_service') {
    $userId = $_POST['name'];
    $service = $_POST['service'];
    $containerName = "{$userId}_{$service}";

    $command = "docker rm -f $containerName";
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to stop container']);
        exit;
    }

    echo json_encode(['status' => 'ok']);
    exit;
}
?>