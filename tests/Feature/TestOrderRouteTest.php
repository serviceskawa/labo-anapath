<?php

namespace Tests\Feature;

use App\Models\Contrat;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\TestOrder;
use App\Models\TypeOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestOrderRouteTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateTestOrder()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $testOrder = [
            'prelevement_date'=>'11/05/23',
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('test_order.store'), $testOrder);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        // $this->assertDatabaseHas('test_orders', $testOrder);
    }

    public function testDeleteTestOrder()
    {
         // Création de l'utilisateur pour s'authentifier
         $user = User::first();
        $testOrder = TestOrder::create([
            'prelevement_date'=>Carbon::now(),
        ]);
        $response = $this->actingAs($user)->get('/test_order/delete/'.$testOrder->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);
        // Vérification que la catégorie a été supprimé avec succès
        // $this->assertDatabaseMissing('test_orders', ['id' => $testOrder->id]);

    }
}
