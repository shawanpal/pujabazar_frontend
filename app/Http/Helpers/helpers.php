<?php

use App\Image;
use App\ProductDesc;

function getProductImages($product_id){
    return Image::where(['product_id' => $product_id])
                    ->get();
}
function getProductDesc($product_id){
    return ProductDesc::where(['product_id' => $product_id])
            ->get();
}