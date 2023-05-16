<?php

namespace Tests\Feature;

use App\Models\CategoryTest;
use App\Models\Contrat;
use App\Models\Details_Contrat;
use App\Models\Permission;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExamenRoutesTest extends TestCase
{
    // use RefreshDatabase;

    use DatabaseTransactions;

    protected $connection = 'testing';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexExamensCategories()
    {
        CategoryTest::factory()->count(5)->create()->unique();

        $user = User::factory()->create();
        $userCheck = User::find($user->id);

        // Envoi de la requête GET pour récupérer tous les catégories de test
        $response = $this->get('/examens/categories');

        //Vérifier si on a une redirection
        $response->assertStatus(302);

        $this->actingAs($userCheck);

        $response = $this->get('/examens/categories');

        // $response->assertStatus(401);
        $response->assertRedirect('/examens/categories');
        // $response->dumpHeaders();
    }

    public function testCreateExamensCategories()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        // Données pour la création d'une catégorie
        $dataCategory = [
            'code' => 'CF',
            'name' => 'Piqûre test'
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('examens.categories.store'), $dataCategory);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('category_tests', $dataCategory);

    }

    public function testUpdateExamensCategories()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $categoryTest = CategoryTest::factory()->create();

        // Données d'une catégorie
        $dataCategory = [
            'id'=>$categoryTest->id,
            'code' => 'CF',
            'name' => 'Piqûre tout coup'
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('examens.categories.update'), $dataCategory,[
            'X-CSRF-Token' => csrf_token(),
        ]);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('category_tests', $dataCategory);

    }

    public function testDeleteExamensCategories()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $categoryTest = CategoryTest::factory()->create();

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('/categorytest/delete/'.$categoryTest->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);
        // Vérification que la catégorie a été supprimé avec succès
        $this->assertDatabaseMissing('patients', ['id' => $categoryTest->id]);

    }

    public function testEditExamensCategories()
    {
        $user = User::first();

        $categoryTest = CategoryTest::factory()->create();

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get("getcategorytest/".$categoryTest->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function testExamenIndex()
    {
        $categories = CategoryTest::factory()->count(5)->create()->unique();
        foreach ($categories as $category) {
            $test1 = Test::factory()->create(['category_test_id' => $category->id]);
        }
        $user = User::factory()->create();
        $userCheck = User::find($user->id);

        // Envoi de la requête GET pour récupérer tous les catégories de test
        $response = $this->get('/examens/index');

        //Vérifier si on a une redirection
        $response->assertStatus(302);

        $this->actingAs($userCheck);

        $response = $this->get('/examens/index');

        // $response->assertStatus(401);
        $response->assertRedirect('/examens/index');
        // $response->dumpHeaders();
    }

    public function testCreateExamen()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $dataCategory = CategoryTest::factory()->create();

        $test = [
            'name'=>'Test test',
            'price'=>5000,
            'category_test_id'=>$dataCategory->id
        ];
        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('examens.store'), $test);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('tests', $test);
    }

    public function testUpdateExamen()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $dataCategory = CategoryTest::factory()->create();
        $test1 = Test::factory()->create(['category_test_id' => $dataCategory->id]);
        $test = [
            'id'=>$test1->id,
            'name'=>'Test test',
            'price'=>5000,
            'category_test_id'=>$dataCategory->id
        ];
        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post('/examens/update', $test);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('tests', $test);
    }

    public function testDeleteExamen()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $dataCategory = CategoryTest::factory()->create();
        $test1 = Test::factory()->create(['category_test_id' => $dataCategory->id]);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('/test/delete/'.$test1->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que l'examen a été supprimé avec succès
        $this->assertDatabaseMissing('patients', ['id' => $test1->id]);
    }

    public function testGetTestAndRemise()
    {
         // Création de l'utilisateur pour s'authentifier
         $user = User::first();

        $contrat = Contrat::create([
            'name'=>'original',
            'type'=>'Ori',
        ]);
        $categoryTest = CategoryTest::factory()->create();
        $test1 = Test::factory()->create(['category_test_id' => $categoryTest->id]);

        $contratDetail = Details_Contrat::create([
            'contrat_id'=>$contrat->id,
            'category_test_id'=>$categoryTest->id,
        ]);

        $data = [
            'data' => $test1,
            'detail' => $contratDetail,
        ];
         // envoi de la requête POST à l'endpoint
         $response = $this->actingAs($user)->get("/gettestremise/".$contrat->id.'/'.$categoryTest->id);

         // Vérification du code de réponse HTTP
        $response->assertStatus(200);

    }
}
