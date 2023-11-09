$(document).ready(function() {

    // populate carousel with images upon file selection

    $('#file_upload_div').on('change', 'input[type="file"]', function(event) {
        console.log('File input changed!');
        update_carousel();
    });   

    // add file input for color when color checkbox is checked
    $("input[name='colors[]']").change(function(checkbox) {
     
        var color_id = this.value;
        //split checkbox ID to get color name
        var color_name = this.id.split("_")[1];

        var fileInputId = 'file_' + color_id;
        var fileInputDiv = document.getElementById(fileInputId);


        if (this.checked) {
            if (!fileInputDiv) {
                var newFileInput = `
                <div class="row mb-2" id="${fileInputId}">
                    <div class="input-group">
                        <label class="input-group-text" for="image_input_${color_id}">${color_name}</label>
                        <input type="file" class="form-control" id="image_input_${color_id}" name="images[${color_id}]" accept="image/*" form="manager_add_new_product" multiple>
                    </div>
                </div>`;
                document.getElementById('file_upload_div').insertAdjacentHTML('beforeend', newFileInput);
            }
        } else {
            // delete file input for color if color checkbox is unchecked
            if (fileInputDiv) {
                fileInputDiv.remove();
                update_carousel();
            }
        }
     
    });

    // add product to database
    $("form#manager_add_new_product").submit(function(e) {
        e.preventDefault();

        let fileInputs = document.querySelectorAll("input[type='file']");
        let files = [];

        fileInputs.forEach(function(input) {
            if (input.files.length > 0) {
                for (let i = 0; i < input.files.length; i++) {
                    files.push(input.files[i]);
                }
            }
        });
        if (files.length === 0) {
            $("#error_message").html("No images uploaded. (At least 1 is required)");
            return; // No file selected
        }

        var formData = new FormData(this);

        server_request("../_php/r_manager_add_product.php", "POST", formData).then(function(response) {
            // handle response from server
            switch (response.status) {
                case "success":
                    $("#manager_add_new_product_form").hide();
                    $("#success_message").html(response.data);
                    setTimeout(function() {
                        window.location.replace("main_page.php");
                    }, 2000);
                    break;
                case "failure":
                    $("#error_message").html(response.data);
                    break;
                case "error":
                    console.error(response.data);
                    break;
                default:
                    console.log(response)
                    break;
            }
        });
    })
    
});

function update_carousel() {
    let fileInputs = document.querySelectorAll("input[type='file']");
    let files = [];

    fileInputs.forEach(function(input) {
        if (input.files.length > 0) {
            for (let i = 0; i < input.files.length; i++) {
                files.push(input.files[i]);
            }
        }
    });
    if (files.length === 0) {
        //$("#uploaded_images_card").hide();
        return; // No file selected
    }
    //$("#uploaded_images_card").show();


    console.log(files);
    let carouselInner = document.getElementById('carousel-inner');
    carouselInner.innerHTML = ''; // Clear the carousel

    for (let i = 0; i < files.length; i++) {
        let reader = new FileReader();
        reader.onload = function(e) {
            let carouselItem = document.createElement('div');
            carouselItem.className = 'carousel-item' + (i === 0 ? ' active' : '');
            let img = document.createElement('img');
            img.className = 'd-block w-100'; // For Bootstrap 5
            img.src = e.target.result;
            carouselItem.appendChild(img);
            carouselInner.appendChild(carouselItem);
        }
        reader.readAsDataURL(files[i]);
    }
    
    // Once all files are read and added to the carousel, reinitialize it.
    var carouselElement = document.getElementById('image-carousel');
    var carouselInstance = new bootstrap.Carousel(carouselElement, {
        interval: false, // Optional: specify the interval in milliseconds
        wrap: true, // Optional: specify if the carousel should cycle continuously or have hard stops
        touch: true // Optional: specify if carousel should respond to touch events
    });
}