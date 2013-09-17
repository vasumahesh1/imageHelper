<?php
include 'imageHelper.php';
$img = new imageHelper("test.jpg",false);
$img->generateThumb("test_mod_thumb.jpg",200,200);
$img->generateThumbNoCrop("test_mod.jpg",200,200);
$img->changeSize("test_mod_resize_small.jpg",0.5);
$img->changeSize("test_mod_resize_large.jpg",2);
$img->changeSizeToHeight("test_mod_resize_height.jpg",450);
$img->changeSizeToWidth("test_mod_resize_width.jpg",450);
?>