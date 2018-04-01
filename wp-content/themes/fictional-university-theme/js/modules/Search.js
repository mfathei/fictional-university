import $ from 'jquery';

class Search {
    constructor() {
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.body = $("body");
        this.searchField = $("#search-term");
        this.resultsDiv = $("#search-overlay__results");
        this.overlayIsOpen = false;
        this.typingTimer = null;
        this.isSpinnerVisible = false;
        this.previousSearch = '';
        this.events();
    }

    events() {
        this.openButton.on("click", this.openOverlay.bind(this)); // .bind(this) will send 'this' object to the function
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    typingLogic(e) {

        if (this.searchField.val() != this.previousSearch) {
            clearTimeout(this.typingTimer);
            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }

        }

        this.previousSearch = this.searchField.val();
    }

    getResults() {
        this.resultsDiv.html('<div>Imagine search results here</div>');
        this.isSpinnerVisible = false;
    }

    keyPressDispatcher(e) {

        if (e.keyCode == 83 && !this.overlayIsOpen && !$("input, textarea").is(":focus")) {
            this.openOverlay();
        }

        if (e.keyCode == 27 && this.overlayIsOpen) {
            this.closeOverlay();
        }

    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        this.body.addClass("body-no-scroll");
        this.overlayIsOpen = true;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        this.body.removeClass("body-no-scroll");
        this.overlayIsOpen = false;
    }

}

export default Search;