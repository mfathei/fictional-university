import $ from 'jquery';

class Search {
    constructor() {
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.body = $("body");
        this.searchField = $("#search-term");
        this.overlayIsOpen = false;
        this.typingTimeout = null;
        this.events();
    }

    events() {
        this.openButton.on("click", this.openOverlay.bind(this)); // .bind(this) will send 'this' object to the function
        this.closeButton.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        this.searchField.on("keydown", this.typingLogic.bind(this));
    }

    typingLogic(e) {
        clearTimeout(this.typingTimeout);
        this.typingTimeout = setTimeout(function () {
            console.log("Hello from search field.");
        }, 2000);
    }

    keyPressDispatcher(e) {

        if (e.keyCode == 83 && !this.overlayIsOpen) {
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