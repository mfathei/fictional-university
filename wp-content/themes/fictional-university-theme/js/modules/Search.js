import $ from 'jquery';

class Search {
    constructor() {
        this.addSearchHTML();// this should be the very top one as others depend on it
        // =======================
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
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
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                this.resultsDiv.html('');
                this.isSpinnerVisible = false;
            }

        }

        this.previousSearch = this.searchField.val();
    }

    getResults() {
        // Asynchronous
        $.when(
            $.getJSON(universityData.root_url+'/wp-json/wp/v2/posts?search=' + this.searchField.val()),
            $.getJSON(universityData.root_url+'/wp-json/wp/v2/pages?search=' + this.searchField.val())
        ).then((posts, pages) => {
            var combinedResults = posts[0].concat(pages[0]);
            this.resultsDiv.html(`
                <h2 class="search-overlay__section-title">General Information</h2>
                ${combinedResults.length ? `<ul class="link-list min-list">`: `<div>No general information matches your search.</div>`}
                ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
                ${combinedResults.length ? `</ul>`: ''}
            `);

            this.isSpinnerVisible = false;
        }, () => {
            this.resultsDiv.html(`<div>Unexpected error; please try again.</div>`);
        });
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
        $("body").addClass("body-no-scroll");
        this.searchField.val('');
        setTimeout(() => this.searchField.focus(), 301);
        this.overlayIsOpen = true;
    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass("body-no-scroll");
        this.overlayIsOpen = false;
    }

    addSearchHTML(){
        $("body").append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input type="text" class="search-term" id="search-term" placeholder="What are you looking for?">
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
        
                <div class="container">
                    <div id="search-overlay__results"></div>
                </div>
    
            </div>
        `);
    }

}

export default Search;