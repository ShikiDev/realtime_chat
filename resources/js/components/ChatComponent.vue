<template>
    <div class="message-container">
        <div class="row" v-if="showError !== false">
            <div class="col">
                <div class="alert alert-danger">
                    <strong>Внимание!</strong> {{errorMessage}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div id="messageBox" class="message-box">
                    <div class="row" v-for="message in messagesList">
                        <div class="col-6" v-bind:class="[(author_id == message.author_id)? 'offset-6':'']">
                            <div class="message-block ml-2 mb-2"
                                 v-bind:class="[(author_id == message.author_id)? 'self':'']">
                                <strong>{{message.author_name}}</strong><span
                                    class="float-right">{{message.time}}</span><br>
                                <p>{{message.message}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" v-model="messageText" placeholder="Сообщение"
                           aria-label="Сообщение"
                           aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" @click="sendMessage">Отправить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                messagesList: [],
                author_id: 0,
                messageText: '',
                errorMessage: '',
                showError: false,
            }
        },
        props: ['messages', 'user_id'],
        mounted() {
            this.author_id = this.user_id;
            this.messagesList = this.messages;

            var socket = io('http://localhost:3000');
            socket.on("get-new-message:App\\Events\\GetMessage", function (data) {
                if (data.result.status === 'success') {
                    let message = data.result.data;
                    this.messagesList.push(message);

                    let mess_box = this.$el.querySelector('#messageBox');
                    mess_box.scrollTop = mess_box.scrollHeight;
                }
            }.bind(this));

            this.scrollDown();
        },
        methods: {
            sendMessage: function () {
                axios.post('/sendMessage', {
                    author_id: this.author_id,
                    message: this.messageText
                }).then(
                    response => {
                        this.errorMessage = '';
                        this.error = false;
                        this.messageText = '';
                        if (response.data.status == 'error') {
                            this.errorMessage = response.data.message;
                            this.error = true;
                        }
                    }
                )
            },
            scrollDown() {
                let mess_box = this.$el.querySelector('#messageBox');
                mess_box.scrollTop = mess_box.scrollHeight;
            }
        }
    }
</script>