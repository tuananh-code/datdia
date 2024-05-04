<?php
include_once 'config.php';
$sql = "SELECT * FROM bravo_cron WHERE name IS NOT NULL";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="crawl-space/bootstrap-css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="./bootstrap-css/bootstrap.min.css" rel="stylesheet" > -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <title>Document</title>
</head>

<body class='container'>
    <?php

    ?>
    <style>
        table,
        td,
        th {
            padding: .5em;
            border: .15em solid black;
        }

        .container {
            max-width: 92%;
        }

        /* Custom select styles */
        .form-select {
            /* Add your desired styles here */
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px;
            width: 200px;
        }

        /* Custom select option styles */
        .form-select option {
            /* Add your desired styles here */
            background-color: #fff;
            color: #333;
        }
    </style>
    <div class="d-flex align-items-center">
        <form action="" method="post" accept-charset="UTF-8" id='formValue'>
            <div class='d-flex justify-content-center align-items-center m-4'>
                <div class='m-2'>
                    <!-- <label for="select">Chọn địa điểm</label>
                    <select name="select" id="select" class="form-select" multiple>
                        <?php foreach ($result as $row) { ?>
                            <option class="getWeb" value="<?= $row['url'] ?>" data-web='<?= $row['name'] ?>'>
                                <?= $row['name'] ?>
                            </option>
                        <?php } ?>
                    </select> -->
                    <label for="url">Website</label>
                    <input class="form-control" type="text" placeholder="Nhập URL Website" value="https://www.century21.com.au/properties-for-sale?searchtype=sale" id='url' name='url'>
                    <!-- <select class="form-control" id='select' name='select'>
                        <option selected>Chọn website</option>
                        <option value="https://alonhadat.com.vn/nha-dat/can-ban">alonhadat.com.vn/nha-dat/can-ban</option>
                        <option value="https://muaban.net/bat-dong-san/ban-nha">muaban.net/bat-dong-san/ban-nha</option>
                    </select> -->
                </div>
                <div class='m-2'>
                    <label for="page">Số trang của website</label>
                    <input class="form-control" type="text" placeholder="Nhập số trang" value='1' id='page' name='page'>
                </div>
                <div class='m-2'>
                    <label for="pageOption">Nhập Url Page</label>
                    <input class="form-control" type="text" placeholder="Nhập Url Page" value="#page=" id='pageOption' name='pageOption'>
                </div>
                <div class='m-2'>
                    <label for="xPath">Nhập XPath</label>
                    <input class="form-control" type="text" placeholder="Nhập XPath" id='xPath' value='//*[@id="left"]/div[1]/div[1]' name='xPath'>
                </div>
            </div>
            <h3 class="text-center">Thông tin bất động sản</h3>
            <div class='d-flex justify-content-center'>
                <div class='m-2'>
                    <label for="moreInfo">Mô tả</label>
                    <input class="form-control" type="text" placeholder="Mô tả" value='//*[@id="left"]/div[1]/div[2]' id='moreInfo' name='moreInfo'>
                </div>
                <!-- <div class='m-2'>
                    <label for="price">Giá</label>
                    <input class="form-control" type="text" placeholder="Chi tiết" value='//*[@id="left"]/div[1]/div[5]/div[2]/table/tbody' id='price' name='price'>
                </div>
                <div class='m-2'>
                    <label for="location">Địa chỉ</label>
                    <input class="form-control" type="text" placeholder="Chi tiết" value='//*[@id="left"]/div[1]/div[5]/div[2]/table/tbody' id='location' name='location'>
                </div> -->
                <div class='m-2'>
                    <label for="detail">Chi tiết</label>
                    <input class="form-control" type="text" placeholder="Chi tiết" value='//*[@id="left"]/div[1]/div[5]/div[2]/table/tbody' id='detail' name='detail'>
                </div>

            </div>
            <div class='d-flex justify-content-center'>
                <button class="border border-primary m-2 p-2 rounded-2" type="submit" id='getValue'> Get Value</button>
                <button class='m-2 p-2 bg-success text-light rounded-2' id='update'>
                    Update lat long
                </button>
                <button class='m-2 p-2 bg-success text-light rounded-2' id='updateExist'>
                    Update duplicate lat long
                </button>
            </div>
        </form>
        <div id='allData'>

        </div>
    </div>
    <div id="loadingAlert" class="" style="display:none;">
        <div class='d-flex justify-content-center'>
            <!-- <img src="./image/713a3272124cc57ba9e9fb7f59e9ab3b.gif" alt=""> -->
        </div>
    </div>

    <!-- //FIXME: change path -->
    <script type='text/javascript' src='crawl-space/js/index.js'>
        // <script type='text/javascript' src='./js/index.js'>
    </script>
</body>

</html>