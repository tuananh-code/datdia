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
    //TODO: uncomment for muaban
    // var url = $("#select").attr("data-web").split("|");
    var url = $("#url").val();
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
    //     for (var l = 0; l < 1; l++) {
    console.log("ok");
    //TODO: for century21
    var link = "https://www.century21.com.au/properties-for-sale";
    var option = "&searchtype=sale";
    // FIXME: Using recursive remove function to get original
    var i = 1;
    function processNext() {
        if (i <= 24) {
            var getUrl = link + pageOption + i + option;
            $.ajax({
                data: {
                    url: getUrl,
                    xPath: xPath,
                },
                url: "/crawl-space/poe.php",
                type: "post",
                beforeSend: function (xhr) {
                    $("#loadingAlert").fadeIn();
                },
                success: function (response) {
                    var result = response[0] || response;
                    console.log(result);
                    var href = result["href"];
                    if (href[0].includes("muaban")) {
                        var infoName = result["name"];
                        var infoAddress = result["price"];
                        var infoBed = result["location"];
                        var infoBath = result["info"];
                    } else if (href[0].includes("century21")) {
                        var infoName = result["name"];
                        var infoAddress = result["address"];
                        var infoBed = result["bed"];
                        var infoBath = result["bath"];
                    }

                    for (var j = 0; j < href.length; j++) {
                        (function (j) {
                            // Using a closure to preserve the value of j
                            $.ajax({
                                data: {
                                    infoName: infoName[j],
                                    infoAddress: infoAddress[j],
                                    infoBed: infoBed[j],
                                    infoBath: infoBath[j],
                                    href: href[j],
                                },
                                url: "/crawl-space/getInfo.php",
                                type: "post",
                                success: function (getInfo) {
                                    console.log(getInfo);
                                    if (getInfo) {
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
                                    }
                                },
                            });
                        })(j);
                    }
                },
                complete: function () {
                    $("#loadingAlert").fadeOut();
                    setTimeout(processNext, 30000); // Set timeout for 30 seconds after completing the AJAX request
                    i++;
                },
            });
        }
    }
    processNext();
    // }
    // }
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
