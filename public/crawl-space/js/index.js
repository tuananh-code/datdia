$("#page").on("input", function (event) {
    var input = $(this);
    var sanitizedValue = input.val().replace(/\D/g, "");
    input.val(sanitizedValue);
});

$("#select").change(function () {
    var selectedOptions = $(this).find(".getWeb:selected");
    var selectedValues = [];
    var cityValues = [];
    selectedOptions.each(function () {
        var value = $(this).val();
        var cities = $(this).attr("data-web");
        selectedValues.push(value);
        cityValues.push(cities);
    });

    $(this).attr("data-web", selectedValues.join("|"));
    $(this).attr("data-city", cityValues.join("|"));
});

// Example usage:
$(document).on("click", "#getValue", function (e) {
    e.preventDefault();
    var data = $("#formValue").serializeArray();
    // $("#getValue").prop("disabled", true);
    $("#update").prop("disabled", true);
    $("#updateExist").prop("disabled", true);
    //TODO: uncomment for muaban
    // var url = $("#select").attr("data-web").split("|");  // console.log(cities);
    // var cities = $("#select").attr("data-city").split("|");
    var url = $("#url").val();
    var page = parseFloat($("#page").val());
    var xPath = $("#xPath").val();
    var pageOption = $("#pageOption").val();
    var moreInfo = $("#moreInfo").val();
    var price = $("#price").val();
    var location = $("#location").val();
    var detail = $("#detail").val();
    // var url = $("#select").attr("data-url").split("|");
    var i = page;
    function processNext() {
        if (i >= 1) {
            var getUrl = url + pageOption + i;
            // var getUrl = link + pageOption + i + option;
            console.log(getUrl);
            $.ajax({
                data: {
                    url: getUrl,
                    // city: getCity,
                    // url: link,
                    xPath: xPath,
                },
                url: "poe.php",
                type: "post",
                beforeSend: function (xhr) {
                    $("#loadingAlert").fadeIn();
                },
                success: function (response) {
                    var result = response[0] || response;
                    console.log(result); 
                    // return
                    var href = result["href"];
                    if (href[0].includes("muaban")) {
                        var infoName = result["name"];
                        var infoAddress = result["price"];
                        var infoBed = result["location"];
                        var infoBath = result["info"];
                        var infoSquare = null;
                        var infoPrice = null;
                    } else if (href[0].includes("century21")) {
                        var infoName = result["name"];
                        var infoAddress = result["address"];
                        var infoBed = result["bed"];
                        var infoBath = result["bath"];
                        var infoSquare = null;
                        var infoPrice = null;
                    } else if (href[0].includes("estately")) {
                        var infoName = result["name"];
                        var infoAddress = result["address"];
                        var infoBed = result["bed"];
                        var infoBath = result["bath"];
                        var infoSquare = result["sqm"];
                        var infoPrice = result["price"];
                    } else if (href[0].includes("edgeprop")) {
                        var infoName = result["name"];
                        // var infoAddress = result["address"];
                        var infoBed = result["bed"];
                        var infoBath = result["bath"];
                        var infoSquare = result["sqm"];
                        var infoPrice = result["price"];
                        var infoContact = result["contact"];
                    }else{
                        var title = result['title'];
                        var href = result['href'];
                        var img = result['img'];
                    }
                    for (var j = 0; j < href.length; j++) {
                        // Using a closure to preserve the value of j
                        $.ajax({
                            data: {
                                // getCity: getCity,
                                // infoName: infoName[j],
                                // infoAddress: infoAddress[j],
                                // infoPrice: infoPrice[j],
                                // infoBed: infoBed[j],
                                // infoBath: infoBath[j],
                                // infoSquare: infoSquare[j],
                                // infoContact: infoContact[j],
                                title: title[j],
                                img: img[j],
                                href: href[j],
                            },
                            url: "getInfo.php",
                            type: "post",
                            success: function (getInfo) {
                                console.log(getInfo);
                                // if (getInfo) {
                                //     var geoLocation = getInfo["location"];
                                //     $.ajax({
                                //         data: {
                                //             location: geoLocation,
                                //         },
                                //         url: "lat_long_convert.php",
                                //         type: "post",
                                //         success: function (getLatLong) {
                                //             console.log(getLatLong);
                                //         },
                                //     });
                                // }
                            },
                        });
                    }
                },
                complete: function () {
                    $("#loadingAlert").fadeOut();
                    // setTimeout(processNext, 1000); // Set timeout for 30 seconds after completing the AJAX request
                    setTimeout(processNext, 10000); // Set timeout for 30 seconds after completing the AJAX request
                    i--;
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
        url: "update_map.php",
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
        url: "update_map.php",
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
