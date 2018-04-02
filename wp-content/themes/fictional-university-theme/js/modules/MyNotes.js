import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();
    }

    events() {
        $(".delete-note").on("click", this.deleteNote);
    }

    // Methods
    deleteNote(){
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url+'/wp-json/wp/v2/note/85',
            type: 'DELETE',
            success: (response) => {
                console.log('Congrats');
                console.log(response);
            },
            error: (error) => {
                console.log('Error deleting note');
                console.log(error);
            }
        });
    }
}

export default MyNotes;