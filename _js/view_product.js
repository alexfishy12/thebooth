$(document).ready(function() {
    get_reviews();
    // check if form is submitted
    $("form#add_to_cart").submit(function(e) {
        e.preventDefault();

        if (add_to_cart_form_is_valid()) {
            var product = {
                id: $("input#product_id").val(),
                quantity: $("input#product_quantity").val(),
                size: $("select#product_size").val(),
                color: $("select#product_color").val(),
                price: $("input#product_price").val()
            };

            //call add_to_cart from cart.js
            add_to_cart(product);
            $("in_cart_message").html("Item added to cart.")
        }
    });

    $("form#leave_review").submit(function(e) {
        e.preventDefault();

        if (leave_review_form_is_valid()) {
            var formData = new FormData(this);

            //add review to DB
            server_request("../_php/r_add_review.php", "POST", formData).then(function(response) {
                // handle response from server
                switch (response.status) {
                    case "success":
                        $("#review_success_message").html(response.data);
                        setTimeout(function() {
                            window.location.replace("view_product.php?product_id=" + $("input#review_product_id").val());
                        }, 2000);
                        break;
                    case "failure":
                        $("#review_error_message").html(response.data);
                        break;
                    case "error":
                        console.error(response.data);
                        break;
                    default:
                        console.log(response)
                        break;
                }
            });
        }
    
    });


    // check if user clicks on a review star rating
    $("span[id^='star_rating_']").click(function() {
        update_product_rating(this);
    });
});

function add_to_cart_form_is_valid() {
    if ($("select#product_color").val() == "" || $("select#product_color").val() == null) {
        $("#error_message").html("Please select a color.");
        return false;
    }
    if ($("select#product_size").val() == "" || $("select#product_size").val() == null) {
        $("#error_message").html("Please select a size.");
        return false;
    }
    if ($("input#product_quantity").val() == "" || $("input#product_quantity").val() == 0 || $("input#product_quantity").val() < 0 || $("input#product_quantity").val() == null) {
        $("#error_message").html("Please enter a valid quantity.");
        return false;
    }
    // check if customer is logged in
    if (!document.cookie.includes('customer_account_info=')) {
        $("#error_message").html("Please log in to add items to your cart.");
        return false;
    }
    $("#error_message").html("");
    return true;
}

function leave_review_form_is_valid() {
    if ($("textarea#review_text").val() == "" || $("textarea#review_text").val() == null) {
        $("#review_error_message").html("Please enter a description for your review.");
        return false;
    }
    if ($("input#review_rating").val() == "" || $("input#review_rating").val() == null || $("input#review_rating").val() == 0) {
        $("#review_error_message").html("Please select a rating for your review.");
        return false;
    }
    $("#review_error_message").html("");
    return true;
}

function update_product_rating(clicked_star) {
    var star_rating = clicked_star.id.split("_")[2];
    console.log("star_rating: " + star_rating);
    $("input#review_rating").val(star_rating);
    update_stars(star_rating);
}

function update_stars(rating) {
    for (var i = 1; i <= 5; i++) {
        if (i <= rating) {
            $("#star_rating_" + i).attr("class", "bi-star-fill clickable text-warning");
        } else {
            $("#star_rating_" + i).attr("class", "bi-star clickable");
        }
    }
}