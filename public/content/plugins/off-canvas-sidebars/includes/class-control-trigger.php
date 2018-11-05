<?php
/**
 * Off-Canvas Sidebars - Class Control Trigger
 *
 * @author  Jory Hogeveen <info@keraweb.nl>
 * @package Off_Canvas_Sidebars
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Off-Canvas Sidebars plugin control trigger API.
 *
 * @author  Jory Hogeveen <info@keraweb.nl>
 * @package Off_Canvas_Sidebars
 * @since   0.5.1
 * @version 0.5.1
 * @uses    \OCS_Off_Canvas_Sidebars_Base Extends class
 */
final class OCS_Off_Canvas_Sidebars_Control_Trigger extends OCS_Off_Canvas_Sidebars_Base
{
	/**
	 * HTML elements not supported as a control trigger.
	 * @since  0.5.1
	 * @var    array
	 */
	public static $unsupported_elements = array(
		'base',
		'body',
		'html',
		'link',
		'meta',
		'noscript',
		'style',
		'script',
		'title', // Meta
	);

	/**
	 * HTML elements that are rendered as singleton elements.
	 * @since  0.5.1
	 * @var    array
	 */
	public static $singleton_elements = array(
		'br', // Why?!
		'hr', // Why?!
		'img',
		'input', // Why?!
	);

	/**
	 * HTML elements that are rendered as singleton elements.
	 * @since  0.5.1
	 * @var    array
	 */
	public static $control_elements = array(
		'button',
		'span',
		'a',
		'b',
		'strong',
		'i',
		'em',
		'img',
		'div',
	);

	/**
	 * Do not allow this class to be instantiated.
	 */
	private function __construct() { }

	/**
	 * Generate a trigger element.
	 *
	 * @since   0.4.0
	 * @since   0.5.0  Add icon options.
	 * @since   0.5.1  Moved to this class and renamed from `do_control_trigger()`.
	 * @static
	 *
	 * @param   string  $sidebar_id  Required.
	 * @param   array   $args        See API: the_ocs_control_trigger() for info.
	 * @return  string
	 */
	public static function render( $sidebar_id, $args = array() ) {

		if ( empty( $sidebar_id ) ) {
			return __( 'No Off-Canvas Sidebar ID provided.', 'off-canvas-sidebars' );
		}

		$sidebar_id = (string) $sidebar_id;

		$defaults = array(
			'text'          => '', // Text to show.
			'action'        => 'toggle', // toggle|open|close.
			'element'       => 'button', // button|span|i|b|a|etc.
			'class'         => array(), // Extra classes (space separated), also accepts an array of classes.
			'icon'          => '', // Icon classes.
			'icon_location' => 'before', // before|after.
			'attr'          => array(), // An array of attribute keys and their values.
		);
		$args = wp_parse_args( $args, $defaults );

		$args['attr'] = off_canvas_sidebars_parse_attr_string( $args['attr'] );

		if ( in_array( $args['element'], self::$unsupported_elements, true ) ) {
			return '<span class="error">' . __( 'This element is not supported for use as a button', OCS_DOMAIN ) . '</span>';
		}

		$singleton = false;

		// Is it a singleton element? Add the text to the attributes.
		if ( in_array( $args['element'], self::$singleton_elements, true ) ) {
			$singleton = true;
			if ( 'img' === $args['element'] && empty( $args['attr']['alt'] ) ) {
				$args['attr']['alt'] = $args['text'];
			}
			if ( 'input' === $args['element'] && empty( $args['attr']['value'] ) ) {
				$args['attr']['value'] = $args['text'];
			}
		}

		$attr = array(
			'class' => array(),
		);
		$attr = array_merge( $attr, $args['attr'] );

		// Add our own classes.
		$prefix = off_canvas_sidebars()->get_settings( 'css_prefix' );
		$classes = array(
			$prefix . '-trigger',
			$prefix . '-' . $args['action'],
			$prefix . '-' . $args['action'] . '-' . $sidebar_id,
		);

		// Optionally add extra classes.
		if ( ! empty( $args['class'] ) ) {
			if ( ! is_array( $args['class'] ) ) {
				$args['class'] = explode( ' ', $args['class'] );
			}
			$classes = array_merge( $classes, (array) $args['class'] );
		}

		// Parse classes.
		if ( ! is_array( $attr['class'] ) ) {
			$attr['class'] = explode( ' ', $attr['class'] );
		}
		$attr['class'] = array_merge( $attr['class'], $classes );
		$attr['class'] = array_map( 'trim', $attr['class'] );
		$attr['class'] = array_filter( $attr['class'] );
		$attr['class'] = array_unique( $attr['class'] );

		// Icons can not be used with singleton elements.
		if ( $args['icon'] && ! $singleton ) {
			if ( strpos( $args['icon'], 'dashicons' ) !== false ) {
				wp_enqueue_style( 'dashicons' );
			}
			$icon = '<span class="icon ' . esc_attr( $args['icon'] ) . '"></span>';
			if ( $args['text'] ) {
				// Wrap label in a separate span for styling purposes.
				$args['text'] = '<span class="label">' . $args['text'] . '</span>';
			}
			if ( 'after' === $args['icon_location'] ) {
				$args['text'] .= $icon;
			} else {
				$args['text'] = $icon . $args['text'];
			}
		}

		$return = '<' . $args['element'] . ' ' . self::parse_to_html_attr( $attr );
		if ( $singleton ) {
			$return .= ' />';
		} else {
			$return .= '>' . $args['text'] . '</' . $args['element'] . '>';
		}

		return $return;
	}

	/**
	 * Get control trigger field options.
	 *
	 * @since   0.5.1
	 * @return  array {
	 *     @type array $field_id {
	 *         @type  string  $type
	 *         @type  string  $name
	 *         @type  string  $label
	 *         @type  string  $description
	 *         @type  string  $group
	 *         @type  bool    $multiline  Note: Only if $type is `text`!
	 *         @type  array   $options {
	 *             NOTE: Only if $type is `select`!
	 *             @type  string  $label
	 *             @type  string  $value
	 *         }
	 *     }
	 * }
	 */
	public static function get_fields() {
		static $fields;

		if ( $fields ) {
			return $fields;
		}

		$sidebars = array(
			array(
				'value' => '',
				'label' => '-- ' . __( 'select', OCS_DOMAIN ) . ' --',
			),
		);
		foreach ( off_canvas_sidebars()->get_sidebars() as $sidebar_id => $sidebar_data ) {
			if ( empty( $sidebar_data['enable'] ) ) {
				continue;
			}
			$label = $sidebar_id;
			if ( ! empty( $sidebar_data['label'] ) ) {
				$label = $sidebar_data['label'] . ' (' . $sidebar_id . ')';
			}
			$sidebars[] = array(
				'label'  => $label,
				'value' => $sidebar_id,
			);
		}

		$elements = array();
		foreach ( self::$control_elements as $e ) {
			$elements[] = array(
				'label'  => '<' . $e . '>',
				'value' => $e,
			);
		}

		$strings = array(
			// Translators: [ocs_trigger text="Your text"] or [ocs_trigger]Your text[/ocs_trigger]
			'your_text' => __( 'Your text', OCS_DOMAIN ),
			// Translators: [ocs_trigger text="Your text"] or [ocs_trigger]Your text[/ocs_trigger]
			'or' => __( 'or', OCS_DOMAIN ),
		);

		$fields = array(
			'id' => array(
				'type'        => 'select',
				'name'        => 'id',
				'label'       => __( 'Sidebar ID', OCS_DOMAIN ),
				'options'     => $sidebars,
				'description' => __( '(Required) The off-canvas sidebar ID', OCS_DOMAIN ),
				'required'    => true,
				'group'       => 'basic',
			),
			'text' => array(
				'type'        => 'text',
				'name'        => 'text',
				'label'       => __( 'Text', OCS_DOMAIN ),
				'description' => __( 'Limited HTML allowed', OCS_DOMAIN ),
				'multiline'   => true,
				'group'       => 'basic',
			),
			'icon' => array(
				'type'        => 'text',
				'name'        => 'icon',
				'label'       => __( 'Icon', OCS_DOMAIN ),
				// Translators: %s stands for <code>dashicons</code>.
				'description' => __( 'The icon classes.', OCS_DOMAIN ) . ' ' . sprintf( __( 'Do not forget the base icon class like %s', OCS_DOMAIN ), '<code>dashicons</code>' ),
				'group'       => 'basic',
			),
			'icon_location' => array(
				'type'    => 'select',
				'name'    => 'icon_location',
				'label'   => __( 'Icon location', OCS_DOMAIN ),
				'options' => array(
					array(
						'label' => __( 'Before', OCS_DOMAIN ) . ' (' . __( 'Default', OCS_DOMAIN ) . ')',
						'value' => '',
					),
					array(
						'label' => __( 'After', OCS_DOMAIN ),
						'value' => 'after',
					),
				),
				'group'       => 'basic',
			),
			'action' => array(
				'type'    => 'select',
				'name'    => 'action',
				'label'   => __( 'Trigger action', OCS_DOMAIN ),
				'options' => array(
					array(
						'label' => __( 'Toggle', OCS_DOMAIN ) . ' (' . __( 'Default', OCS_DOMAIN ) . ')',
						'value' => '',
					),
					array(
						'label' => __( 'Open', OCS_DOMAIN ),
						'value' => 'open',
					),
					array(
						'label' => __( 'Close', OCS_DOMAIN ),
						'value' => 'close',
					),
				),
				'group'       => 'advanced',
			),
			'element' => array(
				'type'        => 'select',
				'name'        => 'element',
				'label'       => __( 'HTML element', OCS_DOMAIN ),
				'options'     => $elements,
				'description' => __( 'Choose wisely', OCS_DOMAIN ),
				'group'       => 'advanced',
			),
			'class' => array(
				'type'        => 'text',
				'name'        => 'class',
				'label'       => __( 'Extra classes', OCS_DOMAIN ),
				'description' => __( 'Separate multiple classes with a space', OCS_DOMAIN ),
				'group'       => 'advanced',
			),
			'attr' => array(
				'type'        => 'text',
				'name'        => 'attr',
				'label'       => __( 'Custom attributes', OCS_DOMAIN ),
				'description' => __( 'key : value ; key : value', OCS_DOMAIN ),
				'multiline'   => true,
				'group'       => 'advanced',
			),
			'nested' => array(
				'type'        => 'checkbox',
				'name'        => 'nested',
				'label'       => __( 'Nested shortcode', OCS_DOMAIN ) . '?',
				'description' => '[ocs_trigger text="' . $strings['your_text'] . '"] ' . $strings['or'] . ' [ocs_trigger]' . $strings['your_text'] . '[/ocs_trigger]',
				'group'       => 'advanced',
			),
		);

		return $fields;
	}

	/**
	 * Filters the list of fields, based on a set of key => value arguments.
	 * @since   0.5.1
	 * @see     \wp_list_filter
	 * @param   array  $filter
	 * @return  array
	 */
	public static function get_fields_by( $filter ) {
		return wp_list_filter( self::get_fields(), $filter );
	}

	/**
	 * Filters the list of fields by group.
	 * @since   0.5.1
	 * @see     \wp_list_filter
	 * @param   string  $group
	 * @return  array
	 */
	public static function get_fields_by_group( $group ) {
		return self::get_fields_by( array(
			'group' => $group,
		) );
	}

	/**
	 * Filters the list of fields by type.
	 * @since   0.5.1
	 * @see     \wp_list_filter
	 * @param   string  $type
	 * @return  array
	 */
	public static function get_fields_by_type( $type ) {
		return self::get_fields_by( array(
			'type' => $type,
		) );
	}

} // End class().
