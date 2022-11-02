<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

   public function test_can_get_all_books(){

        $books = Book::factory(4)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title'     => $books[0]->title,
            ])
            ->assertJsonFragment([
                'title'     => $books[1]->title,
            ]);

   }

   public function test_can_get_one_book(){

        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJson([
                'title'     => $book->title,
            ]);
   }

   public function test_can_create_books(){

        $this->postJson(route('books.store', []))
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store', [
            'title'     => 'New Book created'
        ]))->assertJsonFragment([
            'title'     => 'New Book created'
        ]);

        $this->assertDatabaseHas('books', [
            'title'     => 'New Book created'
        ]);
   }

   public function test_can_update_books(){

        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title'     => 'My book updated',
        ])->assertJsonFragment([
            'title'     => 'My book updated',
        ]);

        $this->assertDatabaseHas('books', [
            'title'     => 'My book updated',
        ]);
   }

   public function test_can_delete_books(){

        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();
   }
}
