<?php

class Sections extends \Hbnro\App\Base
{

  function __construct()
  {
    $this->error = [];
  }

  function index()
  {
    $result = \Section::get('title', 'slug', 'indexed', 'published', 'created_at', 'modified_at');
    $this->sections = paginate_to(url_for('sections'), $result, params('p'), 33);
  }

  function create()
  {
    $slug = slugify(params('thing'));

    if ( ! \Section::create_by_slug($slug)) {
      flash('error', $row->errors);
    }

    return redirect(url_for('show_section', $slug));
  }

  function update()
  {
    if ($found = \Section::first(params('id'))) {
      switch (params('action')) {
        case 'toggle';
          $key = params('field');
          $found->$key = params('value');
          return $found->save() ? [$key => $found->$key] : ['error' => 'failure'];
        case 'update';
          $found->description = params('excerpt');
          $found->content = params('content');
          $found->title = params('title');
          $found->tags = params('tags');

          if ($found->save()) {
            return [
              'title' => $found->title,
              'body' => md($found->content),
            ];
          } else {
            return ['error' => $found->errors->all()];
          }
        default;
          return ['error' => 'unknown'];
      }
    }
    return ['error' => 'missing'];
  }

  function fetch()
  {
    if ($found = \Section::first(params('id'))) {
      return [
        'title' => $found->title,
        'body' => $found->content,
        'excerpt' => $found->description,
      ];
    }
    return ['error' => 'missing'];
  }

  function delete()
  {
    if (\Section::delete_all(['_id' => params('id')])) {
      return redirect_to('sections', ['success' => 'A section was deleted']);
    }
    return redirect_to('sections', ['error' => 'The section was not found']);
  }

}
