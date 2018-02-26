////////////////////////////////////////////////////////////
//~ DEFINE jQuery and  JAVASCRIPT CRUD CLASS
//////////////////////////////////////////////////////////
 
var $ = jQuery;
var crudApiPluginData = window.crudApiPluginData;
//~CREATE JAVASCRIPT CRUD CLASS with EVENT LISTENERS and CRUD methods
 
class MyNotes {
  constructor() {
    this.events();
    this.newFormIsVisible = false;
    this.newForm = $('#newForm');
    console.log('MyNotes CLASS was constructed');
  }

  events() {
    $("#toggleNewForm").on("click",    this.toggleNew.bind(this) );

 
    $("#my-notes").on("click", ".delete-note", this.deleteNote);
    $("#my-notes").on("click", ".edit-note", this.editNote.bind(this));
    $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
    $(".submit-note").on("click", this.createNote.bind(this));
    //~ TO BIND OR NOT TO BIND?
     $("#my-notes-page").on("click", ".import-notes", this.importNotes.bind(this));
     // $("#my-notes-page").on("click", ".import-notes", this.importNotes);//binds to the UI button
  }

  // Methods will go here
  editNote(e) {
    var thisNote = $(e.target).parents("li");
    if (thisNote.data("state") == "editable") {
      this.makeNoteReadOnly(thisNote);
    } else {
      this.makeNoteEditable(thisNote);
    }
  }

  makeNoteEditable(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
    thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
    thisNote.find(".update-note").addClass("update-note--visible");
    thisNote.data("state", "editable");
  }

  makeNoteReadOnly(thisNote) {
    thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
    thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
    thisNote.find(".update-note").removeClass("update-note--visible");
    thisNote.data("state", "cancel");
  }

 // method to toggle hide/show the NEW NOTE FORM
  toggleNew(e) {
    console.log('Two Properties in the constructor, one bound event , one method, and a UI button.');
    var thisNewNote = $(e.target);
     if(this.newFormIsVisible){
       //$(this.newForm).addClass('hide');
       $(this.newForm).slideUp();

     }else{
       //$(this.newForm).removeClass('hide');
       $(this.newForm).slideDown();
    }
    this.newFormIsVisible =! this.newFormIsVisible;
  }

 
//~ CRUD Methods----------------------------------------------------


  deleteNote(e) {
    var thisNote = $(e.target).parents("li");

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', crudApiPluginData.nonce);
      },
      url: crudApiPluginData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
      type: 'DELETE',
      success: (response) => {
        thisNote.slideUp();
        console.log("Congrats");
        console.log(response);
        if (response.userNoteCount < 10) {
          $(".note-limit-message").removeClass("active");
        }
      },
      error: (response) => {
        console.log("Sorry 1");
        console.log(response);
      }
    });
  }

  updateNote(e) {
    var thisNote = $(e.target).parents("li");

    var ourUpdatedPost = {
      'title': thisNote.find(".note-title-field").val(),
      'content': thisNote.find(".note-body-field").val()
    }
    
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', crudApiPluginData.nonce);
      },
      url: crudApiPluginData.root_url + '/wp-json/wp/v2/note/' + thisNote.data('id'),
      type: 'POST',
      data: ourUpdatedPost,
      success: (response) => {
        this.makeNoteReadOnly(thisNote);
        console.log("Congrats");
        console.log(response);
      },
      error: (response) => {
        console.log("Sorry 2");
        console.log(response);
      }
    });
  }

  createNote(e) {
      console.log('createNote function is called....');
    // console.log(window.mudcake);
      console.log(window.crudApiPluginData);
      
    var ourNewPost = {
      'title': $(".new-note-title").val(),
      'content': $(".new-note-body").val(),
      'status': 'publish'
    }
    
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', crudApiPluginData.nonce);
      },
      url: crudApiPluginData.root_url + '/wp-json/wp/v2/note/',
      type: 'POST',
      data: ourNewPost,
      success: (response) => {
        $(".new-note-title, .new-note-body").val('');
        $(`
          <li data-id="${response.id}">
            <input readonly class="note-title-field" value="${response.title.raw}">
            <button class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
             <button class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</button>
            <button class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
            <textarea readonly class="note-body-field">${response.content.raw}</textarea>
           
          </li>
          `).prependTo("#my-notes").hide().slideDown();

        console.log("Congrats");
        console.log(response);
      },
      error: (response) => {
        console.log('The response of createing a note and reaching the limit...'+response);
        if(response.responseText == "You have reached your note limit.") {
          $(".note-limit-message").addClass("active");
        }
        console.log("Sorry 3");
        console.log(response);
      }
    });
  }
  //////////////////////////////////////
  test(){
      console.log('test class method call from ajax success result.');
  }
   importNotes() {
      console.log('importNotes function is called....');
      var parent = this;
      //get data via API
      	$.ajax({
            		url: "https://www.sprite-pilot.com/api/API_TEST/api.php?dataId=finaltest",
            		success: function( data ) {
            		    console.log('data from js class:');
            			console.log(data[0]);
            			//jQuery('#result').html('<p>ajax:'+data[0].name+'</p>');
            			 console.log('this:'+this);
            			 console.log(this);
            			 console.log('parent:'+parent);
            			 console.log(parent);
            			 parent.importNotes_callback(data[0]);
            		}
            	});
      
      //callback function on success
      
      
   
  }
  ///
  importNotes_callback(data){
      
       var ourNewPost = {
      'title':  data.name,
      'content':  data.type,
      'status': 'publish'
    }
    
    
    //~ INSERT NEW DATA FROM API (instead of from UI.)
    //~ Upon success, display in UI.
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', crudApiPluginData.nonce);
      },
      url: crudApiPluginData.root_url + '/wp-json/wp/v2/note/',
      type: 'POST',
      data: ourNewPost,
      success: (response) => {
        $(".new-note-title, .new-note-body").val('');
        $(`
          <li data-id="${response.id}">
            <input readonly class="note-title-field" value="${response.title.raw}">
            <button class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button>
            <button class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
            <textarea readonly class="note-body-field">${response.content.raw}</textarea>
            <button class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</button>
          </li>
          `).prependTo("#my-notes").hide().slideDown();

        //console.log("Congrats");
        console.log(response);
      },
      error: (response) => {
        console.log('The response of creating a note and reaching the limit...'+response);
        if(response.responseText == "You have reached your note limit.") {
          $(".note-limit-message").addClass("active");
        }
        console.log("Sorry 3");
        console.log(response);
      }
    });
  }
  //////////////////////////////////////////////////
}

var mynotes = new MyNotes();


