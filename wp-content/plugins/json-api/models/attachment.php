<?php

class JSON_API_Attachment
{
    public $id;          // Integer
  public $url;         // String
  public $slug;        // String
  public $title;       // String
  public $description; // String
  public $caption;     // String
  public $parent;      // Integer
  public $mime_type;   // String
  
  public function JSON_API_Attachment($wp_attachment = null)
  {
      if ($wp_attachment) {
          $this->import_wp_object($wp_attachment);
          if ($this->is_image()) {
              $this->query_images();
          }
      }
  }
  
    public function import_wp_object($wp_attachment)
    {
        $this->id = (int) $wp_attachment->ID;
        $this->url = $wp_attachment->guid;
        $this->slug = $wp_attachment->post_name;
        $this->title = $wp_attachment->post_title;
        $this->description = $wp_attachment->post_content;
        $this->caption = $wp_attachment->post_excerpt;
        $this->parent = (int) $wp_attachment->post_parent;
        $this->mime_type = $wp_attachment->post_mime_type;
    }
  
    public function is_image()
    {
        return (substr($this->mime_type, 0, 5) == 'image');
    }
  
    public function query_images()
    {
        $sizes = array('thumbnail', 'medium', 'large', 'full');
        if (function_exists('get_intermediate_image_sizes')) {
            $sizes = array_merge(array('full'), get_intermediate_image_sizes());
        }
        $this->images = array();
        $home = get_bloginfo('url');
        foreach ($sizes as $size) {
            list($url, $width, $height) = wp_get_attachment_image_src($this->id, $size);
            $filename = ABSPATH . substr($url, strlen($home) + 1);
            if (file_exists($filename)) {
                list($measured_width, $measured_height) = getimagesize($filename);
                if ($measured_width == $width &&
            $measured_height == $height) {
                    $this->images[$size] = (object) array(
            'url' => $url,
            'width' => $width,
            'height' => $height
          );
                }
            }
        }
    }
}
