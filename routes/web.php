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
    Route::resource('categories', 'CategoryController')->parameters([
        'categories' => 'category:slug'
    ])->except(['show', 'create', 'edit']);
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


/* 
MANY TO MANY

- Creare modello 
php artisan make:model Models/Tag

- Entrare nel modello:
public function posts() : BelongsToMany { ->importare BelongsTo many
    return $this->belongsTo Many(Post::class);
}

- Entrare nel modello post

public function tags(): BelongsToMany ->importare 
{
    return $this->belongsToMany(Tag::class);
}

- php artisan make:migration create_tags_table (creare migrazione tabella tags)

- Entrare nella migrazione:

$table->string('name', 100)->unique();
$table->string('slug', 150)

- php artisan migrate

- php srtisan make:seeder TagSeeder

- Entrare nel seeder (importare Str, importare modello Tag)

 $tags = ['coding', 'laravel', 'css', 'Js', 'vue', 'sequel'];

        foreach ($tags as $tag) {
            $new_tag = new tag();
            $new_tag->name = $tag;
            $new_tag->slug = Str::slug($new_tag->name);
            $new_tag->save();
        }

- php artisan db:seed --class=TagSeeder

- php artisan tin
Tag::all
exit

- Creare la tabella pivot:
php artisan make:migration create_post_tag_table

Entrare nella migrazione(metodo up):
$table->unsignedBigInteger('post_id')->nullable();
$table->foreign('post_id)->references('id)->on('posts')->cascadeOnDelete();
$table->unsignedBigInteger('tag_id')->nullable();
$table->foreign('tag_id)->references('id)->on('tags')->cascadeOnDelete();

-php artisan migrate

- php artisan ti
$post = Post::find(18)

$tag = Tag::find(5)

$tag->posts()->attach($post->id) (oppure inserire numero del post nelle parentesi di attach)

$tag->posts

$tag->posts()->sync([1,2,3])
$tag->posts()->detach()

- Entrare nel PostController

passare la lista dei tag -> importare modello Tag
$tags = Tag::all()
passarli tramite compact

- Entrare nella view create:
fare un tag select

bs5 multi-select

for tags name tags[] id tags ara label tags
<option value "" disabled>Select Tags </option>
forelse($tags as $tag)
<option value="{{$tag->id}}>{{$tag->name}}</option>
@empty
<option value="">No Tags</option>
@endforelse

- Entrare nel post controller

metodo store

$new_post = Post::create($val_data);
$new_post->tags()->attach($request->tags);

- Mostrare tags nello show di post

aggiungere div tags
@if(count($post->tags >0 ))
    strong Tags:
    @foreach($post->tags as $tag)
    span #{{$tag->name}}
    @endforeach
@else
span N/A
@endif

- Validazione tags nel PostRequest
'tags' => [exists:tags,id],   RISOLVERE BUG

- Aggiungere select all'edit

bs5 multi-select

for tags name tags[] id tags ara label tags
<option value "" disabled>Select Tags </option>
forelse($tags as $tag)
<option value="{{$tag->id}}>{{$tag->name}}</option>
@empty
<option value="">No Tags</option>
@endforelse

- Passare tags nel metodo edit con il compact

- Aggiunger old nell'edit


@if($errors->any())
<option value"{{$tag->id}}" {{in_array($tag->id, old('tags)) ? 'selected' : ''}}> {{$tag->name}} >/option>
@else
<option value"{{$tag->id}}" {{$post->tags->contains($tag->id)) ? 'selected' : ''}}> {{$tag->name}} >/option>
@endif

- Validare tags nel metodo update

'tags' => 'exists:tags,id'

- Sync tags

$post->tags->sync($request->tags);

- IMPLEMENTARE CRUD PER I TAGS

- php artisan make:controller Admin/TagController -rm App/Models/Tag

- Metodo index

$tags = Tag::all();
return view('admin.tags.index, compact('tags))

- Creare le rotte

Route::resource('tag', 'tagController')->parameters([
        'tag' => 'tag:slug'
    ])->except(['show', 'create', 'edit']);

- crare view in admin

- Copiare html da index categories

-Nel tag controller copiare validazione e metodo store da conroller categories

- Copiare update e destroy da categories

- METTERE IN RELAZIONE UTENTI E POST

- User Model :

public function posts(): HasMany -> importare
{
    return $this->hasMany(Post::class)
}

- User Post

public function user() : BelongsTo
{
    return $this->belongsTo(User::class)

}

- php artisan make:migration add_user_id_to_posts_table

- entrare nella migrazione metodo up
$table->unsigendBigInteger('user_id')->nullable()->after('id);
$table->foreign('user_id')->reference('id')->on('user')->onDelete('set null');

-metodo down
$table->dropForeign('posts_users_id_foreign');
$table->dropColumn('user_id');

- php artisan migrate

- spostare User all'interno di Models e cambiare namespace
modifcare namespace nella registrazione App\Models\User

- associare post a noi stessi
php artisan ti

User::all()

Post::all()

$user_one_posts = Post::where('id', '<', 20)->get()
$user_two_posts = Post::where('id', '>', 20)->get()

$user_one = User::find(1)
$user_tow = User::find(2)

$user_one->posts 








*/