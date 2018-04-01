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
        
        $.getJSON(universityData.root_url+'/wp-json/university/v1/search?term='+this.searchField.val(), (results) => {
            this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${results.generalInfo.length ? `<ul class="link-list min-list">`: `<div>No general information matches your search.</div>`}
                        ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post'? `by ${item.authorName}`: ''}</li>`).join('')}
                        ${results.generalInfo.length ? `</ul>`: ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.programs.length ? `<ul class="link-list min-list">`: `<div>No programs match your search. <a href="${universityData.root_url+'/programs'}">View all programs</a></div>`}
                        ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                        ${results.programs.length ? `</ul>`: ''}
                        <h2 class="search-overlay__section-title">Professors</h2>
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${results.campuses.length ? `<ul class="link-list min-list">`: `<div>No campuses match your search. <a href="${universityData.root_url+'/campuses'}">View all campuses</a></div>`}
                        ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                        ${results.campuses.length ? `</ul>`: ''}
                        <h2 class="search-overlay__section-title">Events</h2>
                    </div>
                </div>
            `);

            this.isSpinnerVisible = false;
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