# Breadcrumb

A lightweight, customisable function to generate and display a breadcrumb for WordPress.

The WordPress Breadcrumb is a quick and easy to use function for creating a WordPress trail. It features support for:

* Static and blog home pages
* Blog and archives (categories, tags, dates and authors)
* Pages and parent pages
* Attachments
* 404 pages
* Search result pages
* Custom Post Type Archive
* Custom Taxonomies
* Custom Post Type

This function simply generates a list of links and returns or echos it. There is no styling, however this can be used out-of-the-box with Bootstrap and Foundation.

## Usage

Simply drop this function into your theme's `functions.php` file and call `breadcrumb()` where you wish to display the breadcrumb in your theme's template files.

There are a few parameters you can pass to alter the breadcrumb:

```
breadcrumb( array(
   'container'        => 'ul',
   'container_id'     => 'breadcrumb',
   'container_class'  => 'breadcrumb',
   'item'             => 'li',
   'item_class'       => '',
   'anchor_class'     => '',
   'separator'        => false,
   'active_class'     => 'active',
   'echo'             => true,
) )
```

The default values are:

```
breadcrumb( array(
   'container'        => 'ul',          // ol or ul
   'container_id'     => 'breadcrumb',  // An id applied to the list
   'container_class'  => 'breadcrumb',  // A class applied to the list
   'item'             => 'li',          // list item li
   'item_class'       => '',            // A class applied to the list-item
   'anchor_class'     => '',            // A class applied to the list-item anchor tag (a)
   'separator'        => '&#47;',       // A separator applied to the list-item 
   'active_class'     => 'active',      // A class applied to the list-item of the current page
   'echo'             => false,         // false to return, true to echo
) )
```

Typical output would be:

```
<ul id="breadcrumb" class="breadcrumb">
  <li><a href="#">Home</a></li>
  <li><a href="#">Blog</a></li>
  <li><a href="#">Category</a></li>
  <li class="active">Post Title</li>
</ul>
```

## Version History

### 1.0.0