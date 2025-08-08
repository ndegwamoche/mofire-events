<?php
/**
 * mofire-events functions and definitions
 *
 * @link https://github.com/ndegwamoche/mofire-events
 *
 * @package mofire-events
 */

// Setup theme defaults
function mofire_events_setup() {
    // Enable custom title
    add_theme_support('title-tag');

    // Enable post thumbnails
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'mofire_events_setup');

// Enqueue scripts and styles
function mofire_events_scripts_styles() {
    // Main styles
    wp_enqueue_style('mofire-events-style', get_stylesheet_uri(), array(), '1.0');
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap', array(), null);
}
add_action('wp_enqueue_scripts', 'mofire_events_scripts_styles');

// Register Custom Post Type for Events
function register_event_post_type() {
    $labels = array(
        'name' => 'Events',
        'singular_name' => 'Event',
        'menu_name' => 'Events',
        'name_admin_bar' => 'Event',
        'add_new' => 'Add New Event',
        'add_new_item' => 'Add New Event',
        'new_item' => 'New Event',
        'edit_item' => 'Edit Event',
        'view_item' => 'View Event',
        'all_items' => 'All Events',
        'search_items' => 'Search Events',
        'not_found' => 'No events found.'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt'
    );
    register_post_type('event', $args);
}
add_action('init', 'register_event_post_type');

// Add Meta Box for Event Fields
function add_event_meta_box() {
    add_meta_box(
        'event_details',
        'Event Details',
        'event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_event_meta_box');

// Meta Box Callback
function event_meta_box_callback($post) {
    // Add nonce field for security
    wp_nonce_field('mofire_event_meta_nonce', 'event_meta_nonce');
    // Retrieve existing meta values
    $event_date = get_post_meta($post->ID, '_event_date', true);
    $event_location = get_post_meta($post->ID, '_event_location', true);
    $registration_url = get_post_meta($post->ID, '_registration_url', true);
    ?>
    <p>
        <label for="event_date">Event Date</label><br>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>" required>
    </p>
    <p>
        <label for="event_location">Event Location</label><br>
        <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($event_location); ?>" required style="width: 100%;">
    </p>
    <p>
        <label for="registration_url">Registration URL</label><br>
        <input type="url" id="registration_url" name="registration_url" value="<?php echo esc_attr($registration_url); ?>" required style="width: 100%;">
    </p>
    <?php
}

// Save Meta Box Data
function save_event_meta($post_id) {
    // Check if nonce is set and valid
    if (!isset($_POST['event_meta_nonce']) || !wp_verify_nonce($_POST['event_meta_nonce'], 'mofire_event_meta_nonce')) {
        error_log('Mofire Events: Nonce verification failed for post ID ' . $post_id);
        return;
    }

    // Prevent autosave from triggering
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        error_log('Mofire Events: Autosave detected for post ID ' . $post_id);
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        error_log('Mofire Events: User lacks permission to edit post ID ' . $post_id);
        return;
    }

    // Check if post type is 'event'
    if (get_post_type($post_id) !== 'event') {
        error_log('Mofire Events: Invalid post type for post ID ' . $post_id);
        return;
    }

    // Save event date
    if (isset($_POST['event_date']) && !empty($_POST['event_date'])) {
        $sanitized_date = sanitize_text_field($_POST['event_date']);
        update_post_meta($post_id, '_event_date', $sanitized_date);
        error_log('Mofire Events: Saved event_date for post ID ' . $post_id . ': ' . $sanitized_date);
    } else {
        delete_post_meta($post_id, '_event_date');
        error_log('Mofire Events: No event_date provided for post ID ' . $post_id);
    }

    // Save event location
    if (isset($_POST['event_location']) && !empty($_POST['event_location'])) {
        $sanitized_location = sanitize_text_field($_POST['event_location']);
        update_post_meta($post_id, '_event_location', $sanitized_location);
        error_log('Mofire Events: Saved event_location for post ID ' . $post_id . ': ' . $sanitized_location);
    } else {
        delete_post_meta($post_id, '_event_location');
        error_log('Mofire Events: No event_location provided for post ID ' . $post_id);
    }

    // Save registration URL
    if (isset($_POST['registration_url']) && !empty($_POST['registration_url'])) {
        $sanitized_url = sanitize_url($_POST['registration_url']);
        update_post_meta($post_id, '_registration_url', $sanitized_url);
        error_log('Mofire Events: Saved registration_url for post ID ' . $post_id . ': ' . $sanitized_url);
    } else {
        delete_post_meta($post_id, '_registration_url');
        error_log('Mofire Events: No registration_url provided for post ID ' . $post_id);
    }
}
add_action('save_post_event', 'save_event_meta', 10, 1);

// Add Custom Columns to Events Admin List
function mofire_event_custom_columns($columns) {
    $new_columns = [];
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['event_date'] = 'Event Date';
    $new_columns['event_location'] = 'Event Location';
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_event_posts_columns', 'mofire_event_custom_columns');

// Populate Custom Columns
function mofire_event_custom_column_data($column, $post_id) {
    if ($column === 'event_date') {
        $date = get_post_meta($post_id, '_event_date', true);
        echo $date ? esc_html(date('M j, Y', strtotime($date))) : '—';
    }
    if ($column === 'event_location') {
        $location = get_post_meta($post_id, '_event_location', true);
        echo esc_html($location ?: '—');
    }
}
add_action('manage_event_posts_custom_column', 'mofire_event_custom_column_data', 10, 2);

