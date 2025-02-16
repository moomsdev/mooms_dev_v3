<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The menu walker.  This is just the methods from `Walker_Nav_Menu` with
 * all of the whitespace generation (eg. `$indent` remove) as well as
 * some restrictions on the CSS classes that are added. Menu item IDs are also
 * removed.
 * Most of the filters here are preserved so it should be backwards
 * compatible.
 *
 * @since   0.1
 */
class MM_Menu_Walker extends Walker_Nav_Menu
{

    /**
     * {@inheritdoc}
     */
    function start_lvl(&$output, $depth = 0, $args = [])
    {
        if ($args->walker->has_children) {
            $output .= '<ul class="nav__dropdown-menu">';
        } else {
            $output .= '<ul class="nav__dropdown-menu">';
        }
    }

    /**
     * {@inheritdoc}
     */
    function end_lvl(&$output, $depth = 0, $args = [])
    {
        if ($args->walker->has_children) {
            $output .= '</ul>';
        } else {
            $output .= '</ul>';
        }
    }

    /**
     * {@inheritdoc}
     */
    // function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    // {
    //     // Create an array of CSS classes for the menu item
    //     $classes   = empty($item->classes) ? [] : (array)$item->classes;
    //     $classes[] = 'nav__dropdown menu-item-' . $item->ID;

    //     // Check if the item is a top-level item (depth = 0)
    //     if ($depth === 0) {
    //         $classes[] = 'menu-item-level-1'; // Add a special class for level 1 items
    //     }

    //     // Add a special class if the menu item has children
    //     if ($args->walker->has_children) {
    //         $classes[] = 'menu-item-has-children';
    //     }

    //     // Combine and filter CSS classes
    //     $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
    //     $class_names = $class_names ? ' class="menu-line ' . esc_attr($class_names) . '"' : '';

    //     // Open the list item with appropriate class names
    //     $output .= '<li' . str_replace('menu-item-has-children', 'has_child_menu', $class_names) . '>';

    //     // Generate attributes for the anchor tag
    //     $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    //     $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    //     $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    //     $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

    //     // Generate the menu link output
    //     $item_output = $args->before;
    //     $item_output .= '<a' . $attributes . '>';
    //     $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
    //     $item_output .= '</a>';
    //     $item_output .= $args->after;

    //     // Append the final output for this item
    //     $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    // }
    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        // Create an array of CSS classes for the menu item
        $classes   = empty($item->classes) ? [] : (array)$item->classes;
        $classes[] = 'nav__dropdown menu-item-' . $item->ID;
    
        // Check if the item is a top-level item (depth = 0)
        if ($depth === 0) {
            $classes[] = 'menu-item-level-1'; // Add a special class for level 1 items
        }
    
        // Add a special class if the menu item has children
        if ($args->walker->has_children) {
            $classes[] = 'menu-item-has-children has_child_menu'; // Add class for items with submenus
        }
    
        // Combine and filter CSS classes
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="menu-line ' . esc_attr($class_names) . '"' : '';
    
        // Open the list item with appropriate class names
        $output .= '<li' . $class_names . '>';
    
        // Generate attributes for the anchor tag
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
    
        // Generate the menu link output
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
    
        // Add a toggle button for submenus (only if it has children)
        if ($args->walker->has_children) {
            $item_output .= '<button class="submenu-toggle" aria-expanded="false"><span class="submenu-toggle-icon">+</span></button>';
        }
    
        // Add after content from arguments and close the item output
        $item_output .= $args->after;
    
        // Append the final output for this item
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    

    /**
     * {@inheritdoc}
     */
    function end_el(&$output, $item, $depth = 0, $args = [])
    {
        $output .= "</li>";
    }
}
