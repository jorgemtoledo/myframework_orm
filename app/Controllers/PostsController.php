<?php
namespace App\Controllers;
use App\Models\Category;
use Core\BaseController;
use App\Models\Post;
// Use Core\Container;
use Core\Redirect;
use Core\Session;
use Core\Validator;
use Core\Auth;

class PostsController extends BaseController
{
  private $post;
  
  public function __construct()
  {
    parent::__construct();
    // $this->post = Container::getModel('Post');
    $this->post = new Post;
  }

  public function index()
  {
    $this->view->posts = $this->post->All();
    $this->setPageTitle('Posts');
    return $this->renderView('posts/index', 'layout');
  }

  public function show($id)
  {
    $this->view->post = $this->post->find($id);
    $this->setPageTitle("{$this->view->post->title}");
    return $this->renderView('posts/show', 'layout');
  }

  public function create()
  {
    $this->setPageTitle('New Post');
    $this->view->categories = Category::all();
    return $this->renderView('posts/create', 'layout');
  }

  public function store($request)
  {
      $data = [
          'user_id' => Auth::id(),
          'title' => $request->post->title,
          'content' => $request->post->content
      ];
      if (Validator::make($data, $this->post->rules())) {
          return Redirect::route("/post/create");
      }
      try{
          $post = $this->post->create($data);
          if(isset($request->post->category_id)){
              $post->category()->attach($request->post->category_id);
          }
          return Redirect::route('/posts', [
              'success' => ['Post criado com sucesso!']
          ]);
      }catch(\Exception $e){
          return Redirect::route('/posts', [
              'errors' => [$e->getMessage()]
          ]);
      }
      /*if($this->post->create($data)){
          return Redirect::route('/posts');
      }else{
          return Redirect::route('/posts', [
              'errors' => ['Erro ao inserir no banco de dados!']
          ]);
      }*/
  }

  public function edit($id)
  {
    $this->view->post = $this->post->find($id);
    $this->view->categories = Category::all();
    if(Auth::id() != $this->view->post->user->id){
      return Redirect::route('/posts', [
          'errors' => ['Você não pode editar post de outro autor.']
      ]);
    }
      $this->setPageTitle('Edit post - ' . $this->view->post->title);
      return $this->renderView('posts/edit', 'layout');
    }

  public function update($id, $request)
  {
    $data = [
      'title' => $request->post->title,
      'content' => $request->post->content
    ];

    if($validator = Validator::make($data,$this->post->rules()))
    {
      return Redirect::route("/post/{$id}/edit");
    }

    // if($this->post->update($data,$id))
    // {
    //   return Redirect::route('/posts', [
    //     'success' => ['Post atualizado com sucesso!']
    //   ]);
    // } else {
    //   return Redirect::route('/posts', [
    //     'errors' => ['Erro ao atualizar!']
    //   ]);
    // }

    try{
      // $post = Post::find($id);
      $post = $this->post->find($id);
      $post->update($data);
      if(isset($request->post->category_id))
      {
        $post->category()->sync($request->post->category_id);
      } else {
        $post->category()->detach();
      }
      return Redirect::route('/posts', [
        'success' => ['Post atualizado com sucesso!']
      ]);

    }catch(\Exception $e){
      return Redirect::route('/posts', [
        'errors' => [$e->getMessage()]
      ]);
    }
  }

  public function delete($id)
  {
    // if($this->post->delete($id)){
    // return Redirect::route('/posts');
    //   }else{
    // return Redirect::route('/posts', [
    //   'errors' => ['Erro ao deletar!']
    // ]);
    // }

    try{
      $post = $this->post->find($id);
      if(Auth::id() != $post->user->id){
        return Redirect::route('/posts', [
            'errors' => ['Você não pode deletar post de outro autor.']
        ]);
      }
      $post->delete();
      return Redirect::route('/posts', [
        'success' => ['Post deletado com sucesso!']
      ]);
    }catch(\Exception $e){
      return Redirect::route('/posts', [
        'errors' => [$e->getMessage()]
      ]);
    }
  }

}