<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;

class CustomerControllerTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    /**
     * @test
     */
    public function it_stores_data()
    {   
        //Membuat objek user yang otomatis menambahkannya ke database.
        $user = User::factory()->create();

        //Membuat objek category yang otomatis menambahkannya ke database.
        $customer = CustomerModel::factory()->create();

        //Acting as berfungsi sebagai autentikasi, jika kita menghilangkannya maka akan error.
        $response = $this->actingAs($user)
        //Hit post ke method store, fungsinya ya akan lari ke fungsi store.
        ->post(route('customer.messageToAnotherCustomer'), [
            //isi parameter sesuai kebutuhan request
            "customer_id"=> $this->faker->randomNumber(1),
            "customer_receiver_id"=> $this->faker->randomNumber(1),
            "staff_id"=> null,
            "staff_receiver_id"=> null,
            "messages"=> $this->faker->words(3, true),
        ]);

        //Tuntutan status 302, yang berarti redirect status code.
        $response->assertStatus(500);
    }
}
