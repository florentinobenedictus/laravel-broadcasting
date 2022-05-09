@extends('layouts.app')

@section('content')

<div class="container">
{{-- uncomment jika menggunakan presence channel
	<div class="col-md-4">
		<center>
			<h3>Username List:</h3><br>
			<div class="mb-4" id="usernameList"></div>
		</center>
	</div>
	<br><br>
--}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Chats</div>

                <div class="panel-body">
                    <chat-messages :messages="messages"></chat-messages>
                </div>
                <div class="panel-footer">
                    <chat-form
                        v-on:messagesent="addMessage"
                        :user="{{ Auth::user() }}"
                    ></chat-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
