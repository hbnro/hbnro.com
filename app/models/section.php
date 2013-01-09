<?php

class Section extends \Servant\Mapper\MongoDB
{

  const TABLE = 'hbnro-sections';
  const CONNECTION = '';

  static $columns = [
            'tags' => ['type' => 'array'],            // related
            'slug' => ['type' => 'string'],           // permalink
            'title' => ['type' => 'string'],          // usual link-text
            'indexed' => ['type' => 'boolean'],       // mark as crawable
            'published' => ['type' => 'boolean'],     // show @ /how-to
            'description' => ['type' => 'string'],    // ...
            'modified_at' => ['type' => 'timestamp'], //
            'created_at' => ['type' => 'timestamp'],  //
          ];

  static $indexes = [
            'slug' => TRUE,
            'tags',
          ];

  static $validate = [
            'uniqueness_of' => 'slug',
          ];


  static $indexed = ['where' => ['indexed' => 'on']];
  static $published = ['where' => ['published' => 'on']];


  function set_content($body)
  {
    $dir = dirname($this->get_file());
    is_dir($dir) OR mkdir($dir, 0755, TRUE);

    write($this->get_file(), $body);
  }

  function get_human_date()
  {
    return mdate('%j de %F, %Y a las %H:%i %a.', strtotime($this->attr('modified_at')));
  }

  function get_pub_date()
  {
    return mdate('%F %j de %Y « %H:%i %a.', strtotime($this->attr('modified_at')));
  }

  function get_content()
  {
    return read($this->get_file());
  }

  function get_title()
  {
    return $this->attr('title') ?: titlecase($this->attr('slug'));
  }

  function get_bytes()
  {
    return is_file($this->get_file()) ? filesize($this->get_file()) : -1;
  }

  function get_tags()
  {
    return array_map('slugify', array_filter((array) $this->attr('tags')));
  }

  function get_link()
  {
    return link_to($this->get_title(), $this->get_url());
  }

  function get_url()
  {
    return url_for('show_section', ['*thing' => $this->attr('slug')]);
  }

  function get_file()
  {
    $date = date('Y/m/d/His', strtotime($this->attr('created_at')));
    $path = path(APP_PATH, 'database', 'sections', $date, $this->id());

    return $path;
  }

}
