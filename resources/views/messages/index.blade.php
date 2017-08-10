<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Bootstrap -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .form-control {
                color: #000 !important;
            }            
        </style>
    </head>
    <body>
        
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Message to everyone
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form v-on:submit.prevent="performSend" id="form">
                            <textarea name="message" id="message" v-model="send.message" cols="30" rows="10" class="form-control"></textarea><br>
                            <input type="submit" value="Send" class="btn btn-primary">
                        </form>
                    </div>                    
                </div>
            </div>
        </div>        

        <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.28/vue.js"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script>
            new Vue({
                el: '#form',
                data: {
                    send: {
                        message: null
                    },
                    submitData: null
                },
                methods: {
                    performSend: function() {
                        this.submitData = this.send;
                        axios.post('/send', {
                            message: this.send.message
                        });
                        this.send.message = "";     
                    }
                }
            });
        </script>
    </body>
</html>
