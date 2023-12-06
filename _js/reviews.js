function get_reviews() {
    var formData = new FormData();
    formData.append("product_id", $("input#product_id_reference").val());
    server_request("../_php/r_get_product_reviews.php", "POST", formData).then(function(response){ 
        // handle response from server
        switch (response.status) {
            case "success":
                console.log(response.data);
                parse_review_data(response.data)
                break;
            case "failure":
                console.log(response.data);
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

function parse_review_data(review_data) {
    review_data = JSON.parse(review_data);
    // Print out in product info section
    $("#product_rating").html(generate_average_rating(roundToTenth(review_data.average_rating)));
    $("#product_review_count").html(generate_total_reviews(review_data.total_reviews));

    // Print out in review section
    generate_average_rating(roundToTenth(review_data.average_rating));
    

    var review_cards = "";
    for (var i = 0; i < review_data.reviews.length; i++) {
        review_cards += generate_review_card(review_data.reviews[i]);
    }
    $("#review_feed").html(review_cards);
}

function generate_average_rating(rating) {
    var stars = generate_stars(rating);

    // print out in product info section
    $("#product_average_stars").html(stars);
    $("#product_average_rating").html(rating + " out of 5");

    // print out in review section
    $("#reviews_average_stars").html(stars);
    $("#reviews_average_rating").html(rating + " out of 5");
}

function generate_total_reviews(total_reviews) {

    // print out in product info section
    $("#product_review_count").html(total_reviews + " total reviews");

    // print out in review section
    $("#reviews_total_count").html(total_reviews + " total reviews");
}

function generate_review_card(review) {
    var stars = generate_stars(review.rating)

    if (review.profile_picture == null) {
        review.profile_picture = "../_assets/placeholder_profile_picture.jpg";
    }

    let date = review.date.split(/[- :]/);
    date[1]--;
    let formatted_date = new Date(...date).toLocaleDateString('en-us', { weekday:"short", year:"numeric", month:"short", day:"numeric"}) ;

    return `
    <div class="mb-4">
        <div class="card h-100">
            <div class="card-header">
                <div class="align-items-center mb-2">
                    <img src="${review.profile_picture}" alt="Profile Picture" class="profile-picture-sm me-2">
                    <small class="text-muted">${review.first_name} ${review.last_name}</small>
                </div>
                <div class="row">
                    <div class="col rating text-start">
                        ${stars}
                    </div>
                    <div class="col rating text-end note">
                        Reviewed on ${formatted_date}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text">${review.review}</p>
            </div>
        </div>
    </div>
    `
}

function generate_stars(rating) {
    var stars = "";
    var maxRating = 5; // Set the maximum rating
    var fullStars = Math.floor(rating);
    var hasHalfStar = rating % 1 >= 0.5;
    var emptyStars = maxRating - fullStars - (hasHalfStar ? 1 : 0);

    // Add full stars
    for (var i = 0; i < fullStars; i++) {
        stars += "<span class='bi-star-fill text-warning'></span>";
    }

    // Add half star if needed
    if (hasHalfStar) {
        stars += "<span class='bi-star-half text-warning'></span>";
    }

    // Add empty stars
    for (var i = 0; i < emptyStars; i++) {
        stars += "<span class='bi-star text-warning'></span>";
    }

    return stars;
}
