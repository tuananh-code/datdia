$("#page").on("input", function (event) {
    var input = $(this);
    var sanitizedValue = input.val().replace(/\D/g, "");
    input.val(sanitizedValue);
});

$("#remove").click(function () {
    $("p, br, .getContent").remove();
});

$("#select").change(function () {
    var selectedOptions = $(this).find(".getWeb:selected");
    var selectedValues = [];

    selectedOptions.each(function () {
        var value = $(this).val();
        selectedValues.push(value);
    });

    $(this).attr("data-web", selectedValues.join("|"));
});

// Example usage:
$(document).on("click", "#getValue", function (e) {
    e.preventDefault();
    var data = $("#formValue").serializeArray();
    var url = $("#select").attr("data-web").split("|");
    var page = parseFloat($("#page").val());
    var xPath = $("#xPath").val();
    var pageOption = $("#pageOption").val();
    var moreInfo = $("#moreInfo").val();
    var price = $("#price").val();
    var location = $("#location").val();
    var detail = $("#detail").val();

    // for (var i = 1; i <= page; i++) {
    //     if (pageOption.length === 0) {
    //         var pageNumber = i;
    //     } else {
    //         var pageNumber = pageOption + i;
    //     }
    //     var lengthLocated = located.length;
    //     // var all = [];
    //     // for (var i = 0; i < lengthLocated; i++) {
    //     //   all.push(url + located[i]);
    //     // }
    //     // $.ajax({
    //     //   data: {
    //     //     url: all,
    //     //   },
    //     //   url: "add_link.php",
    //     //   method: "post",
    //     //   success: function (response) {
    //     //     console.log(response);
    //     //   },
    //     // });
    //     // return;
    for (var i = 0; i < url.length; i++) {
        var multiUrl = url[i];
        $.ajax({
            data: {
                // url: url,
                url: multiUrl,
                // url: url + pageNumber + located[l],
                // page: i,
                xPath: xPath,
            },
            url: "/crawl-space/poe.php",
            type: "post",
            // dataType: 'JSON', // It bug the code and don't send value to ajax
            // contentType: false,
            beforeSend: function (xhr) {
                $("#loadingAlert").fadeIn();
            },
            success: function (response) {
                // console.log(response[0]);
                if (response[0]) {
                    var result = response[0];
                } else {
                    var result = response;
                }
                var contentLength = result["href"].length;
                var allArr = [];
                var allNames = "";
                var allPrices = "";
                var allLocations = "";
                for (var x = 0; x < contentLength; x++) {
                    var content = result["name"][x];
                    // allArr.push(result['content'][x]);
                    // var pushAll = allContent += result['content'][x] + `<br>`;
                    var pushName = (allNames += result["name"][x] + `<br>`);
                    var pushPrice = (allPrices += result["price"][x] + `<br>`);
                    var pushLocation = (allLocations +=
                        result["location"][x] + `<br>`);
                    // console.log(content);
                }
                // var para = `
                //     <td>${pushName}</td>
                //     <td>${pushPrice}</td>
                //     <td>${pushLocation}</td>`;
                // var button = `<div class='d-flex align-items-center'>
                //         <button type='submit' class='d-flex align-items-center p-1 getContent'> Lấy toàn bộ chap và nội dung </button>
                //     </div>`;
                // var getAll = $('#getAll');
                // getAll.append(button)
                var para = `
                  <div class='d-flex'>
                      <p class='col-lg-6 p-1'>${pushName}</p>
                      <p class='col-lg-2 p-1'>${pushPrice}</p>
                      <p class='col-lg-4 p-1'>${pushLocation}</p>
                  </div>
                  <br>`;
                // var allContent = allArr.join(' '); // Join the array elements with a space
                $("#allData").html(para);
                var href = result["href"];
                // console.log(href.length);
                for (var i = 0; i < href.length; i++) {
                    $.ajax({
                        data: {
                            infoName: result["name"][i],
                            infoPrice: result["price"][i],
                            infoLocation: result["location"][i],
                            info: result["info"][i],
                            // infoDescription: result['description'][i],
                            // infoPrivate: result['info_private'][i],
                            // infoPublic: result['info_public'][i],
                            moreInfo: moreInfo,
                            detail: detail,
                            href: href[i],
                        },
                        url: "/crawl-space/getInfo.php",
                        type: "post",
                        success: function (getInfo) {
                            // var geoLocation = [];
                            console.log(getInfo);
                            var geoLocation = getInfo["location"];
                            $.ajax({
                                data: {
                                    location: geoLocation,
                                },
                                url: "/crawl-space/lat_long_convert.php",
                                type: "post",
                                success: function (getLatLong) {
                                    console.log(getLatLong);
                                },
                            });
                        },
                    });
                }
            },
            complete: function () {
                $("#loadingAlert").fadeOut();
            },
        });
        // }
    }
});

$("#update").click(function (e) {
    e.preventDefault();
    $.ajax({
        data: {
            action: "update",
        },
        url: "/crawl-space/update_map.php",
        type: "post",
        success: function (result) {
            if (result !== "none") {
                alert("All space update success!!");
            } else {
                alert("All space update success!!");
            }
        },
    });
});

$(document).on("click", "#updateExist", function (e) {
    e.preventDefault();
    $.ajax({
        data: {
            action: "update_exist",
        },
        url: "/crawl-space/update_map.php",
        type: "post",
        success: function (result) {
            console.log(result);
            if (result !== "none") {
                alert("All space update success!!");
            } else {
                alert("All space update success!!");
            }
        },
    });
});
