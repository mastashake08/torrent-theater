<template>
    <div class="container">
          <div class="row">
              <div class="col-md-8 col-md-offset-2">
                  <div class="panel panel-default">
                      <div class="panel-heading">Dashboard</div>

                      <div class="panel-body">
                        <!--<dashboard-component user-object={{Auth::user()}}></dashboard-component>-->
                        <div align="center" class="embed-responsive embed-responsive-16by9">
                        <video id="video-player" v-on:playing="stopModal()" autoplay controls class="embed-responsive-item"></video>
                        </div>
                        <transition name="fade">
                        <button v-if="a2hs" v-on:click="addToHomescreen()" class="btn btn-block btn-primary">Install To Homescreen</button>
                        </transition>
                      </div>
                  </div>
              </div>
          </div>
      <div class="row">
          <div class="col-md-8 col-md-offset-2">
              <div class="panel panel-default">
                  <div class="panel-heading">Already Know The Magnet?</div>

                  <div class="panel-body">
                    <input type="text" class="form-control" v-model="magnet_uri" placeholder="Magnet URI"></input>
                    <br>
                    <button class="btn btn-primary" v-on:click="downloadTorrent(magnet_uri,true)">Get Torrent</button>
                  </div>
              </div>
          </div>
      </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">All Torrents</div>

                    <div class="panel-body">
                      <input placeholder="Search Title" v-model="query" class="form-control" v-on:keyup="fetchTorrents('https://yts.am/api/v2/list_movies.json?sort=seeds&quality=1080p&query_term='+encodeURIComponent(query))">
                      <br>
                      <transition name="fade">
                        <div v-if="isReady" class="row">
                          <div v-for="torrent in torrents.data.movies" class="col-md-6">
                            <img class="rounded mx-auto d-block" :src="torrent.medium_cover_image"/>
                            <hr>
                            <div class="form-group  text-center">
                              {{torrent.title}}:
                              <br>
                              <button class="btn btn-sm btn-default" v-on:click="downloadTorrent(torrent)">Stream</button>
                              <a class="btn btn-sm btn-success" :href="'https://www.youtube.com/watch?v='+torrent.yt_trailer_code" target="_blank">Trailer</a>

                            </div>
                          </div>
                        </div>
                  </transition>
                      <div class="pagination" v-if="torrents.data != null">
                       <button class="btn btn-default" @click="fetchTorrents('https://yts.am/api/v2/list_movies.json?sort=seeds&quality=1080p&page='+(torrents.data.page_number - 1))"
                               :disabled="torrents.data.page_number == 1">
                           Previous
                       </button>
                       <span>Page {{torrents.data.page_number}} of {{(torrents.data.movie_count/torrents.data.limit).toFixed(0)}}</span>
                       <button class="btn btn-default" @click="fetchTorrents('https://yts.am/api/v2/list_movies.json?sort=seeds&quality=1080p&page='+(torrents.data.page_number + 1))"
                               :disabled="torrents.data.page_number == (torrents.data.movie_count/torrents.data.limit).toFixed(0)">Next
                       </button>
                   </div>
                 </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
      <div class="modal fade" id="torrentModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Loading Torrent</h4>
            </div>
            <div class="modal-body">
              <div class="loader"></div>
              <br>
              Progress: {{total}}%
            </div>
            <div class="modal-footer">
            </div>
          </div>

        </div>
      </div>
        </div>

</template>

<script>
    export default {
        mounted() {

          },
        data() {
          return{
            client: {},
            torrents: {},
            isReady: false,
            magnet_uri: 'magnet:?xt=urn:btih:08ada5a7a6183aae1e09d831df6748d566095a10&dn=Sintel&tr=udp%3A%2F%2Fexplodie.org%3A6969&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.empire-js.us%3A1337&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Ftracker.opentrackr.org%3A1337&tr=wss%3A%2F%2Ftracker.btorrent.xyz&tr=wss%3A%2F%2Ftracker.fastcast.nz&tr=wss%3A%2F%2Ftracker.openwebtorrent.com&ws=https%3A%2F%2Fwebtorrent.io%2Ftorrents%2F&xs=https%3A%2F%2Fwebtorrent.io%2Ftorrents%2Fsintel.torrent',
            query: '',
            total:0,
            a2hs: false,
            deferredPrompt:null
          }

        },
        methods:{
          addToHomescreen: function(){
            // Show the prompt
            this.deferredPrompt.prompt();
            // Wait for the user to respond to the prompt
            this.deferredPrompt.userChoice
              .then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                  console.log('User accepted the A2HS prompt');
                } else {
                  console.log('User dismissed the A2HS prompt');
                }
                this.deferredPrompt = null;
              });
          },
          downloadTorrent: function(torrent,userInput){
            var that = this;
            this.client.torrents.forEach(function(item,key){
              that.client.remove(item);
            });
            this.total = 0;
            userInput = userInput || false;
            if(userInput == false){
            torrent = this.formatMagnet(torrent);
          }
          $(window).scrollTop(0);
          $('#torrentModal').modal('show');
            var that = this;
            this.client.add(torrent,function(tor){
              var _this = that;
              tor.on('download', function (bytes) {
                _this.total = (tor.progress * 100).toFixed(3);
              });
              tor.on('ready',function(){});
               tor.files.forEach(function (file) {

                 // Display the file by appending it to the DOM. Supports video, audio, images, and
                 // more. Specify a container element (CSS selector or reference to DOM node).
                 if(file.name.endsWith('mp4')){
                 file.renderTo('video#video-player')


               }
               else{

               }
               });
            });
          },
          formatMagnet: function(torrent){
            var torrentHash = torrent.torrents[0].hash;
            var dn = encodeURIComponent(torrent.title);
            var tr1 = 'wss://tracker.openwebtorrent.com';
            var tr2 = 'wss://tracker.btorrent.xyz';
            var tr3 = 'wss://tracker.fastcast.nz';
            var tr4 = 'udp://glotorrents.pw:6969/announce';
            var tr5 = 'udp://tracker.opentrackr.org:1337/announce'
            var tr6 =  'udp://torrent.gresille.org:80/announce'
            var tr7 =  'udp://tracker.openbittorrent.com:80'
            var tr8 =  'udp://tracker.coppersurfer.tk:6969'
            var tr9 =  'udp://tracker.leechers-paradise.org:6969'
            var tr10 = 'udp://p4p.arenabg.ch:1337'
            var tr11 = 'udp://tracker.internetwarriors.net:1337'
            var magnet = 'magnet:?xt=urn:btih:'+torrentHash+'&dn='+dn+'&tr=udp%3A%2F%2Fexodus.desync.com%3A6969&tr=udp%3A%2F%2Fopen.demonii.com%3A1337&tr=udp%3A%2F%2Ftracker.coppersurfer.tk%3A6969&tr=udp%3A%2F%2Ftracker.leechers-paradise.org%3A6969&tr=udp%3A%2F%2Fzer0day.ch%3A1337&tr=wss%3A%2F%2Ftracker.btorrent.xyz&tr=wss%3A%2F%2Ftracker.fastcast.nz&tr=wss%3A%2F%2Ftracker.openwebtorrent.com'
            + '&tr=' + tr4+ '&tr=' + tr5+ '&tr=' + tr6+ '&tr=' + tr7+ '&tr=' + tr8+ '&tr=' + tr9+ '&tr=' + tr10+ '&tr=' + tr11;
            return magnet;
          },
          fetchTorrents: function(url){
            var that = this;
            axios.get(url).then(function(data){
              that.torrents = data.data;

              that.isReady = true;
            })
          },
          stopModal: function(){

              $('#torrentModal').modal('hide');
          }
        },
        props: ['user-object'],
        created(){
          if (window.matchMedia('(display-mode: standalone)').matches) {
          this.a2hs = false
          }
          if (window.navigator.standalone === true) {
            this.a2hs = false
          }
          if (WebTorrent.WEBRTC_SUPPORT) {
          // WebRTC is supported
          if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
              navigator.serviceWorker.register('/background.js').then(function(registration) {

              }, function(err) {
                // registration failed :(
                console.log('ServiceWorker registration failed: ', err);
              });
            });
          }


          window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            this.deferredPrompt = e;
            this.a2hs = true;
          });
          window.addEventListener('appinstalled', (evt) => {
            app.logEvent('a2hs', 'installed');
          });
          this.client = new WebTorrent();
          this.fetchTorrents('https://yts.am/api/v2/list_movies.json?sort=seeds');
        } else {
          // Use a fallback
          alert("WebTorrent is not supported in this browser! Please upgrade your browser!");
        }

                }
    }
</script>
