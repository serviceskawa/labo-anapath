<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\CategoryTest;
use App\Models\Consultation;
use App\Models\Contrat;
use App\Models\Details_Contrat;
use App\Models\DetailTestOrder;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\LogReport;
use App\Models\Operation;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\Report;
use App\Models\Ressource;
use App\Models\Test;
use App\Models\TestOrder;
use App\Models\TitleReport;
use App\Models\TypeOrder;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    // use RefreshDatabase;

    use DatabaseTransactions;

   public function testRelationBetweenAppointmentAndPatient()
   {
        $this->withoutExceptionHandling();

        $patient = Patient::factory()->create();
        $appointment = Appointment::create(['patient_id' => $patient->id]);

        $this->assertEquals($patient->id,$appointment->patient->id);
   }

   public function testReletionBeweenAppointmentAndDoctor()
   {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $user = User::factory()->create();
        $appointment = Appointment::create(['patient_id' => $patient->id,'user_id'=>$user->id]);

        $this->assertEquals($user->id, $appointment->doctor_interne->id);
   }

    public function testCategoryTestHasManyTests()
    {
        $categoryTest = CategoryTest::factory()->create();
        $test1 = Test::factory()->create(['category_test_id' => $categoryTest->id]);
        $test2 = Test::factory()->create(['category_test_id' => $categoryTest->id]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $categoryTest->tests);
        $this->assertEquals(2, $categoryTest->tests->count());
        $this->assertTrue($categoryTest->tests->contains($test1));
        $this->assertTrue($categoryTest->tests->contains($test2));
    }

    public function testRelationBetweenConsultationAndDoctor()
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $doctor = Doctor::create([
            'name' => 'Assoufou',
            'commission' => 15,
        ]);
        $consultation = Consultation::create(['patient_id'=>$patient->id,'doctor_id'=>$doctor->id]);

        $this->assertEquals($doctor->id,$consultation->doctor->id);
    }

    public function testRelationBetweenConsultationAndPatient()
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $consultation = Consultation::create(['patient_id'=>$patient->id]);

        $this->assertEquals($patient->id,$consultation->patient->id);
    }

    public function testRelationBetweenConsultationAndAttribuateDoctor()
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $user = User::factory()->create();
        $consultation = Consultation::create(['patient_id'=>$patient->id,'attribuate_doctor_id'=>$user->id]);

        $this->assertEquals($user->id,$consultation->attribuateToDoctor->id);
    }

    public function testRelationBetweenDetailsContratAndContrat()
    {
        $this->withoutExceptionHandling();
        $contrat = Contrat::create([
            'name'=>'original',
            'type'=>'Ori',
        ]);
        $categoryTest = CategoryTest::factory()->create();
        $contratDetail = Details_Contrat::create([
            'contrat_id'=>$contrat->id,
            'category_test_id'=>$categoryTest->id,
        ]);

        $this->assertEquals($contrat->id, $contratDetail->contrat->id);
    }

    public function testRelationBetweenInvoiceAndDetails()
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $invoice = Invoice::create(['patient_id'=>$patient->id]);
        $detailInvoice1 = InvoiceDetail::create(['invoice_id'=>$invoice->id]);
        $detailInvoice2 = InvoiceDetail::create(['invoice_id'=>$invoice->id]);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $invoice->details);
        $this->assertEquals(2, $invoice->details->count());
        $this->assertTrue($invoice->details->contains($detailInvoice1));
        $this->assertTrue($invoice->details->contains($detailInvoice2));
    }

    public function testRelationBetweenInvoiceAndTestOrder()
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id
        ]);
        $invoice = Invoice::create(['patient_id'=>$patient->id, 'test_order_id'=>$testOrder->id]);

        $this->assertEquals($testOrder->id,$invoice->order->id);
    }

    public function testRelationBetweenLogReportAndReport()
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();
        $report = Report::create([
            'patient_id'=>$patient->id,
        ]);
        $logReport = LogReport::create(['operation'=>'create','report_id'=>$report->id]);

        $this->assertEquals($report->id, $logReport->report->id);
    }

    public function testRelationBetweenLogReportAndUser()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $logReport = LogReport::create(['operation'=>'create','user_id'=>$user->id]);
        $this->assertEquals($user->id, $logReport->user->id);

    }

    public function testRelationBetweenPatientAndOrder()
    {
        $this->withoutExceptionHandling();
        $patient = Patient::factory()->create();

        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $typeOrder1 = TypeOrder::create(['slug'=>'RASM']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id
        ]);
        $testOrder1 = TestOrder::create([
            'patient_id'=>$patient->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder1->id
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $patient->orders);
        $this->assertEquals(2, $patient->orders->count());
        $this->assertTrue($patient->orders->contains($testOrder));
        $this->assertTrue($patient->orders->contains($testOrder1));

    }

    public function testRelationBetweenReportAndTestOrder()
    {
        $this->withoutExceptionHandling();

        $patient = Patient::factory()->create();
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id]);
        $report = Report::create([
            'patient_id' => $patient->id,
            'test_order_id' => $testOrder->id,
        ]);

        $this->assertEquals($testOrder->id, $report->order->id);

    }

    public function testRelationBetweenReportAndTitle()
    {
        $this->withoutExceptionHandling();

        $patient = Patient::factory()->create();
        $title = TitleReport::create(['title' => 'TEST']);
        $report = Report::create([
            'patient_id' => $patient->id,
            'title_id' => $title->id,
        ]);

        $this->assertEquals($title->id, $report->title->id);

    }

    public function testRelationBetweenReportAndPatient()
    {
        $this->withoutExceptionHandling();

        $patient = Patient::factory()->create();
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id]);
        $report = Report::create([
            'patient_id' => $patient->id,
            'test_order_id' => $testOrder->id,
        ]);

        $this->assertEquals($patient->id, $report->patient->id);

    }

    public function testRelationBetweenTestOrderAndPatient()
    {
        $patient = Patient::factory()->create();
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id]);

        $this->assertEquals($patient->id,$testOrder->patient->id);
    }

    public function testRelationBetweenTestOrderAndDoctor()
    {
        $patient = Patient::factory()->create();
        $doctor = Doctor::create([
            'name' => 'Assoufou',
            'commission' => 15,
        ]);
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'prelevement_date'=>Carbon::now(),
            'doctor_id' =>$doctor->id,
            'type_order_id'=>$typeOrder->id
        ]);

        $this->assertEquals($doctor->id,$testOrder->doctor->id);
    }


    public function testRelationBetweenTestOrderAndHospital()
    {
        $patient = Patient::factory()->create();
        $hospital = Hospital::create(['name'=>'CAAP']);
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'hospital_id'=>$hospital->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id]);

        $this->assertEquals($hospital->id,$testOrder->hospital->id);
    }

    public function testRelationBetweenTestOrderAndContrat()
    {
        $patient = Patient::factory()->create();
        $contrat = Contrat::create([
            'name'=>'original',
            'type'=>'Ori',
        ]);
        $hospital = Hospital::create(['name'=>'CAAP']);
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'contrat_id'=>$contrat->id,
            'hospital_id'=>$hospital->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id]);

        $this->assertEquals($contrat->id,$testOrder->contrat->id);
    }

    public function testRelationBetweenTestOrderAndAttribuateDoctor()
    {
        $patient = Patient::factory()->create();
        $user = User::factory()->create();
        $contrat = Contrat::create([
            'name'=>'original',
            'type'=>'Ori',
        ]);
        $hospital = Hospital::create(['name'=>'CAAP']);
        $typeOrder = TypeOrder::create(['slug'=>'RAS']);
        $testOrder = TestOrder::create([
            'patient_id'=>$patient->id,
            'contrat_id'=>$contrat->id,
            'hospital_id'=>$hospital->id,
            'attribuate_doctor_id'=>$user->id,
            'prelevement_date'=>Carbon::now(),
            'type_order_id'=>$typeOrder->id]);

        $this->assertEquals($user->id,$testOrder->attribuateToDoctor->id);
    }

}
