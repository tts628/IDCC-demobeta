<!doctype html>
<html>

<head>
    <style>
        html,
        body {
            background-color: rgb(39, 39, 39) !important;
        }

        .bg-gray {
            background-color: #333333;
        }

        #screenshot-image {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            /* Maintain aspect ratio */
            height: auto;
            /* Maintain aspect ratio */
        }

        .screenshot-container {
            width: 100vw;
            /* or another appropriate value */
            height: 80vh;
            overflow: hidden;
            /* Hide overflow if the image exceeds container dimensions */
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
</head>

<body>
    <div class="bg-gray p-3 mb-3">
        <h3 class="text-white">
            <span id="machine-name"></span>
            軟體運行畫面 Màn hình chạy phần mềm
        </h3>
    </div>
    <select class="form-select w-25 ms-3" id="machine-selector"></select>
    <div class="mt-5 text-center screenshot-container">
        <img id="screenshot-image">
    </div>
</body>

</html>
<script src="./machineSelectHandler.js" type="module"></script>