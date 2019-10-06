<?php

namespace App\Http\Controllers;

use App\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    //
    /**
     * добавил этот action, чтобы закешировать руты приложения
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $emptyMessage = [
            0 => [
                'message' => 'Пока нет никаких сообщений. Будьте первым',
                'author_name' => 'Server Message'
            ],
        ];

        $user_id = 0;
        $user_name = '';

        if (Auth::check()) {
            $messages = $this->getMessageList();
            $messages = (!empty($messages)) ? $messages : $emptyMessage;
        } else {
            $messages = $emptyMessage;
        }

        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $user_name = Auth::user()->name;
        }
        return view('index', [
            'authorized' => (Auth::check()),
            'messages' => json_encode($messages),
            'user_name' => $user_name,
            'user_id' => $user_id
        ]);
    }

    /**
     * Сохраняем сообщения
     *
     * @return mixed
     */
    public function sendMessage(Request $request)
    {
        try {
            if (!Auth::check()) throw new \Exception('Пользователь не авторизован');
            $author_id = $request->post('author_id', 0);
            $message = $request->post('message', '');
            if (empty($author_id)) throw new \Exception('Неопределен пользователь');
            if (empty($message)) throw new \Exception('Пустое сообщение');

            $chat = new Chat();
            $chat->author_id = $author_id;
            $chat->message = $message;
            $chat->created_at = new \DateTime();
            $chat->updated_at = new \DateTime();
            $chat->save();

            $result['status'] = 'success';
            $result['message'] = 'ok';
            $result['data'] = [
                'message' => $chat->message,
                'author_name' => $chat->author->name,
                'author_id' => $chat->author_id,
                'time' => $chat->created_at->format('d.m.Y H:i')
            ];

            event(new \App\Events\GetMessage($result));
        } catch (\Exception $exception) {
            $result['status'] = 'error';
            $result['message'] = $exception->getMessage();
            $result['data'] = [];

            return $result;
        }
    }

    private function getMessageList()
    {
        $chats = Chat::orderBy('created_at', 'desc')->take(20)->get();
        $chat_data = [];

        if (!empty($chats)) {
            foreach ($chats->reverse()->values() as $key => $chat) {
                $chat_data[$key] = $chat;
                $chat_data[$key]['time'] = \DateTime::createFromFormat('Y-m-d H:i:s', $chat_data[$key]['created_at'])->format('d.m.Y H:i');
                $chat_data[$key]['author_name'] = $chat->author->name;
            }
        }

        return $chat_data;
    }

}
