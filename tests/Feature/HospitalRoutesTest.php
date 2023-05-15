<?php

namespace Tests\Feature;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HospitalRoutesTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateHospital()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $data = [
            'name'=>'Hospital Test'
        ];
        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('hopitals.store'), $data);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('hospitals', $data);
    }

    public function testUpdateHospital()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $hospital = Hospital::create([
            'name' => 'Hosto Big'
        ]);

        // Données d'une catégorie
        $dataaHosto = [
            'id'=>$hospital->id,
            'name'=>'Hosto Bam'
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('hopitals.update'), $dataaHosto);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('hospitals', $dataaHosto);
    }

    public function testDeleteHospital()
    {
         // Création de l'utilisateur pour s'authentifier
         $user = User::first();

         $hospitalTest = Hospital::create([
            'name'=>'Hospital Big Bam'
         ]);

         // envoi de la requête POST à l'endpoint
         $response = $this->actingAs($user)->get('/hopitals/delete/'.$hospitalTest->id);

         // Vérification du code de réponse HTTP
         $response->assertStatus(302);
         // Vérification que la catégorie a été supprimé avec succès
         $this->assertDatabaseMissing('hospitals', ['id' => $hospitalTest->id]);
    }

    public function testEditHospital()
    {
        $user = User::first();

        $hospital = Hospital::create(['name'=>'Hospital Big bam']);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('gethopital/'.$hospital->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(200);
        $response->assertJsonCount(8);
    }
}
