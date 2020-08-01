<?php

use App\Image;
use App\ProductDesc;
use App\Product;
use App\Package;
use App\Booking;

function getProductImages($product_id) {
    return Image::where(['product_id' => $product_id])
                    ->get();
}

function getProductDesc($product_id) {
    return ProductDesc::where(['product_id' => $product_id])
                    ->get();
}

function getBannerLink($json) {
    $decode = json_decode($json);
    if ($decode->type == 'product') {
        $link = Product::where(['id' => $decode->item])
                ->first();
        return url('/product-details/'.$link->code);
    } elseif ($decode->type == 'package') {
        $link = Package::where(['id' => $decode->item])
                ->first();
        return url('/package-details/'.$link->code);
    } elseif ($decode->type == 'booking') {
        $link = Booking::where(['id' => $decode->item])
                ->first();
        return url('/booking-details/'.$link->code);
    }
}
