<?php

class Controller
{
    public function view($view, $data = [])
    {
        // Meng-ekstrak data array menjadi variabel individual
        extract($data);

        // Memuat file tampilan (view) dari folder views
        require_once '../app/views/' . $view . '.php';
    }

    public function model($model)
    {
        // Memuat file model dari folder models
        require_once '../app/models/' . $model . '.php';

        // Mengembalikan instance dari model yang dimuat
        return new $model;
    }
}
