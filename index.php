
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>IAMZON EXCEL/CSV BIRLESTIR</title>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css'>
    <link rel="stylesheet" href="./style.css">

  <style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
* {
    font-family: 'Montserrat', sans-serif;
}
    body {
      background-color: #000;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Arial', sans-serif;
    }
    



    .container {
      padding: 4rem 5rem;
      background-color: #fff;
      border-radius: 1rem;
      width: 100%;
      max-width: 45rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1, h4, .btn, .form-control {
      color: ;
      text-align: center;
    }
    h4 {
    color: #b0aeae;
    text-align: center;
    font-size: 20px;
}
p.text-center {
    color: #7e7e8a;
    margin-bottom: 0px;
}
.btn.btn-primary {
    background-color: #eab831;
    border-color: #eab831;
    outline: none;
}

    .btn-primary:hover {
      background-color: #efae01;
    }

.input-group-text {
    background-color: #eab831;
    border: none;
}

.logo {
    text-align: center;
    padding-bottom: 20px;
}
.logo {
    text-align: center;
    padding-bottom: 20px;
}
button.btn.btn-success.btn-lg.birlestir.mt-3{
    width: 100%;
    margin-top: 30px!important;
    background-color: black;
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}.btn.btn-primary:hover {
    background-color: #ffba00;
    border-color: #eab831;
}
.btn.btn-primary:active, .btn.btn-primary:focus {
    background-color: #eab831;
    border-color: #ddaf32;
}
.input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
    margin-left: -1px;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    background-color: #eab831;
        color: white;

}



  </style>
  

</head>
<body>
  <div class="container">
      <div class="logo">
      <img decoding="async" width="300" height="64" src="https://iamzon.com/wp-content/uploads/2023/03/logo2-1.svg" class="wp-image-7255" alt="">
      </div>
    <h1>Excel / CSV Birleştirme</h1>
    <h4>Sütun yapıları aynı olan Excel veya CSV dosyalarınızı buradan yükleyebilirsiniz.</h4>
    <form id="uploadForm" action="birlestir.php" method="post" enctype="multipart/form-data">
        
        
        
      <div class="form-group" x-data="{ fileName: '' }">
        <div class="input-group shadow">
<span class="input-group-text px-3 text-muted">
  <img src="merge.svg" alt="Merge Icon" style="width: 24px; height: 24px;">
</span>

          <input type="file" id="files" x-ref="file" @change="fileName = $refs.file.files[0].name" name="files[]" class="d-none" multiple="multiple" accept=".xlsx, .xls, .csv">
          <input type="text" class="form-control form-control-lg" placeholder="Desteklenen formatlar: .xlsx, .xls, .csv" x-model="fileName" readonly>
          <button class="browse btn btn-primary px-4" type="button" x-on:click.prevent="$refs.file.click()">Seçin</button>
        </div>
        
        <div class="form-group">
    <label for="headerRowCount">Başlık Satır Sayısı:</label>
    <select name="headerRowCount" id="headerRowCount" class="form-control">
        <option value="1">1 Satır</option>
        <option value="2">2 Satır</option>
        <option value="3" selected>3 Satır</option>
        <!-- İstenilen sayıda seçenek ekleyebilirsiniz -->
    </select>
</div>


        <button id="submitBtn" type="submit" class="btn btn-success btn-lg birlestir mt-3">BİRLEŞTİR</button>
      </div>
    </form>
    <p class="text-center">Lütfen yüklemek istediğiniz dosyaların sütun yapılarının aynı olduğundan emin olun.</p>
  </div>

  

<script>
    // index.php içindeki JavaScript kodunda

// Form submit butonunun click handler'ında:
formData.append('headerRowCount', document.getElementById('headerRowCount').value);
// ...fetch çağrısı...

</script>




<script>
    document.getElementById('files').addEventListener('change', function(e) {
    var totalSize = 0;
    for (var i = 0; i < this.files.length; i++) {
        totalSize += this.files[i].size;
    }
    if (totalSize > 10000000) { // 10 MB sınırı
        alert('Toplam dosya boyutu 10MB’yi aşmamalıdır.');
        this.value = ''; // Dosya seçimini sıfırla
    }
});


</script>

<script>
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
    var totalSize = 0;
    var files = document.getElementById('files').files;
    for (var i = 0; i < files.length; i++) {
        totalSize += files[i].size;
    }
    if (totalSize > 10000000) { // 10 MB sınırı
        e.preventDefault(); // Form gönderimini durdur
        alert('Toplam dosya boyutu 10MB’yi aşmamalıdır. Lütfen dosyalarınızı kontrol edin.');
    }
});


</script>
  <script src='https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js'></script>



</body>
</html>
