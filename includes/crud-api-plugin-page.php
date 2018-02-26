<div id="my-notes-page" class="container container--narrow page-section">
      
      <span" ><button  id="toggleNewForm" class="btn btn--green" >New Note(child)</button></span>
      
      <button   class="import-notes btn btn--green" >Import Notes from API</button>
      
       
      
      <div id="newForm" class="create-note hide">
        <h2 class="headline headline--medium">Create New Note</h2>
        <input class="new-note-title" placeholder="Title">
        <textarea class="new-note-body" placeholder="Your note here..."></textarea>
        <button class="submit-note">Create Note</button>
        <span class="note-limit-message"> </span>
      </div>
       <ul class="min-list link-list" id="my-notes">
        <?php 
          $userNotes = new WP_Query(array(
            'post_type' => 'note',
            'posts_per_page' => -1,
            'author' => get_current_user_id()
          ));

          while($userNotes->have_posts()) {
            $userNotes->the_post(); ?>

            <li data-id="<?php the_ID(); ?>">

              <input 
              readonly 
              class="note-title-field" 
              value="<?php echo str_replace('Private: ', '', esc_attr(get_the_title())); ?>"
              >

              <button class="edit-note">
                  <i class="fa fa-pencil" aria-hidden="true"></i> 
                  Edit
              </button>
             
              <button class="delete-note">
                  <i class="fa fa-trash-o" aria-hidden="true"></i> 
                  Delete
              </button>

              <textarea readonly class="note-body-field">
                  <?php echo esc_textarea(get_the_content()); ?>
              </textarea>

              <button class="update-note btn btn--blue btn--small">
                  <i class="fa fa-arrow-right" aria-hidden="true"></i> 
                  Save
              </button>

            </li>

          <?php    }  ?>
      </ul>
        
    </div>
    