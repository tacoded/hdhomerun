<?php
$jsonData = file_get_contents('http://hdhr-10ad25aa/lineup.json');
    
file_put_contents('ota.json', $jsonData);

$channels = json_decode($jsonData, true);

$count = count($channels);

function getSignalColor($value) {
    if ($value >= 60) return '#4cff50'; // Green
    return '#f44400';                   // Red
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="hdhr.ico">
    <meta charset="UTF-8">
    <title>Signal Metrics</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 30px; background-color: #f0f2f5; }
        table { width: 900px; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        th { background-color: #1a237e; color: white; padding: 15px; text-align: left; text-transform: uppercase; font-size: 0.85rem; }
        td { padding: 12px 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #ccccff; }

        .bar-container { display: flex; align-items: center; min-width: 140px; }
        .bar-label { width: 35px; font-size: 0.85rem; font-weight: bold; color: #555; }
        .bar-bg { flex-grow: 1; background: #aaa; height: 8px; border-radius: 4px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 4px; transition: width 0.5s ease-in-out; }

        .badge { padding: 2px 6px; border-radius: 3px; font-size: 0.7rem; font-weight: 800; margin-right: 4px; display: inline-block; vertical-align: middle; }
        .hd { background: #00bcd4; color: white; }
        .drm { background: #e91e63; color: white; }
        .codec { color: #888; font-size: 0.8rem; }
        .url-btn { text-decoration: none; background: #cccccc; color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; }
        .url-btn:hover { background: #555555; }
    </style>
</head>
<body>

<?php include 'discover.php'; ?>

<h2>Signal Metrics (<?php echo $count; ?> channels)</h2>

<table>
    <thead>
        <tr>
            <th>Channel</th>
            <th>Name</th>
            <th>Flags</th>
            <th>Signal Strength</th>
            <th>Signal Quality</th>
            <th>Specs</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($channels as $channel): ?>
        <tr>
            <td><strong><?php echo $channel['GuideNumber']; ?></strong></td>
            <td>
                <?php echo $channel['GuideName']; ?>
                </td><td><?php if (isset($channel['HD']) && $channel['HD'] == 1): ?>
                    <span class="badge hd">HD</span>
                <?php endif; ?>
                <?php if (isset($channel['DRM']) && $channel['DRM'] == 1): ?>
                    <span class="badge drm">DRM</span>
                <?php endif; ?>
            </td>
            
            <td>
                <div class="bar-container">
                    <span class="bar-label"><?php echo $channel['SignalStrength'] ?? ''; ?></span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: <?php echo $channel['SignalStrength'] ?? ''; ?>%; background-color: <?php echo getSignalColor($channel['SignalStrength'] ?? ''); ?>;"></div>
                    </div>
                </div>
            </td>

            <td>
                <div class="bar-container">
                    <span class="bar-label"><?php echo $channel['SignalQuality'] ?? ''; ?></span>
                    <div class="bar-bg">
                        <div class="bar-fill" style="width: <?php echo $channel['SignalQuality'] ?? ''; ?>%; background-color: <?php echo getSignalColor($channel['SignalQuality'] ?? ''); ?>;"></div>
                    </div>
                </div>
            </td>

            <td>
                <span class="codec"><?php echo $channel['VideoCodec']; ?> / <?php echo $channel['AudioCodec'] ?? '---'; ?></span>
            </td>

            <td>
                <a class="url-btn" href="<?php echo $channel['URL']; ?>?duration=10">Sample</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
