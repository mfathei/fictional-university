import $ from 'jquery';

class Like {
    constructor() {
        this.events();
    }

    events() {
        $(".like-box").on("click", this.clickLikeDispatcher.bind(this));
    }

    // methods
    clickLikeDispatcher(e) {
        var currentLikeBox = $(e.target).closest(".like-box");

        if (currentLikeBox.attr("data-exists") == "yes") {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox) {

        var data = {
            'professorId': currentLikeBox.attr("data-professor")
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'POST',
            data: data,
            success: (response) => {
                currentLikeBox.attr("data-exists", "yes");
                var likesCount = parseInt(currentLikeBox.find(".like-count").html());
                likesCount++;
                currentLikeBox.find(".like-count").html(likesCount);
                currentLikeBox.attr("data-like", response);
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }

    deleteLike(currentLikeBox) {
        var data = {
            'like': currentLikeBox.attr("data-like")
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url + '/wp-json/university/v1/manageLike',
            type: 'DELETE',
            data: data,
            success: (response) => {
                currentLikeBox.attr("data-exists", "no");
                var likesCount = parseInt(currentLikeBox.find(".like-count").html());
                likesCount--;
                currentLikeBox.find(".like-count").html(likesCount);
                currentLikeBox.attr("data-like", '');
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }

}

export default Like;