<?php
Breadcrumbs::for('home', function ($trail) {
     $trail->push('Home', URL::action('ViewController@index'));
});


// Home > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('home');
    $trail->push($category, route('category', $category));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('subcategory', function ($trail, $subcategory) {
	$trail->parent('category', $subcategory[0]->category_name);
    $trail->push($subcategory, route('itemsShowBySubCategory', $subcategory[0]->sub_category_name));
});