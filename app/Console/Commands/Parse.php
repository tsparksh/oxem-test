<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Goods;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class Parse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:files {file=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse json files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        switch ($this->argument('file')) {
            case('categories'):
                $this->parseFile('categories.json', 'parseCategories');
                break;
            case('products'):
                $this->parseFile('products.json', 'parseProducts');
                break;
            default:
                $this->parseFile('categories.json', 'parseCategories');
                $this->parseFile('products.json', 'parseProducts');
                break;
        }

        echo "Выполнение команды завершено.\n";

        return true;
    }

    /**
     * Функция для парсинга и вставки в базу данных из файла categories.json
     *
     * @param array $categories Передаем по ссылке, т.к. нет смысла копировать исходный массив.
     * @return bool
     */
    protected function parseCategories(array &$categories): bool
    {
        foreach ($categories as $key => $category) {
            try {
                $category = new Request($category);
                $category->validate(
                    [
                        'name' => 'required|string|max:200',
                        'parent_category_id' => 'exists:categories,id',
                        'external_id' => 'required|integer|min:1',
                    ]
                );

                Category::create($category->all());
            } catch (\Illuminate\Validation\ValidationException $exception) {
                echo $key . " строка: " . json_encode($exception->errors()) . "\n";
            }
        }

        return true;
    }

    /**
     * Функция для парсинга и вставки в базу данных из файла products.json.
     *
     * @param array $products Передаем по ссылке, т.к. нет смысла копировать исходный массив.
     * @return bool
     */
    protected function parseProducts(array &$products): bool
    {
        foreach ($products as $key => $product) {
            try {
                $product = new Request($product);
                $product->validate(
                    [
                        'name' => 'required|max:200',
                        'description' => 'max:1000',
                        'price' => 'required|numeric',
                        'quantity' => 'required|integer',
                        'external_id' => 'required|integer|min:1',
                        'category_id' => 'required|array',
                        'category_id.*' => 'exists:categories,id',
                    ]
                );

                // Переводим цену в копейки, чтобы избежать округлений.
                $product->price *= 100;

                $goods = Goods::create($product->all());
                $goods->categories()->attach($product->category_id);

            } catch (\Illuminate\Validation\ValidationException $exception) {
                echo $key . " строка: " . json_encode($exception->errors()) . "\n";
            }
        }

        return true;
    }

    /**
     * Функция для парсинга файла.
     *
     * @param string $filename
     * @param string $callback
     * @return bool
     */
    protected function parseFile(string $filename, string $callback): bool
    {
        echo "Начал работу с файлом $filename\n";

        try {
            $fileContent = json_decode(file_get_contents("jsons/$filename"), true);
        } catch (\Throwable $e) {
            echo "При чтении $filename произошла ошибка\n";
            return false;
        }

        $this->$callback($fileContent);

        echo "Закончил работу с файлом $filename\n";

        return true;
    }
}
