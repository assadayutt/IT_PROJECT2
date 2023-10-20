<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <title>Ads</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link href="../../Template/Officer/css/History.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="../../Template/officer/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</head>
<style>
.modal-header {
    background-color: #007bff;
    color: #fff;
}

.modal-title {
    color: #fff;
}
</style>
<?php include '../Admin/nav.php'; ?>

<body class="navbar-fixed sidebar-fixed" id="body">

    <div class="content-wrapper">
        <div class="content">
            <div class="card card-default">
                <div class="card-header align-items-center px-3 px-md-5">
                    <h2>จัดการรูปภาพหน้าเว็บไซต์ </h2>
                    <button type="button" class="btn btn-primary" data-toggle="modal" id='addpicture'> เพิ่มรูปภาพ
                    </button>
                </div>
                <div class="row">
                    <div class="card-body">
                        <table id="productsTable" class="table table-hover table-product" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th style="width: 50%">รูปภาพ</th>
                                    <th>ชื่อรูปภาพ</th>
                                    <th>ลบ</th>
                                </tr>
                            </thead>
                            <tbody id="imageList">
                                <!-- ที่นี่จะแสดงรายการรูปภาพ -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!------------------------ Modal Addpicture ----------------------->
    <div class="modal fade" id="Modal_addpicture">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มรูปภาพ โฆษณา</h4>
                </div>
                <div class="modal-body">
                    <form id="imageUploadForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <center>
                                <div class="mt-2">
                                    <img id="previewImage" class="preview-image" alt="รูปภาพ"
                                        style="width: 100%; height: 100%; object-fit: fit;" src="../../Images/4688.png">
                                </div>
                            </center>
                            <br>
                            <label for="picture"><span style="color: red;">*</span> อัพโหลดรูปภาพ</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="imageInput" name="image"
                                    accept="image/*" onchange="showPreview()">

                                <label class="custom-file-label" for="profileImage"
                                    id="profileImageLabel">เลือกไฟล์รูปภาพ</label>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="close_Modal" data-dismiss="modal">ปิด</button>
                    <button type="button" class="btn btn-success" id="savedata" onclick="uploadImage()">บันทึก</button>

                </div>
                </form>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <?php include '../../Footer/footer.php'; ?>
</body>

<script>
$(document).ready(function() {
    $("#addpicture").click(function() {
        $("#Modal_addpicture").modal('show');
    });
});

function showPreview() {
    var fileInput = document.getElementById('imageInput');
    var previewImage = document.getElementById('previewImage');
    var fileName = document.getElementById('imageInput').files[0].name;
    var reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
    }

    if (fileInput.files[0]) {
        reader.readAsDataURL(fileInput.files[0]);
        document.getElementById('profileImageLabel').innerHTML = fileName;
    } else {
        previewImage.src = '#';
        document.getElementById('profileImageLabel').innerHTML = 'เลือกไฟล์รูปภาพ';
    }
}
document.addEventListener("DOMContentLoaded", function() {
    fetchImages();

    function fetchImages() {
        fetch('../../AJAX/Admin_AJAX/Ads.php')
            .then(response => response.json())
            .then(data => {
                const imageList = document.getElementById('imageList');
                data.forEach((image, index) => {
                    // สร้างแถวในตาราง
                    const row = document.createElement('tr');

                    // เพิ่มคอลัมน์ลำดับ
                    const orderCell = document.createElement('td');
                    orderCell.textContent = index + 1;
                    row.appendChild(orderCell);

                    // เพิ่มคอลัมน์รูปภาพ
                    const imageCell = document.createElement('td');
                    const img = document.createElement('img');
                    img.src = image;
                    img.style.width = "200px";
                    img.style.height = "200px"; // เพิ่มเป็น "200%" เพื่อขยายรูปภาพใหญ่ขึ้น
                    imageCell.appendChild(img);

                    img.addEventListener('click', () => {
                        // เมื่อคลิกที่รูปภาพในตาราง
                        openImageInNewTab(image);
                    });
                    imageCell.appendChild(img);
                    row.appendChild(imageCell);

                    // เพิ่มคอลัมน์ชื่อรูปภาพ
                    const nameCell = document.createElement('td');
                    const imageName = image.split('/').pop(); // ดึงชื่อไฟล์
                    nameCell.textContent = imageName;
                    row.appendChild(nameCell);

                    // เพิ่มคอลัมน์ลบ
                    const deleteCell = document.createElement('td');
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'ลบ';
                    deleteButton.addEventListener('click', () => {
                        // ตัดชื่อไฟล์ออกจาก URL และพิมพ์ชื่อไฟล์ลงใน console.log
                        const imageName = image.split('/').pop();
                        console.log("ลบรูปภาพ: " + imageName);

                        // ส่งคำร้องขอลบรูปภาพไปยังไฟล์ PHP
                        fetch('../../AJAX/Admin_AJAX/delete_images_ads.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'image_name=' + encodeURIComponent(imageName)
                            })
                            .then(response => response.json())
                            .then(result => {
                                console.log(result);

                                if (result.message === 'success') {

                                    imageList.removeChild(row);
                                    Swal.fire({
                                        title: "Success!",
                                        text: "ลบรูปภาพสำเร็จ",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: "ไม่สามารถลบรูปภาพได้",
                                        icon: "error",
                                        confirmButtonText: "OK"
                                    });
                                }
                            })
                            .catch(error => console.error(error));
                    });


                    deleteCell.appendChild(deleteButton);
                    row.appendChild(deleteCell);

                    // เพิ่มแถวลงในตาราง
                    imageList.appendChild(row);
                });
            })
            .catch(error => console.error(error));
    }

    function openImageInNewTab(imageUrl) {
        // เปิดรูปภาพในหน้าต่างใหม่
        window.open(imageUrl, '_blank');
    }
});

function uploadImage() {
    const imageInput = document.getElementById('imageInput');
    const imageUploadForm = document.getElementById('imageUploadForm');
    const previewImage = document.getElementById('previewImage');

    if (imageInput.files.length > 0) {
        const formData = new FormData(imageUploadForm);

        fetch('../../AJAX/Admin_AJAX/Add_images_ads.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                if (result.success) {
                    Swal.fire({
                        title: "Success!",
                        text: "เพิ่มรูปภาพสำเร็จ",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        location.reload();
                    });
                    previewImage.src = result.image_url;
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: "ไม่สามารถเพิ่มรูปภาพได้",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            })
            .catch(error => console.error(error));
    } else {
        alert('โปรดเลือกไฟล์รูปภาพ');
    }
}
</script>


</html>