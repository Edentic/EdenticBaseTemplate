<?php
if(!function_exists('getThumbnailPicOfPage')) {
    function getThumbnailPicOfPage($id = null, $size = 'large') {
        if($id == null) return '';
        $id = get_post_thumbnail_id($id);
        $pic = wp_get_attachment_image_src($id, $size);
        return (isset($pic[0])) ? $pic[0] : '';
    }
}
?>
<?php if(has_post_thumbnail($postID)) { ?>
    <figure class="responsive" data-media="<?php echo getThumbnailPicOfPage($postID, 'medium'); ?>" data-media300="<?php echo getThumbnailPicOfPage($postID, 'large'); ?>" data-media1024="<?php echo getThumbnailPicOfPage($postID, 'full') ?>"  title="">
        <noscript>
            <img src="<?php echo getThumbnailPicOfPage($postID, 'large'); ?>" alt="">
        </noscript>
    </figure>
<?php }?>