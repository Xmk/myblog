<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Articles extends Model
{
    protected $table="articles";
    
    use SearchableTrait;
    
    protected $searchable = [
        'columns' => [
            'articles.title'   => 10,
            'articles.preview' => 10,
            'articles.content' => 10,
            'categories.name'  => 10,
        ],
        'joins' => [
            'categories' => ['articles.categories_id','categories.id'],
        ],
    ];    
    
    protected $fillable = [
        'curl',
        'active',
        'title',
        'preview',
        'content',
        'meta_description',
        'meta_keywords',
        'categories_id',
        'comments_enable',
    ];

    //Получаем все опубликованные статьи
    public function getAllArticles(){
        return $this->published()->orderByParam();
    }

    //Получить все статьи определенной категории
    public function getArticleByCategory($name){
        return $this->published()->getCategory($name)->orderByParam();
    }
    //Получить все статьи определенного тега
    public function getArticleByTag($name){
        return $this->published()->getCategory($name)->orderByParam();
    }
    //Получить опубликованные коментарии определённой статьи (Таким способом можно сделать например фильтр)
    //2й published уже метод класса мадели Comments
    public function getCommentsByСurl($curl){
        return $this->published()->GetArticleByCurl($curl)->first()->comments()->published()->get();
    }




    //Отношение * к 1 (Категории)
    public function category()
    {
        return $this->belongsTo('App\Model\Categories','categories_id','id');
    }
    public function withCategory(){
        $this->with('category');
    }

    //Отношение * ко * (Cтатья * <- 1 ГруппаТегов 1 -> * Название тегов)
    public function tags()
    {
        return $this->belongsToMany('App\Model\Tags','tags_gr','articles_id','tags_id');
    }

    //Отношение 1 к * (коментарии, картинки)
    public function comments()
    {
        return $this->hasMany('App\Model\Comments','articles_id','id');
    }
    public function images()
    {
        return $this->hasMany('App\Model\Images','articles_id','id');
    }


    //scope
    public function scopeGetById($query,$id){
        $query->where(['id'=>$id]);
    }
    public function scopePublished($query){
        $query->where(['active'=>1]);
    }
    public function scopeGetCategory($query,$id){
        $query->where(['categories_id'=>$id]);
    }
    //Зачем это выносить в отдельную OrderBy функцию?
    //На тот случай, если изменятся названия полей в БД,
    //то нужно будет поменять поле только в одном месте (в scopeOrderByParam)
    //Полный полиморфизм))))
    public function scopeOrderByParam($query){
        $query->orderBy('created_at');
    }
}
