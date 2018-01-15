<template>
    <div class="container">
      <div class="row">
          <div class="col-md-8 col-md-offset-2">
              <div class="panel panel-default">
                  <div class="panel-heading">Already Know The Magnet?</div>

                  <div class="panel-body">
                    <input type="text" class="form-control" v-model="magnet_uri" placeholder="Magnet URI"></input>
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
                      <transition name="fade">
                      <table v-if="isReady == true" class="table table-responsive">
                      <thead>
                        <tr>
                          <th>Poster</th>
                          <th>Name</th>
                          <th>MPA Rating</th>
                          <th>Review Rating</th>
                          <th>Genre</th>
                          <th>Description</th>
                          <th>Runtime</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="torrent in torrents.data.movies">
                          <td><img class="img img-rounded img-responsive" :src="torrent.medium_cover_image"/></td>
                          <td>{{torrent.title}}</td>
                          <td>{{torrent.mpa_rating}}</td>
                          <td>{{torrent.rating}}</td>
                          <td>
                            <ul>
                              <li v-for="genre in torrent.genres">{{genre}}</li>
                            </ul>
                          </td>
                          <td><p>{{torrent.synopsis}}</p></td>
                          <td>{{torrent.runtime}} minutes</td>
                          <td>
                            <button class="btn btn-sm btn-default" v-on:click="downloadTorrent(torrent)">Download</button>
                            <a class="btn btn-success" :href="'https://www.youtube.com/watch?v='+torrent.yt_trailer_code" target="_blank">Trailer</a>
                          </td>
                          </tr>
                      </tbody>
                      </table>
                    </table>
                  </transition>
                      <div class="pagination">
                       <button class="btn btn-default" @click="fetchTorrents('https://yts.am/api/v2/list_movies.json?page='+(torrents.data.page_number - 1))"
                               :disabled="torrents.data.page_number == 1">
                           Previous
                       </button>
                       <span>Page {{torrents.data.page_number}} of {{(torrents.data.movie_count/torrents.data.limit).toFixed(0)}}</span>
                       <button class="btn btn-default" @click="fetchTorrents('https://yts.am/api/v2/list_movies.json?page='+(torrents.data.page_number + 1))"
                               :disabled="torrents.data.page_number == (torrents.data.movie_count/torrents.data.limit).toFixed(0)">Next
                       </button>
                   </div>
                 </div>
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
          return{
            client: {},
            torrents: {},
            isReady: false,
            magnet_uri: ''
          }

        },
        methods:{
          downloadTorrent: function(torrent,userInput){
            userInput = userInput || false;
            if(userInput == false){
            torrent = this.formatMagnet(torrent);
          }

            this.client.add(torrent,function(tor){
              tor.on('download', function (bytes) {
                console.log('just downloaded: ' + bytes)
                console.log('total downloaded: ' + torrent.downloaded);
                console.log('download speed: ' + torrent.downloadSpeed)
                console.log('progress: ' + torrent.progress)
              });
              // Got torrent metadata!
               console.log('Client is downloading:', tor.infoHash)

               tor.files.forEach(function (file) {
                 console.log(file.name);
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
              console.log(data.data);
              that.torrents = data.data;
              console.log(that.torrents);
              that.isReady = true;
            })
          },
        },
        props: ['user-object'],
        created(){
          this.client = new WebTorrent();
        this.fetchTorrents('https://yts.am/api/v2/list_movies.json');
        }
    }
</script>
