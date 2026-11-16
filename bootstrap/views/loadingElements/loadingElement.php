<?php
$clientRoot = explode('/', $_SERVER["REQUEST_URI"])[1];
?>
<link rel="stylesheet" href="/<?php echo $clientRoot; ?>/bootstrap/views/loadingElements/loadingElement.css">

<div id="loading-spinner" class="hidden loading-element">
    <div class="spinner-container">
        <div class="spinner-border" role="status"></div>
        <span class="spinner-indicator indicator-text">
            <span id="spinner-loading-text">Loading</span>
            <span id="spinner-loading-dots"></span>
        </span>
    </div>
</div>

<div id="loading-circular" class="hidden loading-element">
    <div class="progress-circle mt-5" id="progress-circle" style="--progress: 0deg;">
        <span class="progress-indicator indicator-text">
            <span id="circle-loading-text">Loading</span>
            <span id="circle-loading-dots"></span>
        </span>
        <div class="progress-text indicator-text" id="progress-text">0%</div>
    </div>
</div>

<div id="loading-linear" class="hidden loading-element">
    <div class="loading-indicator indicator-text">
        <span id="linear-loading-text">Loading</span>
        <span id="linear-loading-dots"></span>
    </div>
    <div class="progress">
        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
            aria-valuemax="100" id="linear-progress-bar"></div>
        <div class="progress-text indicator-text" id="linear-progress-text">0%</div>
    </div>
</div>