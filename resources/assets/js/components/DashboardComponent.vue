<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
          return {
          user : {},
          torrents: {},
          isReady: false
        }
        },
        props: ['user-object'],
        created(){
          if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
              navigator.serviceWorker.register('/background.js').then(function(registration) {

              }, function(err) {
                // registration failed :(
                console.log('ServiceWorker registration failed: ', err);
              });
            });
          }
          var that = this;
          this.user = this.userObject;
          axios.get('/api/torrent').then(function(data){
            that.torrents = data.data;
            that.isReady = true;
          });
        }
    }
</script>
