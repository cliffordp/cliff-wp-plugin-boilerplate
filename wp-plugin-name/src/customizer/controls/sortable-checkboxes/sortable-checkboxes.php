<?php

namespace WP_Plugin_Name\Customizer;

use WP_Customize_Control;
use WP_Plugin_Name\Common\Common as Common;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Sortable_Checkboxes_Control' ) ) {
	/**
	 * Sortable checkboxes control class.
	 *
	 * @link    http://scottfennell.org/2015/05/28/adding-a-draggablesortable-multi-checkbox-control-to-the-wordpress-customizer/ Adapted from this.
	 *          Confirmed okay to license as GPLv3+ via Twitter: https://twitter.com/TourKick/status/1089524933133303808
	 */
	class Sortable_Checkboxes_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 */
		public $type = 'sortable_checkboxes';

		/**
		 * Get the Common instance.
		 *
		 * @var Common
		 */
		private $common;

		/**
		 * Constructor
		 */
		public function __construct( $manager, $id, $args = [], $options = [] ) {
			$this->common = Common::get_instance();
			parent::__construct( $manager, $id, $args );
		}

		/**
		 * Enqueue our scripts and styles
		 */
		public function enqueue() {
			wp_enqueue_script( $this->handle( 'js' ), plugin_dir_url( __FILE__ ) . 'js/script.js', [ 'jquery', 'jquery-ui-sortable' ], $this->common->version, true );
			wp_enqueue_style( $this->handle(), plugin_dir_url( __FILE__ ) . 'css/style.css', [], $this->common->version, 'all' );
		}

		/**
		 * Get the script or style handle, like `cp-toptal-social-sortable_checkboxes-js`.
		 *
		 * @param string $suffix
		 *
		 * @return string
		 */
		private function handle( $suffix = '' ) {
			$result = $this->common->plugin_text_domain . '-' . $this->type;
			if ( ! empty( $suffix ) ) {
				$result .= '-' . $suffix;
			}

			return $result;
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			$out = '';

			$class = $this->type . '-checkbox_group-hidden';
			$class = " class='$class' ";
			$id    = esc_attr( $this->id );
			$name  = " name='$id' ";

			// WP uses this to create the value of the input.
			$link = $this->get_link();

			/**
			 * Store the choices for this setting in a data attr. In our JS,
			 * we'll use these to make the checkboxes.
			 */
			$choices      = $this->choices;
			$choices_data = esc_attr( json_encode( $choices ) );
			$choices_data = " data-choices='$choices_data' ";

			// TODO: The type of input. 'text' for debugging purposes, 'hidden' for production.
			$type = " type='hidden' ";

			if ( $this->label ) {
				$out .= "<span class='customize-control-title'>" . esc_html( $this->label ) . "</span>";
			}

			if ( $this->description ) {
				$out .= "<span class='description customize-control-description'>" . esc_html( $this->description ) . "</span>";
			}

			$out .= "<input $choices_data $link $type $name $class";
			echo $out;

			/**
			 * Example how to disable sorting when adding a control:
			 * 'input_attrs' => [
			 * 'data-disable_sortable' => 'true',
			 * ],
			 */
			$this->input_attrs();

			// Close the <input>
			echo '>';
		}
	}
}