<?php

/**
 * Header
 * 
 * @link https://github.com/ndegwamoche/mofire-events
 *
 * @package mofire-events
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<nav class="navbar">
    <div class="container">
        <div class="logo"><a href="<?php echo esc_url(home_url('/')); ?>">Mofire Events</a></div>
        <ul class="nav-links">
            <li><a href="<?php echo esc_url(home_url('/')); ?>" class="active">Home</a></li>
            <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('events'))); ?>">Events</a></li>
        </ul>
        <div class="menu-toggle">☰</div>
    </div>
</nav>