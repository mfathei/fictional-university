import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();
    }

    events() {
        $(".delete-note").on("click", this.deleteNote.bind(this));
        $(".edit-note").on("click", this.editNote.bind(this));
        $(".update-note").on("click", this.updateNote.bind(this));
    }

    // Methods
    deleteNote(e){
        var thisNote = $(e.target).parents("li");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url+'/wp-json/wp/v2/note/'+ thisNote.data("id"),
            type: 'DELETE',
            success: (response) => {
                thisNote.slideUp();
                console.log('Congrats');
                console.log(response);
            },
            error: (error) => {
                console.log('Error deleting note');
                console.log(error);
            }
        });
    }

    updateNote(e){
        var thisNote = $(e.target).parents("li");

        var ourUpdatedPost = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find(".note-body-field").val()
        };

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
            },
            url: universityData.root_url+'/wp-json/wp/v2/note/'+ thisNote.data("id"),
            type: 'POST',
            data: ourUpdatedPost,
            success: (response) => {
                this.makeNoteReadonly(thisNote);
                console.log('Congrats');
                console.log(response);
            },
            error: (error) => {
                console.log('Error deleting note');
                console.log(error);
            }
        });
    }

    editNote(e){
        var thisNote = $(e.target).parents("li");
        if(thisNote.data("state") == "editable"){
            this.makeNoteReadonly(thisNote);
        } else {
            this.makeNoteEditable(thisNote);
        }
    }

    makeNoteEditable(thisNote){
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly", "readonly").addClass("note-active-field");
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");
    }

    makeNoteReadonly(thisNote){
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i>Edit');
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.data("state", "readonly");
    }
}

export default MyNotes;