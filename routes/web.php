<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//Define /admin routes
Route::middleware('auth')->prefix('admin')->namespace('Admin')->name('admin.')->group(function () {
    Route::get('/', 'HomeController@index')->name('dashboard');
    Route::resource('posts', 'PostController')->parameters([
        'posts' => 'post:slug'
    ]);
});

//Route::get('/home', 'HomeController@index')->name('home');


Route::get('{any?}', function () {
    return view('guest.home');
})->where('any', '.*');


/* 

- Creare Modello Catagories:

php artisan make:model Models/Category -a

- spostare il comntroller all'interno di admin e cambiare il namespace e importare il controller

- Entrare nel database e creare la migrazione:

$table->string('name', 50);
$table->string('slug', 100);

- Definire il seeder

$categories = ['FrontEnd', 'Backend', 'Programming', 'Design', 'FullStack'];

foreach($categories as $category) {
    $new_cat = new Category();
    $new_cat->name = $category;
    $new_catslug = Str::slug($new_cat->name); ->importare slug
    $new_cat->save();
}

- php artisan migrate

- Fare seed database separatamente o insieme: php artisan migrate --seed

- Creare la relazione:

Entrare nel modello Post:

public function category(): BelongsTo { -> importare BelongTo
    return $this->belongsTo(Category::class);
}

Entrare nel modello Category:

public function posts(): HasMany { -> importare HasMany
    return $this->hasMany(Post::class);
}

- php artisan make:migration add_category_id_to_posts_table

- entrare nella migrazione e aggiungere colonna nel metodo up:

$table->unsignedBigInteger('category_id')->nullable()->after('id');

$table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

- Entrare nel metodo down:

$table->dropForeign('posts_category_id_foreign');
$table->dropColumn('category_id);

- php artisan migrate

- Aggiungere post a categoria nel tinker:

$post = Post::find(18);
$category = Category::find(3);

$category->posts()->save($posts)

---------------------------------

$category = Category::find(6);
$post = Post::find(19);

$post->category()-Associate($category);

---------------

$posts = Post::where('id', < , 18)->get();

$cat = Category::find(4);

$cat->posts()->saveMany($posts);

- Layout aggiunta categorie

- andare nel modello Post e aggiungere 'category_id' alle fillable

- Entrare nel controller PostController metodo create: ->importare Category

$cateogries = Category::all() e passarle con compact
dd($categories);

- Creare una select nel file create (bs5 form-select)
for="Category_id"

- Fare ciclo su option (lasciare un option con value vuoto)

<option value="">Select a category<option/>
foreach($categories as $category)
<option value="{{$category->id}}">{{$category->name}}<option/>

- Modificare validazione metodo store (verficiare se l'id inserito esiste tra gli id delle categorie)

(exists:table,id)

exists::categories,id

- entrare in Post request e aggiungere:

'category_id'=> ['nullable', 'exists:categories,id']

- Modificare edit:

Copiare e incollare la select da create

Nel post controller metodo edit:

$cateogries = Category::all() e passarle con compact
dd($categories);

- aggiungere selected nello'option

{{$category->id == old('category_id', $post->category->id) ? 'selected' : ''}}

- Fare validazione nel post controller

'category_id' => 'nullable|exists:categories,id

- Mostrare categorie dentro i post

- entrare nella view show

fare div con classe meta
Category: {{$post->category ? $post->category->name : 'N/A'}}


- Implementare CRUD PER LE CATEGORIE

- Nuova rotta Route::resource('catgories', 'CategoryController')->parameters({
    'categories' => 'category:slug';
})->except(['show', 'create', 'edit']);

- Entrare nel category controller:

metodo index 
$cats = Category::all()
return view ('admin.categories.index', compact('cats'))

-Creare cartella categories nelle vies e fare file index

- estendere layout admin

- Aggiungere section('content')

h1 All categories

bs5-grid-default

container>riga>colonne2
form cona ll'interno input con il nome
bottone bs5 submit

nella seconda colonna

bs5-table-special

id name slug actions

mettere td in un forelse

- Implementare metodo store
dd($request->all());

Validare
v$val_data = $request->validate([
    'name' => 'required|unique:catagories'
])
generare slug (importare slug)

$slug = Str::slug($request->name);
$val_data['slug'] = $slug

salvare
Catagory::create($val_data)

redirect (aggiungere protected fillable)
return redirect()->back()->with('message', 'category $slug added successfully');

- Aggiungere session message nell'index (fare parziale session_message)

- implementare delete

form action delete {{route('admin.categories.destroy', $category->id)}}

nel controller

$category->delete();
return redirect()->back()->with('message', 'category $slug added successfully');

METODO EDIT

.inserire nle td form (id category-{{$category->slug}}) action route('admin.categories.update, $category->id) post con input text, name 'name', value {{$category->name}}

- m,ettere bs5 form submit update aggiungere form e passargli id del form

- nel metodo update del controller

dd($request->all())

- copiare validazione da store

- $category->update($val-data)
return redirect->back().....

- importare dai parziali gli errors

nella validazione 'name' => ['required' , Roule::unique('categories')->($category)]

- th Posts Count td {{count($category->posts)}}



*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
