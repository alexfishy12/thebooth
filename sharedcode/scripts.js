// Make an AJAX server request
// method: GET or POST
// url: URL to send request to
// data: Data to send to server (if any), can be left blank
function server_request(url, method, data = null) {
    return new Promise(function(resolve) {
        $.ajax({
            type: method,
            url: url,
            data: data,
            processData: false, // Prevent serialization of the FormData object
            contentType: false, // Let the browser set the correct content type for FormData
            success: function (response, status) {
                // AJAX Success, server responded
                try {
                    // Try to parse response as JSON
                    response = JSON.parse(response);
                } catch (e) {
                    // Response is not JSON, Error in PHP was not caught
                    response = {
                        status: 'error', 
                        data: "Error parsing response from server.\n\nJS ERROR\n" + e + "\n\nPHP ERROR\n" + response
                    };
                    resolve(response);
                }
                // Response successfully parsed as JSON, PHP compiled and caught all errors
                resolve(response);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // AJAX Error, server did not respond
                response = {
                    status: 'error', 
                    data: "AJAX ERROR (" + textStatus + ")\n" + errorThrown
                };
                resolve(response);
            }
        })
    });
}

function roundToTenth(num) {
    return Math.round(num * 10) / 10;
}

function getCookie(name) {
    var cookieArr = document.cookie.split(';');
    for (var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split('=');
        if (name === cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return null;
}

function get_placeholder_img(product) {
    switch(product.category) {
        case "shirt":
            return "../_assets/tshirt_placeholder.png";
        case "jacket":
            return "../_assets/jacket_placeholder.png";
        case "pants":
            return "../_assets/pants_placeholder.png";
        case "dress":
            return "../_assets/dress_placeholder.png";
        default:
            return "../_assets/tshirt_placeholder.png";
    }
}