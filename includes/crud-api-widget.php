<?php
/**
 * Adds CRUD_API_WIDGET widget.
 */
class CRUD_API_WIDGET extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'CRUD_API_WIDGET', // Base ID
			esc_html__( 'CRUD_API Widget', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'A CRUD_API Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		 
		echo('<div class="crud-api-widget-container">');
		 if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		//echo esc_html__( $instance['description'], 'text_domain' );
		echo ( $instance['description']  );
		// class item end
		include(plugin_dir_path( __FILE__ ).'crud-api-plugin-page.php');
		echo("</div>");

		echo $args['after_widget'];


	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		$description = ! empty( $instance['description'] ) ? $instance['description'] : esc_html__( 'New description', 'text_domain' );

		?>
		<!-- Form Item -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'text_domain' ); ?>
			</label> 
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
				class="widefat" 
				type="text" 
				value="<?php echo esc_attr( $title ); ?>"
			>
		</p>
		<!-- Form Item -->
	</br>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Description:', 'text_domain' ); ?>
			</label> 
			<input 
				id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" 
				class="widefat" 
				type="text" 
				value="<?php echo esc_attr( $description ); ?>"
			>
		</p>
		

		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';

		return $instance;
	}
	///////////////////////////////////////////////////////////////////////
	//add in functions here.

	 
	///////////////////////////////////////////////////////////////////////

} // class CRUD_API_WIDGET