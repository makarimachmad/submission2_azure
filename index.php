<?php

require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = 'DefaultEndpointsProtocol=https;AccountName=makarimwebapp;AccountKey=1qX4Ke9CVDODMY7oKqp6knvy3JS1DqhjDRIAYu8keVUf30HmMf+SaPFnCoPtvQ8RGss0NMV2IeEvQS9fBSjq8w==;EndpointSuffix=core.windows.net';

$blobClient = BlobRestProxy::createBlobService($connectionString);

$createContainerOptions = new CreateContainerOptions();
$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
$createContainerOptions->addMetaData("key1", "value1");
$createContainerOptions->addMetaData("key2", "value2");

//generate string
//$containerName = "makarim".generateRandomString();

//nama container yg sudah ada
$containerName = "submission2";

//membuat container
//$blobClient->createContainer($containerName, $createContainerOptions);


if (isset($_POST['submit'])) {

    $fileToUpload = $_FILES["fileToUpload"]["name"];
    $content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
    echo fread($content, filesize($fileToUpload));
    $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
    header("Location: submission2.php");
    
}

$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);

?>  

<!DOCTYPE html>
<html>

<head>

    <style>
    th {
        background-color:#707B7C; border-right:solid 1px black; border-bottom:solid 1px black; font-size:8pt; padding:5px;font-family: arial; border-top: solid 1px black; border-left: solid 1px black;
    }
    td {
        border-right:solid 1px black; border-bottom:solid 1px black; font-size:8pt; padding:5px; font-family:arial; border-left:solid 1px black; border-top: solid 1px black; text-align: right;
    }
</style>
<title>Upload Photo and Computer Vision Analyze</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript" src="analisis.js"></script>

</head>

<body>
    Upload picture that need to be analyzed :
    <form action="submission2.php" method="post" enctype="multipart/form-data">
        <input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
        <input type="submit" name="submit" value="Upload">
    </form>

    <br>
    <br>

    <table>
        <tr>
            <th>File Name</th>
            <th>Gambar</th>
            <th>URL</th>
            <th>Action</th>
        </tr>

        <tbody>
            <?php
            do {
                foreach ($result->getBlobs() as $blob) {
                    ?>
                    <tr>
                        <td><?php echo $blob->getName() ?></td>
                        <td>
                            <img src="<?php echo $blob->getUrl() ?>" style="width: 30%; align-items: center;">
                        </td>
                        <td><?php echo $blob->getUrl()  ?></td>
                        <td>

                            <input type="hidden" name="url_blob" id="url_blob" value="<?php echo $blob->getUrl()?>">

                            <button onclick="processImage()">Analisa</button>

                        </td>
                    </tr>
                    <?php
                }
                $listBlobsOptions->setContinuationToken($result->getContinuationToken());
            }
            while ($result->getContinuationToken());
            ?>
        </tbody>
    </table>

    <br><br>
    <div id="wrapper" style="width:1020px; display:table;">
        <div id="jsonOutput" style="width:600px; display:table-cell;">
            Response:
            <br><br>
            <textarea id="responseTextArea" class="UIInput"
            style="width:580px; height:400px; display: none;"></textarea>
            <button id="tombollihat" onclick="myFunction()" style="display: block;">Lihat</button>
            <button id="tombolsembunyi" onclick="myFunction()" style="display: none;">Sembunyikan</button>
        </div>
        <div id="imageDiv" style="width:420px; display:table-cell;">
            Source image:
            <br><br>
            <img id="sourceImage" width="400" />
            <h3 id="description">...</h3>
        </div>
    </div>
</body>
</html>

<script type="text/javascript">
    function myFunction() {
      var x = document.getElementById("responseTextArea");
      var y = document.getElementById("tombollihat");
      var z = document.getElementById("tombolsembunyi");
      if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
        z.style.display = "block";
    } else {
        x.style.display = "none";
        y.style.display = "block";
        z.style.display = "none";
    }
} 
</script>
<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<!-- <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script> -->
