<?php
use yii\helpers\Html;
$this->registerJsFile('/statics/js/spark-md5/spark-md5.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<label for="">md5</label>
<div class="input-group" style="position: relative">

    <input type="text" id="md5" class="form-control" name="<?= $model->formName()."[{$field}]" ?>" placeholder="点击右边选择文件" value="<?php echo isset($model->md5)? $model->md5 : ''; ?>" />
    <input type="file" id="file" class="form-control" style="display: none"/>
    <span class="input-group-btn">
            <?= Html::button('选择文件', [
                'class' => 'btn btn-default select-file',
            ]) ?>

    </span>
</div>

<div id="box" style="display: none"></div>

<?php
$JS =<<<JS
   
  function calculate(){
    var fileReader = new FileReader(),
      box=document.getElementById('box');
      blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice,
      file = document.getElementById("file").files[0],
      chunkSize = 2097152,
      // read in chunks of 2MB
      chunks = Math.ceil(file.size / chunkSize),
      currentChunk = 0,
      spark = new SparkMD5();

    fileReader.onload = function(e) {
      console.log("read chunk nr", currentChunk + 1, "of", chunks);
      spark.appendBinary(e.target.result); // append binary string
      currentChunk++;

      if (currentChunk < chunks) {
        loadNext();
      }
      else {
        console.log("finished loading");
        box.innerText=spark.end();
        $('#md5').val(box.innerText)
        console.info("computed hash", spark.end()); // compute hash
      }
    };

    function loadNext() {
      var start = currentChunk * chunkSize,
        end = start + chunkSize >= file.size ? file.size : start + chunkSize;

      fileReader.readAsBinaryString(blobSlice.call(file, start, end));
    };

    loadNext();
  }
  
   $('.select-file').click(function() {
      $('#file').click();
  });
 
   $('#file').change(function() {
       calculate();
   });
JS;

$this->registerJs($JS);

?>

