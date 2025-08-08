<?php

/**
 * Mofire Events
 *
 * This file is used to display the Default Events page.
 * 
 * @category Theme
 * @package  mofire-events
 * @author   Martin Ndegwa <ndegwamoche@gmail.com>
 * @license  GPL-2.0-or-later https://www.gnu.org/licenses/gpl-2.0.html
 * @version  GIT: 1.0.0
 * @link     https://github.com/ndegwamoche/mofire-events
 * Requires PHP: 7.0        
 * Requires at least: 6.0                                       
 */

get_header();
?>

<header class="hero">
    <div class="container">
        <h1>Mofire Events</h1>
        <p>Crafting Unforgettable Experiences Across Kenya</p>
       <a href="<?php echo esc_url(get_permalink(get_page_by_path('events'))); ?>" class="btn btn-primary">Discover Our Events</a>

    </div>
</header>
<main>
    <section class="intro container">
        <h2>About Mofire Events</h2>
        <p>Welcome to Mofire Events, Kenya's leading event management company. We specialize in creating vibrant, memorable experiences that bring communities together. From electrifying music festivals to insightful corporate summits, our passion for excellence drives every moment.</p>
        <p>Join us as we ignite the spirit of celebration and connection across Nairobi and beyond!</p>
    </section>
    <section id="events" class="events container">
        <h2>Upcoming Events</h2>
        <div class="events-grid">
            <?php
            $today = date('Y-m-d');
            $args = array(
                'post_type' => 'event',
                'posts_per_page' => 3,
                'meta_key' => '_event_date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => '_event_date',
                        'value' => $today,
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                )
            );
            $events_query = new WP_Query($args);
            if ($events_query->have_posts()) :
                while ($events_query->have_posts()) : $events_query->the_post();
                    $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                    $event_location = get_post_meta(get_the_ID(), '_event_location', true);
                    $registration_url = get_post_meta(get_the_ID(), '_registration_url', true);
                    $display_date = $event_date ? date('F j, Y', strtotime($event_date)) : 'Date not set';
            ?>
                    <div class="event-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>">
                        <?php else : ?>
                            <img src="https://via.placeholder.com/300x200?text=<?php echo urlencode(get_the_title()); ?>" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="event-content">
                            <h3><?php the_title(); ?></h3>
                            <p><strong>Date:</strong> <?php echo esc_html($display_date); ?></p>
                            <p><strong>Location:</strong> <?php echo esc_html($event_location ? $event_location : 'Location not set'); ?></p>
                            <p><?php the_excerpt(); ?></p>
                            <a href="<?php echo esc_url($registration_url ? $registration_url : '#'); ?>" class="btn btn-secondary">Register Now</a>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <p class="no-events">No upcoming events at this time. Stay tuned!</p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php

get_footer();
