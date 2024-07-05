<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    //Список всех постов
    public function index()
    {
        $posts = Post::paginate(10);

        $posts->getCollection()->transform(function ($post) {
            $dummyPost = Http::get("https://dummyjson.com/posts/{$post->dummy_post_id}")->json();

            // Обновляем поля поста с полученными данными
            $post->title = $dummyPost['title'] ?? $post->title;
            $post->body = $dummyPost['body'] ?? $post->body;
            $post->author = $dummyPost['userId'] ?? $post->user_id;

            return $post;
        });

        return response()->json($posts);
    }

    public function test()
    {
        return response()->json(['message' => 'API is working fine!']);
    }

    // Создание нового поста
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Создаем пост на dummyjson.com и получаем dummy_post_id
        $response = Http::post('https://dummyjson.com/posts/add', [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'userId' => auth()->id(),
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to create post on dummyjson.com'], 500);
        }

        $dummyPost = $response->json();

        // Создаем пост в локальной базе данных
        $post = Post::create([
            'user_id' => auth()->id(),
            'dummy_post_id' => $dummyPost['id'],
            'title' => $dummyPost['title'],
            'body' => $dummyPost['body'],
        ]);

        return response()->json($post, 201);
    }

    // Отображение конкретного поста
    public function show($id)
    {
        $post = Post::findOrFail($id);
        $dummyPost = Http::get("https://dummyjson.com/posts/{$post->dummy_post_id}")->json();

        $post->title = $dummyPost['title'] ?? $post->title;
        $post->body = $dummyPost['body'] ?? $post->body;
        $post->author = $dummyPost['userId'] ?? $post->user_id;

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post);
    }

    // Обновление поста
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = Post::findOrFail($id);

        if ($post->user_id != auth()->id()) {
            return response()->json(['error' => 'You can only edit your own posts'], 403);
        }

        $response = Http::put("https://dummyjson.com/posts/{$post->dummy_post_id}", [
            'title' => $request->input('title'),
            'body' => $request->input('body'),
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to update post on dummyjson.com'], 500);
        }

        $dummyPost = $response->json();

        $post->update([
            'title' => $dummyPost['title'],
            'body' => $dummyPost['body'],
        ]);

        return response()->json($post);
    }

    // Удаление поста
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Проверка, что текущий пользователь является автором поста
        if ($post->user_id != auth()->id()) {
            return response()->json(['error' => 'You can only delete your own posts'], 403);
        }

        $response = Http::delete("https://dummyjson.com/posts/{$post->dummy_post_id}");

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to delete post on dummyjson.com'], 500);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
