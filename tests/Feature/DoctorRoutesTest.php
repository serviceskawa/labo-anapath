<?php

namespace Tests\Feature;

use App\Models\CategoryTest;
use App\Models\Contrat;
use App\Models\Details_Contrat;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DoctorRoutesTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateDoctor()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $data = [
            'name'=>'Doctor Test',
            'commission'=>5
        ];
        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('doctors.store'), $data);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('doctors', $data);
    }

    public function testUpdateDoctor()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $doctor = Doctor::create([
            'name' => 'Doctor stone coeurs',
            'commission'=>10
        ]);

        // Données d'une catégorie
        $dataHosto = [
            'id'=>$doctor->id,
            'name'=>'Doctor Bam',
            'commission'=>15
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('doctors.update'), $dataHosto);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('doctors', $dataHosto);
    }

    public function testDeleteDoctor()
    {
         // Création de l'utilisateur pour s'authentifier
         $user = User::first();

         $doctorTest = Doctor::create([
            'name'=>'Doctor Big Bam maman',
            'commission'=>20
         ]);

         // envoi de la requête POST à l'endpoint
         $response = $this->actingAs($user)->get('/doctors/delete/'.$doctorTest->id);

         // Vérification du code de réponse HTTP
         $response->assertStatus(302);
         // Vérification que la catégorie a été supprimé avec succès
         $this->assertDatabaseMissing('doctors', ['id' => $doctorTest->id]);
    }

    public function testEditDoctor()
    {
        $user = User::first();

        $doctor = Doctor::create(['name'=>'Doctor Big bam father','commission'=>20]);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('getdoctor/'.$doctor->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(200);
        $response->assertJsonCount(8);
    }

    public function testCreatePatient()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $data = [
            'code' =>'azer7541',
            'firstname'=>'Kossi',
            'lastname'=>'Koffi',
            'genre'=>'masculin',
            'telephone1' => '545487688',
            'age'=>25,
            'year_or_month'=>1
        ];
        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('patients.store'), $data);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('patients', $data);
    }

    public function testCreatePatientWithInvalidEnter()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $data = [
            'code' =>'azer7541',
            'lastname'=>'Koffi',
            'telephone1' => '545487688',
            'age'=>25,
        ];
        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('patients.store'), $data);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
    }

    public function testUpdatePatient()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $patient = Patient::create([
            'code' =>'azer7541',
            'firstname'=>'Kossi',
            'lastname'=>'Koffi',
            'genre'=>'masculin',
            'telephone1' => '99874562',
            'age'=>25,
            'year_or_month'=>1
        ]);

        // Données d'une catégorie
        $dataHosto = [
            'id'=> $patient->id,
            'code' =>'azer7541',
            'firstname'=>'Kossi',
            'lastname'=>'Haasane',
            'genre'=>'masculin',
            'telephone1' => '87542135',
            'age'=>25,
            'year_or_month'=>1
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('patients.update'), $dataHosto);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('Patients', $dataHosto);
    }

    public function testDeletePatient()
    {
         // Création de l'utilisateur pour s'authentifier
         $user = User::first();

         $patientTest = Patient::create([
            'code' =>'azer7587',
            'firstname'=>'Kossi',
            'lastname'=>'Koffi',
            'genre'=>'masculin',
            'age'=>25,
            'year_or_month'=>1
        ]);

         // envoi de la requête POST à l'endpoint
         $response = $this->actingAs($user)->get('/patients/delete/'.$patientTest->id);

         // Vérification du code de réponse HTTP
         $response->assertStatus(302);
         // Vérification que la catégorie a été supprimé avec succès
         $this->assertDatabaseMissing('Patients', ['id' => $patientTest->id]);
    }

    public function testEditPatient()
    {
        $user = User::first();

        $patient = Patient::create([
            'code' =>'azer77511',
            'firstname'=>'Kossi',
            'lastname'=>'Albertine',
            'genre'=>'féminin',
            'age'=>25,
            'year_or_month'=>1
        ]);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('getpatient/'.$patient->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(200);
        $response->assertJsonCount(14);
    }

    public function testCreateContrat()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $data = [
            'name'=>'ORIGINAL',
            'type'=>'chimio',
            'description' => 'dispensable'
        ];
        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('contrats.store'), $data);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('contrats', $data);
    }

    public function testUpdateContrat()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $doctor = Contrat::create([
            'name'=>'FAKE FAKE',
            'type'=>'FF',
            'description' => 'dispensable'
        ]);

        // Données d'une catégorie
        $dataHosto = [
            'id'=>$doctor->id,
            'name'=>'ORIDJIDJI',
            'type'=>'chimio',
            'description' => 'dispensable'
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('contrats.update'), $dataHosto);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('contrats', $dataHosto);
    }

    public function testDeleteContrat()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $contratTest = Contrat::create([
            'name'=>'FAKE FAKE',
            'type'=>'FF',
            'description' => 'dispensable'
        ]);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('/contrats/delete/'.$contratTest->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);
        // Vérification que la catégorie a été supprimé avec succès
        $this->assertDatabaseMissing('Patients', ['id' => $contratTest->id]);
    }

    public function testEditContrat()
    {
        $user = User::first();

        $contratTest = Contrat::create([
            'name'=>'FAKE FAKE',
            'type'=>'FF',
            'description' => 'dispensable'
        ]);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('getcontrat/'.$contratTest->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(200);
        $response->assertJsonCount(9);
    }

    public function testCloseContrat()
    {
        $user = User::first();

        $contratTest = Contrat::create([
            'name'=>'FAKE FAKE',
            'type'=>'FF',
            'description' => 'dispensable'
        ]);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('/contrats/close/'.$contratTest->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);
    }

    public function testCreateDetailContrat()
    {
        // Création de l'utilisateur pour s'authentifier
        $user = User::first();

        $contrat = Contrat::create([
            'name'=>'FAKE FAKE',
            'type'=>'FF',
            'description' => 'dispensable'
        ]);
        $categoryTest = CategoryTest::factory()->create();

        $data = [
            'contrat_id' => $contrat->id,
            'pourcentage' => 50,
            'category_test_id' => $categoryTest->id,
        ];

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->post(route('contrat_details.store'), $data);

        // Vérification du code de réponse HTTP
        $response->assertStatus(302);

        // Vérification que la catégorie a été créée dans la base de données
        $this->assertDatabaseHas('details__contrats', $data);
    }

    // public function testUpdateDetailContrat()
    // {
    //     // Création de l'utilisateur pour s'authentifier
    //     $user = User::first();

    //     $contrat = Contrat::create([
    //         'name'=>'FAKE FAKE',
    //         'type'=>'FF',
    //         'description' => 'dispensable'
    //     ]);
    //     $categoryTest = CategoryTest::factory()->create();

    //     $data = Details_Contrat::create([
    //         'contrat_id' => $contrat->id,
    //         'pourcentage' => 50,
    //         'category_test_id' => $categoryTest->id,
    //     ]);

    //     // Données d'une catégorie
    //     $dataDetailContrat = [
    //         'id'=>$data->id,
    //         'contrat_id' => $contrat->id,
    //         'pourcentage' => 20,
    //         'category_test_id' => $categoryTest->id,
    //     ];

    //     // envoi de la requête POST à l'endpoint
    //     $response = $this->actingAs($user)->post(route('contrat_details.update'), $dataDetailContrat);

    //     // Vérification du code de réponse HTTP
    //     $response->assertStatus(302);

    //     // Vérification que la catégorie a été créée dans la base de données
    //     $this->assertDatabaseHas('details__contrats', $dataDetailContrat);
    // }

    // public function testDeleteDetailContrat()
    // {
    //     // Création de l'utilisateur pour s'authentifier
    //     $user = User::first();

    //     $contrat = Contrat::create([
    //         'name'=>'FAKE FAKE',
    //         'type'=>'FF',
    //         'description' => 'dispensable'
    //     ]);
    //     $categoryTest = CategoryTest::factory()->create();

    //     $data = Details_Contrat::create([
    //         'contrat_id' => $contrat->id,
    //         'pourcentage' => 50,
    //         'category_test_id' => $categoryTest->id,
    //     ]);

    //     // envoi de la requête POST à l'endpoint
    //     $response = $this->actingAs($user)->get('/contrats_details/delete/'.$data->id);

    //     // Vérification du code de réponse HTTP
    //     $response->assertStatus(302);
    //     // Vérification que la catégorie a été supprimé avec succès
    //     $this->assertDatabaseMissing('details__contrats', ['id' => $data->id]);
    // }

    public function testEditDetailContrat()
    {
        $user = User::first();

        $contrat = Contrat::create([
            'name'=>'FAKE FAKE',
            'type'=>'FF',
            'description' => 'dispensable'
        ]);
        $categoryTest = CategoryTest::factory()->create();

        $data = Details_Contrat::create([
            'contrat_id' => $contrat->id,
            'pourcentage' => 50,
            'category_test_id' => $categoryTest->id,
        ]);

        // envoi de la requête POST à l'endpoint
        $response = $this->actingAs($user)->get('getcontratdetails/'.$data->id);

        // Vérification du code de réponse HTTP
        $response->assertStatus(200);
        $response->assertJsonCount(6);
    }
}
