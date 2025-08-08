<?php

/**
 * Footer
 * 
 * @link https://github.com/ndegwamoche/mofire-events
 *
 * @package mofire-events
 */
?>

<footer>
    <div class="container">
        <p>&copy; 2025 Mofire Events. All rights reserved.</p>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navLinks = document.querySelector('.nav-links');

        if (menuToggle && navLinks) {
            menuToggle.addEventListener('click', () => {
                navLinks.classList.toggle('active');
            });
        }
    });
</script>

<?php wp_footer(); ?>

</body>

</html>